<?php
class Countries_Model extends CI_Model
{

    function __construct()
    {
        $this->_tablename = "countries";
    }

    /* returns array */
    function getAll() {
        return $this->db->select('*')->from($this->_tablename)->get()->result_array();
    }
}