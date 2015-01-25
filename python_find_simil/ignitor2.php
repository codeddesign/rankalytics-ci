<?php
error_reporting(0);
$file_name="";
$result = 0;
if(isset($_POST['website']) && $_POST['website'])
{
    $parse = parse_url($_POST['website']);

    $site_name = $parse['scheme']."://".preg_replace('#^www\.(.+\.)#i', '$1', $parse['host']) . $parse['path'];

    $site_name = (substr($site_name, -1) == '/') ? substr($site_name, 0, -1) : $site_name;
    //$site_name = "http://en.wikipedia.org/wiki/Horseshoe";
   $keyword = urlencode($_POST["keyword"]);
     //$keyword = "horse";
     //$site_name = $_POST['website'];
    $file_name=  md5($site_name.$keyword.date("Y-m-d H:i:s")).".txt";
    //echo "python similarity.py --domain $site_name --keyword $keyword --output similarities/$file_name";
    shell_exec("python similarity.py --domain $site_name --keyword $keyword --output ../similarities/$file_name");
}

if($file_name)
{
    
    $file = "../similarities/".$file_name;
    $file_temp = $file;
    
    if (file_exists($file)) {
    $file = fopen($file,"r");
    $keyword = urlencode($_POST['keyword']);
    $keyword_array = array();
    $keyword_array = explode("+", $keyword);

    $count = 0;
    $sum = 0;
    while(! feof($file))
    {
        $string = fgets($file);
        foreach ($keyword_array as $value) 
        {
            if (stristr($string,$value) !== false) 
            {
                $temp_array = explode("+", urlencode($string));
                if(strcasecmp($temp_array[0], $value) == 0)
                {
                    $count++;
                    $sum = $sum+$temp_array[2];
                }
            } 
        }

    }
    if($count > 1)
    $result = $sum/$count;
    else
    $result = 0;
    echo json_encode(array("similarity_score" =>($result) ?  round($result, 3) :"0"));
    @unlink($file_temp);
    }
    else
    {
        echo json_encode(array("similarity_score" =>($result) ? $result :"N/A"));
    }
    exit;
}
    
?>