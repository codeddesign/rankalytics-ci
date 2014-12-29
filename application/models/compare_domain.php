<?php
class Compare_Domain extends CI_Model
{
    // var $_tablename = "tbl_project";
        
    function __construct() 
    {
        // parent::Model();
        //$this->load->database();
        $this->pgsql = $this->load->database('pgsql', true);
    }

    public function get_domains($get_user_id) 
    {
        /*
        $this->load->model("common_model");
        $id = $this->common_model->getNewId($this->_tablename);
        $data_array['id']=$id;
        */
        $this->pgsql->select('id, project_name, domain_url, id, userId');
        $this->pgsql->from('tbl_project');
        $this->pgsql->where('userId', $get_user_id);
        $query = $this->pgsql->get();
        // echo $this->db->last_query();
        return $query->result_array();
    }
    public function get_keywords()
    {
        $this->pgsql->select('*');
        $this->pgsql->from('tbl_project_keywords');
        $this->pgsql->where('project_id', $_POST['keywordresearch-keyword']);
        $query = $this->pgsql->get();
        // echo $this->db->last_query();
        $temp_array = array();
        $resutl = $query->result_array();
        $final_array = array();
        
        foreach ($resutl as $key => $value) 
        {
            $temp_array[$key] = similar_text ($value['keyword'], $_POST['keywordresearch-keyword_text']);
        }
        arsort($temp_array);
        foreach ($temp_array as $key => $value) 
        {
            $final_array[$key] = $resutl[$key];
        }
        return $final_array;
    }
    public function get_ranking($get_competitor_url, $get_keyword)
    {
        $get_competitor_url_no_slash = rtrim($get_competitor_url, '/');
        $get_competitor_url_with_slash = rtrim($get_competitor_url, '/') . '/';
        $this->pgsql->select('rank');
        $this->pgsql->from('crawled_sites');
        $this->pgsql->limit(1);
        $this->pgsql->where('site_url',$get_competitor_url_no_slash);
        $this->pgsql->or_where('site_url',$get_competitor_url_with_slash);
        $this->pgsql->where('keyword', $get_keyword);
        $this->pgsql->where("page_rank IS NOT NULL",null,FALSE);
        $query = $this->pgsql->get();
        //echo $this->db->last_query().'<br/>';
        // echo "<br />";
        // echo "<pre>"; print_r($query->result_array()); echo "</pre>";
        $rank = "--";
        $result = $query->result_array();
        if(!empty($result))
        {
            $rank = $result[0]['rank'];
        }
        return $rank;
    }
}