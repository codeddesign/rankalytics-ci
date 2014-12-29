<?php 

error_reporting(E_ERROR | E_PARSE);
$domain="http://mozcast.com/";

$output = file_get_contents($domain);
 $dom = new DOMDocument();
$dom->loadHTML($output);
$domx = new DOMXPath($dom);
//$entries = $domx->evaluate("//li");
$entries = $domx->evaluate('//li[@class="row"]');
$arr = array();
$res = array();
$i=1;
foreach ($entries as $entry) {
    $arr["t".$i] = substr(trim($entry->nodeValue),0,5) ;
    $arr["d".$i] = substr(trim($entry->nodeValue),4) ;
    $res[]=$arr;
    $i++;
}


include("assets/screen/GrabzItClient.class.php");
$grabzIt = new GrabzItClient("YTNkNjM0YmE2NDE0NDk0NTg5ODgxYzM5ZjNjODAxNDM=", "GSc/bD8leQlsNgc/LhJOPz90Tj8FLT9qGBohP2NAPz8=");
$grabzIt->SetImageOptions($domain, null, null, null, 545, 400, "png", null, "highcharts-0" );
$grabzIt->SaveTo("assets/screen/temp.png");


?>

    <div class="yellowtopline"></div>
	<div class="topinfobar">
		<a href="#" id="weather" <a href="#" onclick="toggle_visibility('weatherpopup');">
			<div class="weathericon">
				<img src="<?php echo base_url(); ?>assets/images/weather/sun.png">
			</div>
			<div class="weathertext">Google Weather</div>
		</a>
		
<!-- toggle seo weather -->
<script type="text/javascript">
    function toggle_visibility(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'block')
          e.style.display = 'none';
       else
          e.style.display = 'block';
    }
</script>
<!-- end toggle -->

<!-- seo weather popup design -->
<div id="weatherpopup" >
	<div class="weatherpopup-top"></div>
	<div class="weatherpopup-bg" >
    	<ul class="nav five-day" style=" margin-left: 32px;">
		<?php 
		$i=1;
		foreach($res as $temp){
		?>
<li class="row" style="list-style: none outside none;  text-align: center; float: left;color: rgb(86, 179, 217);   font: bolder 12px;  width: 100px;">
				<div class="span2 icon">
					<img src="/assets/images/sunrise.png" >
				</div>
				<div class="span2 temp" style="font-size:30px;padding-left: 10px;">
					<?php echo $temp['t'.$i];?> 
				</div>
				<div class="span2 date" style="margin-left: 5px;   padding-top: 10px;">
					<?php echo $temp['d'.$i];?>
				</div>
			</li>
			<?php $i++; }?>	
		</ul>
    	<br><img  style="margin-top:45px; margin-left: 40px;" src="http://rankalytics.com/assets/screen/temp.png"/>
	</div>
</div>		
<!-- end seo weather design -->	
		
		<div class="toptitlebar">KEYWORD RANKINGS FOR: <?php echo isset($domain_url['domain_url'])?$domain_url['domain_url']:'Domain.com';?></div>
	</div>
	<div class="projectbackground">
		<div class="leftsidebar">
			<a href="/keywordsuggestions">
				<div class="leftsidebarbutton-one">
					<div class="ranksicons"></div>
				</div>
				<div class="leftsidebarout-one">Keyword Suggestion</div>
			</a>
			<a href="/competitoranalysis">
				<div class="leftsidebarbutton-two">
					<div class="reportsicons"></div>
				</div>
				<div class="leftsidebarout-two">Competitor Analysis</div>
			</a>	
		</div>
		<div class="dashcontent">
			<div class="keywordrankgraph">
				<!--
				<div class="keywordrankgraph-title">SEO GRAPH</div>
				<div class="keywordrankgraph-filters">
					<input type="text" id="start_date" name="start_date" value="Start Date" />-<input type="text" name="end_date" id="end_date" value="End Date" />
				    <input type="submit" value="GO" /><!-- &nbsp;&nbsp;Zoom: <button id="zoom100">100%</button> <button id="zoom150">150%</button> <button id="zoom200">200%</button>
				</div>
				-->
			</div>
			<div class="rankingschartarea">
        			<!--div id="chart" style="width: 1050px; height: 450px;"></div-->
                                <?php $this->load->view('analytics/graph/index2',$csv) ?>
			</div>
			<?php
                        $data["keywords_array"] = $keywords_array;
                        $data["quicksearch"] = $quicksearch;
                        $data["username"] = $username;
                        $data["project_name_raw"] = $project_name_raw;
                        ?>
                    <link  href="<?php echo base_url();?>assets/pagination.css" rel="stylesheet" type="text/css" />
    <style>
    #scrollbar{
        margin-top:35px;
    }    
    #rankdata{
        position:relative;
    }
    .overlay{
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 10;
  background-color: rgba(0,0,0,0.5); /*dim the background*/
  display: none;
  
}
.overlay img{
    left: 50%;
    position: relative;
    top: 50%;
}
</style>			<div class="keywordrankings"  >
				<div class="keywordrankings-title">KEYWORD RANKINGS</div>
			</div>

            <div id="rank_data_outer" style="position:relative;float:left; ">
                <div class="overlay"><div id="spinner"></div><!--img src="<?php echo base_url();?>assets/images/loading.gif" style="margin:10px auto"--></div>
		<div id="horiz_container_outer">
		<div id="horiz_container">	
			<!-- start individual keyword list -->
			<div class="keywordtopbar">
				<div class="keywordtopbar-keyword">KEYWORD</div>
				<div class="keywordtopbar-position">POSITION</div>
				<div class="keywordtopbar-estimatedtraffic">7-DAY</div>
				<div class="keywordtopbar-estimatedtraffic">28-DAY</div>
				<div class="keywordtopbar-estimatedtraffic">.COM</div>
				<div class="keywordtopbar-estimatedtraffic">LOCAL</div>
				<div class="keywordtopbar-estimatedtraffic">NEWS</div>
				<div class="keywordtopbar-estimatedtraffic">IMAGES</div>
				<div class="keywordtopbar-estimatedtraffic">VIDEO</div>
				<div class="keywordtopbar-estimatedtraffic">SHOPPING</div>
				<!--div class="keywordtopbar-group">GROUP</div-->
				<div class="keywordtopbar-estimatedtraffic">ERT</div>
				<div class="keywordtopbar-competition">COMPETITION</div>
				<div class="keywordtopbar-pagemeta">PAGE META</div>
				<div class="keywordtopbar-kei">KEI</div>
				<div class="keywordtopbar-competingpages">COMP. PAGES</div>
				<div class="keywordtopbar-searchvol">SEARCH VOLUME</div>
				<div class="keywordtopbar-cpc">CPC</div>
			</div>
                    <div id='rankData'>
                    <?php 
                        $this->load->view("analytics/rank_keyword_data",$data);
                    ?>
                    </div>
                    </div><!-- end horiz_container -->	
                        <div id="scrollbar">
                            <div id="track">
                                    <div id="dragBar"></div>
                            </div>
                            </div>
                 
		</div><!-- end horiz_container_outer -->
		<div class="keywordlistbottom"  style="display:none">
                    <div class="compareselected"></div>
                    <form id="quicksearch" method="post" action="<?php echo base_url()?>ranktracker/rankings/<?php echo $username."/".$project_name_raw?>">
                        <input type="text" name="quicksearch" placeholder="filter by keyword..." value="<?php echo isset($quicksearch)?$quicksearch:'';?>">
                        <input type="hidden" name="isAjax" id="isAjax" value="0" />
                    </form>
                    
                    <div id="paging"><?php //echo $this->pagination->create_links(); ?></div>
		</div>
            </div>
                    <script>
                     $(document).ready(function(){
                         /*quicksearchActionOriginal = $("#quicksearch").attr("action");
                         $(".pagination a").click(function(){
                            $("#quicksearch").attr("action",$(this).attr("href"));
                            $("#quicksearch").submit();
                            return false;
                         });
                         
                         $('#quicksearch').submit(function(e){
                            e.preventDefault();
                            
                            $("#isAjax").val(1);
//                            $("#form-loading").show();
                            $("#rank_data_outer .overlay").show();
                            $.post($(this).attr('action'), $(this).serialize(), function( data ) {
                                //$("#form-loading").hide();
                                $("#rank_data_outer .overlay").hide();
                                if(parseInt(data.error))
                                        {
                                            //alert("in error")
                                        }
                                        else
                                        {
                                            $("#quicksearch").attr("action",quicksearchActionOriginal);
                                            $("#rankData").html( data.html);
                                            $("#paging").html( data.pagination);
                                             $(".pagination a").click(function(){
                                                $("#quicksearch").attr("action",$(this).attr("href"));
                                                $("#quicksearch").submit();
                                                return false;
                                             });
                                        }

                            }, 'json');
                            return false;			
                       });*/
                       
                   });
                function delete_keyword(keyword_id,project_id,keywordstr){
                    if(confirm("Are you sure to delete the keyword '"+keywordstr+"'")){
                        $("#rank_data_outer .overlay").show();
                        $.ajax({
                            url:'<?php echo base_url();?>project/delete_project_keyword',
                            type:'POST',
                            data:{keyword_id:keyword_id,project_id:project_id},
                            success:function(response){
                       //           response = JSON.parse(response);
                                  $("#rank_data_outer .overlay").hide();
                                  //alert(response)
                                  if(response=='1'){
                                      $("#"+keyword_id+"-"+project_id).hide();
                                  }
                            }
                         });
                       }
                   }     
                        </script>

		</div>
	</div>