<?php

function gsscrape($keyword) {
  $keyword=str_replace(" ","+",$keyword);
  $keyword=str_replace("&","+",$keyword);
  global $kw;
  $data=file_get_contents('http://suggestqueries.google.com/complete/search?client=chrome&q='.$keyword);
 
  
  $data=explode('[',$data,3);
   
  $data=explode('],[',$data[2]);
  $data=explode(',',$data[0]);
 
  foreach($data as $temp) {
  $kw[]= str_replace('"', '',$temp);
 
 
  }
 
  return $kw;
}

	$aUsers = array();
        $kw = array();
	$input = strtolower(str_replace(' ','+' , urldecode($key) ) );
        
       
        
      	$len = strlen($input);
	$limit = isset($limit) ? (int) $limit : 0;
       
	$kw=gsscrape($input);
        
         sort($kw);
        foreach($kw as $data){
        $aUsers[]=$data;
        }
       
     $input=  str_replace('&',' ' ,$input);
	$aResults = array();
	$count = 0;
	
	if ($len)
	{
		for ($i=0;$i<count($aUsers);$i++)
		{
			if (strtolower(substr(utf8_decode($aUsers[$i]),0,$len)) == str_replace('+',' ',$input))
			{
				$count++;
				$aResults[] = array( "id"=>($i+1) ,"value"=>htmlspecialchars($aUsers[$i]) );
			}
			
			if ($limit && $count==$limit)
				break;
		}
	}
	
	
	
	
	
	header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
	header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header ("Pragma: no-cache"); // HTTP/1.0
	
	
	
	if (isset($json))
	{
		header("Content-Type: application/json");
	
		echo "{\"results\": [";
		$arr = array();
		for ($i=0;$i<count($aResults);$i++)
		{
			$arr[] = "{\"id\": \"".$aResults[$i]['id']."\", \"value\": \"".$aResults[$i]['value']."\", \"info\": \"\"}";
		}
		echo implode(", ", $arr);
		echo "]}";
	}
	else
	{
		header("Content-Type: text/xml");

		echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?><results>";
		for ($i=0;$i<count($aResults);$i++)
		{
			echo "<rs id=\"".$aResults[$i]['id']."\>".$aResults[$i]['value']."</rs>";
		}
		echo "</results>";
	}
?>