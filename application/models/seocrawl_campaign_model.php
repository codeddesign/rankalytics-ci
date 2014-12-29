<?php
class Seocrawl_Campaign_Model extends CI_Model {
    var $_tablename;

    function __construct() {
        $this->_tablename = 'seocrawl_campaign';
    }

    /*
     * ads a new campaign
     * returns: inserted id;
     * */
    function add($info) {
        $this->db->set($info);
        $this->db->insert($this->_tablename);

        return $this->db->insert_id();
    }

    /*
     * gets a list of campaigns and their information by user_id
     * sorted by added date, descending
     * ! if $userId = null it gets all of them <- used for admins
     * * ^ returns false if no match
     * */
    function getAll($userId = null, $campaignId = null, $limit = null, $offset = 0) {
        // ..
        $cond = array();
        $this->db->select('*')->from($this->_tablename)->order_by('added_at', 'desc');

        // checks:
        if($userId != null) {
            $cond['user_id'] = $userId;
        }

        if($campaignId != null) {
            $cond['id'] = $campaignId;
        }

        // apply conditions .. ?
        if(count($cond) > 0) {
            $this->db->where($cond);
        }

        // apply limits:
        if($limit != null) {
            $this->db->limit($limit, $offset);
        }

        // getting data
        $r = $this->db->get()->result_array();

        // if no result:
        if(count($r) == 0) {
            return FALSE;
        }

        // return ALL:
        if(!isset($cond['id'])) {
            return $r;
        }

        // return SOLO:
        return $r[0];
    }

    /*
     * returns campaign information by id + userId
     * false if match fails
     * */
    function getOne($userId, $campaignId) {
        return $this->getAll($userId, $campaignId);
    }

    /*
     * gets all user's campaigns
     * */
    function getAllByUserId($userId) {
        return $this->getAll($userId, null);
    }

    /*
     * returns number of campaigns
    */
    function total($userId) {
        $this->db->select('user_id');
        $this->db->from($this->_tablename);
        $r = $this->db->where('user_id', $userId)->get()->result_array();

        return count($r);
    }

    /*
     * function is being called from admin area
     * it updates: 'completed' value, number of links found, drop box link
     * ^ by campaign id.
     * */
    function updateById($campId, $info) {
        $this->db->set($info);
        $this->db->where('id', $campId);
        $this->db->update($this->_tablename);
    }
}