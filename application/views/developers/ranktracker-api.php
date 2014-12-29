<?= lang('developerstrackapi.doctype');?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />

<!-- favicon -->
<link rel="icon" type="image/png" href="https://rankalytics.com/assets/images/favicon.png">
<link rel="shortcut icon" type="image/png" href="https://rankalytics.com/assets/images/favicon.png"/>
<!-- end favicon -->


<title><?= lang('developerstrackapi.title');?></title>
<META NAME="Description" CONTENT="<?= lang('developerstrackapi.description');?>">

<link href="<?php echo base_url(); ?>assets/home.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript" src="//use.typekit.net/pjn4zge.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">

<!-- clicky tracking -->
<script type="text/javascript">
var clicky_site_ids = clicky_site_ids || [];
clicky_site_ids.push(100773884);
(function() {
  var s = document.createElement('script');
  s.type = 'text/javascript';
  s.async = true;
  s.src = '//static.getclicky.com/js';
  ( document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0] ).appendChild( s );
})();
</script>
<noscript><p><img alt="Clicky" width="1" height="1" src="//in.getclicky.com/100773884ns.gif" /></p></noscript>
<!-- End clicky tracking -->
</head>
<body>

<?php $this->load->view('home/page-navplacement.php');?>
		
		<div class="ranktracker-topline"></div>

	</div>
</div>

<div class="bodywrapper">
	<div class="features-lefttapwrap">
		<?php $this->load->view('developers/developernav.php');?>
	</div>
	<div class="features-longline"></div>
	<div class="features-rightwrap">
		<div class="features-righttitle"><?= lang('developerstrackapi.righttitle');?></div>
		
		<div class="features-rightlistitems">
			<div class="features-image">
				<img src="https://rankalytics.com/assets/images/featurestrafficanalytics.png" style="margin-left: 10px;"/>
			</div>
			<div class="features-rightlistwrap">
				<div class="features-rightlisttitle"><?= lang('developerstrackapi.listtitle');?></div>
				<div class="features-rightlistsub">
				<?= lang('developerstrackapi.listcontent');?></p>
				<p>
				<div class="apititle"><?= lang('developerstrackapi.apititle');?></div>
				<ul>
					<li><?= lang('developerstrackapi.apilione');?></li>
					<li><?= lang('developerstrackapi.apilitwo');?></li>
					<li><?= lang('developerstrackapi.apilithree');?></li>
				</ul>
				</p>
					<div class="apititle"><?= lang('developerstrackapi.apititletwo');?></div>
					<div class="apicontent">
					function CallAPI($url, $data = false)<br>
					{<br>
						<blockquote>
 						$curl = curl_init();<br>
 						curl_setopt($curl, CURLOPT_POST, 1);<br>
 						curl_setopt($curl, CURLOPT_POSTFIELDS, $data);<br>
 						curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);<br>
 						curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: multipart/form-data"));<br>
 						curl_setopt($curl, CURLOPT_URL, $url);<br>
 						curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);<br>
 						return curl_exec($curl);<br>
 						</blockquote>
					}<br>
					</div>
					<div class="apititle"><?= lang('developerstrackapi.apititletwo');?></div>
					<div class="apicontent">
					function callApi(endpoint, data)<br>
					{<br>
						<blockquote>
 						$.ajax({<br>
 						url: endpoint,<br>
 						dataType: "json",<br>
 						type: "POST",<br>
 						data:data,<br>
 
 						success: function( data ) {<br>
 						}<br>
 						});<br>
 						</blockquote>
					}<br>
					</div>
					<div class="apititle"><?= lang('developerstrackapi.endpoint');?></div>
					<div class="apicontent">End-point URL = http://rankalytics.com/api/api.php</div>
					<div class="apititle" style="margin-top:70px;"><?= lang('developerstrackapi.apirequests');?></div>
					
					<div class="apititle">PHP</div>
					<div class="apicontent">
					$data =array(<br>
						<blockquote>
 						"token" => "XXXXXXXXXXXXXXXXXXXXXXXXXXXX",<br>
 						"request" => "request command",<br>
 						"attributes 1" => "attributes 1 values",<br>
 						"attributes 2" => "attributes 2 values",<br>
 						"attributes N" => "attributes N values",<br>
 						</blockquote>
					);<br>
					$End_point = "http://rankalytics.com/api/api.php"<br>
					$result = CallAPI($End_point, $data)<br>
					<p></p>
					// <br><?= lang('developerstrackapi.phpcomment');?> <br>//
					</div>
					
					<div class="apititle">AJAX</div>
					<div class="apicontent">
					var data={<br>
						<blockquote>
 						request : " request command ",<br>
 						token : " XXXXXXXXXXXXXXXXXXXXXXXXXXXX ",<br>
 						attributes 1 : attributes 1 values,<br>
 						attributes 2 : attributes 2 values,<br>
 						attributes N : attributes N values"<br>
 						</blockquote>
 					};<br>
 					callApi("http://localhost/api/api.php", data);<br>	
					</div>
					
					<div class="apititle" style="margin-top:65px;"><?= lang('developerstrackapi.apicommands');?></div>
					
					<table width="480" border="0">
  						<tbody>
  						  <tr>
  						    <td width="125"><strong><?= lang('developerstrackapi.apirequest');?></strong></td>
  						    <td width="103"><strong><?= lang('developerstrackapi.attributes');?></strong></td>
  						    <td width="228"><strong><?= lang('developerstrackapi.apidescription');?></strong></td>
  						  </tr>
  						  <tr>
  						    <td>createCampaigns</td>
  						    <td>project_name</td>
  						    <td><?= lang('developerstrackapi.apicampname');?></td>
  						  </tr>
  						  <tr>
  						    <th align="left" scope="row">&nbsp;</th>
  						    <td>domain_url</td>
  						    <td><?= lang('developerstrackapi.campaignurl');?></td>
  						  </tr>
  						  <tr>
  						    <th align="left" scope="row">&nbsp;</th>
  						    <td>keywords</td>
  						    <td><?= lang('developerstrackapi.jsonencoded');?></td>
  						  </tr>
  						</tbody>
					</table>
					
					<p><br></p>
					
					<table width="480" border="0">
  						<tbody>
  						  <tr>
  						    <td width="125"><strong><?= lang('developerstrackapi.apirequest');?></strong></td>
  						    <td width="103"><strong><?= lang('developerstrackapi.attributes');?></strong></td>
  						    <td width="228"><strong><?= lang('developerstrackapi.apidescription');?></strong></td>
  						  </tr>
  						  <tr>
  						    <td>delCampaigns</td>
  						    <td>project_id</td>
  						    <td><?= lang('developerstrackapi.projectdelete');?></td>
  						  </tr>
  						</tbody>
					</table>
					
					<p><br></p>
					
					<table width="480" border="0">
					  <tbody>
					    <tr>
  						    <td width="125"><strong><?= lang('developerstrackapi.apirequest');?></strong></td>
  						    <td width="103"><strong><?= lang('developerstrackapi.attributes');?></strong></td>
  						    <td width="228"><strong><?= lang('developerstrackapi.apidescription');?></strong></td>
  						  </tr>
					    <tr>
					      <td>addMoreKeywords</td>
					      <td>project_id</td>
					      <td>Project ID of campaign</td>
					    </tr>
					    <tr>
					      <td>&nbsp;</td>
					      <td>keywords</td>
					      <td><?= lang('developerstrackapi.jsonarray');?></td>
					    </tr>
					  </tbody>
					</table>
					
					<p><br></p>
					
					<table width="480" border="0">
					  <tbody>
					    <tr>
  						    <td width="125"><strong><?= lang('developerstrackapi.apirequest');?></strong></td>
  						    <td width="103"><strong><?= lang('developerstrackapi.attributes');?></strong></td>
  						    <td width="228"><strong><?= lang('developerstrackapi.apidescription');?></strong></td>
  						  </tr>
					    <tr>
					      <td>getMyCampaigns</td>
					      <td>project_id</td>
					      <td><?= lang('developerstrackapi.projectiddelete');?></td>
					    </tr>
					  </tbody>
					</table>
					
					<p><br></p>
					
					<table width="480" border="0">
					  <tbody>
					    <tr>
  						    <td width="125"><strong><?= lang('developerstrackapi.apirequest');?></strong></td>
  						    <td width="103"><strong><?= lang('developerstrackapi.attributes');?></strong></td>
  						    <td width="228"><strong><?= lang('developerstrackapi.apidescription');?></strong></td>
  						  </tr>
					    <tr>
					      <td>getCampaignDetails</td>
					      <td><p>project_name / project_id</p></td>
					      <td><?= lang('developerstrackapi.speccampaign');?></td>
					    </tr>
					  </tbody>
					</table>
					
					<p><br></p>
					
					<table width="480" border="0">
					  <tbody>
					    <tr>
  						    <td width="125"><strong><?= lang('developerstrackapi.apirequest');?></strong></td>
  						    <td width="103"><strong><?= lang('developerstrackapi.attributes');?></strong></td>
  						    <td width="228"><strong><?= lang('developerstrackapi.apidescription');?></strong></td>
  						  </tr>
					    <tr>
					      <td>getKeywordDetails</td>
					      <td><p>project_id</p></td>
					      <td><?= lang('developerstrackapi.ycampid');?></td>
					    </tr>
					    <tr>
					      <td>&nbsp;</td>
					      <td>keyword_id</td>
					      <td><?= lang('developerstrackapi.campaignid');?></td>
					    </tr>
					  </tbody>
					</table>
					
					<div class="apititle" style="margin-top:65px;"><?= lang('developerstrackapi.responses');?></div>
					<ul>
						100 => <?= lang('developerstrackapi.100');?><br>
						101 => <?= lang('developerstrackapi.101');?><br>
						102 => <?= lang('developerstrackapi.102');?><br>
						103 => <?= lang('developerstrackapi.103');?><br>
						105 => <?= lang('developerstrackapi.105');?><br>
						106 => <?= lang('developerstrackapi.106');?><br>
						200 => OK<br>
						201 => <?= lang('developerstrackapi.201');?><br>
						202 => <?= lang('developerstrackapi.202');?><br>
						203 => <?= lang('developerstrackapi.203');?><br>
						204 => <?= lang('developerstrackapi.204');?><br>
						205 => <?= lang('developerstrackapi.205');?><br>
						206 => <?= lang('developerstrackapi.206');?><br>
						403 => <?= lang('developerstrackapi.403');?><br>
						404 => <?= lang('developerstrackapi.404');?><br>
						405 => <?= lang('developerstrackapi.405');?><br>
						500 => <?= lang('developerstrackapi.500');?><br>
						502 => <?= lang('developerstrackapi.502');?><br>
						503 => <?= lang('developerstrackapi.503');?><br>
						504 => <?= lang('developerstrackapi.504');?><br>
					</ul>
				</div>
			</div>
		</div>
		
	</div>



	<div class="homefeatures-smallline"></div>
	<div class="checkoutfeatures"><?= lang('developerstrackapi.checkout');?></div>
	<a href="/products" class="featurescheck"></a>
</div>

<?php $this->load->view('include/mainfooter');?>
<?php $this->load->view('include/login-signup');?>

</body>
</html>