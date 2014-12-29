<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />

<!-- favicon -->
<link rel="icon" type="image/png" href="https://rankalytics.com/assets/images/favicon.png">
<link rel="shortcut icon" type="image/png" href="https://rankalytics.com/assets/images/favicon.png"/>
<!-- end favicon -->


<title>Rankalytics Reports</title>
<link href="http://rankalytics.com//assets/css/style.css" rel="stylesheet" type="text/css">


</head>
<style>
    .reportspdf-list table {
    position: relative;
    top: 10px;
}
.reportspdf-infoline {
    border-bottom: 37px solid #DDDDDD;
    float: left;
    height: 0;
    width: 100%;
}
    .reportspdf-list {
    border-bottom: 8px solid #DDDDDD;
    float: left;
    height: 30px;
    width: 100%;
}
.reportspdf-list ul li {
    list-style-type: none;
    height: 40px;
}
</style>
<?php
$this->pgsql = $this->load->database('pgsql', true);

$query = $this->pgsql->query('SELECT * FROM tbl_project where id=\'' . $domain_id . '\'');
        $project_data = $query->result_array();
        $domain_name = $project_data[0]['domain_url'];
        $project_name = $project_data[0]['project_name'];

           $key_query="SELECT * FROM tbl_project_keywords as pk join project_keyword_relation as pkr on pkr.keyword_id=pk.unique_id where pkr.project_id='".$domain_id."'";
           $query = $this->pgsql->query($key_query);
                $keyword_data = $query->result_array();

?>
<body style="background:#FFFFFF;">

<div class="reportspdfwrap" style="margin-top: 2px;">
                <table> <tr> <td style="width:360px">       
	<div class="reportspdf-logo" style="float: left">
             <?php if(!empty($companyLogo)){?>
            
            <img   src="http://rankalytics.com/uploads/logos/thumbnails/<?php echo $companyLogo; ?>" />
           
           <?php }
            else{
            echo "<div class='reportslogo' ></div>";
             }?>
        </div></td><td style="width:300px">
       <div class="reportspdf-rightreport" style="float: right" >
		<div class="reportspdf-rightreporttext">CAMPAIGN REPORT</div>
		<div class="reportspdf-createdby" style="text-transform:uppercase;">CREATED BY: <?php if(!empty($companyName)){
                echo $companyName;
                }else{
                   
                  echo "RANKALYTICS RANK TRACKER";
                }
                    
                    
                    ?></div>
		<div class="reportspdf-rightreportdate">FROM: <?php echo date('M. d, Y', strtotime($start_date))?> TO: <?php echo date('M. d, Y', strtotime($end_date))?></div>
            </div>
        </td>  
              <tr>    </table>
	<!-- the div where we are going to plot -->
       <?php 
            $date   =  date("Y-m-d" ,strtotime($start_date));
            $end_date   =  date("Y-m-d" ,strtotime($end_date));
            $avg_rank=0;
            $avg=0;
            $start_pos=0;
            $end_pos=0;
            $count=0;
            $total=count($keyword_data);
            $total_key=array();
     if(!empty($keyword_data)){   
        foreach ($keyword_data as $row) {

            $keyword = $row['keyword'];
            $unique_id = $row['unique_id'];
            
          
            
            $query = $this->pgsql->query('SELECT avg(rank::int) as avg FROM crawled_sites  where keyword_id=\''.$unique_id.'\' and  host = \''.$domain_name. '\' and crawled_date >=\''. $date .'\'  and crawled_date <=\''. $end_date .'\'  group by keyword_id' );
            $keyword_rank = $query->result_array();
            if(!empty($keyword_rank)){
            if($count==0)    {
                
              $start_pos= $keyword_rank[0]['avg']; 
            }
             $end_pos= $keyword_rank[0]['avg']; 
            $avg_rank=$avg_rank+$keyword_rank[0]['avg'];
            $total_key[]= $keyword_rank[0]['avg'];
            }
           
          }
         $avg_rank;
         $avg=round( $avg_rank /$total,1);
         $avg=number_format((float)$avg, 1, '.', '');
          rsort($total_key);
         
       
         
     }
        ?>
        <div class="reportspdf-boxwrap" style="margin-bottom:20px;">
            <table><tr>
		<td><div class="reportspdf-boxwrapinner">
			<div class="reportspdf-boxwraptitle">AVERAGE POSITION</div>
			<div class="reportspdf-boxwrapsub"><?php echo  $avg ?></div>
                    </div></td>
                    
		<td><div class="reportspdf-boxwrapinner">
			<div class="reportspdf-boxwraptitle">KEYWORDS IN TOP 10</div>
			<div class="reportspdf-boxwrapsub"><?php
                       
                        
                        if(!empty($keyword_rank)){
                            
                        if($total>10){
                            $top_val =$total_key[9];
                            $top_ten=0;
                           foreach ($total_key as $val){
                            if($val>=$top_val)  {
                             $top_ten++  ;
                            }
                           }
                             
                          echo $top_ten;  
                        }else{
                            echo $total;
                            
                        }
                        
                        }else{
                            echo "0";
                        }
                      
                       ?></div>
                    </div></td>
                    
		<td><div class="reportspdf-boxwrapinner">
			<div class="reportspdf-boxwraptitle">POSITIONS CHANGE</div>
			<div class="reportspdf-boxwrapsub"><?php $pos=$start_pos-$end_pos;
                        if($start_pos==$end_pos){echo '0';}
                        if($start_pos>$end_pos){echo '+'.$pos;}
                        if($start_pos<$end_pos){echo $pos;}
                       ?></div>
                    </div></td>
            </tr>
            </table>
	</div>
	
     
	
            
	
        <div class="reportspdf-clientname" style="text-transform:uppercase;">PROJECT: <?php echo $project_name; ?></div>
	<div class="reportspdf-infoline">
            <table> <tr>
                    <td><div class="reportpdf-datetitle" style="width: 150px; line-height: 15px;" >KEYWORD</div></td>
		 <td><div class="reportpdf-chantitle" style="width: 60px; line-height: 15px;">START</div></td>
		 <td><div class="reportpdf-camptitle" style="width: 60px; line-height: 15px;">END</div></td>
		 <td><div class="reportpdf-adgrouptitle" style="width: 60px; line-height: 15px;">CHANGE</div></td>
		 <td><div class="reportpdf-erttitle" style="width: 70px; line-height: 15px;">ERT</div></td>
		 <td><div class="reportpdf-erttitle" style="width: 70px; line-height: 15px;">KEI</div></td>
		 <td><div class="reportpdf-erttitle" style="width: 70px; line-height: 15px;">COMP. PAGES</div></td>
		 <td><div class="reportpdf-erttitle" style="width: 70px; line-height: 15px;">SE VOLUME</div></td>
		 <td><div class="reportpdf-erttitle" style="width: 70px; line-height: 15px;">CPC</div></td>
                </tr></table>
	</div>
	<div class="reportspdf-list">
		<ul>
                             <?php 
 foreach ($keyword_data as $row) {
     
          $query = $this->pgsql->query('SELECT * FROM project_keywords_adwordinfo  where keyword_id=\'' . $row['unique_id'] . '\'');
            $keyword_other = $query->result_array();

            if (!empty($keyword_other)) {
                $competition = $keyword_other[0]['competition'];
                $search_volume = $keyword_other[0]['volume'];
                $cpc = $keyword_other[0]['CPC'];
            } else {

                $competition = 'n/a';
                $search_volume = 'n/a';
                $cpc = 'n/a';
            }
           
            $search_val=1;
            $comp_pages = number_format($row['total_records'], 0 , '.' , ',');
            if (!empty($keyword_other)) {
            if($keyword_other[0]['volume']==0) 
                                    {$search_val= 0;
                                    
                                    }else{
                                        $search_val= $keyword_other[0]['volume'];
                                    }
            }
            if ($search_val != 0){
                $kei =number_format(round($row['total_records'] / $search_val,2), 0 , '.' , ',');
            }else{
                $kei = "n/a";
            }
            $keyword = $row['keyword'];
            $unique_id = $row['unique_id'];
          
            $date   =  date("Y-m-d" ,strtotime($start_date));
            $end_date   =  date("Y-m-d" ,strtotime($end_date));
           
            $query = $this->pgsql->query('SELECT * FROM crawled_sites  where keyword_id=\''. $unique_id.'\' and  host = \''.$domain_name. '\' and crawled_date =\''. $date .'\'  ' );
            $keyword_rank = $query->result_array();
            if(!empty($keyword_rank)){
            $start_rank=$keyword_rank[0]['rank'];
            
            switch($start_rank){
                                case 1:
                                    $percent = 30;
                                    break;
                                case 2:
                                    $percent = 16;
                                    break;
                                case 3:
                                    $percent = 10;
                                    break;
                                case 4:
                                    $percent = 8;
                                    break;
                                case 5:
                                    $percent = 6;
                                    break;
                                case 6:
                                    $percent = 4;
                                    break;
                                case 7:
                                    $percent = 3;
                                    break;
                                case 8:
                                    $percent = 3;
                                    break;
                                case 9:
                                    $percent = 2;
                                    break;
                                case 10:
                                    $percent = 2;
                                    break;
                                case 11:
                                    $percent = 1;
                                    break;
                               case 12:
                                    $percent = 0.7;
                                    break;
                                case 13:
                                    $percent = 0.7;
                                    break;
                                case 14:
                                    $percent = 0.6;
                                    break;
                                case 15:
                                    $percent = 0.4;
                                    break;
                                case 16:
                                    $percent = 0.35;
                                    break;
                                case 17:
                                    $percent = 0.33;
                                    break;
                                case 18:
                                    $percent = 0.27;
                                    break;
                                case 19:
                                    $percent = 0.27;
                                    break;
                               case 20:
                                    $percent = 0.29;
                                    break;
                                case 21:
                                    $percent = 0.1;
                                    break;
                                case 22:
                                    $percent = 0.1;
                                    break;
                                case 23:
                                    $percent = 0.08;
                                    break;
                                case 24:
                                    $percent = 0.06;
                                    break;
                                case 25:
                                    $percent = 0.06;
                                    break;
                                case 26:
                                    $percent = 0.05;
                                    break;
                                case 27:
                                    $percent = 0.05;
                                    break;
                                case 28:
                                    $percent = 0.05;
                                    break;
                                case 29:
                                    $percent = 0.04;
                                    break;
                                case 30:
                                    $percent = 0.06;
                                    break;
                                default:
                                    $percent=0;
                                    
                                    
                            }
                            if($percent==0){
                                $ert = "Low Rank";
                                
                            }else{
                                $ert = $keyword_other[0]['volume']*$percent;
                            }
            
           
            }
            else{
                $start_rank="n/a";
                $ert = "Low Rank";
            } 
            $query = $this->pgsql->query('SELECT * FROM crawled_sites  where keyword_id=\''. $unique_id.'\' and  host = \''.$domain_name. '\'   and crawled_date <=\''. $end_date .'\'' );
            $keyword_rank = $query->result_array();
            if(!empty($keyword_rank)){
            $end_rank=$keyword_rank[0]['rank'];
            }
            else{
                $end_rank="n/a";
            } 
            $change='n/a';
            if($start_rank!="n/a" or $end_rank!='n/a'){
              if($start_rank!="n/a" and $end_rank=='n/a'  ){ $change="No Change" ; } 
              if($start_rank=="n/a" and $end_rank!='n/a'  ){ $change=$end_rank ; }               
                
            }
            if($start_rank!="n/a" and $end_rank!='n/a'){
                
              $change=$start_rank-$end_rank;
                
            }
            
            
            
            ?>
                    
			<li>
                            <table> <tr>
		 
                                <td><div class="reportpdf-keywordtitle"  style="width: 150px; line-height: 15px;"><?php echo $keyword; ?></div></td>
				<td><div class="reportpdf-keywordstart" style="width: 60px; line-height: 15px;" ><?php echo $start_rank; ?></div></td>
				<td><div class="reportpdf-keywordcurrent" style="width: 60px; line-height: 15px;"  ><?php echo $end_rank ?></div></td>
				<td><div class="reportpdf-keywordchange"  style="width: 60px; line-height: 15px;" ><?php echo $change ;?></div></td>
				<td><div class="reportpdf-keywordert" style="width: 70px; line-height: 15px;" ><?php echo $ert; ?></div></td>
				<td><div class="reportpdf-keywordert" style="width: 70px; line-height: 15px;" ><?php echo $kei; ?></div></td>
				<td><div class="reportpdf-keywordert" style="width: 70px; line-height: 15px;" ><?php echo $comp_pages; ?></div></td>
				<td><div class="reportpdf-keywordert" style="width: 70px; line-height: 15px;" ><?php echo $search_volume; ?></div></td>
				<td><div class="reportpdf-keywordert" style="width: 70px; line-height: 15px;" ><?php echo $cpc ; ?></div></td>
                                </tr></table>
			</li>
		  <?php 
                }
                          ?>	
		</ul>
	</div>
</div>

</body>
</html>