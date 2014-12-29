<?php

class Project_Keywords_Adwordinfo_Model extends CI_Model
{
    var $_tablename;

    function __construct()
    {
        $this->_tablename = "project_keywords_adwordinfo";
        $this->pgsql = $this->load->database('pgsql', true);
    }

    public function get_latest_keywordinfo_by_keywordid($keyword_id)
    {
        //$this->db->query("set max_length_for_sort_data = 2048");

        $this->pgsql->select("MAX(created_on) as max_date");
        $this->pgsql->from($this->_tablename);
        $this->pgsql->where(array("keyword_id" => $keyword_id));
        $this->pgsql->limit(1);
        $query = $this->pgsql->get();
        $result = $query->result_array();
        if (isset($result['0'])) {
            $this->pgsql->select("CPC,keyword,volume,competition,keyword_id,id,created_on");
            $this->pgsql->from($this->_tablename);
            $this->pgsql->where(array("keyword_id" => $keyword_id));
            $this->pgsql->where("created_on", $result[0]['max_date']);
            $this->pgsql->limit(1);
            $query = $this->pgsql->get();
            $result = $query->result_array();
            return @$result['0'];
        } else
            return array();
    }

    public function get_dated_keywordinfo_by_keywordid($keyword_id, $date)
    {
        $this->pgsql->select("*");
        $this->pgsql->from($this->_tablename);
        $this->pgsql->where("keyword_id", $keyword_id);
        //$this->db->order_by("created_on desc");
        $this->pgsql->limit(1);
        $query = $this->pgsql->get();
        //echo "<br>".$this->db->last_query();
        $result = $query->result_array();
        if (isset($result['0']))
            return $result['0'];
        else
            return array();
    }


}