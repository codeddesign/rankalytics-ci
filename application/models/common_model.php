<?php

class Common_Model extends CI_Model
{

    function __construct()
    {

    }

    public function createUniqueId($unique = array("rand" => ''))
    {
        if ($unique['rand'] == '') {
            $rand = rand(1, 100000);
            $md5 = md5(time() . $rand);
        } else {
            $md5 = md5($unique['rand']);
        }
        return $md5;
    }

    public function getNewId($tablename)
    {
        //$this->db->select("id")->from($tablename)->order_by("id desc")->limit(1);
        $this->db->select_max("id")->from($tablename);

        $query = $this->db->get();
        if (!$query->num_rows()) {
            return 1;
        } else {
            $ids = $query->result_array();

            $id = $ids[0]['id'];
            $id++;
            return $id;
        }
    }

    //function saveNewKeywordAdword($keywordIds,$projectId=''){
    function saveNewKeywordAdword($projectId = '')
    {
        //print_r($keywordIds);

        //print_r($projectId);
        //if(is_array($keywordIds) && !empty($keywordIds) && $projectId != ''){
        if ($projectId != '') {

            // Get cURL resource
            $curl = curl_init();
            // Set some options - we are passing in a useragent too here
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => 'http://5.101.106.7/run_adword.php?projectId='.$projectId,
                CURLOPT_USERAGENT => 'Codular Sample cURL Request',
            ));
            // Send the request & save response to $resp
            $resp = curl_exec($curl);
            curl_close($curl);

            return $resp;
        } else {

            return false;
        }
    }

    //function crawlNewKeyword($keywordIds,$projectId=''){
    function crawlNewKeyword($projectId = '')
    {
        //print_r($keywordIds);
        //echo "[roject".$projectId;
        //if(is_array($keywordIds) && !empty($keywordIds) && $projectId != ''){
        if ($projectId != '') {

            // Get cURL resource
            $curl = curl_init();
            // Set some options - we are passing in a useragent too here
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => 'http://5.101.106.7/small_crawler/crawler0.php',
                CURLOPT_USERAGENT => "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.16) Gecko/20080702 Firefox/2.0.0.16",
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => array("projectId" => $projectId)

            ));
            // Send the request & save response to $resp
            $resp = curl_exec($curl);
            // Close request to clear up some resources
            //print_r($resp);
            curl_close($curl);

            return $resp;
        } else {

            return false;
        }
    }

    function array_to_csv($array, $download = "")
    {

        /*if ($download != "")
        {
            echo "in jheader ";
            header('Content-Type: application/csv');
            header('Content-Disposition: attachement; filename="' . $download . '"');
        } */

        ob_start();
        //$f = fopen('php://output', 'w') or die("Can't open php://output");
        $f = fopen($download, 'w') or die("Can't open php://output");
        $n = 0;
        foreach ($array as $line) {
            $n++;
            if (!fputcsv($f, $line)) {
                die("Can't write line $n: $line");
            }
        }
        fclose($f) or die("Can't close php://output");
        $str = ob_get_contents();
        ob_end_clean();
//echo "str".$str;
        if ($download == "") {
            return $str;
        } else {
            echo $str;
        }
    }

    public function get_keyword_limit($acctype)
    {
        if ($acctype == "" OR $acctype == 'none') {
            $acctype = "free";
        }

        $plan = $this->config->item('subscription_plans');
        $limit = $plan[$acctype]['keywordsAllowed'];
        return $limit;
    }

    /* not being used - SHOULDN'T ! */
    public function keywords_used($user_id)
    {
        $this->db->select('pk.keyword_id');
        $this->db->from('project_keyword_relation AS pk');
        $this->db->join('tbl_project AS p', 'p.id = pk.project_id');
        $this->db->where('p.userId', $user_id);
        $result = $this->db->get();
        $count = $result->num_rows();;
        return $count;
    }

    public function getFileLastModifiedDate($fileName)
    {

        if (file_exists($fileName)) {
            //return date ("F d Y H:i:s.", filemtime($fileName));
            return date("Y-m-d", filemtime($fileName));
        } else {
            return false;
        }
    }
}
