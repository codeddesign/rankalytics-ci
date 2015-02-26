<?php

class Analytical_Model extends CI_Model
{

    function __construct()
    {
        $this->pgsql = $this->load->database('pgsql', true);
    }

    public function getAnalyticalData($limit_start, $limit_end)
    {
        $this->pgsql->select('*');
        $this->pgsql->from('crawled_sites');
        $this->pgsql->limit($limit_start, $limit_end);
        $query = $this->pgsql->get();
        return $query->result_array();
    }

    public function getRandomProxy()
    {
        $this->pgsql->select('*');
        $this->pgsql->from('proxy');
        #$this->pgsql->where('google_blocked', 0);
        $this->pgsql->where('for_crawler', "others");
        $this->pgsql->order_by("id", "random");
        $query = $this->pgsql->get();
        return $query->result_array();
    }

    public function getKeywords($category, $limit, $ids, $for = "ajax")
    {
        $this->pgsql->select('unique_id,keyword,uid');
        $this->pgsql->from('tbl_project_keywords');
        if ($for == "ajax") {
            $this->pgsql->like('keyword', $category);
        }

        $this->pgsql->limit($limit);
        $this->pgsql->where_not_in("project_id", "python_generated");
        $this->pgsql->where_in('uid', $ids);

        $query = $this->pgsql->get();
        return $query->result_array();
    }

    public function getKeywordByKeyword($keyword)
    {
        $this->pgsql->select('total_records,total_search, unique_id');
        $this->pgsql->from('tbl_project_keywords');
        $this->pgsql->where('keyword', $keyword);
        $this->pgsql->limit(1);
        $query = $this->pgsql->get();
        return $query->result_array();
    }

    public function getTopTenCrawledSiteByKeyword($keyword)
    {
        $this->pgsql->distinct();
        $this->pgsql->select('site_url, rank, title, description, host, header_tags, page_rank');
        $this->pgsql->from('crawled_sites');
        $this->pgsql->where('LOWER(keyword)', mysql_real_escape_string(strtolower($keyword)));
        $this->pgsql->or_where('keyword', mysql_real_escape_string($keyword));
        $this->pgsql->or_where('keyword', mysql_real_escape_string(strtolower($keyword)));

        $this->pgsql->limit(30);
        $query = $this->pgsql->get();
        $resutl = $query->result_array();
        
        $final_array = array();
        $temp_array = array();
        foreach ($resutl as $key => $value) {
            $temp_array[$key] = $value['rank'];
        }

        asort($temp_array);
        foreach ($temp_array as $key => $value) {
            $final_array[$key] = $resutl[$key];
        }

        return $final_array;
    }

    public function getAdWordInfoByID($id)
    {
        $query = "SELECT * FROM project_keywords_adwordinfo WHERE keyword_id =  '" . $id . "'";
        $query .= "AND created_on = ( SELECT MAX( created_on )";
        $query .= "FROM project_keywords_adwordinfo WHERE keyword_id =  '" . $id . "' LIMIT 1 ) LIMIT 1";

        $query = $this->pgsql->query($query);
        return $query->result_array();
    }

    public function getAdWordInfoByKeyword($keyword)
    {
        $query = "SELECT *
            FROM  `project_keywords_adwordinfo` 
            WHERE LOWER(keyword) =  '" . mysql_real_escape_string(strtolower($keyword)) . "'
            OR keyword =  '" . mysql_real_escape_string($keyword) . "'
            OR keyword =  '" . mysql_real_escape_string(strtolower($keyword)) . "'
            AND created_on = ( 
            SELECT MAX( created_on ) 
            FROM project_keywords_adwordinfo
            WHERE LOWER(keyword) =  '" . mysql_real_escape_string(strtolower($keyword)) . "'
            OR keyword =  '" . mysql_real_escape_string($keyword) . "'
            OR keyword =  '" . mysql_real_escape_string(strtolower($keyword)) . "'
            LIMIT 1 ) LIMIT 1";
        $query = $this->pgsql->query($query);
        return $query->result_array();
    }

    public function getGoogleTemperature()
    {
        $this->pgsql->select('MAX(date_crawled) AS max_date');
        $this->pgsql->from('tbl_mozcast');
        $this->pgsql->limit(1);
        $query = $this->pgsql->get();
        $resutl_array = $query->result_array();
        if (!empty($resutl_array)) {
            $this->pgsql->select('temperature,date');
            $this->pgsql->from('tbl_mozcast');
            $this->pgsql->where_in('date_crawled', @$resutl_array[0]['max_date']);
            $this->pgsql->limit(30);
            $query = $this->pgsql->get();
            $resutl_array = $query->result_array();
        }

        return $resutl_array;
    }
}
