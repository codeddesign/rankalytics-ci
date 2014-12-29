<?php

class IndexCount
{
    protected $search_string, $proxies, $content;

    public function __construct($search_string, array $proxies)
    {
        $this->search_string = trim($search_string);
        $this->proxies = $proxies;
    }

    public function doGoogleSearch()
    {
        // get A random proxy
        $currentProxy = rand(0, count($this->proxies) - 1);
        $proxy = $this->proxies[$currentProxy];
        
        // sets:
        $lang = 'de';
        $url = "http://www.google.de/search?q=site:" . $this->search_string . "&num=1&hl=" . $lang . "&start=0&sa=N";

        // curl time:
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYTYPE, 'HTTP');
        curl_setopt($ch, CURLOPT_PROXY, $proxy['ip']);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.16) Gecko/20080702 Firefox/2.0.0.16");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        if (trim($proxy['username']) != "" && trim($proxy['password']) != "") {
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxy['username'] . ':' . $proxy['password']);
            curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);
        }

        curl_setopt($ch, CURLOPT_TIMEOUT, 6);
        $this->content = curl_exec($ch);
        curl_close($ch);

        // remove used proxy:
        unset($this->proxies[$currentProxy]);

        //reset array ids:
        $this->proxies = array_values($this->proxies);
    }

    /**
     * @param $no
     * @return bool
     */
    public function isANumber($no)
    {
        $bad = array(',', '.');
        $no = str_replace($bad, '', $no);

        return is_numeric($no);
    }

    /**
     * @return bool|number
     */
    public function getIndexedCount()
    {
        $found = false;
        if (preg_match('/id="resultStats">(.*?)<\/div>/', $this->content, $matched)) {
            $matched = explode(' ', $matched[1]);
            foreach ($matched as $m_no => $m) {
                if ($this->isANumber($m)) {
                    $found = $m;
                    break;
                }
            }
        }

        return $found;
    }

    public function getContent() {
        return $this->content;
    }

    public function isBlocked() {
        $bad_cases = array(
            'computer virus or spyware application',
            'entire network is affected',
            'http://www.download.com/Antivirus',
            'the document has moved',
        );

        $blocked = false;
        foreach ($bad_cases as $b_no => $case) {
            if (stripos($this->content, $case) !== false) {
                echo "\n".'! Info: A proxy-blocked pattern matched. We\'ll use next proxy!.'."\n";
                $blocked = true;
            }
        }

        if($blocked == false && strlen(trim($this->content)) == 0) {
            echo "\n".'! Info: body is empty. We assume the proxy is blocked or failed to connect. We\'ll use next proxy!'."\n";
            $blocked = true;
        }

        return $blocked;
    }
}