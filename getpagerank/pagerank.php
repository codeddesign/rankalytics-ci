<?php
require 'db_con3.php';
require 'pagerank_class.php';
$gpr = new GooglePR();
 
$pagerank_query = "SELECT  unique_id,site_url FROM crawled_sites WHERE page_rank is NULL limit 5";

$result = mysql_query($pagerank_query);


while ($row = mysql_fetch_array($result)) 
{
	
	  
	

	  $pagerank = $gpr->getPagerank((trim($row['site_url'])));
 
     if($pagerank)
     {
		  $query = "UPDATE crawled_sites SET page_rank = '".$pagerank."' WHERE unique_id='".$row['unique_id']."'";
          mysql_query($query);
		
	 }
	 
}
?>
