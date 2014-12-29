<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />

<!-- favicon -->
<link rel="icon" type="image/png" href="https://rankalytics.com/assets/images/favicon.png">
<link rel="shortcut icon" type="image/png" href="https://rankalytics.com/assets/images/favicon.png"/>
<!-- end favicon -->


<title>Failed!</title>
<meta name="robots" content="noindex">


<link href="<?php echo base_url(); ?>assets/home.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url(); ?>assets/css/radiobuttons.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript" src="//use.typekit.net/pjn4zge.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<link href="http://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">

</head>
<body>

<?php $this->load->view('home/page-navplacement.php');?>
		
		<div class="ranktracker-topline"></div>

	</div>
</div>

<span itemtype="http://schema.org/LocalBusiness" itemscope="">
<div class="bodywrapper">
	
	<div class="robotwrap">
		<div class="robotfailed"></div>
	</div>
	<div class="verify-success">failed!</div>
	<div class="verify-readytogo">oh no! your account verification <br>has failed!</div>
	
	<div class="verify-doublecheck">please double-check your verification url.  If you continue <br>to receive this message, please contact us.</div>
	
	<div class="verify-buttonswrap">
		<div class="verify-buttonsmargin">
			<a href="http://rankalytics.com/">
				<div class="verify-bluebutton">Return to Homepage</div>
			</a>
			<a href="http://rankalytics.com/contactus">	
				<div class="verify-yellowbutton">Contact</div>
			</a>	
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