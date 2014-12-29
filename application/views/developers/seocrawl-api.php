<?= lang('developerscrawl.doctype');?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />

<!-- favicon -->
<link rel="icon" type="image/png" href="https://rankalytics.com/assets/images/favicon.png">
<link rel="shortcut icon" type="image/png" href="https://rankalytics.com/assets/images/favicon.png"/>
<!-- end favicon -->


<title><?= lang('developerscrawl.title');?></title>
<META NAME="Description" CONTENT="<?= lang('developerscrawl.description');?>">

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
		<div class="features-righttitle"><?= lang('developerscrawl.pagetitle');?></div>
		
		<div class="features-rightlistitems">
			<div class="features-image">
				<img src="https://rankalytics.com/assets/images/featureskeywordresearch.png" style="margin-left: 10px;"/>
			</div>
			<div class="features-rightlistwrap">
				<div class="features-rightlisttitle"><?= lang('developerscrawl.featurestitle');?></div>
				<div class="features-rightlistsub">
				<?= lang('developerscrawl.featuresdesc');?>
				</div>
			</div>
		</div>
		
	</div>



	<div class="homefeatures-smallline"></div>
	<div class="checkoutfeatures"><?= lang('developerscrawl.checkout');?></div>
	<a href="/products" class="featurescheck"></a>
</div>

<?php $this->load->view('include/mainfooter');?>
<?php $this->load->view('include/login-signup');?>

</body>
</html>