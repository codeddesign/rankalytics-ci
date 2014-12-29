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

<title>Rank Tracker Settings</title>
<!--link href="http://rankalytics.com/assets/style.css" rel="stylesheet" type="text/css"-->
<link href="<?php echo base_url(); ?>assets/style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/js/jquery.horizontal.scroll.css" />

<!-- Google CDN jQuery with fallback to local -->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
            
<script src="<?php echo base_url(); ?>assets/js/jquery.horizontal.scroll.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/Chart.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/modernizer-custom.js" type="text/javascript"></script>
<script>		
 $(document).ready(function()
    {
        $('#horiz_container_outer').horizontalScroll();
        });
            </script>
<style>
    .headernav a{text-decoration:none;color:inherit;}
 </style>

</head>
<body>
<?php if(!isset($current))$current=''; ?>
<div class="headerline"></div>
<div class="bodywrap <?php echo $current; ?>">
	<div class="logo"></div>
	<div class="headernav">
		<ul>
			<li><a href="<?php echo base_url();?>users/logout">Logout</a></li>
			<li ><a href='#' onclick='loginoverlay()' >Login</a></li>
			<li <?php echo ($current=="support"? 'class="active"':''); ?>><a href="<?php echo base_url();?>#">Support</a></li>
			<li <?php echo ($current=="reports"? 'class="active"':''); ?>><a href="<?php echo base_url();?>/ranktracker/reports">Reports</a></li>
			<li <?php echo ($current=="dashboard"? 'class="active"':''); ?>><a href="<?php echo base_url();?>ranktracker/dashboard">Dashboard</a></li>
		</ul>
	</div>
	<div class="yellowtopline"></div>
   
      <?php 
      include_once("application/views/dashboard/common/common_login.php");
      ?>
      
      