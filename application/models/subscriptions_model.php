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
            'started_on <' => $current['started_on'],
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
    function getSubscriptionInfo($user_id, $service, $single = true, $dateOrdered = true, $special = true)
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
        if($special) {
            $special_ops = array('downgrade', 'extension');
            $this->db->where_not_in('operation', $special_ops);

            // or get last approved downgrade/extension:
            foreach ($special_ops as $s_no => $special) {
                $or_part = '(operation="' . $special . '" AND status="approved" AND user_id="' . $user_id . '"';
                if ($service !== null) {
                    $or_part .= ' AND service="' . $service . '"';
                }
                $or_part .= ')';
                $this->db->or_where($or_part);
            }
        }

        if ($single) {
            $this->db->limit(1);
        }

        if ($dateOrdered) {
            $this->db->order_by('started_on', 'desc');
        }

        $response = $this->db->get()->result_array();

        if (count($response) == 1) {
            return $response[0];
        }

        if (count($response) > 1) {
            return $response;
        }

        return false;
    }

    /**
     * @param array $info
     * @return mixed
     */
    function doSave(array $info)
    {
        $this->db->set($info);
        $q = $this->db->insert($this->_tablename);
        return $q;
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
}