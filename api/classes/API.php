<?php

class API
{
    private $db = null, $pg = null;

    public $data = "", // common data
        $user_data = "", // hold user's data
        $_content_type = "application/json",
        $_method = "",
        $_code = 200,
        $_UID = 0,
        $_userInfo = array(), $_allow = array(), $_request = array();

    public $_restricted_commands = array(
        "DELETE",
        "SELECT",
        "UPDATE",
        "FROM",
        "JOIN",
        "INNER",
        "ORDER BY",
        "WHERE",
        "ON",
        "*",
        "OUTER JOIN",
        "LEFT JOIN",
        "RIGHT JOIN",
        "CROSS JOIN",
        "LIMIT",
        "COUNT",
        "SUM",
        "AVG"
    );

    protected $db_config;

    /**
     * __construct
     * @desc The constructer is used here for assign new values
     */
    public function __construct()
    {
        $config          = config_item( 'database' );
        $this->db_config = $config['default'];

        // Initiate MySQL Database connection:
        $this->dbConnect();

        // Initiate PostgreSQL Database object that holds helper methods and connection:
        $this->pg = new PostgreSQL();
    }


    /**
     * json
     * @desc Encrypt to Json
     */
    public function json( $data )
    {
        if (is_array( $data )) // if values exists
        {
            #todo - remove
            /*print_r($data);*/
            return json_encode( $data );
        }
    }

    /**
     * get_referer
     * @desc get server referer
     */
    public function get_referer()
    {
        return $_SERVER['HTTP_REFERER'];
    }

    /**
     * response
     * @desc print output ot world
     */
    public function response( $data )
    {
        echo $data;
    }

    /**
     * get_status_message
     * @desc All status messages of API
     */

    public function get_status_message( $status_code )
    {
        $status = array(
            100 => 'Project Name Already Exist',
            101 => 'Invalid Project Name',
            102 => 'Empty Keyword',
            103 => 'Error In Domain Url/ Empty',
            105 => 'Invalid Project id/ Missing project id',
            106 => 'Project Not exists',
            107 => 'Invalid Project id/ Missing project name',
            108 => 'Missing Project ID and Keyword ID',
            109 => 'Missing Location',
            110 => 'The list of keywords exceeds your current subscription plan.',
            200 => 'OK',
            201 => 'Created',
            202 => 'Deleted',
            203 => 'Invalid Access Token',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Restricted Commands Found',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            306 => '(Unused)',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Error in Request',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported'
        );
        $this->set_headers( $status_code, ( $status[$status_code] ) ? $status[$status_code] : $status[500] );
        return ( $status[$status_code] ) ? $status[$status_code] : $status[500];
    }

    /**
     * get_request_method
     * @desc Get request details
     */
    public function get_request_method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * set_headers
     * @desc setting header to know which type of data we are output
     */

    public function set_headers( $status, $message )
    {
        header( "Content-Type:" . $this->_content_type );
        header( "HTTP/1.1 " . $status . " " . $message );
    }

    /**
     * dbConnect
     * @desc The function is used for db connectivity
     *
     */
    private function dbConnect()
    {
        $this->db = mysql_connect( $this->db_config['hostname'], $this->db_config['username'], $this->db_config['password'] );
        if ($this->db) {
            mysql_select_db( $this->db_config['database'], $this->db );
        }
    }

    /**
     * processApi
     * @desc This method dynmically call the method based on the query string
     *
     */
    public function processApi()
    {
        if ($this->isValidToken()) {
            $func = strtolower( trim( str_replace( "/", "", $_REQUEST['request'] ) ) );
            if (method_exists( $this, $func )) {
                $this->$func();
            } else {
                $this->response( $this->json( array( "status" => 404, "msg" => $this->get_status_message( 404 ) ) ) );// If the method not exist with in this class, response would be "Page not found".
            }
        }
    }

    /**
     * isValidToken
     * @desc Check it always the access token is correct or not
     *
     */
    public function isValidToken()
    {
        // Cross validation if the request method is POST else it will return "Not Acceptable" status
        if ($this->get_request_method() != "POST") {
            $this->response( '', 406 );
        }

        $token = mysql_real_escape_string( trim( $_REQUEST['token'] ) );
        if (strlen( $token ) == 32) {
            $query = "SELECT id, userRole, status FROM users WHERE access_token = '" . ( $token ) . "' LIMIT 1";
            $sql   = mysql_query( $query, $this->db );

            if (mysql_num_rows( $sql ) == 0) {
                $this->response( $this->json( array( "status" => 203, "msg" => $this->get_status_message( 203 ) ) ) );
                return false;
            } else {
                $user_array      = mysql_fetch_array( $sql, MYSQL_ASSOC );
                $this->_userInfo = $user_array; // <- keep all 'might' needed info
                $this->_UID      = $user_array['id']; // only the user's id;

                return true;
            }
        } else {
            $this->response( $this->json( array( "status" => 203, "msg" => $this->get_status_message( 203 ) ) ) );    // Number of Letters in access token faild
            return false;
        }
    }

    /**
     * returns number of words the user has added to his projects
     * @return int
     */
    private function getCurrentNumberOfKeywords()
    {
        $query = 'SELECT keyword FROM tbl_project_keywords WHERE uid=\'' . $this->_UID . '\'';
        return count( $this->pg->getResults( $query ) );
    }

    /**
     * returns maximum number of keywords for this user's subscription
     * @return int
     */
    private function getMaxLimit()
    {
        // service:
        $service = 'ranktracker';

        // get current subscription:
        $query = 'SELECT * FROM user_subscriptions WHERE service="' . $service . '"';
        $query .= ' AND user_id=\'' . $this->_UID . '\'';
        $query .= ' ORDER BY started_on DESC LIMIT 1';
        $sql = mysql_query( $query, $this->db );

        if (mysql_num_rows( $sql ) == 0) {
            // handle 'no subscription found'
            $sub_info = ( $this->_userInfo['userRole'] == 'admin' ) ? Subscriptions_Lib::getDefaultForAdmin( $service ) : Subscriptions_Lib::getDefaultNotSubscribed( $service );
        } else {
            $sub_info = mysql_fetch_array( $sql, MYSQL_ASSOC );
        }

        // handle 'pending':
        if ($sub_info['status'] !== 'approved') {
            $sub_info = Subscriptions_Lib::getDefaultNotSubscribed( $service );
        }

        // handle 'expiration' and 'limits':
        $sub_info['expires_on']     = Subscriptions_Lib::getExpirationTimestamp( $sub_info );
        $sub_info['expired']        = Subscriptions_Lib::isExpired( $sub_info['expires_on'] );
        $sub_info['crawl_limit']    = Subscriptions_Lib::$_service_limits[$service][$sub_info['plan']]['text'];
        $sub_info['crawl_limit_no'] = Subscriptions_Lib::$_service_limits[$service][$sub_info['plan']]['number'];

        return $sub_info['crawl_limit_no'];
    }

    /**
     * @param array $newWords
     *
     * @return bool
     */
    private function exceedsLimit( array $newWords )
    {
        return ( ( count( $newWords ) + $this->getCurrentNumberOfKeywords() > $this->getMaxLimit() ) );
    }

    /**
     * createUniqueId
     * @desc creating unique ids
     *
     */
    public function createUniqueId( $unique = array( "rand" => '' ) )
    {
        if ($unique['rand'] == '') {
            $rand = rand( 1, 100000 );
            $md5  = md5( time() . $rand );
        } else {
            $md5 = md5( $unique['rand'] );
        }
        return $md5;
    }

    /**
     * array_iunique
     * @desc case-insensitive array_unique
     *
     */
    public function array_iunique( $array )
    {
        return array_intersect_key(
            $array,
            array_unique( array_map( "StrToLower", $array ) )
        );
    }

    /**
     * creates a campaign
     * requires: project name, keywords, domain, &location
     */
    public function createCampaigns()
    {
        // defaults:
        $sql_array = $is_duplicate_array = $project_keyword_relation = $final_array = $keyword_array = array();

        /* CHECKS : */

        // ..
        if ( ! ( isset( $_REQUEST['project_name'] ) && $_REQUEST['project_name'] )) {
            $this->response( $this->json( array( "status" => 101, "msg" => $this->get_status_message( 101 ) ) ) );
            exit;
        }

        // ..
        if ( ! ( isset( $_REQUEST['keywords'] ) && $_REQUEST['keywords'] )) {
            $this->response( $this->json( array( "status" => 102, "msg" => $this->get_status_message( 102 ) ) ) );
            exit;
        }

        $keyword_array = @json_decode( $_REQUEST['keywords'] );
        if (empty( $keyword_array )) {
            $this->response( $this->json( array( "status" => 102, "msg" => $this->get_status_message( 102 ) ) ) );
            exit;
        }

        if ($this->exceedsLimit( $keyword_array )) {
            $this->response( $this->json( array( "status" => 110, "msg" => $this->get_status_message( 110 ) ) ) );
            exit;
        }

        // ..
        if ( ! ( isset( $_REQUEST['domain_url'] ) && $_REQUEST['domain_url'] )) {
            $this->response( $this->json( array( "status" => 103, "msg" => $this->get_status_message( 103 ) ) ) );
            exit;
        }

        // ..
        if ( ! ( isset( $_REQUEST['location'] ) && $_REQUEST['location'] )) {
            $this->response( $this->json( array( "status" => 109, "msg" => $this->get_status_message( 109 ) ) ) );
            exit;
        } else {
            $location = trim( strtolower( $_REQUEST['location'] ) );
        }

        // Validate the Request
        $project_name = trim( $_REQUEST['project_name'] );
        $query        = "SELECT id FROM tbl_project WHERE lower(project_name) =lower('" . $project_name . "') OR project_name = '" . $project_name . "' LIMIT 1";

        if (count( $this->pg->getResults( $query ) ) > 0) {
            $this->response( $this->json( array( "status" => 100, "msg" => $this->get_status_message( 100 ) ) ) ); // check the project name is already exists
            exit;
        }

        $lower_keywords      = array_map( 'strtolower', $keyword_array );
        $lower_keywords_temp = $lower_keywords;

        $limit = count( $keyword_array );

        $keywords       = "'" . implode( "', '", $keyword_array ) . "'"; // There is chance for german special char, It directly check if its exists]
        $lower_keywords = "'" . implode( "', '", $lower_keywords ) . "'";  // covert all array elemets to lowecase for checking the keyword is already exists


        // creating new project
        $project_id = $this->createUniqueId();
        $query      = "INSERT INTO tbl_project(id, project_name, domain_url,uploaded_date, \"userId\", uploaded_from, location) VALUES ('" . $project_id . "', '" . $_REQUEST['project_name'] . "', '" . $_REQUEST['domain_url'] . "','" . date( "Y-m-d H:i:s" ) . "', '" . $this->_UID . "', 'API', '" . $location . "')";
        $this->pg->runQuery( $query );

        // Check whether keywords is already exist, if its exist then it will added to project relation table
        $query   = "SELECT unique_id,keyword, lower(keyword) AS lower_keyword FROM tbl_project_keywords WHERE lower(keyword) IN ($lower_keywords) OR keyword IN ($keywords) LIMIT $limit";
        $results = $this->pg->getResults( $query );

        foreach ($results as $r_no => $row) {
            // deleting matched elements from array;
            for ($i = 0; $i < $limit; $i ++) {
                if (in_array( $row['keyword'], $keyword_array )) {
                    if ( ! array_key_exists( $row['unique_id'], $is_duplicate_array )) {
                        $project_keyword_relation[] = "('" . $this->createUniqueId() . "','" . $project_id . "', '" . $row['unique_id'] . "','" . date( "Y-m-d H:i:s" ) . "')";
                    }

                    unset( $keyword_array[$i] );
                    unset( $lower_keywords_temp[$i] );
                    $is_duplicate_array[$row['unique_id']] = 1;
                }

                if (in_array( $row['lower_keyword'], $lower_keywords_temp )) {
                    if ( ! array_key_exists( $row['unique_id'], $is_duplicate_array )) {
                        $project_keyword_relation[] = "('" . $this->createUniqueId() . "','" . $project_id . "', '" . $row['unique_id'] . "','" . date( "Y-m-d H:i:s" ) . "')";
                    }

                    unset( $lower_keywords_temp[$i] );
                    unset( $keyword_array[$i] );
                    $is_duplicate_array[$row['unique_id']] = 1;
                }
            }
        }

        // deleting matched elements from array;
        $final_array = array_merge( $lower_keywords_temp, $keyword_array );
        $final_array = $this->array_iunique( $final_array );

        if ( ! empty( $final_array )) {
            foreach ($final_array as $value) {
                $keyword_id                 = $this->createUniqueId();
                $project_keyword_relation[] = "('" . $this->createUniqueId() . "','" . $project_id . "', '" . $keyword_id . "','" . date( "Y-m-d H:i:s" ) . "')";
                $sql_array[]                = "('" . $keyword_id . "','" . $project_id . "', '" . mysql_real_escape_string( $value ) . "','0', '0','0','" . date( "Y-m-d H:i:s" ) . "', '" . $this->_UID . "', 'yes','" . $location . "','" . date( "Y-m-d H:i:s" ) . "')";
            }

            $query = "INSERT INTO tbl_project_keywords(unique_id,project_id, keyword, crawled_status, total_records, total_search,\"uploadedOn\",uid,ranktracker,location,crawled_date) VALUES " . implode( ',', $sql_array );
            $this->pg->runQuery( $query );
        }

        if ( ! empty( $project_keyword_relation )) {
            $query = "INSERT INTO project_keyword_relation(id,project_id, keyword_id, created_on) VALUES " . implode( ',', $project_keyword_relation );
            $this->pg->runQuery( $query );
        }

        $this->response( $this->json( array( "status" => 201, "msg" => $this->get_status_message( 201 ), "id" => $project_id ) ) );
        exit;
    }

    /**
     * delCampaings
     * @desc Deleting campaings
     */
    public function delCampaigns()
    {
        if ( ! ( isset( $_REQUEST['project_id'] ) && $_REQUEST['project_id'] )) {
            $this->response( $this->json( array( "status" => 105, "msg" => $this->get_status_message( 105 ) ) ) );
            exit;
        }
        // Delete Queries
        $query = "DELETE FROM tbl_project WHERE id='" . $_REQUEST['project_id'] . "'";
        $this->pg->runQuery( $query );

        $query = "DELETE FROM project_keyword_relation WHERE project_id='" . $_REQUEST['project_id'] . "'";
        $this->pg->runQuery( $query );

        $this->response( $this->json( array( "status" => 202, "msg" => $this->get_status_message( 202 ) ) ) );
    }

    /**
     * addMoreKeywords
     * @desc Insert keywords to existing campagin
     */
    public function addMoreKeywords()
    {
        $keyword_array = @json_decode( $_REQUEST['keywords'] );
        if (empty( $keyword_array )) {
            $this->response( $this->json( array( "status" => 102, "msg" => $this->get_status_message( 102 ) ) ) );
            exit;
        }

        if ($this->exceedsLimit( $keyword_array )) {
            $this->response( $this->json( array( "status" => 110, "msg" => $this->get_status_message( 110 ) ) ) );
            exit;
        }

        if (( isset( $_REQUEST['project_id'] ) && $_REQUEST['project_id'] )) {
            $query   = "SELECT id, location FROM tbl_project WHERE id='" . $_REQUEST['project_id'] . "' LIMIT 1";
            $project = $this->pg->getResults( $query );

            if (count( $project ) > 0) {
                // sets:
                $location   = trim( $project[0]['location'] );
                $project_id = $_REQUEST['project_id'];

                // defaults:
                $project_keyword_relation = $is_duplicate_array = array();

                // ..
                $lower_keywords      = array_map( 'strtolower', $keyword_array );
                $lower_keywords_temp = $lower_keywords;
                $limit               = count( $keyword_array );
                $keywords            = "'" . implode( "', '", $keyword_array ) . "'"; // There is chance for german special char, It directly check if its exists]
                $lower_keywords      = "'" . implode( "', '", $lower_keywords ) . "'";  // covert all array elemets to lowecase for checking the keyword is already exists

                // Check whether keywords is already exist, if its exist then it will added to project relation table
                $query   = "SELECT unique_id,keyword, lower(keyword) AS lower_keyword FROM tbl_project_keywords WHERE lower(keyword) IN ($lower_keywords) OR keyword IN ($keywords) LIMIT $limit";
                $results = $this->pg->getResults( $query );
                foreach ($results as $r_no => $row) {
                    // deleting matched elements from array;
                    for ($i = 0; $i < $limit; $i ++) {
                        if (in_array( $row['keyword'], $keyword_array )) {
                            if ( ! array_key_exists( $row['unique_id'], $is_duplicate_array )) {
                                if ( ! $this->isProjectRelationAlreadyExists( $project_id, $row['unique_id'] )) {
                                    $project_keyword_relation[] = "('" . $this->createUniqueId() . "','" . $project_id . "', '" . $row['unique_id'] . "','" . date( "Y-m-d H:i:s" ) . "')";
                                }
                            }

                            unset( $keyword_array[$i] );
                            unset( $lower_keywords_temp[$i] );
                            $is_duplicate_array[$row['unique_id']] = 1;
                        }

                        if (in_array( $row['lower_keyword'], $lower_keywords_temp )) {

                            if ( ! array_key_exists( $row['unique_id'], $is_duplicate_array )) {
                                if ( ! $this->isProjectRelationAlreadyExists( $project_id, $row['unique_id'] )) {
                                    $project_keyword_relation[] = "('" . $this->createUniqueId() . "','" . $project_id . "', '" . $row['unique_id'] . "','" . date( "Y-m-d H:i:s" ) . "')";
                                }
                            }

                            unset( $keyword_array[$i] );
                            unset( $lower_keywords_temp[$i] );
                            $is_duplicate_array[$row['unique_id']] = 1;
                        }
                    }
                }

                // deleting matched elements from array;
                $final_array = array_merge( $lower_keywords_temp, $keyword_array );
                $final_array = $this->array_iunique( $final_array );
                $sql_array   = array();
                if ( ! empty( $final_array )) {
                    foreach ($final_array as $value) {
                        $keyword_id                 = $this->createUniqueId();
                        $project_keyword_relation[] = "('" . $this->createUniqueId() . "','" . $project_id . "', '" . $keyword_id . "','" . date( "Y-m-d H:i:s" ) . "')";
                        $sql_array[]                = "('" . $keyword_id . "','" . $project_id . "', '" . mysql_real_escape_string( $value ) . "','0', '0','0','" . date( "Y-m-d H:i:s" ) . "', '" . $this->_UID . "', 'yes','" . $location . "','" . date( "Y-m-d H:i:s" ) . "')";
                    }

                    $query = "INSERT INTO tbl_project_keywords(unique_id,project_id, keyword, crawled_status, total_records, total_search,\"uploadedOn\",uid,ranktracker,location,crawled_date) VALUES " . implode( ',', $sql_array );
                    $this->pg->runQuery( $query );
                }

                if ( ! empty( $project_keyword_relation )) {
                    $query = "INSERT INTO project_keyword_relation(id,project_id, keyword_id, created_on) VALUES " . implode( ',', $project_keyword_relation );
                    $this->pg->runQuery( $query );
                }

                $this->response( $this->json( array( "status" => 200, "msg" => $this->get_status_message( 200 ), "id" => $project_id ) ) );
                exit;
            } else {
                $this->response( $this->json( array( "status" => 106, "msg" => $this->get_status_message( 106 ) ) ) ); // if project is not exists
                exit;
            }
        } else {
            $this->response( $this->json( array( "status" => 105, "msg" => $this->get_status_message( 105 ) ) ) );
            exit;
        }
    }


    /**
     * isProjectRelationAlreadyExists
     * @desc check whether the relation is already exists in the database
     *
     * @var $project_id referes to main project id
     * @var $keyword_id referes to main keyword id
     * @return bool values
     */
    public function isProjectRelationAlreadyExists( $project_id, $keyword_id )
    {
        $query = "SELECT id FROM project_keyword_relation WHERE project_id='" . $project_id . "' AND keyword_id = '" . $keyword_id . "' LIMIT 1";
        if (count( $this->pg->getResults( $query ) ) > 0) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * get's user's campaigns, if any;
     */
    public function getMyCampaigns()
    {
        $query = "SELECT tbl_project.id, tbl_project.project_name, tbl_project.domain_url, tbl_project.uploaded_date ";
        $query .= "FROM tbl_project WHERE \"userId\" = '" . $this->_UID . "'";
        $results = $this->pg->getResults( $query );

        // ..
        $return_array = array();
        foreach ($results as $r_no => $result) {
            $keyword_array = array();
            $query         = "SELECT keyword, location ";
            $query .= "FROM tbl_project_keywords WHERE project_id = '" . $result['id'] . "'";
            $results2 = $this->pg->getResults( $query );

            foreach ($results2 as $r_no2 => $result2) {
                $keyword_array[] = $result2;
            }

            $return_array[] = array( "project_details" => $result, "keywords" => $keyword_array );
        }

        $this->response( $this->json( array( "status" => 200, "msg" => $this->get_status_message( 200 ), "data" => $return_array ) ) );
    }

    /**
     * get campaign info by project_id.
     */
    public function getCampaignsById()
    {
        if ( ! ( isset( $_REQUEST['project_id'] ) && $_REQUEST['project_id'] )) {
            $this->response( $this->json( array( "status" => 105, "msg" => $this->get_status_message( 105 ) ) ) );
            exit;
        }

        // ..
        $id = $_REQUEST['project_id'];

        // form query:
        $query = "SELECT tbl_project.id, tbl_project.project_name, tbl_project.domain_url, tbl_project.uploaded_date,  ";
        $query .= "crawled_sites.host, crawled_sites.crawled_date, crawled_sites.rank AS keyword_rank, crawled_sites.title,
                  crawled_sites.description, crawled_sites.total_back_links, crawled_sites.header_tags, crawled_sites.google_com_rank,
                  crawled_sites.title_com,crawled_sites.desc_com, crawled_sites.url_com,crawled_sites.rank_local, crawled_sites.title_local,
                  crawled_sites.des_local, crawled_sites.url_local, crawled_sites.total_records, crawled_sites.total_records_com,crawled_sites.total_records_local,
                  crawled_sites.page_rank ";

        $query .= " FROM crawled_sites INNER JOIN tbl_project_keywords ON tbl_project_keywords.unique_id = crawled_sites.keyword_id ";
        $query .= " INNER JOIN project_keyword_relation ON project_keyword_relation.keyword_id = tbl_project_keywords.unique_id";
        $query .= " INNER JOIN tbl_project ON tbl_project.id = project_keyword_relation.project_id";
        $query .= " WHERE tbl_project.id='$id'";
        $return_array = $this->pg->getResults( $query );

        $this->response( $this->json( array( "status" => 200, "msg" => $this->get_status_message( 200 ), "data" => $return_array ) ) );
    }

    /**
     * get campaign details by project id OR project_name
     */
    public function getCampaignDetails()
    {
        if ( ! ( isset( $_REQUEST['project_id'] ) && $_REQUEST['project_id'] ) && ! ( isset( $_REQUEST['project_name'] ) && $_REQUEST['project_name'] )) {
            $this->response( $this->json( array( "status" => 107, "msg" => $this->get_status_message( 107 ) ) ) );
            exit;
        }

        $query = "SELECT tbl_project.id, tbl_project.domain_url, tbl_project.uploaded_date,tbl_project.project_name, tbl_project_keywords.unique_id, tbl_project_keywords.keyword,tbl_project_keywords.location";
        $query .= ' FROM tbl_project';
        $query .= " INNER JOIN project_keyword_relation ON project_keyword_relation.project_id=tbl_project.id";
        $query .= " INNER JOIN tbl_project_keywords ON tbl_project_keywords.unique_id = project_keyword_relation.keyword_id";

        if ( ! ( isset( $_REQUEST['project_id'] ) && $_REQUEST['project_id'] ) && ( isset( $_REQUEST['project_name'] ) && $_REQUEST['project_name'] )) {
            $query .= " WHERE tbl_project.project_name = '" . $_REQUEST['project_name'] . "'";
        } else if (( isset( $_REQUEST['project_id'] ) && $_REQUEST['project_id'] ) && ! ( isset( $_REQUEST['project_name'] ) && $_REQUEST['project_name'] )) {
            $query .= " WHERE tbl_project.id = '" . $_REQUEST['project_id'] . "'";
        } else if (( isset( $_REQUEST['project_id'] ) && $_REQUEST['project_id'] ) && ( isset( $_REQUEST['project_name'] ) && $_REQUEST['project_name'] )) {
            $query .= " WHERE tbl_project.id = '" . $_REQUEST['project_id'] . "'";
        }

        $query .= " AND tbl_project.\"userId\"='" . $this->_UID . "'";

        $return_array = $this->pg->getResults( $query );
        $this->response( $this->json( array( "status" => 200, "msg" => $this->get_status_message( 200 ), "data" => $return_array ) ) );
    }

    /**
     * getKeywordDetails
     * @desc get keyword details from crawled restults
     */
    public function getKeywordDetails()
    {
        if ( ! ( isset( $_REQUEST['project_id'] ) && $_REQUEST['project_id'] ) OR ! ( isset( $_REQUEST['keyword_id'] ) && $_REQUEST['keyword_id'] )) {
            $this->response( $this->json( array( "status" => 108, "msg" => $this->get_status_message( 108 ) ) ) );
            exit;
        }

        $query = "SELECT tbl_project.id, tbl_project.project_name, tbl_project.domain_url, tbl_project.uploaded_date,";
        $query .= "crawled_sites.host, crawled_sites.crawled_date, crawled_sites.rank AS keyword_rank, crawled_sites.title,";
        $query .= "crawled_sites.description, crawled_sites.total_back_links, crawled_sites.header_tags, crawled_sites.google_com_rank,";
        $query .= "crawled_sites.title_com,crawled_sites.desc_com, crawled_sites.url_com,crawled_sites.rank_local, crawled_sites.title_local,";
        $query .= "crawled_sites.des_local, crawled_sites.url_local, crawled_sites.total_records, crawled_sites.total_records_com,";
        $query .= "crawled_sites.total_records_local, crawled_sites.page_rank";

        $query .= " FROM crawled_sites INNER JOIN tbl_project_keywords ON tbl_project_keywords.unique_id = crawled_sites.keyword_id ";
        $query .= " INNER JOIN project_keyword_relation ON project_keyword_relation.keyword_id = tbl_project_keywords.unique_id";
        $query .= " INNER JOIN tbl_project ON tbl_project.id = project_keyword_relation.project_id";
        $query .= " WHERE tbl_project.id='" . $_REQUEST['project_id'] . "' AND tbl_project_keywords.unique_id = '" . $_REQUEST['keyword_id'] . "'";

        $return_array = $this->pg->getResults( $query );
        $this->response( $this->json( array( "status" => 200, "msg" => $this->get_status_message( 200 ), "data" => $return_array ) ) );
    }
}