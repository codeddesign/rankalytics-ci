<?php
$proxy = '179.43.138.160:3128';
$search_string= "computer";
$max_results = 20;

            //$url = "https://www.google.de/search?q=".$search_string."&num=$max_results&hl=en&start=0&sa=N";
            $url = "https://www.google.de/search?q=cats";
            //$user_name = 'thomasstehle';
            //$password = 'My6Celeb';
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            curl_setopt($ch, CURLOPT_PROXYTYPE, 'HTTP');
            curl_setopt($ch, CURLOPT_PROXY, $proxy);
            //curl_setopt($ch, CURLOPT_PROXYUSERPWD, "$user_name:$password");
            //curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);

            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_USERAGENT , "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.16) Gecko/20080702 Firefox/2.0.0.16");
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
           // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
            //curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt");
            //curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
            
            $htmdata = curl_exec($ch);
            echo curl_error($ch);
            echo $htmdata;
            curl_close($ch);exit;
?>