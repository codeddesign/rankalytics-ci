<?php

error_reporting(E_STRICT | E_ALL);
ini_set('display_errors', '1');

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Demo extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        //load our new Adwords library
        $this->load->library('My_adwords_api');
    }

    function index()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes
        $data = array();

        if ($this->input->post('searchurl') != "") {
            $domainurl = $this->input->post('searchurl');
            $domainurl = trim($domainurl);
            $domainurl = str_ireplace("www.", "", $domainurl);
            $domainurl = str_ireplace("http://", "", $domainurl);
            $domainurl = "www." . $domainurl;
            $data['default'] = 1;


            $output_arr = array();
            $data["domainurl"] = $domainurl;

            $ch = curl_init("http://enterprise.majesticseo.com/api/json?app_api_key=CF2C61AFBE51F1A8B3E0947073C0D3D5&cmd=GetTopPages&Query=" . $domainurl . "&Count=15&datasource=fresh");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            $data['output_arr'] = json_decode($output, true);
            curl_close($ch);
            $ch_dom = curl_init("http://enterprise.majesticseo.com/api/json?app_api_key=CF2C61AFBE51F1A8B3E0947073C0D3D5&cmd=GetBackLinksHistory&item0=" . $domainurl . "&Count=100&datasource=fresh");
            curl_setopt($ch_dom, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch_dom);

            $data['ip_arr'] = json_decode($output, true);
            curl_close($ch_dom);
            $ch_refdom = curl_init("http://enterprise.majesticseo.com/api/json?app_api_key=CF2C61AFBE51F1A8B3E0947073C0D3D5&cmd=GetRefDomains&items=1&item0=" . $domainurl . "&Count=100&datasource=fresh");
            curl_setopt($ch_refdom, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch_refdom);

            $data['refdom_arr'] = json_decode($output, true);
            curl_close($ch_refdom);
            $ch_anchortext = curl_init("http://enterprise.majesticseo.com/api/json?app_api_key=CF2C61AFBE51F1A8B3E0947073C0D3D5&cmd=GetAnchorText&item=" . $domainurl);
            curl_setopt($ch_anchortext, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch_anchortext);

            $data['anchortext'] = json_decode($output, true);
            curl_close($ch_anchortext);

            $ch = curl_init("http://de.api.semrush.com/?action=report&type=url_organic&key=2bbc00ee2214773f6d73794b789d6f0f&display_limit=15&export=api&export_columns=Ph&url=http://$domainurl/");

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
//        echo "pos ".strpos($output,":: NOTHING FOUND");
            if (strpos($output, ":: NOTHING FOUND")) {
                $data['keywords_arr'] = "";
            } else {
                $data['keywords_arr'] = explode("\n", $output);
            }
            curl_close($ch);

            $ch = curl_init("http://de.api.semrush.com/?action=report&type=domain_rank_history&key=2bbc00ee2214773f6d73794b789d6f0f&display_limit=7&export=api&export_columns=Rk,Ot,,Tr,Dt&domain=$domainurl&display_sort=dt_desc");

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            //        echo "pos ".strpos($output,":: NOTHING FOUND");
            if (strpos($output, ":: NOTHING FOUND")) {
                $data['keyword_estimate'] = "";
            } else {
                $data['keyword_estimate'] = explode("\n", $output);
            }
            curl_close($ch);
            $ch = curl_init($domainurl);

            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

            $html = curl_exec($ch);
            curl_close($ch);

            $doc = new DOMDocument();
            @$doc->loadHTML($html);
            $nodes = $doc->getElementsByTagName('title');
            $title = "";
            //get and display what you need:
            if (@$nodes->item(0)->nodeValue) {
                $title = $nodes->item(0)->nodeValue;
            }
            $description = "";
            $metas = $doc->getElementsByTagName('meta');

            for ($i = 0; $i < $metas->length; $i++) {
                $meta = $metas->item($i);
                if ($meta->getAttribute('name') == 'description')
                    $description = $meta->getAttribute('content');
                if ($meta->getAttribute('name') == 'keywords')
                    $keywords = $meta->getAttribute('content');
            }
            $data['title'] = $title;
            $data['description'] = $description;
        } else {
            $data['default'] = 0;
            $data['title'] = "";
            $data['description'] = "";
            $data['keyword_estimate'] = "";
            $data['output_arr'] = "";
            $data['ip_arr'] = "";
            $data['keywords_arr'] = "";
            $data['anchortext'] = "";
            $data['refdom_arr'] = "";
            $data['domainurl'] = "";
        }
        
        // load language file:
        $this->lang->load('apidemo');
        $this->load->view("analytics/live_search", $data);
    }

}

?>