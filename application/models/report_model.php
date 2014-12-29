<?php

class Report_model extends CI_Model {
	var $_tablename;
        var $_validation_rules ;
        function __construct() 
        {
            $this->_tablename="tbl_rt_report";
            $this->_validation_rules=array(
               array(
                     'field'   => 'report_name',
                     'label'   => 'Report Name',
                     'rules'   => 'required'
                  ),
               array(
                     'field'   => 'select_domain',
                     'label'   => 'Select Domain',
                     'rules'   => 'required'
                  ),
               array(
                     'field'   => 'start_date',
                     'label'   => 'Start Date',
                     'rules'   => 'required'
                  ),
            array(
                     'field'   => 'end_date',
                     'label'   => 'End Date',
                     'rules'   => 'required'
                  ));
	}

public function save($users_array) 
        {
            $this->db->set($users_array);
            $this->db->set('created_date','now()',FALSE);
            if($this->db->insert($this->_tablename)){
                return 1;
            }else{
                return 0;
            }
            
            return ;
	}
public function delete($id) 
        {
            $this->db->set($users_array);
            $this->db->set('created_date','now()',FALSE);
            if($this->db->delete($this->_tablename)){
                return 1;
            }else{
                return 0;
            }
            
            return ;
	}
        
}
?>
