<?php
$file_name="";
$html = "<h1>N/A</h1>";
$limit = 10;
$count = 0;
$vector = 0;
if(isset($_POST['website']) && $_POST['website'])
{
    $parse = parse_url(addhttp($_POST['website']));

    $site_name = $parse['scheme']."://".preg_replace('#^www\.(.+\.)#i', '$1', $parse['host']) . $parse['path'];
    $site_name = (substr($site_name, -1) == '/') ? substr($site_name, 0, -1) : $site_name;
    $file_name = md5($site_name).".txt";
    shell_exec("python similarity_live.py --domain $site_name --output ../similarities/$file_name");
    $file = "../similarities/".$file_name;
    $file_temp = $file;
        if (file_exists($file)) 
        {
            $html = "";
            $file = fopen($file,"r");
            while(!feof($file))
            {
                $grey = 0;
                $blue=0;
                $string = fgets($file);
                $temp_array = explode("+", urlencode($string));
                $percentage = round(($temp_array[1]*100), 1);
                if($percentage <= 50)
                {
                  $grey =  $percentage;
                  $blue = 0;
                }
                else
                {
                    $grey = 100;
                    $blue = $percentage - 50;
                }
                $blue = $blue *2;
                $vector = round($temp_array[1], 2);
                if($vector >= 1)
                {
                    $vector = $vector*100;
                    $vector = "0.".$vector;
                }
                else
                {
                    $vector = $vector*100;
                    $vector = "0.0".$vector; 
                }
                $html .= '<li>
                        <div class="title">'.$temp_array[0].'</div>
                        <div class="bullet">
                            <div class="container_a">
                                <div style="width:'.$grey.'%" class="bullet_a"></div>
                            </div>
                            <div class="container_b">
                                <div class=""></div>
                                <div style="width:'.$blue.'%" class="bullet_b"></div>
                            </div>
                        </div>
                        <div class="vector">'.$vector.'</div>
                    </li>';
                $count ++;
                if($count >= $limit)
                {
                    break;
                }
            } 
        }
            @unlink($file_temp); 
            echo json_encode(array("html" => $html));
}
function addhttp($url) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    return $url;
}
exit;  
?>
