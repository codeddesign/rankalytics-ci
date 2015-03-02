<?php

class Project_Model extends CI_Model
{
    var $_tablename = 'tbl_project';

    function __construct()
    {
        $this->pgsql = $this->load->database( 'pgsql', true );
    }

    public function saveProject( $domain_array )
    {
        $this->pgsql->set( $domain_array );
        $this->pgsql->set( "uploaded_date", "now()", false );
        $this->pgsql->insert( 'tbl_project' );

        return $domain_array['id'];
    }

    public function isProjectExists( $name, $userId )
    {
        $this->pgsql->select( '*' );
        $this->pgsql->from( 'tbl_project' );

        $this->pgsql->where( array( 'project_name' => $name, "userId" => $userId, 'deleted' => 0 ) );
        $this->pgsql->limit( 1 );
        $query = $this->pgsql->get();

        return $query->num_rows();
    }

    public function isDomainExists( $url, $userId )
    {
        $this->pgsql->select( '*' );
        $this->pgsql->from( 'tbl_project' );

        $this->pgsql->where( array( 'domain_url' => $url, "userId" => $userId, 'deleted' => 0 ) );
        $this->pgsql->limit( 1 );
        $query = $this->pgsql->get();
        return $query->num_rows();
    }

    public function insertKeywords( $keyword_array )
    {
        $this->pgsql->insert_batch( 'tbl_project_keywords', $keyword_array );
    }

    public function getProjectData( $userId, $userRole, $limit = array() )
    {
        $this->pgsql->select( 'tbl_project.*' );
        $this->pgsql->from( 'tbl_project' );


        $this->pgsql->where( array( "userId" => $userId, 'deleted' => 0 ) );

        if ( ! empty( $limit ) && isset( $limit[0] )) {
            if (isset( $limit[1] )) {
                $this->pgsql->limit( $limit[1], $limit[0] );
            } else {
                $this->pgsql->limit( $limit[0] );
            }
        }
        $p_data = $this->pgsql->get()->result_array();

        $this->db->select( 'users.*' );
        $this->db->from( 'users' );
        if ($userRole != 'admin') {
            $this->db->where( array( 'id' => $userId ) );
            $this->db->limit( 1 );
        }

        $u_data = $this->db->get()->result_array();
        foreach ($p_data as $p_no => $p) {
            foreach ($u_data as $u_no => $u) {
                if ($u['id'] == $p['userId']) {
                    $p_data[$p_no] += $u;
                }
            }

        }

        return $p_data;
    }

    public function getProjectUsername( $project_id )
    {

        $project = $this->getProjectById( $project_id );

        $userId = $project['0']['userId'];
        $this->load->model( "users_model" );

        $user_arr = $this->users_model->getUserById( $userId );
        return $user_arr['0']['userName'];
    }

    //
    public function getProjectDataWithKeywords( $userId, $userRole )
    {
        $project_data = $this->getProjectData( $userId, $userRole );
        if (is_array( $project_data )) {
            foreach ($project_data as $key => $project) {
                $project_data[$key]['keywords_array'] = $this->getProjectKeywords( $project['id'] );
            }

        }
        return $project_data;
        //die();
    }

    // Created By sudhir for fetching project Domain
    public function getProjectDomain( $project_id )
    {
        if ($project_id == 0) {
            return array();
        }

        $this->pgsql->select( "domain_url" );
        $this->pgsql->from( "tbl_project" );
        $this->pgsql->where( 'id', $project_id );
        $this->pgsql->limit( 1 );
        $result = $this->pgsql->get();
        $result = $result->result_array();
        if (is_array( $result ) && isset( $result['0'] )) {
            return $result['0'];
        } else {
            return array();
        }

    }

    public function getProjectKeywordQuery( $project_id, $like = array(), $where = array() )
    {
        $this->pgsql->select( "tbl_project_keywords.*,tbl_project.domain_url,project_keyword_relation.project_id" );
        $this->pgsql->from( "tbl_project" );

        $this->pgsql->join( "project_keyword_relation", "project_keyword_relation.project_id=tbl_project.id" );
        $this->pgsql->join( "tbl_project_keywords", "tbl_project_keywords.unique_id=project_keyword_relation.keyword_id" );
        $this->pgsql->where( array( 'project_keyword_relation.project_id' => $project_id ) );
        if ( ! empty( $where )) {
            $this->pgsql->where( $where );
        }

        if ( ! empty( $like )) {
            $this->pgsql->like( $like );
        }

        return;
    }

    public function getProjectKeywordsCount( $project_id, $like = array(), $where = array() )
    {
        $this->getProjectKeywordQuery( $project_id, $like, $where );
        $query = $this->pgsql->get();

        return $query->num_rows();
    }

    public function delete( $id )
    {
        $this->pgsql->where( array( 'id' => $id ) );
        return $this->pgsql->update( 'tbl_project', array(
            'deleted' => 1,
        ) );

        /*$keyword_ids = $this->project_keywords->getKeywordIdsByProjectId($id);*/

        $this->pgsql->where( array( 'id' => $id ) );
        if ($this->pgsql->delete( "tbl_project" )) {
            /*//remove 1
            $this->load->model('Project_Keywords_Model', 'project_keywords');
            $this->project_keywords->deleteKeywordByProjectId($id);

            //remove 2
            $this->load->model('Project_Keyword_Relation_Model', 'project_keyword_relation');
            $this->project_keyword_relation->deleteByProjectId($id);

            if (count($keyword_ids) > 0) {
                //remove 3: from crawled_sites too:
                $this->pgsql->where_in('keyword_id', $keyword_ids);
                $this->pgsql->delete('crawled_sites');

                //remove 4: from trend_data too:
                $this->pgsql->where_in('keyword_id', $keyword_ids);
                $this->pgsql->delete('trend_data');
            }*/

            return 1;
        } else {
            return 0;
        }
    }

    public function deleteProjectByUserid( $userId )
    {
        if ($userId == '' || $userId == 0) {
            return;
        }
        $projects = $this->getProjectData( $userId, '' );
        foreach ($projects as $project) {
            $this->delete( $project['id'] );
        }
    }

    public function get_id_by_projectname( $project_name, $userId )
    {
        $this->pgsql->select( "*" );
        $this->pgsql->from( "tbl_project" );
        $this->pgsql->where( 'userId', $userId );
        $this->pgsql->where( "project_name", $project_name );
        $qry         = $this->pgsql->get();
        $result_rows = $qry->result_array();

        if (isset( $result_rows['0'] )) {
            return $result_rows['0']['id'];
        }
        return 0;
    }

    public function getProjectById( $id )
    {
        $this->db->select( '*' );
        $this->db->from( 'users' );
        $this->db->where( array( 'id' => $this->users->isLoggedIn() ) );
        $result    = $this->db->limit( 1 )->get()->result_array();
        $user_info = $result[0];

        $this->pgsql->select( '*' );
        $this->pgsql->from( 'tbl_project' );
        $this->pgsql->where(
            array(
                "tbl_project.id" => $id,
                'userId'         => $user_info['id'],
            )
        );
        $result       = $this->pgsql->get()->result_array();
        $project_info = $result[0] + $user_info;

        return $project_info;
    }

    public function getProjectByUserid( $userId )
    {
        $user = $this->users->getUserById( $userId );
        return $this->getProjectData( $userId, $user[0]['userRole'] );
    }

    public function getProjectKeyword( $project_id, $like = array(), $where = array() )
    {
        $this->getProjectKeywordQuery( $project_id, $like, $where );
        $query = $this->pgsql->get() or die( mysql_error() );
        return $query->result_array();
    }


    public function getProjectKeywords( $project_id, $like = array(), $limit = array() )
    {
        $this->getProjectKeywordQuery( $project_id, $like );
        if ( ! empty( $limit ) && isset( $limit[0] )) {
            if (isset( $limit[1] )) {
                $this->pgsql->limit( $limit[1], $limit[0] );
            } else {
                $this->pgsql->limit( $limit[0] );
            }
        }

        return $this->pgsql->get()->result_array();
    }

    function get_project_keyword_details_by_keyword( $keyword_array, $date, $date_mdY )
    {
        $total_rank       = 0;
        $estimated_trafic = 0;
        $kei              = 0;
        foreach ($keyword_array as $key => $value2) {
            $unique_id  = $value2['unique_id'];
            $domain_url = $value2['domain_url'];

            $info = $this->crawled_sites_model->getCrawledInfo( array( 'keyword_id' => $unique_id, 'crawled_date' => $date ) );

            $total_rank = $total_rank + $info['rank']; //total rank calculation for further use to calculate average position

            switch ($info['rank']) {
                case 1:
                    $percent = 30;
                    break;
                case 2:
                    $percent = 16;
                    break;
                case 3:
                    $percent = 10;
                    break;
                case 4:
                    $percent = 8;
                    break;
                case 5:
                    $percent = 6;
                    break;
                case 6:
                    $percent = 4;
                    break;
                case 7:
                    $percent = 3;
                    break;
                case 8:
                    $percent = 3;
                    break;
                case 9:
                    $percent = 2;
                    break;
                case 10:
                    $percent = 2;
                    break;
                case 11:
                    $percent = 1;
                    break;
                case 12:
                    $percent = 0.7;
                    break;
                case 13:
                    $percent = 0.7;
                    break;
                case 14:
                    $percent = 0.6;
                    break;
                case 15:
                    $percent = 0.4;
                    break;
                case 16:
                    $percent = 0.35;
                    break;
                case 17:
                    $percent = 0.33;
                    break;
                case 18:
                    $percent = 0.27;
                    break;
                case 19:
                    $percent = 0.27;
                    break;
                case 20:
                    $percent = 0.29;
                    break;
                case 21:
                    $percent = 0.1;
                    break;
                case 22:
                    $percent = 0.1;
                    break;
                case 23:
                    $percent = 0.08;
                    break;
                case 24:
                    $percent = 0.06;
                    break;
                case 25:
                    $percent = 0.06;
                    break;
                case 26:
                    $percent = 0.05;
                    break;
                case 27:
                    $percent = 0.05;
                    break;
                case 28:
                    $percent = 0.05;
                    break;
                case 29:
                    $percent = 0.04;
                    break;
                case 30:
                    $percent = 0.06;
                    break;
                default:
                    $percent = 0;
            }

            $keyword_info = $this->project_keywords_adwordinfo->get_dated_keywordinfo_by_keywordid( $unique_id, $date );

            if (isset( $keyword_info['volume'] ) && $keyword_info['volume'] > 0) {
                $estimated_trafic = $estimated_trafic + ( $keyword_info['volume'] * $percent );
            }

            if (isset( $keyword_info['volume'] ) && $keyword_info['volume'] > 0) {
                $kei = $kei + round( $info['total_records'] / $keyword_info['volume'], 2 );
            }

        } // end foreach for $value

        $project_keywords_details_arr['average_position']  = $total_rank;
        $project_keywords_details_arr['estimated_traffic'] = $estimated_trafic;

        if ($project_keywords_details_arr['average_position'] >= 1 && $project_keywords_details_arr['average_position'] <= 30) {
            $project_keywords_details_arr['visibility'] = 30 - $project_keywords_details_arr['average_position'] + 1;
        } else {
            $project_keywords_details_arr['visibility'] = '0';
        }

        $project_keywords_details_arr['keyword_effectiveness_index'] = $kei;
        $project_keywords_details_return_arr[]                       = $project_keywords_details_arr['average_position'];
        $project_keywords_details_return_arr[]                       = $project_keywords_details_arr['estimated_traffic'];
        $project_keywords_details_return_arr[]                       = $project_keywords_details_arr['keyword_effectiveness_index'];
        $project_keywords_details_return_arr[]                       = $project_keywords_details_arr['visibility'];
        $project_keywords_details_return_arr[]                       = $date_mdY;

        return $project_keywords_details_return_arr;
    }

}