<?php
// var_dump($this->session->userdata('logged_in'));
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />

<META http-equiv="Cache-Control" content="no-cache">
<META http-equiv="Pragma" content="no-cache">
<META http-equiv="Expires" content="0">

<!-- favicon -->
<link rel="icon" type="image/png" href="https://rankalytics.com/assets/images/favicon.png">
<link rel="shortcut icon" type="image/png" href="https://rankalytics.com/assets/images/favicon.png"/>
<!-- end favicon -->

<title>Rankalytics Domain Comparison</title>

<link href="/assets/style.css" rel="stylesheet" type="text/css">
<link href="/assets/js/jquery.horizontal.scroll.css" rel="stylesheet" type="text/css"/>
<link href="/assets/css/c3.css" rel="stylesheet" type="text/css" media="all"/>
<link href="/assets/js/jquery-ui-datepicker/css/smoothness/jquery-ui-1.10.3.custom.css" rel="stylesheet" type="text/css">
<link href="http://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">

<!-- Google CDN jQuery with fallback to local -->
<script src="/assets/js/jquery-1.11.0.min.js" type="text/javascript"></script>
<script src="/assets/js/jquery.horizontal.scroll.js" type="text/javascript"></script>
<script src="/assets/js/Chart.js" type="text/javascript"></script>
<script src="/assets/js/modernizer-custom.js" type="text/javascript"></script>

<script type="text/javascript" src="//use.typekit.net/pjn4zge.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<script src="/assets/js/d3.min.js" type="text/javascript"></script>
<script src="/assets/js/c3.min.js" type="text/javascript"></script>
<script src="/assets/js/jquery.horizontal.scroll.js" type="text/javascript"></script>
<script src="/assets/js/jquery-ui-datepicker/js/jquery-ui-1.10.3.custom.js" type="text/javascript"></script>

<script type="text/javascript">
	$(document).ready(function(){
		//$('#horiz_container_outer').horizontalScroll();
	});
</script>

<script type="text/javascript">
    jQuery(function($)
    {
		$("#weather").click(function()
		{
		   $("#weatherpopup").toggle();
		});
		$(".compareadd").click(function()
		{
			var length_compare = $(":input.compare-domain").length;
			if (length_compare<8)
			{
				var input=document.createElement("input");
				input.setAttribute("type","url");
				input.setAttribute("name","competitor_url[]");
				input.setAttribute("id","domain_compare_"+length_compare);
				input.setAttribute("class","compare-domain");
				input.setAttribute("placeholder","begin typing in one of your competitor URL...");
				input.setAttribute("style", "margin-left:0px");

				document.getElementById('domain_add').appendChild(input);
			}
			else alert ('Competitor URLs cannot be more than 8.');
		});
     });    
</script>
<style type="text/css">
#domain_add
{
	float: right;
	width: 415px;
}
</style>
</head>
<body>

<?php $this->load->view("include/header"); ?>

<!-- <div class="headerline"></div> -->
<div class="bodywrap">
	<div class="overlay" ><div id="spinner"></div></div>
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
    function triggerChange()
    {
        $('#keywordresearch-keyword_text').val($("#keywordresearch-keyword option:selected").text());
        return true;
    }
</script>
<!-- end toggle -->

<!-- seo weather popup design -->
<div id="weatherpopup" class="link_toggle">
	<div class="weatherpopup-top"></div>
	<div class="weatherpopup-bg" >
    	<ul class="nav fiveday">
		<?php 
		$i=1;
		if ($res)
		{
			foreach($res as $temp)
			{
			?>
				<li class="row">
					<div class="span2 icon">
						<img src="/assets/images/sunrise.png" >
					</div>
					<div class="span2 temp">
						<?php echo $temp['t'.$i];?> 
					</div>
					<div class="span2 date">
						<?php echo $temp['d'.$i];?>
					</div>
				</li>
				<?php $i++;
			}
		}
			?>	
		</ul>
    	<br><img  style="margin-top:45px; margin-left: 40px;" src="http://rankalytics.com/assets/screen/temp.png"/>
	</div>
</div>		
<!-- end seo weather design -->	
		
		<div class="toptitlebar">KEYWORD RANKINGS</div>
	</div>

	<div class="projectbackground">
		<div class="leftsidebar">
			<div class="leftsidebarbutton-one">
				<div class="ranksicons"></div>
			</div>
				<div class="leftsidebarout-one">Keyword Suggestions</div>
			<div class="leftsidebarbutton-two">
				<div class="reportsicons"></div>
			</div>
				<div class="leftsidebarout-two">Competitor Analysis</div>
		</div>
		<div class="dashcontent">
		
			<div class="compare-grayarea">
				<div class="compare-wrapper">
					<div class="compare-redtitle">Locate your Competition</div>
					<div class="compare-contentsub">By comparing the frequency of your keywords compared with the competition around you, Rank Tracker is able to track and locate your top competitors.</div>
				</div>
				<div class="compare-bluebutton">Competition Search</div>
			</div>
<?php
// echo "<pre>"; print_r($key_words); echo "</pre>";
?>
                    <form class="keywordresearch-form" method="post" action="<?php echo base_url(); ?>ranktracker/CompareCompetition/" onsubmit="return triggerChange()">
				<div class="keywordresearch-titletop">1. Please select one of your URL's</div>
                                <input type="hidden" value="0" id="keywordresearch-keyword_text" name="keywordresearch-keyword_text"/>
                                <select name="keywordresearch-keyword" id="keywordresearch-keyword" class="keywordresearch-keyword" style="padding:13px 13px 11px 12px;width:391px;height:46px;margin-bottom:23px;" onchange="javascript:triggerChange()">
					<option value="">Select URL</option>
					<?php
					if ($domains)
					{
						foreach ($domains as $key => $domain)
						{
							?>
							<option value="<?php echo $domain['id']; ?>" <?php if ($_POST) { if ($_POST['keywordresearch-keyword']==$domain['id']) echo 'selected="selected"'; else echo ''; } ?>><?php echo $domain['domain_url']; ?></option>
							<?php
						}
					}
					?>
				</select>

				<div class="compare-titletop">2. Enter the URLs of your competitors.</div>
				<input class="compare-domain" type="url" name="competitor_url[]" id="domain_compare" placeholder="Enter a competitor's URL">
				<div id="domain_add"></div>
				<div class="compareadd" style="margin-left:20px;"></div>

				<input type="submit" value="" class="comparerun-button">
			</form>

			<div class="compare-bottomline"></div>

			<div class="compare-toplistitemwrap">
				<div class="compare-toplistitem">
					<ul>
<?php
if ($_POST)
{
	//echo "<pre>"; print_r($competitor_urls); echo "</pre>";
	if ($competitor_urls)
	{
		function remote_file_exists($url){
		   return(bool)preg_match('~HTTP/1\.\d\s+200\s+OK~', @current(get_headers($url)));
		}
		foreach ($competitor_urls as $key3 => $competitor_url)
		{
			?>
			<li>
				<div class="compare-toplisttitle"><?php echo $competitor_url; ?></div>
				<?php
					$fav_url = substr($competitor_url, -1);
					$no_favicon_url = base_url().'assets/images/nofavicon.png';
					if ($fav_url=='/')
					{
						$favicon_url = $competitor_url.'favicon.ico';
						if (remote_file_exists($favicon_url)) {
							echo '<img src="'.$favicon_url.'" />';
						}
						else {
							echo '<img src="'.$no_favicon_url.'" />';
						}
					}
					else
					{
						$favicon_url = $competitor_url.'/favicon.ico';
						if (remote_file_exists($favicon_url)) {
							echo '<img src="'.$favicon_url.'" />';
						}
						else {
							echo '<img src="'.$no_favicon_url.'" />';
						}
					}
				?>
			</li>
			<?php
		}
	}
}
?>
					</ul>
				</div>
			</div>
<?php
if ($_POST)
{
	?>
			<div class="keywordtopbar" style="height:42px;">
				<div class="keywordtopbar-keyword" style="width:273px;">KEYWORD</div>
				<div class="keywordtopbar-position" style="width:106px;text-align:center;">RANKINGS</div>
			</div>
	<?php
	// echo "<pre>"; print_r($ranking); echo "</pre>";
	if ($key_words)
	{
		// echo "<pre>"; print_r($key_words); echo "</pre>";
		foreach ($key_words as $key2 => $key_word)
		{
			// echo ' K2: '.$key2;
			// echo "<pre>"; print_r($ranking[$key2]); echo "</pre>";
			?>
			<!-- start individual keyword list -->
			<div class="keywordmainback" style="height:48px;">
				<a href="http://google.de/search?q=<?php echo $key_word['keyword']; ?>" target="_blank"><div class="compare-worldbutton"></div></a>
				<div class="compare-keywordtitle"><?php echo $key_word['keyword']; ?></div>
				<?php
				foreach ($ranking[$key2] as $key4 => $get_ranking)
				{
					// print_r($get_ranking[0]['rank']);
					// echo $key4.' ';
					if ($key4%2==0)
					{
						?>
						<div class="compare-displaystat">
							<?php
							if ($get_ranking)
								echo $get_ranking;
							else echo "--";
							?>
						</div>
						<?php
					}
					else
					{
						?>
						<div class="compare-displaystatodd">
							<?php
   
							if ($get_ranking)
								echo $get_ranking;
							else echo "--";
							?>
						</div>
						<?php
					}
				}
				?>
			</div>
			<!-- end individual keyword list -->
			<?php
		}
	}
}
?>

		</div>
	</div>
</div>

</body>
</html>