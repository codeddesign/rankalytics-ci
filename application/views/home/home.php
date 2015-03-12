<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8"/>
    <!-- favicon -->
	<link rel="icon" type="image/png" href="https://rankalytics.com/assets/images/favicon.png">
	<link rel="shortcut icon" type="image/png" href="https://rankalytics.com/assets/images/favicon.png"/>
	<!-- end favicon -->
	<!-- viewport -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- end viewport -->
    <?= lang( 'home.contenttype' ); ?>
    <TITLE><?= lang( 'home.title' ); ?></TITLE>
    <META NAME="Description" CONTENT="<?= lang( 'home.description' ); ?>">
    <META NAME="Keywords" CONTENT="<?= lang( 'home.keywords' ); ?>">
    <link rel="author" href="https://plus.google.com/u/0/107956745270665771232/">
    <a href="https://plus.google.com/107956745270665771232" rel="publisher"></a>
    <meta name="DC.title" content="<?= lang( 'home.dctitle' ); ?>"/>
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

    <!-- facebook meta -->
    <meta name="og:title" content="<?= lang( 'home.ogtitle' ); ?>"/>
    <meta name="og:type" content="website"/>
    <meta name="og:url" content="https://www.rankalytics.com/"/>
    <meta name="og:image" content="https://rankalytics.com/assets/images/facebookmeta.jpg"/>
    <meta name="og:site_name" content="Rankalytics"/>
    <meta name="og:description" content="<?= lang( 'home.description' ); ?>"/>
    <!-- end Facebook meta -->
    <!-- open graph contact info -->
    <meta name="go:email" content="support@rankalytics.com"/>
    <meta name="go:phone_number" content="704-665-7765"/>
    <!-- end og contact info -->
    <!-- og address info -->
    <meta name="go:locality" content="Charlotte"/>
    <meta name="go:region" content="United States"/>
    <meta name="go:postal-code" content="28278"/>
    <meta name="go:country-name" content="EN"/>
    <!-- end og address info -->

    <!-- favicon -->
    <link rel="shortcut icon" type="image/png" href="https://rankalytics.com/assets/images/favicon.png"/>
    <link rel="shortcut icon" type="image/png" href="https://rankalytics.com/assets/images/favicon.png"/>
    <!-- end favicon -->

    <link href="<?php echo base_url(); ?>assets/home.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>assets/css/radiobuttons.css" rel="stylesheet" type="text/css">

    <script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
    <script type="text/javascript" src="//use.typekit.net/pjn4zge.js"></script>
    <script type="text/javascript">try {
            Typekit.load();
        } catch (e) {
        }</script>
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
    <script src="<?php echo base_url(); ?>assets/js/spiner.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/common.js" type="text/javascript"></script>
    <script type="text/javascript">
        onload = function startAnimation() {
            var frameHeight = 57;
            var frames = 12;
            var frame = 0;
            var div = document.getElementById("animation");
            setInterval(function () {
                var frameOffset = (++frame % frames) * -frameHeight;
                div.style.backgroundPosition = "0px " + frameOffset + "px";
            }, 108);
        }
    </script>

    <!-- clicky tracking -->
    <script type="text/javascript">
        var clicky_site_ids = clicky_site_ids || [];
        clicky_site_ids.push(100773884);
        (function () {
            var s = document.createElement('script');
            s.type = 'text/javascript';
            s.async = true;
            s.src = '//static.getclicky.com/js';
            ( document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0] ).appendChild(s);
        })();
    </script>
    <noscript><p><img alt="Clicky" width="1" height="1" src="//in.getclicky.com/100773884ns.gif"/></p></noscript>
    <!-- End clicky tracking -->
</head>
<!-- <body> -->
<body style="margin-top:139px;">
<!-- start beta div -->
<div class="ranktracker-whitearea">
	<div class="bodywrapper">
		<div class="whitetitlewrapper" style="margin-left:24px;">
			<div class="ranktracker-whitetitle" style="font-size:22px;">Currently in Private Beta</div>
			<div class="ranktracker-whitesubtitle" style="font-size:16px;line-height:24px;">To offer the best experience possible, we are currently working out the kinks in our system.<br><span style="font-size:17px;font-weight:500;">Sign up now to be added to the private beta wait-list.</span></div>
		</div>
		<a href='#' onclick='mailchimpoverlay();return false;'>
			<div class="ranktracker-signupbutton">Sign up now</div>
		</a>	
	</div>
</div>
<!-- end beta div -->
	
<div class="rankbg"></div>

<?php $this->load->view('home/navplacement.php');?>
		
		<div class="hometitle">
			<span id="quotes" class="commercialmaintitle">
				<?= lang('home.quotes');?>
			</span>
		</div>
		<div class="homesmalltitle"><?= lang('home.smalltitle');?></div>
		<div class="homemediumtitle"><?= lang('home.mediumtitle');?></div>
		
		<div class="homebuttonwrap">
			<a href="/products" class="homelearnmore">
				<div class="homelearnmore-text"><?= lang('home.learnmore');?> <div class="homelearnmore-arrow"></div></div>
			</a>
			<a href="/developers" class="trythedemo">
				<div class="trythedemo-text"><?= lang('home.trydemo');?> <div class="trythedemo-arrow"></div></div>
			</a>

			<div class="transbar">
				<div class="transbar-text"><?= lang('home.transbar');?></div>
			</div>
		</div>
	</div>
</div>
<div id="animation"></div>
<span itemscope itemtype="http://schema.org/Product"><div class="bodywrapper">
	<div class="homewhiteleft">
		<div class="whitearea-title"><?= lang('home.whiteareatitle');?></div>
		<div class="homewhiteleft-text"><?= lang('home.whiteleft');?></div>
		<!-- rankalytics promo video area 
		<div class="homewhiteleft-video">
		<img src="<?php echo base_url(); ?>assets/images/videoplacement.png"></img>
		</div>
		-->
		<div class="servgraphic"></div>
	</div>
	<div class="homewhiteright">
		<div class="whitearea-title"><?= lang('home.whiteareatitletwo');?></div>
		<div class="homewhiteright-text"><?= lang('home.whiteright');?></div>
	</div>
</div>
<div class="whiteblueline"></div>
<div class="rankscale">
	<div class="bodywrapper">
		<div class="rankscale-wrap">
			<div class="rankscale-title"><?= lang('home.rankscaletitle');?></div>
			<div class="rankscale-subtitle"><?= lang('home.rankscalesub');?></div>
			<div class="rankscale-content"><?= lang('home.rankscalecontent');?></div>
			<div class="rankscale-video">
				<img src="<?php echo base_url(); ?>assets/images/rankscalevideo.png" title="Enterprise SEO Software"></img>
			</div>
			<div class="rankscale-videotext"><?= lang('home.rankscalevideo');?></div>
		</div>
	</div>
</div>
<div class="bodywrapper">
	<a name="homemoreinfo" style="float:left;height:1px;width:100%;"></a>
	<div class="homefeatures-left">
		<div class="homefeaturesbox">
			<div class="homefeaturesimage">
				<img itemprop="image" src="<?php echo base_url(); ?>assets/images/trafficanalysis.png" title="Real Time Keyword Rank Tracking">
			</div>
			<a href="/ranktracker" class="homefeatures-title"><?= lang('home.featurestitle');?></a>
			<div class="homefeatures-content"><?= lang('home.featurescontent');?></div>
		</div>
		
		<div class="homefeaturesbox">
			<div class="homefeaturesimage">
				<img src="<?php echo base_url(); ?>assets/images/completeapi.png" title="SEO Metrics API">
			</div>
			<a href="/developers/rankalytics-api" class="homefeatures-title"><?= lang('home.featurestitletwo');?></a>
			<div class="homefeatures-content"><?= lang('home.featurescontenttwo');?></div>
		</div>

	</div>
	<div class="homefeatures-right">
		<div class="homefeaturesbox">
			<div class="homefeaturesimage">
				<img src="<?php echo base_url(); ?>assets/images/comptracking.png" title="SEO Spider and Crawling Tool">
			</div>
			<a href="/seocrawl" class="homefeatures-title"><?= lang('home.featurestitlethree');?></a>
			<div class="homefeatures-content"><?= lang('home.featurescontentthree');?></div>
		</div>
		
		<div class="homefeaturesbox">
			<div class="homefeaturesimage">
				<img src="<?php echo base_url(); ?>assets/images/realtimedata.png">
			</div>
			<a href="/roadmap" class="homefeatures-title"><?= lang('home.featurestitlefour');?></a>
			<div class="homefeatures-content"><?= lang('home.featurescontentfour');?></div>
		</div>
		
	</div>
	<div class="homefeatures-smallline"></div>
	<div class="checkoutfeatures"><?= lang('home.checkoutfeatures');?></div>
	<a href="<?php echo base_url(); ?>products"><div class="featurescheck"></div></a>
</div>
<?php $this->load->view('include/mainfooter');?>

<?php $this->load->view('include/login-signup');?>
<!-- javascript text rotator -->
<script type="text/javascript">
 $(document).ready(function()
 {
     setupRotator();
 });
 function setupRotator()
 {
     if($('.textItem').length > 1)
     {
         $('.textItem:first').addClass('current').fadeIn(1000);
         setInterval('textRotate()', 3000);
     }
 }
     function textRotate()
     {
         var current = $('#quotes > .current');
         if(current.next().length == 0)
         {
             current.removeClass('current').fadeOut(1000);
             $('.textItem:first').addClass('current').fadeIn(1000);
         }
         else
         {
             current.removeClass('current').fadeOut(1000);
             current.next().addClass('current').fadeIn(1000);
         }
     }
</script>
<!-- end text rotator -->