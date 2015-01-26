<?php
error_reporting( E_STRICT | E_ALL );
ini_set( 'display_errors', '1' );

if ( ! defined( 'BASEPATH' )) {
    exit( 'No direct script access allowed' );
}

class Adwords extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        //load our new Adwords library
        $this->load->library( 'My_adwords_api' );
        //$this->load->model('project_keyword_list_model','project_keyword_list',true);
        $this->load->model( 'common_model', 'common', true );
    }

    function GetKeywordIdeasExample( $keyword = "" )
    {
        if (isset( $_REQUEST['keyword'] )) {
            $key = $_REQUEST['keyword'];
        }

        if ($keyword != "") {
            $key = $keyword;
        }
        // Get the service, which loads the required classes.
        $user                 = new My_adwords_api();
        $targetingIdeaService = $user->GetService( 'TargetingIdeaService', ADWORDS_VERSION );

        // Create seed keyword.
        $keyword = $key;
        // Create selector.
        $keywords_details      = array();
        $selector              = new TargetingIdeaSelector();
        $selector->requestType = 'STATS';
        // $selector->requestType = 'IDEAS';
        $selector->ideaType = 'KEYWORD';

        $selector->requestedAttributeTypes = array( 'KEYWORD_TEXT', 'SEARCH_VOLUME', 'COMPETITION', 'AVERAGE_CPC' );

        $languageParameter            = new LanguageSearchParameter();
        $english                      = new Language();
        $english->id                  = 1001;
        $languageParameter->languages = array( $english );

        $locationParameter            = new LocationSearchParameter();
        $germany                      = new Location();
        $germany->id                  = 2276;
        $locationParameter->locations = array( $germany );

        // Create related to query search parameter.
        $relatedToQuerySearchParameter          = new RelatedToQuerySearchParameter();
        $relatedToQuerySearchParameter->queries = array( $keyword );
        $selector->searchParameters[]           = $relatedToQuerySearchParameter;
        $selector->searchParameters[]           = $languageParameter;
        $selector->searchParameters[]           = $locationParameter;

        // Set selector paging (required by this service).
        $selector->paging          = new Paging( 0, AdWordsConstants::RECOMMENDED_PAGE_SIZE );
        $info_array['volume']      = null;
        $info_array['competition'] = null;
        do {
            $page = $targetingIdeaService->get( $selector );


            // Display results.
            if (isset( $page->entries )) {
                foreach ($page->entries as $targetingIdea) {
                    $data          = MapUtils::GetMap( $targetingIdea->data );
                    $keyword       = $data['KEYWORD_TEXT']->value;
                    $search_volume = isset( $data['SEARCH_VOLUME']->value ) ? $data['SEARCH_VOLUME']->value : 0;
                    //$targeted_monthly_searches = isset($data['TARGETED_MONTHLY_SEARCHES']->value) ? $data['TARGETED_MONTHLY_SEARCHES']->value : 0;
                    $competition = isset( $data['COMPETITION']->value ) ? $data['COMPETITION']->value : 0;
                    $avg_cpc     = isset( $data['AVERAGE_CPC']->value ) ? $data['AVERAGE_CPC']->value->microAmount : 0;
//                $categoryIds = implode(', ', $data['CATEGORY_PRODUCTS_AND_SERVICES']->value);
//                printf("Keyword idea with text '%s', category IDs (%s) and average "
//                        . "monthly search volume '%s' was found.\n", $keyword, $categoryIds, $search_volume);
                    /*printf("Keyword with text '%s', Average CPC '%s' average "
                            . "monthly search volume '%s' and COMPETITION '%s' was found.\n", $keyword, $avg_cpc, $search_volume, $competition);
                    echo "<br/><br/><br/>";*/
                    $info_array['keywords']    = $keyword;
                    $info_array['cpc']         = $avg_cpc;
                    $info_array['volume']      = $search_volume;
                    $info_array['competition'] = $competition;
                    // $keywords_details[]=$info_array;
                }
            } else {
                unset( $info_array );
                $info_array                = array();
                $info_array['keywords']    = $keyword;
                $info_array['cpc']         = 0;
                $info_array['volume']      = 0;
                $info_array['competition'] = 0;
            }

            // Advance the paging index.
            $selector->paging->startIndex += AdWordsConstants::RECOMMENDED_PAGE_SIZE;
        } while ($page->totalNumEntries > $selector->paging->startIndex);
        print_r( json_encode( $info_array ) );
        return $info_array;
    }

    function get_rank( $domain, $keywords )
    {
        // Clean the post data and make usable
        $i      = 1;
        $hit    = 0;
        $domain = filter_var( $domain, FILTER_SANITIZE_STRING );

        $keywords = filter_var( $keywords, FILTER_SANITIZE_STRING );

        // Remove begining http and trailing /

        $domain = substr( $domain, 0, 7 ) == 'http://' ? substr( $domain, 7 ) : $domain;

        $domain = substr( $domain, - 1 ) == '/' ? substr_replace( $domain, '', - 1 ) : $domain;

        $keywords = strstr( $keywords, ' ' ) ? str_replace( ' ', '+', $keywords ) : $keywords;

        $html = new DOMDocument();

        @$html->loadHtmlFile( 'http://www.google.de/search?q=' . $keywords );

        $xpath = new DOMXPath( $html );


        $nodes = $xpath->query( '//div[1]/cite' );

        $hit = 2;

        foreach ($nodes as $n) {
            // echo '<div style="font-size:0.7em">'.$n->nodeValue.'<br /></div>'; // Show all links

            if (strstr( $n->nodeValue, $domain )) {
                $message = $i;
                $hit     = 1;

            } else {
                ++ $i;
            }

        }

        if (isset( $message )) {
            echo $message;
        }
    }

    function getTemp()
    {
        $this->load->view( "dashboard/weather" );
    }

    function getunq()
    {

        $this->load->model( 'project_keywords_model', 'project_keywords', true );
        $keywords    = array( "ranking", "kye2", "kye3", "kye4", "kye5" );
        $keyword_arr = $this->project_keywords->KeywordsUnique( $keywords );
        $kcount      = 0;
        foreach ($keyword_arr as $value) {
            $keywordId_projectId_arr[$kcount]['keyword_id'] = $value['unique_id'];
            //$keywordId_projectId_arr[$kcount]['project_id'] = $keyword_tbl_arr['project_id'];
            $keywordId_projectId_arr[$kcount]['project_id'] = 9;
            $keywordId_projectId_arr[$kcount]['id']         = md5( rand( 1, 10000 ) . microtime() . rand( 1, 10000 ) );
            $keywordId_projectId_arr[$kcount]['created_on'] = date( "Y-m-d" );
            $kcount ++;
        }

        print_r( $keywordId_projectId_arr );
    }

    function getkey_used( $id )
    {
        $this->load->model( 'project_keywords_model', 'project_keywords' );
        echo $this->project_keywords->getNumberOfKeywordsByUser( $id );

        /*echo $this->common->keywords_used($id);*/
    }

    function  get_keyword_limit( $acctype = "" )
    {
        echo $this->common->get_keyword_limit( $acctype );
    }

    function check()
    {
        $this->load->library( 'session' );
        $user    = $this->session->userdata( 'logged_in' );
        $id      = $user['0']['id'];
        $query   = $this->db->query( 'SELECT accountType FROM users where id="' . $id . '"' );
        $acctyep = $query->result_array();
        $type    = $acctyep['0']['accountType'];
        if ($type == "") {
            $type = "free";
        }
        if ($type == "enterprise") {
            echo 1;
            return;
        }
        $plan = $this->config->item( 'subscription_plans' );

        $limit = $plan[$type]['keywordsAllowed'];


        $total = $this->keywords_count( $id );
        if ($total < $limit) {
            echo 1;
        } else {
            echo 0;
        }
    }

    function keywords_count( $id )
    {
        $query      = $this->db->query( 'SELECT * FROM tbl_project where userId="' . $id . '"' );
        $project_id = $query->result_array();
        $id_list    = "";
        foreach ($project_id as $id) {

            if ($id_list != "") {
                $id_list .= "  ,  ";
            }

            $id_list .= "'" . $id['id'] . "'";

        }

        $query       = $this->db->query( 'SELECT DISTINCT keyword   FROM `tbl_project_keywords` where project_id in ( 0,  ' . $id_list . ' ) ' );
        $keywordlist = $query->result_array();
        $total       = count( $keywordlist );
        return $total;

        //print_r($user)
    }
}
