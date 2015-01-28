<?php

class Users_Model extends CI_Model
{
    var $_tablename;
    var $_validation_rules;
    var $_validation_userInfo;

    function __construct()
    {
        $this->_tablename = "users";
        $this->_validation_userInfo = array(
            array(
                'field' => 'firstName',
                'label' => 'First Name',
                'rules' => 'required'
            ),
            array(
                'field' => 'lastName',
                'label' => 'Last Name',
                'rules' => 'required'
            ),

            array(
                'field' => 'phoneNumber',
                'label' => 'phone Number',
                'rules' => 'required'
            ),
            array(
                'field' => 'streetAddress',
                'label' => 'street Address',
                'rules' => 'required'
            ),
            array(
                'field' => 'city',
                'label' => 'city',
                'rules' => 'required'
            ),
            array(
                'field' => 'zipCode',
                'label' => 'zip Code',
                'rules' => 'required'
            ),
            array(
                'field' => 'country',
                'label' => 'Country',
                'rules' => 'required'
            ));

        $this->_validation_companyInfo = array(array(
            'field' => 'companyName',
            'label' => 'Company Name',
            'rules' => 'required'
        ));
        $this->_validation_billingInfo = array(
            array(
                'field' => 'cardHolderName',
                'label' => 'Card Holder Name',
                'rules' => 'required'
            ),
            array(
                'field' => 'creditCardNumber',
                'label' => 'Credit Card Number',
                'rules' => 'required'
            ),
            array(
                'field' => 'expireMonth',
                'label' => 'Expire Month',
                'rules' => 'required|integer|max_length[2]'
            ),
            array(
                'field' => 'expireYear',
                'label' => 'Expire Year',
                'rules' => 'required|integer|max_length[4]'
            ),
            array(
                'field' => 'cvvCvc',
                'label' => 'cvv / cvc',
                'rules' => 'required'
            ));
    }

    /*        public function getNewId(){
                            $this->load->model("common_model");
                $id = $this->common_model->getNewId($this->_tablename);


                $id = $ids[0]['id'];

                $id ++;

                return $id ;

            }*/
    public function save($users_array)
    {
        $this->load->model("common_model");
        $id = $this->common_model->getNewId($this->_tablename);
        $users_array['id'] = $id;
        $this->db->set($users_array);
        $this->db->set('createdOn', 'now()', FALSE);
        //$users_array['createdOn']='now()';
        $this->db->insert($this->_tablename);
        return $users_array['id'];
    }

    function setAsPro($userId)
    {
        $user['status'] = 1;
        $user['isPaid'] = "yes";
        $this->db->set($user);
        $this->db->set("verifiedOn", 'now()', FALSE);
        $this->db->limit(1);
        $condition = array("id" => $userId);
        $this->db->where($condition);
        $this->db->update($this->_tablename);


    }

    public function getUserById($id)
    {
        $this->db->where(array('id' => $id));
        $query = $this->db->get($this->_tablename);

        return $query->result_array();
    }

    /*public function update($data,$where,$limit=0){
        $this->db->where($where);
        if($limit!=0){
            $this->db->limit($limit);
        }
        return $this->db->update($this->_tablename, $data);
    }*/

    public function doLogin($login_array = array())
    {
        if (empty($login_array)) return 'invalid';
        $this->db->select('id,firstName,lastName,emailAddress,status,isPaid,createdOn,userRole,accountType');
        $this->db->from($this->_tablename);
        $this->db->where(array('emailAddress' => $login_array['emailAddress'], 'password' => md5($login_array['password'])));
        $this->db->limit(1);
        $query = $this->db->get();

        $found = 0;
        if ($query->num_rows()) {
            $found = 1;
        } else {
            $this->db->select('id,firstName,lastName,emailAddress,status,isPaid,createdOn,userRole,accountType');
            $this->db->from($this->_tablename);
            $this->db->where(array('userName' => $login_array['emailAddress'], 'password' => md5($login_array['password'])));
            $this->db->limit(1);
            $query = $this->db->get();
            if ($query->num_rows()) {
                $found = 1;
            }
        }
        //echo $this->db->last_query();

        if ($found == 1) {
            $session_array = $query->result_array();
            $this->session->set_userdata('logged_in', $session_array);
            if (isset($login_array['remember']) && $login_array['remember'] == 1) {
                $this->config->set_item('sess_expire_on_close', false);
                $this->session->sess_expiration = 500000;

            } else {
                $this->config->set_item('sess_expire_on_close', '1');
            }

            return 'valid';
        } else {

            return "invalid";
        }
    }

    public function isUserExists($where = array())
    {
        if (empty($where) || !is_array($where)) {
            return false;
        }
        $this->db->select('*');
        $this->db->from($this->_tablename);
        $this->db->where($where);
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function verifyUser($userId, $code)
    {
        $this->db->select('*');
        $this->db->from($this->_tablename);
        $this->db->where(array("id" => $userId, "verificationCode" => $code)); // still need to check the date sent
        $this->db->where_in(array("status" => array("0", null))); // still need to check the date sent
        $this->db->limit(1);
        $query = $this->db->get();
        //echo "<br>in select".$this->db->last_query();
        if ($query->num_rows()) {
            $user_arr = $query->result_array();
            $datetime1 = new DateTime(date('Y-m-d'));
            $datetime2 = new DateTime($user_arr['0']['verificationCodeSentDate']);
            $interval = $datetime1->diff($datetime2);
            $interval = abs($interval->format('%R%a days'));

            if (abs($interval) > 14) {
                return false;
            }
            $data = array("verifiedOn" => date("Y-m-d"), "status" => 1);
            $condition = array("id" => $userId);
            $res = $this->updateTable($data, $condition);

            //  echo "<br>in update ".$this->db->last_query();
            return $res;
        } else {
            return false;
        }
    }

    public function isLoggedIn()
    {
        $user = $this->session->userdata('logged_in');
        if (isset($user['0']['id']) && $user['0']['id'] >= 1) { // redirect to dashboard if logged in
            return $user['0']['id'];
        } else {
            return false;
        }
    }

    public function isVerified($userId)
    {
        $user = $this->getUserById($userId);
        if ($user['0']['status'] == 0 || $user['0']['status'] == null) {
            return 0;
        } else {
            return 1;
        }
    }

    public function isAdmin()
    {
        $user = $this->session->userdata('logged_in');
        if (isset($user['0']['id']) && $user['0']['id'] >= 1 && $user['0']['userRole'] == 'admin') { // redirect to dashboard if logged in
            return $user['0']['id'];
        } else {
            return false;
        }
    }


    public function getUserWhere($where = array())
    {

        $this->db->select("*")->from($this->_tablename);
        $this->db->where($where);
        $rows = $this->db->get()->result_array();
        if (isset($rows['0'])) {
            return $rows;
        } else {
            return array();
        }
    }

    public function getUseridByUsername($userName)
    {
        $rows = $this->getUserWhere(array("userName" => $userName));
        if (empty($rows)) {
            return 0;
        }
        return $rows[0]['id'];
    }

    public function get_active_users()
    {
        $this->db->select("*")->from($this->_tablename);
        //$this->db->where_not_in('status',array(0,5));// "0" is inactive status and "5" is closed status
        $rows = $this->db->get()->result_array();
        if (isset($rows['0'])) {
            return $rows;
        } else {
            return array();
        }
    }

    public function getUserDetails($userid)
    {
        $userDetailArr = $this->getUserById($userid);
        $userInfo['details'] = $userDetailArr['0'];
        $userInfo['details']['userType'] = $this->getUserType($userDetailArr['0']);
        $userInfo['history'] = array();

        return $userInfo;
    }

    public function getUserHistory($userid, $clientid)
    {
        $this->load->model('user_account_logs_model', "user_account_logs");
        $logs = $this->user_account_logs->getUserAccountLogByUserid($userid);
        if (is_array($logs)) {
            return $logs;
        } else {
            return array();
        }

    }

    public function getUsersCount($searchString = '', $mainId = 0)
    {
        $where = '';
        if ($searchString != '') {
            if ($where != '') {
                $where .= 'and ';

            }
            $where .= '(firstName  like "%' . $searchString . '%" or lastName like "%' . $searchString . '%" or emailAddress like "%' . $searchString . '%")  ';
        }

        if ($mainId >= 1) {
            if ($where != '') {
                $where .= 'and ';
            }
            $where .= 'mainId="' . $mainId . '"';
        }
        if ($where != '')
            $this->db->where($where, null, false);

        return $this->db->count_all_results($this->_tablename);
    }

    public function getUsers($searchString = '', $limit = array(), $mainId = 0, array $order = array())
    {
        $where = '';
        if ($searchString != '') {
            if ($where != '') {
                $where .= 'and ';

            }
            $where .= '(lower(firstName)  like lower("%' . $searchString . '%") or lower(lastName) like lower("%' . $searchString . '%") or lower(emailAddress) like lower("%' . $searchString . '%"))  ';
        }

        if (!empty($limit) && isset($limit[0])) {
            if (isset($limit[1])) {
                $this->db->limit($limit[1], $limit[0]);
            } else {
                $this->db->limit($limit[0]);
            }
        }
        if ($mainId >= 1) {
            if ($where != '') {
                $where .= 'and ';
            }
            $where .= 'mainId="' . $mainId . '"';
        }

        if ($where != '')
            $this->db->where($where, null, false);

        if(count($order) > 0) {
            $this->db->order_by($order['by'], $order['how']);
        }

        $users = $this->db->get($this->_tablename)->result_array();

        if (is_array($users)) {
            foreach ($users as $key => $user) {
                $users[$key]['userType'] = $this->getUserType($user);
            }
        }
        return $users;
    }

    function getUserType($user = array())
    {
        if (empty($user)) {
            return false;
        }
        if ($user['isPaid'] == 'yes' && $user['accountType'] == 'pro') {
            return "pro";
        } elseif ($user['isPaid'] == 'yes' && $user['accountType'] == 'enterprise') {
            return "enterprise";
        } elseif ($user['pro_allowed'] == 'yes' && $user['allowed_upto'] >= date('Y-m-d')) {
            return "pro";
        } else {
            return "free";
        }
    }

    function getUserTypeById($userId)
    {
        $result = $this->db->select("*")->from($this->_tablename)->where(array("id" => $userId))->get()->result_array();
        if (is_array($result)) {
            return $this->getUserType($result['0']);
        } else {
            return false;
        }

    }

    public function updateTable($data, $condition, $limit = 0)
    {
        $this->db->where($condition);
        if ($limit != 0) {
            $this->db->limit($limit);
        }
        return $this->db->update($this->_tablename, $data);
    }

    public function delete($where, $limit = 0)
    {
        if ($limit != 0) {
            $this->db->limit($limit);
        }
        if (empty($where)) {
            return false;
        }
        return $this->db->delete($this->_tablename, $where);;
    }

    public function getRelatedUsers($id)
    {
        $main_ids = array();
        $this->db->select("id,mainId");
        $this->db->from($this->_tablename);
        $this->db->where("id", $id);
        $this->db->or_where("mainId", $id);
        $query = $this->db->get();
        $result = $query->result_array();
        foreach ($result as $value) {
            $main_ids[] = $value['id'];
        }
        return $main_ids;
    }

    public function save_access_token($user_id, $acesstoken)
    {
        $data = array('acess_token' => $acesstoken);
        $this->db->where('id', $user_id);
        $this->db->update('users', $data);
    }

}
