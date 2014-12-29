<?php

class webhook_subscriptions_model extends CI_Model {
	var $_tablename = "webhook_subscriptions";
        
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
}