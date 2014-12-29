<?php

class project_Keyword_Relation_Model extends CI_Model
{
    var $_tablename;

    function __construct()
    {
        $this->_tablename = "project_keyword_relation";
        $this->pgsql = $this->load->database('pgsql', true);
    }

    public function saveBulk($domain_array)
    {
        return $this->pgsql->insert_batch($this->_tablename, $domain_array);
    }

    public function delete($where, $limit = 0)
    {
        if ($limit != 0) {
            $this->pgsql->limit($limit);
        }
        if (empty($where)) {
            return 0;
        }
        if ($this->pgsql->delete($this->_tablename, $where)) {
            return 1;
        } else {
            return 0;
        }
    }

    public function deleteByProjectId($project_id)
    {
        //print_r($id);
        $this->pgsql->where(array("project_id" => $project_id));
        if ($this->pgsql->delete($this->_tablename)) {
            return 1;

        } else {
            return 0;
        }

    }

    public function checkKeywordId($keyword_id, $projectId)
    {
        $this->pgsql->select('*');
        $this->pgsql->from($this->_tablename);
        $condition = array('keyword_id' => $keyword_id, "project_id" => $projectId);
        $this->pgsql->where($condition);
        $this->pgsql->limit(1);
        $query = $this->pgsql->get();
        return $query->num_rows();
    }


}