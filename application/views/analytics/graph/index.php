<!--
<script src="<?php echo base_url(); ?>application/views/analytics/graph/d3.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/d3.min.js" type="text/javascript"></script>
-->

<link rel="stylesheet" href="<?php echo base_url(); ?>application/views/analytics/graph/pikaday.css">
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,700|Open+Sans+Condensed:300,700" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>application/views/analytics/graph/style.css">
<!--[if IE 6]>
  <div id="error">
  <p>This interactive graphic requires a browser with SVG support, such as <a href="http://www.google.com/chrome">Chrome</a>, <a href="http://www.mozilla.org/en-US/firefox/">Firefox</a>, <a href="http://www.apple.com/safari/download/">Safari</a> or the latest <a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home">Internet Explorer 9</a>. </p>
  <img src="errorimage.png" width="500" alt="Error">
  <div id="document" style="display:none;">
<![endif]-->
<!--[if IE 7]>
  <div id="error">
  <p>This interactive graphic requires a browser with SVG support, such as <a href="http://www.google.com/chrome">Chrome</a>, <a href="http://www.mozilla.org/en-US/firefox/">Firefox</a>, <a href="http://www.apple.com/safari/download/">Safari</a> or the latest <a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home">Internet Explorer 9</a>. </p>
   <img src="errorimage.png" width="500" alt="Error">
  <div id="document" style="display:none;">
<![endif]-->
<!--[if IE 8]>
  <div id="error">
  <p>This interactive graphic requires a browser with SVG support, such as <a href="http://www.google.com/chrome">Chrome</a>, <a href="http://www.mozilla.org/en-US/firefox/">Firefox</a>, <a href="http://www.apple.com/safari/download/">Safari</a> or the latest <a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home">Internet Explorer 9</a>. </p>
  <img src="errorimage.png" width="500" alt="Error">
  <div id="document" style="display:none;">
<![endif]-->
<!--[if IE 9]>
	<div id="document">
<![endif]-->
<![if !IE]><div id="document"><![endif]>
  <div id="viz"></div>
</div>
<script src="<?php echo base_url(); ?>application/views/analytics/graph/pikaday.js"></script>
<script src="<?php echo base_url(); ?>application/views/analytics/graph/drawViz.js"></script>