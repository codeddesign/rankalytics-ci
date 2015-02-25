<?php

class Pplans_Model extends CI_Model
{

    function __construct()
    {
        $this->_tablename = "user_paypal_plans";
    }

    /**
     * @param array $condition
     *
     * @return mixed
     */
    public function getAll( array $condition = array() )
    {
        $this->db->select( '*' )->from( $this->_tablename );
        if (count( $condition ) > 0) {
            $this->db->where( $condition );
        }

        return $this->db->get()->result_array();
    }

    /**
     * @param array $condition
     *
     * @return mixed
     */
    public function getFirstWhere( $condition )
    {
        $result = $this->getAll( $condition );
        if ( ! count( $result )) {
            return false;
        }

        return $result[0];
    }

    /**
     * @param array $plans
     *
     * @return mixed
     */
    function doSave( array $plans )
    {
        if (is_array( $plans[key( $plans )] )) {
            $this->db->insert_batch( $this->_tablename, $plans );
            return true;
        }

        $this->db->set( $plans );
        $this->db->insert( $this->_tablename );

        return true;
    }
}