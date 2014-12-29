<?php

class Paymilltransactions_Model extends CI_Model {
	var $_tablename;
	function __construct() 
        {
            $this->_tablename='paymilltransactions';
	}
        
	public function saveSubscription($subscription) 
        {
            $this->load->model("common_model");
            $newid=$this->common_model->getNewId($this->_tablename);
            
            $subscription_array["id"] = $newid;
            $subscription_array["subscription_id"] = $subscription['id'];
            $subscription_array["offerId"] = $subscription['offer']['id'];
            $subscription_array["livemode"] = ($subscription['livemode']==''||$subscription['livemode']==null)?" ":$subscription['livemode'];
            $subscription_array["cancel_at_period_end"] = ($subscription['cancel_at_period_end']==''||$subscription['cancel_at_period_end']==null)?" ":$subscription['cancel_at_period_end'];
            $subscription_array["trial_start"] = ($subscription['trial_start']==''||$subscription['trial_start']==null)?" ":$subscription['trial_start'];
            $subscription_array["trial_end"] = ($subscription['trial_end']==''||$subscription['trial_end']==null)?" ":$subscription['trial_end'];
            $subscription_array["next_capture_at"] = $subscription['next_capture_at'];
            $subscription_array["created_at"] = $subscription['created_at'];
            $subscription_array["updated_at"] = $subscription['updated_at'];
            $subscription_array["canceled_at"] = ($subscription['canceled_at']==''||$subscription['canceled_at']==null)?" ":$subscription['canceled_at'];;
            $subscription_array["payment_id"] = $subscription['payment']['id'];
            $subscription_array["app_id"] = ($subscription['app_id']==''||$subscription['app_id']==null)?" ":$subscription['app_id'];
            $subscription_array["paymill_client_id"] = $subscription['client']['id'];
            $subscription_array["user_id"] = $subscription['user_id'];
            $this->db->set($subscription_array);
            $this->db->set("createdOn","now()",FALSE);
            $this->db->insert($this->_tablename);
            return $subscription_array["id"];//$this->db->insert_id();
	}
        public function getSubscriptionByUserid($userId){
            $this->db->select("*")->from($this->_tablename)->where(array("user_id"=>$userId))->or_where_in("trim(canceled_at) ",array("",null));
            
            $query = $this->db->get();
            //echo $this->db->last_query();
            return $query->result_array();
        }
        public function updateTable($data,$condition, $limit=0){
            $this->db->where($condition);
            if($limit!=0){
                $this->db->limit($limit);
            }
            return $this->db->update($this->_tablename, $data);
        }
}