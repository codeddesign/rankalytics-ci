<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />

<!-- favicon -->
<link rel="icon" type="image/png" href="https://rankalytics.com/assets/images/favicon.png">
<link rel="shortcut icon" type="image/png" href="https://rankalytics.com/assets/images/favicon.png"/>
<!-- end favicon -->
<!-- viewport -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- end viewport -->

<title>Keyword Rankings API | Rankalytics Real-time SEO Marketing Analysis</title>
<meta name="description" content="">

<link rel="author" href="https://plus.google.com/u/0/107956745270665771232/">
<a href="https://plus.google.com/107956745270665771232" rel="publisher"></a>

<meta name="DC.title" content="Real-time Search Engine Rank Tracking" />
<META NAME="Geography" CONTENT="Charlotte, North Carolina">
<META NAME="Language" CONTENT="English">
<META NAME="Copyright" CONTENT="Copyright and Trademark by Rankalytics.com">
<META NAME="distribution" CONTENT="Global">
<META NAME="zipcode" CONTENT="28278">
<META NAME="city" CONTENT="Charlotte">
<META NAME="country" CONTENT="United States">
<meta name="coverage" content="Worldwide">
<meta name="rating" content="General">
<meta name="revisit-after" content="7 days">
<meta http-equiv="Expires" content="0">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">


<link href="<?php echo base_url(); ?>assets/home.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url(); ?>assets/css/radiobuttons.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript" src="//use.typekit.net/pjn4zge.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">

</head>
<body>

<?php $this->load->view('home/page-navplacement.php');?>
		
		<div class="ranktracker-topline"></div>

	</div>
</div>

<div class="contactmap"></div>
<span itemscope itemtype="https://schema.org/LocalBusiness">
<div class="bodywrapper">
	<div class="ranktracker-bottomwhitetitle">Rankalytics SEO API</div>
	<div class="ranktracker-bottomwhitewrapper">
		<div class="ranktracker-bottomwhitesubcontent" style="margin-bottom:70px;width:652px;text-align:left;margin-left:129px;">
		The Rankalytics Rank Tracker R/T API is a REST API to create, read, update, and delete data from your Rank Tracker R/T account.  All Pro and Enterprise customers have unlimited access and request's to the API.  <p>However, we do monitor the API transactions and do now allow commercial resale of the API or Access Token with written consent.
		<h4>Creating the API Access Token</h4>
		<ul style="font-size:16px;list-style-type: decimal;">
			<li>Create a Pro or Enterprise Rank Tracker R/T account</li>
			<li>Login</li>
			<li>Go to Account > Settings</li>
			<li>Scroll down the page to "Generate Access Token"</li>
		</ul>
		
		<h4>Universal API Call</h4>
		in PHP
<pre>
<code>
function CallAPI($url, $data = false) {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: multipart/form-data"));
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	return curl_exec($curl);
}
</code>
</pre>

		<div style="float:left;">in AJAX</div>
<pre>
<code>
function callApi(endpoint, data) {
	$.ajax({
	url: endpoint,
	dataType: "json",
	type: "POST",
	data:data,
 
	success: function( data ) {
		}
	}); 
}
</code>
</pre>		
		
		<h4>End-Point</h4>
<pre><code>
End-point url = https://rankalytics.com/api/api.php
</code></pre>

		<h4>General Form of API Request's</h4>
		PHP
<pre>
<code>
$data =array(
	"token" => "XXXXXXXXXXXXXXXXXXXXXXXXXXXX",
	"request" => "request command",
	"attributes 1" => "attributes 1 values",
	"attributes 2" => "attributes 2 values",
	"attributes N" => "attributes N values",
);
$End_point = “https://rankalytics.com/api/api.php“
$result = CallAPI($End_point, $data)
</code>
</pre>

	<div style="float:left;">The API will give Json formatted responds for every requests.<p></p>AJAX</div>
	
<pre>
<code>
var data={
	request : " request command ",
	token : " XXXXXXXXXXXXXXXXXXXXXXXXXXXX ",
	attributes 1 : attributes 1 values,
	attributes 2 : attributes 2 values,
	attributes N : attributes N values"
};
callApi("https://localhost/api/api.php", data);
</code>
</pre>	
	
		
		
		</div>

	</div>
	<div class="homefeatures-smallline"></div>
	<div class="checkoutfeatures">There are many great reasons to choose Rankalytics. <a href="/products"><span>View all products now</span></a></div>
	<a href="/products" class="featurescheck"></a>
</div>

<?php $this->load->view('include/mainfooter');?>

<?php $this->load->view('include/login-signup');?>


</body>
</html>