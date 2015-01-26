<a href="#" id="weather">
		<div class="weathericon">
			<img src="<?php echo base_url(); ?>assets/images/weather/sun.png">
		</div>
		<div class="weathertext">Google Weather</div>
		</a>
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
 
<style>
    
.fiveday li {
    color: rgb(86, 179, 217);
    font: bold 12px Arial,Helvetica;
    float: left;
}
.fiveday .date {
    margin-left: 5px;
    padding-top: 10px;
   
}
.span2 {
    width: 60px;
    
}
.fiveday .row
{
list-style:none;
    text-align: center;
    width: 100px
}
.fiveday{
    margin-left: 32px;
}
.fiveday .temp {
    font-size: 30px;
    padding-left: 10px
}
</style>

<div style="margin: 0 auto ;width:600px">
 <ul class="nav fiveday">
                <?php 
                $i=1;
                foreach($res as $temp){
                ?>
			<li class="row">
                            <div class="span2 icon">
                                <img src="/assets/images/sunrise.png" >
                            </div>
			<div class="span2 temp">
				<?php echo $temp['t'.$i];?> 
			</div>
			<div class="span2 date">
				<?php echo $temp['d'.$i];?>			</div>
		</li>
		<?php $i++; }?>	
	</ul>
    <br><img  style="margin-top:45px; margin-left: 40px;" src="http://rankalytics.com/assets/screen/temp.png"/>
            </div>
                    
    
<script>
      jQuery(function($){
           
        
    
$("#weather").click(function(){
    
   $("#weatherpopup").toggle();
});
 });
</script>