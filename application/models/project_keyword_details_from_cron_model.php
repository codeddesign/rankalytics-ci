<?php

class project_keyword_details_from_cron_model extends CI_Model
{
    var $_tablename = "project_keyword_details_from_cron";

    function __construct()
    {
        $this->pgsql = $this->load->database('pgsql', true);
    }

    public function save($data_array)
    {
        $this->load->model("common_model");
        $id = $this->common_model->getNewId($this->_tablename);
        $data_array['id'] = $id;
        $this->pgsql->set($data_array);
        $this->pgsql->set("createdOn", "now()", FALSE);
        $this->pgsql->insert($this->_tablename);

        return $data_array['id'];
    }

    public function project_keyword_details_by_project_id($projectId)
    {
        $rows = $this->pgsql->select("*")->from($this->_tablename)->where(array("project_id" => $projectId))-> /*order_by('createdOn desc, id desc')->*/
        limit(1)->get()->result_array();

        if (isset($rows['0'])) {
            return $rows;
        } else {
            return array();
        }
    }


    public function project_keyword_details_save($project_data)
    {
        foreach ($project_data as $key => $value) {
            $total_rank = 0;
            $estimated_trafic = 0;
            $kei = 0;
            foreach ($value['keywords_array'] as $key => $value2) {
                $unique_id = $value2['unique_id'];
                $domain_url = $value2['domain_url'];
                $date = date("Y-m-d");
                $info = $this->crawled_sites_model->getCrawledInfo(array('keyword_id' => $unique_id, 'crawled_date' => $date));
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
                $keyword_info = $this->project_keywords_adwordinfo->get_latest_keywordinfo_by_keywordid($unique_id);
                //print_r($keyword_info);
                if (isset($keyword_info['volume']) && $keyword_info['volume'] > 0) {
                    $estimated_trafic = $estimated_trafic + ($keyword_info['volume'] * $percent);
                }
                if (isset($keyword_info['volume']) && $keyword_info['volume'] > 0) {
                    $kei = $kei + round($info['total_records'] / $keyword_info['volume'], 2);
                }
            } // end foreach for $value
            $project_keywords_details_arr['project_id'] = $value['id'];
            $project_keywords_details_arr['average_position'] = (count($value['keywords_array']) >= 1) ? ($total_rank / count($value['keywords_array'])) : 'na';
            $project_keywords_details_arr['estimated_traffic'] = $estimated_trafic;
            if ($project_keywords_details_arr['average_position'] >= 1 && $project_keywords_details_arr['average_position'] <= 30) {
                $project_keywords_details_arr['visibility'] = 30 - $project_keywords_details_arr['average_position'] + 1;
            } else {
                $project_keywords_details_arr['visibility'] = '0';
            }
            $project_keywords_details_arr['keyword_effectiveness_index'] = $kei;
            $this->save($project_keywords_details_arr);

        } // end for $project_data
    }

    public function project_keyword_details_by_where($projectId, $date)
    {
        if($projectId == 0) {
            return array();
        }
        
        $rows = $this->pgsql
            ->select("*")->from($this->_tablename)
            ->where('project_id', $projectId)
            ->order_by('createdOn', 'desc')
            ->where("createdOn >= ", $date.' 00:00:00')
            ->where('createdOn <=', $date.' 23:59:59')
            ->limit(1)->get()->result_array();

        if (isset($rows['0'])) {
            return $rows;
        } else {
            return array();
        }
    }

    public function getGoogleWeather($date)
    {
        $temp = 0;
        $split_date = explode("-", $date);
        $match_date = ltrim($split_date[1], '0') . "/" . ltrim($split_date[2], '0');
        $this->pgsql->select("temperature");
        $this->pgsql->from("tbl_mozcast");
        $this->pgsql->where("date", $match_date);
        $this->pgsql->limit(1);
        $rows = $this->pgsql->get()->result_array();

        if (isset($rows['0'])) {
            $temp = $rows['0']['temperature'];
            return $temp;
        } else {
            return $temp;
        }
    }

    public function last_five_days_for_graph($projectId)
    {
        $dateDB['0'] = date('Y-m-d');

        $dateGR['0'] = date('m/d/Y');
        $dateDB['1'] = date('Y-m-d', strtotime(' -1 day'));
        $dateGR['1'] = date('m/d/Y', strtotime(' -1 day'));
        $dateDB['2'] = date('Y-m-d', strtotime(' -2 day'));
        $dateGR['2'] = date('m/d/Y', strtotime(' -2 day'));
        $dateDB['3'] = date('Y-m-d', strtotime(' -3 day'));
        $dateGR['3'] = date('m/d/Y', strtotime(' -3 day'));
        $dateDB['4'] = date('Y-m-d', strtotime(' -4 day'));
        $dateGR['4'] = date('m/d/Y', strtotime(' -4 day'));

        $data_arr[] = array('Rankings', 'ERT', 'KEI', 'Google Wetter', 'Date');
        foreach ($dateDB as $key => $value) {
            $temp_weather = $this->getGoogleWeather($value);
            $rows = $this->project_keyword_details_by_where($projectId, $value);

            if (isset($rows['0'])) {
                $row = $rows['0'];

                if(is_numeric($row['average_position'])) {
                   $average_position = number_format($row['average_position'], 2);
                } else {
                    $average_position = 0;
                }
                $data_arr[] = array($average_position, $row['estimated_traffic'], $row['keyword_effectiveness_index'], $temp_weather, $dateGR[$key]);
            } else {
                $data_arr[] = array(0, 0, 0, $temp_weather, $dateGR[$key]);
            }

        }


        /*print_r($data_arr);

        exit;*/
        return $data_arr;
    }
}