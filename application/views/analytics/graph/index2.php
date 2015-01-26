  <script src="/assets/graph/d3.min.js"></script>
  <link rel="stylesheet" href="/assets/graph/pikaday.css">
  <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,700|Open+Sans+Condensed:300,700" rel="stylesheet" type="text/css">
  <link rel="stylesheet" type="text/css" href="/assets/graph/style.css">

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
<script>
    var csvFileUrl="<?php echo $csv;?>";
    var options = {
        width: 850, //page width
        height: 450, //overall chart height
        uiHeight: 50, //height of title / date selector div
        buttonHeight: 100, //height of button group div
        btnDims: [135, 35], //[width, height] of individual button divs
        ttGroupDims: [115, 35], //[width, height] of individual tooltip series divs
        margins: {"left": 40, "right": 15, "top": 35, "bottom": 35}, //svg margins
        //rows to use as data series, if they exist in the input dataset
        //rows: ["Site Traffic", "Backlinks", "Keyword Rankings", "SEO Visibility"],
        rows: ["Rankings", "ERT", "KEI", "<?php echo lang('rankgraph.weather');?>"],
        startRow: 0, //zero based index of which row to initially highlight
        //formats to use when displaying the data for each of the rows defined above
        rowFormats: [d3.format(",.0f"), d3.format(",.0f"), d3.format(",.0f"), d3.format(".c")],
        rowColors: ["#029dc8", "#e87777", "#fea501", "#a169a6"], //colors for the rows defined above
        toolColor: "#029dc8", //color of tooltip element
        t: 500, //transition time in ms
        interp: "linear" //interpolation mode for line / area generators
    };
</script>
<script src="/assets/graph/pikaday.js"></script>
<script src="/assets/graph/drawViz.js"></script>

<!--/body>
</html-->
