<?php
class Webhook_transactions_Model extends CI_Model {
	var $_tablename = "webhook_transactions";
	function __construct() 
        {
	}
	public function save($transaction_array) 
        {
            $this->load->model("common_model");
            $id = $this->common_model->getNewId($this->_tablename);
            $transaction_array['id']=$id;
            print_r($transaction_array);
            $this->db->set($transaction_array);
            $this->db->set("createdOn","now()",FALSE);
            $this->db->insert($this->_tablename);
            return $transaction_array['id'];//$this->db->insert_id();
	}
        function getTransactionByPaymentId($paymentId){
            $this->db->select("*")->from($this->_tablename)->where(array("payment_id"=>$paymentId));
            $query = $this->db->get();
            return $query->result_array();
        }
        public function getTransactionByClientId($clientid){
            $this->db->where(array("client_id"=>$clientid));
            $this->db->from($this->_tablename);
            return $this->db->get()->result_array();
            
        }
}