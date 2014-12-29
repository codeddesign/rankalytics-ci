<?php

class webhook_refunds_model extends CI_Model {
	var $_tablename = "webhook_refunds";
        
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
        public function getRefundsByClientId($clientid){
            $this->db->where(array("client_id"=>$clientid));
            $this->db->from($this->_tablename);
            return $this->db->get()->result_array();
        }
        
}