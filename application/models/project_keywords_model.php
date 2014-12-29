<?php

class Project_Keywords_Model extends CI_Model
{
    var $_tablename;

    function __construct()
    {
        $this->_tablename = "tbl_project_keywords";
        $this->pgsql = $this->load->database('pgsql', true);
    }

    #boovad:
    public function saveBulk($keywords_arr) {
            return $this->pgsql->insert_batch($this->_tablename, $keywords_arr);
    }

    public function saveKeyword($keyword_array)
    {
        $this->pgsql->set($keyword_array);
        $this->pgsql->set("uploadedOn", "now()", FALSE);
        $this->pgsql->insert($this->_tablename);
        return $keyword_array['unique_id'];
    }

    public function deleteByProjectId($project_id)
    {
        $this->pgsql->where(array("project_id" => $project_id));
        if ($this->pgsql->delete($this->_tablename)) {
            return 1;

        } else {
            return 0;
        }

    }

    public function KeywordsUnique($keywords = "")
    {
        $this->pgsql->select('unique_id ,keyword');
        $this->pgsql->from($this->_tablename);
        $this->pgsql->where_in('keyword', $keywords);
        $query = $this->pgsql->get();
        return $query->result_array();
    }

    public function isKeywordExists($keyword, $projectId)
    {
        $this->pgsql->select('*');
        $this->pgsql->from($this->_tablename);
        $condition = array('keyword' => $keyword, "project_id" => $projectId);
        $this->pgsql->where($condition);
        $this->pgsql->limit(1);
        $query = $this->pgsql->get();
        return $query->num_rows();
    }

    public function insertKeywords($keyword_array)
    {
        $this->pgsql->insert_batch($this->_tablename, $keyword_array);
    }

    public function deleteKeywordByProjectId($project_id)
    {
        if (!empty($project_id) && $project_id != 0) {
            $this->pgsql->where(array("project_id" => $project_id));
            if ($this->pgsql->delete($this->_tablename)) {

                return 1;
            } else {
                return 0;
            }
        }
    }

    // returns array;
    function getKeywordIdsByProjectId($id) {
        $this->pgsql->select('unique_id')->from($this->_tablename)->where('project_id', $id);

        $keyword_ids = array();
        foreach($this->pgsql->get()->result_array() as $k_no => $info) {
            $keyword_ids[] = $info['unique_id'];
        }

        return $keyword_ids;
    }

    function getNumberOfKeywordsByUser($userId) {
        $r = $this->pgsql->select('keyword')->from($this->_tablename)->where('uid', $userId)->get()->result_array();
        if(!is_array($r)) {
            return 0;
        } else {
            return count($r);
        }
    }
}