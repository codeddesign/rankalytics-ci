<?php

class Webhook_payments_Model extends CI_Model {
	var $_tablename = "webhook_payments";
        
	function __construct() 
        {
            
	}
	public function save($payment_array) 
        {
            $this->load->model("common_model");
            $id = $this->common_model->getNewId($this->_tablename);
            $payment_array['id']=$id;
            $this->db->set($payment_array);
            $this->db->set("createdOn","now()",FALSE);
            $this->db->insert($this->_tablename);
            return $payment_array['id'];//$this->db->insert_id();
	}
        function getPaymentsByClientId($clientId){
            $this->db->select("*")->from($this->_tablename)->where(array("client_id"=>$clientId));
            $query = $this->db->get();
            return $query->result_array();
        }
}