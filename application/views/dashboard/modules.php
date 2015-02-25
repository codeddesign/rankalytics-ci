<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8"/>
    <!-- viewport -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- end viewport -->
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta name="robots" content="noindex">
    
    <!-- favicon -->
<link rel="icon" type="image/png" href="https://rankalytics.com/assets/images/favicon.png">
<link rel="shortcut icon" type="image/png" href="https://rankalytics.com/assets/images/favicon.png"/>
<!-- end favicon -->
	
	<title>Rankalytics Modules</title>
    <!--
	    <title><?php echo (isset($meta_title) && $meta_title != '') ? $meta_title : "Ranktracker"; ?></title>
	-->    
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url(); ?>assets/style.css"/>

    <!--link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url(); ?>assets/js/jquery.horizontal.scroll.css"/-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>assets/js/jquery-ui-datepicker/css/smoothness/jquery-ui-1.10.3.custom.css" rel="stylesheet" type="text/css">

    <!-- Google CDN jQuery with fallback to local -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery-1.11.0.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
    <script type="text/javascript" src="//use.typekit.net/pjn4zge.js"></script>
    <script type="text/javascript">
        try {
            Typekit.load();
        } catch (e) {
        }
    </script>
    <script src="<?php echo base_url(); ?>assets/js/jquery-ui.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/modernizer-custom.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/spiner.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/common.js" type="text/javascript"></script>

    <script type="text/javascript">
        function dashoverlay() {
            el = document.getElementById("dashoverlay");
            el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";
        }

        function closeSelf() {
            document.getElementById("dash overlay").style.visibility = 'hidden';
        }

        function dashglobaloverlay() {
            el = document.getElementById("dashglobaloverlay");
            el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";
        }

        function closeSelf() {
            document.getElementById("dashglobaloverlay").style.visibility = 'hidden';
        }
        
        // create free-user modal
        $(document).ready(function(){
			// add cookie
			// check cookie
		    var freevisited = $.cookie("freevisited")
			if (freevisited == null) {
				// show modal and hide onClick
		        $("#freeuser-overlayback").show();
		        $("#freeuser-nope").click(function(){
				    $("#freeuser-overlayback").hide();
				});
				$.cookie('freevisited', 'yes');
		    } else {
			    // hide modal if cookie present
			    $("#freeuser-overlayback").hide();
		    }
		    $.cookie('freevisited', 'yes', { expires: 1, path: '/' });
			// end add cookie
		});
    </script>
    <style type="text/css">
        label {
            margin-right: 20px;
        }

        body {
            background: none repeat scroll 0 0 #394453 !important;
            margin: 0;
        }


    </style>
</head>
<body onload="init()">

<div id="freeuser-overlayback">
	<div class="freeuser-overlay">
		<div class="freeuser-name">Hello Free user!</div>
		<div class="freeuser-desc">WE LOVE TO SEE YOU USING RANKALYTICS,<br>BUT THERE IS SO MUCH MORE THAT WE CAN OFFER YOU!</div>
		<div class="freeuser-sub">WOULD YOU LIKE TO UPGRADE YOUR MODULES?</div>
		<a href="/users/subscription">
			<div id="freeuser-subscribe">I WAN’T TO UPDADE!</div>
		</a>
		<div id="freeuser-nope">NOPE</div>
	</div>
</div>

<div class="headerline"></div>
<div class="bodywrap">
    <a href="<?php echo base_url(); ?>">
        <div class="logo"></div>
    </a>

    <div class="headernav">
        <?php $this->load->view("include/navheader.php") ?>
    </div>
    <!-- PROJECT LIST START -->
    <div class="choosebelowtitle"><?= lang('modules.title');?></div>

    <div class="choosecurrentline"></div>
    
    <a href="/ranktracker/dashboard" class="signin-rtmodule">
    	<div class="modulelogo"></div>
    	<div class="moduletitle">Rank Tracker R/T</div>
    </a>
    <a href="/seocrawl/dashboard" class="signin-scmodule">
    	<div class="modulelogo"></div>
    	<div class="moduletitle">SEO Crawl</div>
    </a>

    <?php $this->load->view("dashboard/common/footer") ?>