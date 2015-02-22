<?php

class Subscriptions_Model extends CI_Model
{

    function __construct()
    {
        $this->_tablename = "user_subscriptions";
    }

    /**
     * @param array $current
     * @return bool|array
     */
    function getPreviousSubscription(array $current) {
        $condition = array(
            'status !=' => 'pending',
            'user_id' => $current['user_id'],
            'created_on <' => $current['created_on'],
            'service' => $current['service'],
        );

        $result = $this->db->select('*')->from($this->_tablename)->where($condition)->limit(1)->get()->result_array();
        if(count($result) == 1) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * @param array $condition
     * @return array|bool
     */
    function getSubscriptionBy(array $condition)
    {
        $this->db->select('*')->from($this->_tablename);
        $this->db->where($condition);
        $this->db->limit(1);
        $response = $this->db->get()->result_array();

        if (count($response) == 1) {
            return $response[0];
        } else {
            return false;
        }
    }

    /**
     * @param $user_id
     * @param $service
     * @param bool $single
     * @param bool $dateOrdered
     * @param bool $special
     * @return bool
     */
    function getSubscriptionInfo($user_id, $service, $single = true, $dateOrdered = true, $special = false)
    {
        //
        $condition = array(
            'user_id' => $user_id,
        );

        if ($service !== null) {
            $condition['service'] = $service;
        }

        // ..
        $this->db->select('*')->from($this->_tablename);
        $this->db->where($condition);
        $this->db->where_not_in('status', array('canceled'));

        if ($single) {
            $this->db->limit(1);
        }

        if ($dateOrdered) {
            $this->db->order_by('created_on', 'desc');
        }

        $response = $this->db->get()->result_array();

        if (count($response) == 1) {
            $paymentType_backup = $tempInfo = $response[0];
            if (trim( $tempInfo['status'] ) !== 'active') {
                $tempInfo           = Subscriptions_Lib::getDefaultNotSubscribed( $service );
                $tempInfo['status'] = $paymentType_backup['status'].' to '.$paymentType_backup['plan'];
            }

            return $tempInfo;
        }

        if (count($response) > 1) {
            return $response;
        }

        return false;
    }

    /**
     * @param array $subscriptions
     *
     * @return mixed
     */
    function doSave( array $subscriptions )
    {
        if (is_array( $subscriptions[key( $subscriptions )] )) {
            $this->db->insert_batch( $this->_tablename, $subscriptions );
            return true;
        }

        $this->db->set( $subscriptions );
        $this->db->insert( $this->_tablename );

        return true;
    }

    /**
     * @param $info
     * @param $condition
     * @return bool
     */
    function doUpdate($info, $condition)
    {
        $current = $this->db->select('*')->from($this->_tablename)->where($condition)->limit(1)->get()->result_array();

        if ($current AND count($current) > 0) {
            unset($info['user_id']);
            $q = $this->db->where($condition)->update($this->_tablename, $info);
            return $q;
        }

        return false;
    }

    /**
     * @param array $condition
     * @return mixed
     */
    function getAll(array $condition = array())
    {
        $this->db->select('*')->from($this->_tablename);
        if (count($condition) > 0) {
            $this->db->where($condition);
        }

        return $this->db->get()->result_array();
    }

    public function removeBySubId( $subId ){
        $this->db->delete($this->_tablename, array('order_id' => $subId));
    }
}