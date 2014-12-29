<?php

class user_account_logs_model extends CI_Model {
	var $_tablename = "user_account_logs";
	function __construct() 
        {
            
	}
	public function save($data_array) 
        {
            $this->load->model("common_model");
            $id = $this->common_model->getNewId($this->_tablename);
            $data_array['id']=$id;
            $this->db->set($data_array);
            $this->db->set("createdOn","now()",FALSE);
            $this->db->insert($this->_tablename);
            return $data_array['id'];//$this->db->insert_id();
	}
        function getUserAccountLogByUserid($userId){
            $logs_arr = $this->db->select("*")->from($this->_tablename)->where(array("user_id"=>$userId))->get()->result_array();
            //print_r($logs_arr)
            return $logs_arr;
        }
}