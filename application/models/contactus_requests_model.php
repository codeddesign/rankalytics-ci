<?php

class Contactus_requests_model extends CI_Model {
	var $_tablename;
        var $_validation_rules ;

	function __construct() 
        {
            $this->_tablename="contactus_requests";
            $this->_validation_rules=array(
               array(
                     'field'   => 'fullName',
                     'label'   => 'Full Name',
                     'rules'   => 'required'
                  ),
               array(
                     'field'   => 'emailAddress',
                     'label'   => 'Email Address',
                     'rules'   => 'required|valid_email'
                  ),
               array(
                     'field'   => 'message',
                     'label'   => 'Message',
                     'rules'   => 'required'
                  ));
	}
	public function save($users_array) 
        {
            $this->db->set($users_array);
            $this->db->set('createdOn','now()',FALSE);
            if($this->db->insert($this->_tablename)){
                return $users_array['id'];
            }else{
                return 0;
            }
            
            return ;
	}
        
        /*public function update($data,$where,$limit=0){
            $this->db->where($where);
            if($limit!=0){
                $this->db->limit($limit);
            }
            return $this->db->update($this->_tablename, $data); 
        }*/
        
      
}