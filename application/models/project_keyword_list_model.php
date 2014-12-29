<?php

class Project_Keyword_List_Model extends CI_Model
{
    var $_tablename;

    function __construct()
    {
        $this->_tablename = "tbl_project_keyword_list";
        $this->pgsql = $this->load->database('pgsql', true);
    }

    public function save($keyword_array)
    {
        $this->load->model('common_model');

        $id = $this->common_model->getNewId($this->_tablename);
        $keyword_array['id'] = $id;
        $this->pgsql->set($keyword_array);
        $this->pgsql->insert($this->_tablename);
        return $id;
    }

    public function getRank($keyword_array)
    {
        $this->pgsql->select('domain_url');
        $this->pgsql->from('tbl_project');
        $condition = array("id" => $keyword_array['project_id']);
        $this->pgsql->where($condition);
        $query = $this->pgsql->get();
        $result = $query->result_array();
        $keywords = $keyword_array['keyword'];
        $domain = $result['0']['domain_url'];
        //$keywords="ebay";
        // $domain="ebay.com";
        $i = 1;
        $hit = 0;
        $domain = filter_var($domain, FILTER_SANITIZE_STRING);

        $keywords = filter_var($keywords, FILTER_SANITIZE_STRING);

        // Remove begining http and trailing /

        $domain = substr($domain, 0, 7) == 'http://' ? substr($domain, 7) : $domain;

        $domain = substr($domain, -1) == '/' ? substr_replace($domain, '', -1) : $domain;

        $keywords = strstr($keywords, ' ') ? str_replace(' ', '+', $keywords) : $keywords;

        $html = new DOMDocument();

        @$html->loadHtmlFile('http://www.google.de/search?q=' . $keywords);

        $xpath = new DOMXPath($html);


        $nodes = $xpath->query('//div[1]/cite');

        $hit = 2;

        foreach ($nodes as $n) {
            // echo '<div style="font-size:0.7em">'.$n->nodeValue.'<br /></div>'; // Show all links

            if (strstr($n->nodeValue, $domain)) {

                $message = $i;
                $hit = 1;

            } else {
                ++$i;
            }

        }
        if (isset($message)) {

            return $message;
        } else {
            return 0;
        }

    }

}




