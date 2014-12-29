<?= lang('apidemo.doctype');?>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
    
<!-- favicon -->
<link rel="icon" type="image/png" href="https://rankalytics.com/assets/images/favicon.png">
<link rel="shortcut icon" type="image/png" href="https://rankalytics.com/assets/images/favicon.png"/>
<!-- end favicon -->
    
    <title><?= lang('apidemo.title');?></title>
    <meta name="description" content="<?= lang('apidemo.description');?>">
    
    <link href="<?php echo base_url(); ?>assets/home.css" rel="stylesheet" type="text/css">

    <script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
    <script type="text/javascript" src="//use.typekit.net/pjn4zge.js"></script>
    <script type="text/javascript">try{Typekit.load();}catch(e){}</script>
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>assets/css/radiobuttons.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/countUp.min.js"></script>

<!--[if lte IE 8]><script src="https://rankalytics.com/assets/js/r2d3.min.js" charset="utf-8"></script><![endif]-->
    <!--[if gte IE 9]><!--><script src="<?php echo base_url(); ?>assets/js/d3.min.js"></script><!--<![endif]-->
<!--[if lte IE 8]><script src="https://rankalytics.com/assets/js/canvason_ie.js" charset="utf-8"></script><![endif]-->

    <script src="<?php echo base_url(); ?>assets/js/gauge.js"></script>
    <style>
        canvas{
        }
        .overlay {
            position: fixed !important;

        }
    </style>
    <script src="<?php echo base_url(); ?>assets/js/loyalty.js"></script>




    <!-- start big graph -->
    <?php
    /* echo "<pre>";
      print_r($keyword_estimate);
      echo "</pre>"; */
    $extbacklink = "";
    $FlagFrames = 0;
    $FlagRedirects = 0;
    $follow = 10;
    $FlagImages = 10;
    $FlagNoFollow = 100;

    $total = 100;
    $TrustFlow = 20;
    $CitationFlow = 10;
    $TotalBackLinks = 100;
    $description_len=10;
    $title_len=0;
    $title_per=0;
    $description_per=0;
    $refdomain=100;

    $font = 'assets/font/arial-Sans.ttf';

    if($title!="" ){
        $fontSize = 18;
         $bbox = fixbbox(imagettfbbox($fontSize, 0, $font, $title));
         $title_len=$bbox['width'];
         $title_per=round(($title_len/512)*100,0);
    }

    if( $description !="" ){
        $fontSize = 13;
         $bbox = fixbbox(imagettfbbox($fontSize, 0, $font, $description));
         $description_len=$bbox['width'];
         $description_per=round(($description_len/923)*100,0);
    }
     function fixbbox($bbox) {
   $xcorr=0-$bbox[6]; //northwest X
   $ycorr=0-$bbox[7]; //northwest Y
   $tmp_bbox['left']=$bbox[6]+$xcorr;
   $tmp_bbox['top']=$bbox[7]+$ycorr;
   $tmp_bbox['width']=$bbox[2]+$xcorr;
   $tmp_bbox['height']=$bbox[3]+$ycorr;

   return $tmp_bbox;
}

if($default==1){

    if (isset($output_arr) && isset($output_arr['DataTables']['Matches']['Data']) && !empty($output_arr['DataTables']['Matches']['Data'])) {
        //$title = $output_arr['DataTables']['Matches']['Data'][0]['Title'];



        $TotalBackLinks = $output_arr['DataTables']['DomainInfo']['Data'][0]['ExtBackLinks'];
        $TrustFlow = $output_arr['DataTables']['Matches']['Data'][0]['TrustFlow'];
        $CitationFlow = $output_arr['DataTables']['Matches']['Data'][0]['CitationFlow'];
        $refdomain = $output_arr['DataTables']['DomainInfo']['Data'][0]['RefDomains'];
        $FlagFrames = $output_arr['DataTables']['DomainInfo']['Data'][0]['FlagFrames'];
        $FlagRedirects = $output_arr['DataTables']['DomainInfo']['Data'][0]['FlagRedirects'];
        $FlagImages = $output_arr['DataTables']['DomainInfo']['Data'][0]['FlagImages'];
        $FlagNoFollow = $output_arr['DataTables']['DomainInfo']['Data'][0]['FlagNoFollow'];

        $total = $output_arr['DataTables']['DomainInfo']['Data'][0]['TotalBackLinks'];
    }

    if (isset($output_arr) && isset($ip_arr['DataTables']['item0']['Data'][7])) {
        $count = 0;
        foreach ($ip_arr['DataTables']['item0']['Data'][7] as $key => $row) {

            if ($count > 0) {

                $extbacklink .='{ "d" : "' . date("Y-m-d", strtotime($key)) . '"  , "v": "' . ($row) . '"  } ,';
            } else {
                $count = 1;
            }
        }
    }
}
    if (!empty($extbacklink)) {

        $extbacklink = "[" . rtrim($extbacklink, ",") . "]";
    } else {
        $extbacklink = '[
        { "d":"2013-09-29"
        ,"v":"879"
         },{ "d":"2013-10-06"
        ,"v":"1129"
         },{ "d":"2013-10-13"
        ,"v":"1100"
         },{ "d":"2013-10-20"
        ,"v":"1600"
         },{ "d":"2013-10-27"
        ,"v":"1700"
         },{ "d":"2013-11-03"
        ,"v":"1589"
         },{ "d":"2013-11-10"
        ,"v":"2100"
         },{ "d":"2013-11-17"
        ,"v":"1400"
         }]';
    }
    ?>
    <?php
    if (isset($keyword_estimate) && !empty($keyword_estimate) && $default==1 ) {
        $count = 0;
        $keyword_estimate = array_reverse($keyword_estimate);
        $month_data="";
       // print_r($keyword_estimate);
        foreach ($keyword_estimate as $row) {

            $row = explode(";", $row);

            $month_data .=$row[1];
            $month_data .=" , ";
            $count++;
            if ($count == 7) {
                break;
            }
        }
    }

    ?>
    <script>
        $(function() {
            // Hero Graph
            var data =<?php echo $extbacklink; ?>

            data.sort(function(a, b) { return d3.ascending(a.d, b.d)});

            var hero_graph_label = "<?= lang('apidemo.externalbacklinks');?>";

            // Data Start & End Date
            var start_date = new Date(data[0]['d']);
            var end_date = new Date(data[data.length-1]['d']);
            end_date.setDate(end_date.getDate() - 0);

            // Tooltip
            var tt = document.createElement('div'),
            leftOffset = -(~~$('html').css('padding-left').replace('px', '') + ~~$('body').css('margin-left').replace('px', '')),
            topOffset = -32;
            tt.className = 'ex-tooltip';
            document.body.appendChild(tt);

            function hover(d,i) {
                var pos = $(this).offset();
                $("#tooltip").html(d3.time.format('%x')(d.date)+'<div class="tooltipdate">'+d.ratio.toFixed(2)+'</div>')
                .css({position:'absolute'})
                .css({top: y(d.ratio)+30 + "px", left: x(d.date)+50 + "px"})
                .show();
            }
            function hover_out() {
                $("#tooltip").hide();
            }

            data.forEach(function(d) {
                d.date = new Date(d['d']);
                d.ratio = +(d['v']);
            });

            // D3.js Structure
            var margin = {top: 20, right: 0, bottom: 30, left:70},
            width = 800 - margin.left - margin.right,
            height = 205 - margin.top - margin.bottom;

            var x = d3.time.scale().domain([start_date, end_date]).range([0, width]).clamp(false);
            var y = d3.scale.linear().range([height, 0]);

            //x.domain(data.map(function(d) { return d.date; }));
            y.domain([0, d3.max(data, function(d) { return d.ratio ; })]);

            var svg = d3.select("#charts").append("svg")
            .attr("width", width + margin.left + margin.right)
            .attr("height", height + margin.top + margin.bottom)
            .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")")

            // Calls the horizonal lines
            function make_y_axis() {
                return d3.svg.axis()
                .scale(y)
                .orient("left")
                .ticks(5)
            }
            svg.append("g") // draws y axis lines
            .attr("class", "grid")
            .call(make_y_axis()
            .tickSize(-width, 0, 0)
            .tickFormat("")
        )

            var area = d3.svg.area() // area fill
            .x(function(d) { return x(d.date); })
            .y0(height)
            .y1(function(d) { return y(d.ratio); });
            svg.append("svg:path")
            .datum(data)
            .attr("d", area)
            .attr("opacity", .7)
            .attr("class", "area");

            var area = d3.svg.area() // area fill
            .x(function(d) { return x(d.date); })
            .y1(function(d) { return y(d.ratio); });
            svg.append("svg:path")
            .datum(data)
            .attr("d", area)
            .attr("opacity", .5)
            .attr("fill", "#fff");

            var line = d3.svg.line() // line
            .x(function(d) { return x(d.date); })
            .y(function(d) { return y(d.ratio); });
            svg.append("svg:path")
            .data(data)
            .attr("d", line(data))
            .attr("class", "line")

            var xAxis = d3.svg.axis() // x axis
            .scale(x)
            .orient("bottom")
            .tickFormat(d3.time.format('%b %y'))
            .tickSize(3)
            .tickPadding(3)
            .ticks(d3.time.months, 1);
            svg.append("g")
            .call(xAxis)
            .attr("class", "axis")
            .attr("transform", "translate(0,"+height+")");

            var yAxis = d3.svg.axis() // y axis
            .scale(y)
            .orient("left")
            .tickFormat(function(d) { return d; })
            .ticks(4)
            .tickSubdivide(true);
            svg.append("g")
            .call(yAxis)
            .attr("class", "axis");

            svg.selectAll("circle")
            .data(data)
            .enter()
            .append("svg:circle")
            .attr("cx", function(d) { return x(d.date); })
            .attr("cy", function(d) { return y(d.ratio); })
            .attr("r", 4)
            .attr("opacity", 3)
            .attr("fill","#00C0FB")
            .attr("cursor","pointer")
            .attr("stroke","#ffffff")
            .attr("stroke-width","3")
            .on("mouseover", hover)
            .on("mouseout", hover_out);

            svg.append("text")
            .text(hero_graph_label)
            .attr("fill","#587387")
            .attr("font-size","11")
            .attr("font-family","helvetica,arial,sans-serif")
            .attr("font-weight","bold")
            .attr("y",height-9)
            .attr("x",10);

            // *----------- Gauges ----------* //





            backlinktrust = new Donut(document.getElementById("impulse"));
            backlinktrust.maxValue = 100;
            backlinktrust.set(<?php echo $TrustFlow; ?>);

            backlinkweight = new Donut(document.getElementById("impulsetwo"));
            backlinkweight.maxValue = 100;
            backlinkweight.set(<?php echo $CitationFlow; ?>);

            pageguage = new Donut(document.getElementById("titleguage"));
            pageguage.maxValue = 100;
            pageguage.set(<?php echo $title_per ;?>);

            pageguagetwo = new Donut(document.getElementById("descguage"));
            pageguagetwo.maxValue = 100;
            pageguagetwo.set(<?php echo $description_per ;?>);

        });
    </script>
    <!-- end big graph -->


    <!-- start demographic graph -->

    <script>
        $(function() {
            <?php
                //boovad: this never gets set, todo - to be tested
                if(!isset($month_data))  {
                    $month_data = '17.0, 17.9, 30.0, 23.1, 12.0';
                }
            ?>
            var day_of_week_values = [<?php echo rtrim($month_data, " , "); ?>];
            var time_of_day_values = [17.0, 17.9, 30.0, 23.1, 12.0];
            var data_max = Math.max(d3.max(day_of_week_values), d3.max(time_of_day_values));

            function plot_when_d3(selector, data, data_max){
                var margin = {top:10, right: 1, bottom: 1, left: 1},
                width = 305 - margin.left - margin.right,
                height = 100 - margin.top - margin.bottom;

                var x = d3.scale.linear();
                x.domain([0, data.length-1]);
                x.range([1, width-1]);

                var y = d3.scale.linear();
                y.domain([0, data_max]);
                y.range([height, 0]);

                var svg = d3.select(selector).append("svg")
                .attr("width", width + margin.left + margin.right)
                .attr("height", height + margin.top + margin.bottom)
                .append("g")
                .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

                var hline = d3.svg.line() // dotted line
                .x(function(d,i){return x(i);})
                .y(function(d,i){return y(100/data.length);});
                svg.append("svg:path")
                .attr("class", "line")
                .attr("d", hline(data))
                .style("stroke-dasharray", ("3, 3"))
                .attr("opacity", .7)
                .style("stroke","#01b9ff");

                var line = d3.svg.line() // line
                .x(function(d, i) { return x(i); })
                .y(function(d) { return y(d); });

                var area = d3.svg.area() // area fill
                .x(function(d,i) { return x(i); })
                .y0(height)
                .y1(function(d) { return y(d); });
                svg.append("svg:path")
                .datum(data)
                .attr("d", area)
                .attr("opacity", .85)
                .attr("class", "area");

                svg.append("svg:path")
                .data(data)
                .attr("d", line(data))
                .attr("class", "line")

                svg.selectAll("circle") // circles
                .data(data)
                .enter()
                .append("svg:circle")
                .attr("cx", function(d,i) { return x(i); })
                .attr("cy", function(d) { return y(d); })
                .attr("r", 4)
                .attr("opacity", 3)
                .attr("fill","#00C0FB")
                .attr("stroke","#ffffff")
                .attr("stroke-width","3")
                /*
            var yAxis = d3.svg.axis() // y axis
                .scale(y)
                .orient("left")
                .tickFormat(function(d) { return d; })
                .ticks(3)
                .tickSubdivide(true);
              svg.append("g")
                  .call(yAxis)
                  .attr("class", "axis");
                 */
            }

            plot_when_d3("#day_of_week",day_of_week_values, data_max);
           // plot_when_d3("#time_of_day",time_of_day_values, data_max);

        })
    </script>

    <!-- end demographic graph -->

    <script type="text/javascript">
    $(document).ready(function(){

    if($.trim($('#searchurl').val()) !="")
    {
            $.ajax({
            url:"<?php echo base_url();?>python_find_simil/ignitor_live.php" ,
            type:"post",
            dataType: "json",
            data:({website:$('#searchurl').val()}),
            success:function(data)
            {
                $('#tf_idf_container').html(data.html);
            }
        });
    }
});
    </script>
</head>
<body>

<div class="productsdrop">
	<div class="producticon-wrap">
		<a href="/roadmap">
			<div class="roadmap-producticon"></div>
		</a>
		<a href="/developers">
			<div class="rankalyticsapi-producticon"></div>
		</a>
		<a href="/seocrawl">
			<div class="seocrawl-producticon"></div>
		</a>
		<a href="/ranktracker">
			<div class="ranktracker-producticon"></div>
		</a>
		
		<div class="whatisamodule"></div>
	</div>
</div>

<script> 
$(document).ready(function(){
  $("#queueproductsdrop").click(function(){
    $(".productsdrop").slideToggle("slow");
  });
});
</script>

    <div id="headerblue"></div>

    <div id="hometop" style="position:relative;z-index:-1;">
        <div class="demomap"></div>
        <div class="bodywrapper">
           	<a href="/"><div class="logo"></div></a>

        <a href="/contactus" target="_blank">
			<div class="listheader"><?= lang('apidemo.support');?></div>
		</a>
		<a href='#' onclick='loginoverlay()'>
			<div class="listheader"><?= lang('apidemo.dash');?></div>
		</a>
		<div class="headernavwrap">
			<!-- <div class="flag"></div> -->
			<div class="gulag"></div>
			
			<a href="/contactus" class="navdotlink"><?= lang('apidemo.contact');?></a>
			<div class="navdotdrop" id="queueproductsdrop"><?= lang('apidemo.products');?></div>
			<!--<a href="/demo" class="navdotlink">API DEMO!</a>-->
			<!--<a href="/features" class="navdotlink">FEATURES</a>-->
			<a href="/developers" class="navdotlink"><?= lang('apidemo.developers');?></a>
		</div>

            <div class="ranktracker-topline"></div>
            <div class="demotitle"><?= lang('apidemo.demotitle');?></div>
            <!--<div class="demotitle-sub">Retrieve real-time seo analysis on any keyword, website, or company in the country</div>-->
            <div class="demotitle-sub"><?= lang('apidemo.demosub');?></div>
            <div class="demosearchbg">
                <form  id="liveinput" name="liveinput" method="POST" action="/demo">
                    <input type="text" id="searchurl" value="<?php if($default==1){echo @$domainurl;} ?>" name="searchurl" placeholder="<?= lang('apidemo.liveinputplace');?>">
                    <input type="submit" value="Submit" onclick=" return valid();"  class="demosubmit">
                </form>
                <a href="/contactus" class="demo-registerbutton"><?= lang('apidemo.contacttwo');?></a>
            </div>
            <div class="demo-realtimetext" style="margin-top:91px;"><?= lang('apidemo.realtimetext');?></div>
            <div class="demo-timebg">
                <div class="demo-counterbg">
                    <h1 class="demo-counternumbers" id="myCrawledPages">24.02</h1>
                    <script>
                        function valid(){
                            var myVariable=$("#searchurl").val().trim();
                            if(myVariable==""){
                                alert("<?= lang('apidemo.crawledalert');?>") ; return false;
                            }

                            if(/^[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?$/.test(myVariable)){
                            } else {
                                alert("<?= lang('apidemo.invalidurl');?>");
                                return false;
                            }
                            $(".overlay").show();
                        }
                        var options = {
                            useEasing : true,
                            useGrouping : true,
                            separator : '<div class="democomma">,</div>',
                            decimal : '.'
                        }
                        var demo = new countUp("myCrawledPages", 24.02, 2118102298, 0, 2.5, options);
                        demo.start();
                    </script>
                </div>
            </div>

        </div>
    </div>
<?php $style=" style='display:none' "; ?>
    <div class="demowrapper"  >

        <div class="democontent-title"><?php if (!empty($domainurl)) {
        echo $domainurl;
    } ?></div>
        <div class="democontent-sub"><?php echo strip_tags($title); ?></div>
        <div  class="democontent-line" <?php if($default!=1){ echo $style; }?>></div>
        <div class="demo-biggraph" <?php if($default!=1){ echo $style; }?>>
            <div id="charts"></div>
            <div id="tooltip"></div>
            <div class="demo-graphright">
                <div class="demo-graphrightsmall"><?= lang('apidemo.graphbacklinks');?></div>
                <div class="demo-graphrightbig"><?php echo number_format((float) $TotalBackLinks, 0, '.', ','); ?></div>

                <div class="demo-graphrightsmall"><?= lang('apidemo.referencedomains');?></div>
                <div class="demo-graphrightbig"><?php echo number_format((float) $refdomain, 0, '.', ','); ?></div>

                <div class="demo-graphrightsmall"><?= lang('apidemo.backlinktrust');?></div>
                <div class="demo-graphrightbig"><?php echo number_format((float) (($TotalBackLinks * $TrustFlow) / 100), 0, '.', ','); ?></div>
            </div>
        </div>
        <div class="demo-graphline" <?php if($default!=1){ echo $style; }?>></div>
        <div class="demo-leftpanel" <?php if($default!=1){ echo $style; }?>>
            <div class="demo-sectiontitle"><?= lang('apidemo.seodata');?></div>
            <div class="demo-sectionsub"><?= lang('apidemo.seodatasub');?></div>
            <div class="demo-longsectional">
                <div class="demo-sectionaltitle"><?= lang('apidemo.backlinkinfo');?></div>
            </div>
            <div class="demo-subsectionleft">
                <div class="demo-subsectionlefttitle"><?= lang('apidemo.backlinkcat');?></div>
                <div class="demo-subsectionrighttitle"><?= lang('apidemo.cattrust');?></div>
                <div class="demo-subsectionline"></div>
                <ul>

                    <?php
                    if (isset($refdom_arr) && !empty($refdom_arr)) {
                        $check_topic = array();
                        $count = 0;
                        foreach ($refdom_arr['DataTables']['Results']['Data'] as $row) {
                            if (!in_array($row['TopicalTrustFlow_Topic_0'], $check_topic)) {
                                ?>
                                <li>
                                    <div class="title"><?php echo $row['TopicalTrustFlow_Topic_0']; ?></div>
                                    <div class="result"><?php echo $row['TopicalTrustFlow_Value_0']; ?></div>
                                </li>
            <?php
            $count++;
            if ($count == 14) {
                break;
            }
        } $check_topic[] = $row['TopicalTrustFlow_Topic_0'];
    }
}
?>



                </ul>
            </div>
            <div class="demo-subsectionright">

                <!--
                <div class="demo-subsectionlefttitle">demographic</div>
                <div class="demo-subsectionline"></div>
                <div id="time_of_day"></div>

                <div class="timebullet">
                    <div class="timebullet_label">Morning</div>
                    <div class="timebullet_value">17.0%</div>
                    <div style="clear: both"></div>
                </div>

                <div class="timebullet">
                    <div class="timebullet_label">Noon</div>
                    <div class="timebullet_value">17.9%</div>
                    <div style="clear: both"></div>
                </div>

                <div class="timebullet">
                    <div class="timebullet_label">Afternoon</div>
                    <div class="timebullet_value">30.0%</div>
                    <div style="clear: both"></div>
                </div>

                <div class="timebullet">
                    <div class="timebullet_label">Evening</div>
                    <div class="timebullet_value">23.1%</div>
                    <div style="clear: both"></div>
                </div>

                <div class="timebullet">
                    <div class="timebullet_label">Late</div>
                    <div class="timebullet_value">12.0%</div>
                    <div style="clear: both"></div>
                </div>--><!-- end time_of_day -->

                <div class="demo-leftgaugearea">
                    <div class="demo-subsectionlefttitle"><?= lang('apidemo.backtrust');?></div>
                    <div class="demo-subsectionline"></div>
                    <div class="gaugevalue"><?php echo $TrustFlow; ?>%</div>
                    <div type="backlinktrust">
                        <canvas width="95px" height="78px" id="impulse" style="padding-top:10px; padding-bottom:10px;margin-left:-5px"></canvas>
                    </div>
                </div>

                <div class="demo-rightgaugearea">
                    <div class="demo-subsectionlefttitle"><?= lang('apidemo.backlinkweight');?></div>
                    <div class="demo-subsectionline"></div>
                    <div class="gaugevalue"><?php echo $CitationFlow; ?>%</div>
                    <div type="backlinkweight">
                        <canvas width="95px" height="78px" id="impulsetwo" style="padding-top:10px; padding-bottom:10px;margin-left:-5px"></canvas>
                    </div>
                </div>
            </div>

            <div class="demo-smallsectional">
                <div class="demo-sectionaltitle"><?= lang('apidemo.refip');?></div>
            </div>
            <div class="demo-smallsectionalright">
                <div class="demo-sectionaltitle"><?= lang('apidemo.backbreak');?></div>
            </div>

            <div class="demo-subsectionleft" style="height:170px;">
                <div class="demo-subsectionlefttitle"><?= lang('apidemo.topip');?></div>
                <div class="demo-subsectionrighttitle"><?= lang('apidemo.backinip');?></div>
                <div class="demo-subsectionline"></div>
                <ul>

                    <?php
                    if (isset($refdom_arr) && !empty($refdom_arr)) {
                        $count = 0;
                        foreach ($refdom_arr['DataTables']['Results']['Data'] as $row) {
                            ?>
                            <li>
                                <div class="title"><?php echo $row['IP']; ?></div>
                                <div class="result"><?php echo number_format((float) $row['ExtBackLinks'], 0, '.', ','); ?></div>
                            </li>
        <?php
        $count++;
        if ($count == 10) {
            break;
        }
    }
}
?>
                </ul>
            </div>


            <div class="demo-subsectionright" style="height:170px;">
                <div class="demo-subsectionlefttitle"><?= lang('apidemo.backtype');?></div>
                <div class="demo-subsectionrighttitle"><?= lang('apidemo.backcount');?></div>
                <div class="demo-subsectionline"></div>
                <ul>

                    <li>
                        <div class="title"><?= lang('apidemo.frames');?></div>
                        <div class="result"><?php if ($total != 0) {
    echo round(($FlagFrames / $total) * 100, 2);
} else {
    echo "0";
} ?>%</div>
                    </li>
                    <li>
                        <div class="title"><?= lang('apidemo.graph');?></div>
                        <div class="result"><?php if ($total != 0) {
    echo round((($FlagImages / $total) * 100), 2);
} else {
    echo "0";
} ?>%</div>
                    </li>
                    <li>
                        <div class="title"><?= lang('apidemo.textlinks');?></div>
                        <div class="result"><?php if ($total != 0) {
    echo round((($total - $FlagImages - $FlagRedirects - $FlagFrames) / $total) * 100, 2);
} else {
    echo "0";
}; ?>% </div>
                    </li>
                    <li>
                        <div class="title"><?= lang('apidemo.redirects');?></div>
                        <div class="result"><?php if ($total != 0) {
                        echo round(($FlagRedirects / $total) * 100, 2);
                    } else {
                        echo "0";
                    }; ?>% </div>
                    </li>
                    <li>
                        <div class="title"><?= lang('apidemo.follow');?></div>
                        <div class="result"><?php if ($total != 0) {
                        echo round((($total - $FlagNoFollow) / $total) * 100);
                    } else {
                        echo "0";
                    } ?>%</div>
                    </li>
                    <li>
                        <div class="title"><?= lang('apidemo.nofollow');?></div>
                        <div class="result"><?php if ($total != 0) {
                        echo round(($FlagNoFollow / $total) * 100);
                    } else {
                        echo "0";
                    } ?>%  </div>
                    </li>

                </ul>
            </div>

            <div class="demo-longsectional" style="margin-top:71px;">
                <div class="demo-sectionaltitle"><?= lang('apidemo.backlocmap');?></div>
            </div>

            <div class="demo-subsectionleft" style="height:210px;">
                <div class="demo-subsectionlefttitle"><?= lang('apidemo.countrieslist');?></div>
                <div class="demo-subsectionline"></div>
                <ul>
<?php
$listcode = "";
$nocode = "";
if (isset($refdom_arr) && !empty($refdom_arr)) {
    $check_value = array();
    $count = 0;

    foreach ($refdom_arr['DataTables']['Results']['Data'] as $row) {
        if (!in_array($row['CountryCode'], $check_value)) {
            $listcode.=$row['CountryCode'];
            $nocode.="0";
            ?>
                                <li>
                                    <div class="title"><?php echo $row['CountryCode']; ?></div>
                                    <div class="result"><?php echo number_format((float) $row['ExtBackLinks'], 0, '.', ','); ?></div>
                                </li>
            <?php
            $count++;
            if ($count == 10) {
                break;
            }
        } $check_value[] = $row['CountryCode'];
    }
}
?>

                </ul>
            </div>


            <div class="demo-subsectionright" style="height:210px;">
                <div class="demo-subsectionlefttitle"><?= lang('apidemo.countriesmap');?></div>
                <div class="demo-subsectionline"></div>
                <div><img src="http://chart.apis.google.com/chart?cht=t&chs=305x190&chco=72ceff,F06867,F06867&chf=bg,s,ffffff&chtm=world&chld=<?php echo $listcode; ?>&chd=s:<?php echo $nocode; ?>" ></div>
                <!--div class="locationsmap"></div-->
            </div>

            <div class="demo-longsectional">
                <div class="demo-sectionaltitle"><?= lang('apidemo.rankingkey');?></div>
            </div>

            <div class="demo-subsectionmiddle" style="height:243px;">
                <div class="demo-subsectionlefttitle"><?= lang('apidemo.subtitlerank');?></div>
                <div class="demo-subsectionlefttitle" style="margin-left:231px;"><?= lang('apidemo.subtitleranktwo');?></div>
                <div class="demo-subsectionlefttitle" style="margin-left:30px;"><?= lang('apidemo.subtitlecpc');?></div>
                <div class="demo-subsectionlefttitle" style="margin-left:40px;"><?= lang('apidemo.subtitlecompetition');?></div>
                <div class="demo-subsectionlefttitle" style="margin-left:30px;"><?= lang('apidemo.subtitlesearchvolume');?></div>
                <div class="demo-subsectionlefttitle" style="margin-left:20px;"><?= lang('apidemo.subtitleesttraf');?></div>
                <div class="demo-subsectionline"></div>
                <ul>
                <?php
                if (isset($keywords_arr) && !empty($keywords_arr) && $default==1) {
                    $count = 0;
                    foreach ($keywords_arr as $row) {
                        if ($count != 0) {
                            $keyword_detials = GetKeywordIdeasExample(trim($domainurl), $row);
                            //print_r($keyword_detials);
                            ?>
                                <li>
                                    <div class="title"><?php echo $row; ?></div>
                                    <div class="rank"><?php if (isset($keyword_detials['rank'])) {
                    echo $keyword_detials['rank'];
                } else {
                    echo "0";
                } ?></div>
                                    <div class="cpc">€<?php if (isset($keyword_detials['cpc'])) {
                    echo round($keyword_detials['cpc'] / 1000000, 3);
                } else {
                    echo "0";
                } ?></div>
                                    <div class="competition"><?php if (isset($keyword_detials['competition'])) {
                        echo round($keyword_detials['competition'], 3);
                    } else {
                        echo "NA";
                    } ?></div>
                                    <div class="volume"><?php if (isset($keyword_detials['volume'])) {
                        echo number_format((float) $keyword_detials['volume'], 0, '.', ',');
                    } else {
                        echo "NA";
                    } ?></div>
                                    <div class="traffic">
               <?php if (isset($keyword_detials['ert'])) {
                      if(number_format((float) $keyword_detials['ert'], 0, '.', ',')!=0)  echo number_format((float) $keyword_detials['ert'], 0, '.', ',');else{echo "Niedriger Rank";}
                    } else {
                        echo "Low Rank";
                    } ?>



                                    </div>
                                </li>
        <?php
        }$count++;
        if ($count == 12) {
            break;
        }
    }
}
?>
                </ul>
            </div>

            <div class="demo-smallsectional">
                <div class="demo-sectionaltitle"><?= lang('apidemo.estkeytraf');?></div>
            </div>
            <div class="demo-smallsectionalright">
                <div class="demo-sectionaltitle"><?= lang('apidemo.linktext');?></div>
            </div>

            <div class="demo-subsectionleft" style="height:170px;">
                <div class="demo-subsectionlefttitle"><?= lang('apidemo.avrank');?></div>
                <div class="demo-subsectionline"></div>
                <div id="day_of_week"></div>
<?php
if (isset($keyword_estimate) && !empty($keyword_estimate)&& $default==1) {
    $count = 0;


    foreach ($keyword_estimate as $row) {

        $row = explode(";", $row);

        $month = date('M', strtotime($row[3]));
        $dd = date('y', strtotime($row[3]));
        ?>
                        <div class="weekbullet">
                            <div class="week_label"><?php echo $month; ?></div>
                            <div class="weekbullet_value"><?php echo $dd; ?></div>
                            <div style="clear: both"></div>
                        </div>

        <?php $count++;
        if ($count == 7) {
            break;
        }
    }
} ?>
            </div>


            <div  class="demo-subsectionright" style="height:170px;">
                <div class="demo-subsectionlefttitle"><?= lang('apidemo.anchor');?></div>
                <div class="demo-subsectionrighttitle"><?= lang('apidemo.distribution');?></div>
                <div class="demo-subsectionline"></div>
                <ul>
<?php
if (isset($anchortext) && !empty($anchortext)) {
    $check_topic = array("");
    $count = 0;
    foreach ($anchortext['DataTables']['AnchorText']['Data'] as $row) {
        if (!in_array($row['AnchorText'], $check_topic)) {
            ?>
                                <li>
                                    <div class="title"><?php echo $row['AnchorText']; ?></div>
                                    <div class="result"><?php echo number_format((float) $row['TotalLinks'], 0, '.', ','); ?></div>
                                </li>
            <?php
            $count++;
            if ($count == 8) {
                break;
            }
        } $check_topic[] = $row['AnchorText'];
    }
}
?>

                </ul>
            </div>

            <div class="demo-longsectional" style="margin-top:30px;">
                <div class="demo-sectionaltitle"><?= lang('apidemo.domainveckey');?></div>
            </div>
            <div class="demo-subsectionmiddle" style="height:243px;">
                <div class="demo-subsectionlefttitle"><?= lang('apidemo.subseckey');?></div>
                <div class="demo-subsectionlefttitle" style="margin-left:244px;"><?= lang('apidemo.subsecgraph');?></div>
                <div class="demo-subsectionlefttitle" style="margin-left:276px;"><?= lang('apidemo.subsecworth');?></div>
                <div class="demo-subsectionline"></div>
                <ul id="tf_idf_container">
                    <div style="height: 45px;left: 50%;position: relative;top: 50%;width: 47px;"><img src="<?php echo base_url();?>assets/images/_ajax_loading.gif" style="margin:10px auto"></div>
                </ul>
            </div>
        </div><!-- end demo-leftpanel -->

        <div class="demo-verticalline" <?php if($default!=1){ echo $style; }?>></div>

        <div class="demo-rightpanel" <?php if($default!=1){ echo $style; }?>>
            <div class="demo-sectiontitle"><?= lang('apidemo.sitedata');?></div>
            <div class="demo-sectionsub"><?= lang('apidemo.onpageanal');?></div>

            <div class="demo-rightsectional">
                <div class="demo-sectionaltitle"><?= lang('apidemo.metadatapp');?></div>
            </div>
            <div class="demo-rightsubsection" style="height:270px;">
                <div class="demo-subsectionlefttitle" ><?= lang('apidemo.sitetitle');?></div>
                <div class="demo-subsectionline"></div>

                <div class="demo-borderredline" <?php if($title_len<512){ echo "style='border-left: 5px solid #5FDCFF;color: #00B0E0;'"; } ?>>
                <?php     echo strip_tags($title); ?></div>
                <div class="demo-bordergrayline"><?= lang('apidemo.pagetitletext');?> <?php echo $title_len;?>px</div>
                <div class="demo-pageguage">
                    <div class="gaugevalue" style="padding-top:39px;font-size:23px;"><?php echo $title_per; ?>%</div>
                    <div type="pageguage" style="width:95px;float:left;">
                        <canvas id="titleguage" style="padding-top:10px; padding-bottom:10px;margin-left:-5px" height="78px" width="95px"></canvas>
                    </div>
                    <div class="pageguage-righttext"><?= lang('apidemo.maxpagetitle');?> <?php echo $title_per; ?>% <?= lang('apidemo.maxpagetitletwo');?></div>
                </div>
            </div>

            <div class="demo-rightsubsection" style="height:270px;">
                <div class="demo-subsectionlefttitle"  ><?= lang('apidemo.pagedesc');?> </div>
                <div class="demo-subsectionline"></div>

                <div class="demo-borderblueline" <?php if($description_len >923){ echo "style='border-left: 5px solid #F06867;color: #F06766;'"; } ?>><?php if(isset($description)) {echo $description ;} ?></div>
                <div class="demo-bordergrayline"><?= lang('apidemo.maxdesc');?> <?php echo $description_len ;?>px</div>
                <div class="demo-pageguage">
                    <div class="gaugevalue" style="padding-top:39px;font-size:23px;"><?php echo $description_per; ?>%</div>
                    <div type="pageguagetwo" style="width:95px;float:left;">
                        <canvas id="descguage" style="padding-top:10px; padding-bottom:10px;margin-left:-5px" height="78px" width="95px"></canvas>
                    </div>
                    <div class="pageguage-righttext"><?= lang('apidemo.maxdesctitle');?> <?php echo $description_per; ?>% <?= lang('apidemo.maxdesctitletwo');?></div>
                </div>
            </div>

            <div class="demo-rightsectional" style="margin-top:35px;">
                <div class="demo-sectionaltitle"><?= lang('apidemo.socialawareness');?></div>
            </div>
<?php
$Twitter=0;
$Facebook=0;
$Google=0;
$totalshare=1;
if($default==1){
require("assets/shareclass.php");
$obj = new shareCount($domainurl);  //Use your website or URL
$Twitter = (int)$obj->get_tweets(); //to get tweets
$Facebook = (int)$obj->get_fb(); //to get facebook total count (likes+shares+comments)
$Google = (int)GetGooglePlusShares($domainurl); //to get google plusones
$totalshare = $Twitter + $Facebook + $Google;
}
?>

            <canvas id="loyalty" height="90" width="90" style="margin:5px 0px 0px 0px;"></canvas>

            <script>
                var doughnutData = [
                    {
                        value: <?php echo $Google; ?>,
                        color:"#d0f4ff"
                    },
                    {
                        value :  <?php echo $Twitter; ?>,
                        color : "#54d7ff"
                    },
                    {
                        value :  <?php echo $Facebook; ?>,
                        color : "#a3e9fe"
                    },
                ];
                var myDoughnut = new Chart(document.getElementById("loyalty").getContext("2d")).Doughnut(doughnutData);


            </script>
            <div class="demo-socialsmalllist">
                <ul>
                    <li>
                        <div class="title">Facebook</div>
                        <div class="value"><?php if($totalshare!=0){echo  round(($Facebook / $totalshare) * 100, 2);} else{echo 0;}?>%</div>
                    </li>
                    <li>
                        <div class="title">Twitter</div>
                        <div class="value"><?php if($totalshare!=0){echo round(($Twitter / $totalshare) * 100, 2);} else{echo 0;}?>%</div>
                    </li>
                    <li>
                        <div class="title">Google+</div>
                        <div class="value"><?php if($totalshare!=0){echo round(($Google / $totalshare) * 100, 2);} else{echo 0;} ?>%</div>
                    </li>
                </ul>
            </div>

            <div class="demo-socialbottomlist">
                <ul>

                    <li>
                        <img class="icon" src="https://rankalytics.com/assets/images/demofacebook.png">
                        <div class="title">Facebook</div>
                        <div class="bigvalue"><?php echo number_format((float) $Facebook, 0, '.', ',') ?></div>
                         <!--div class="smallvalue">Avg. 273</div-->
                    </li>
                    <li>
                        <img class="icon" src="https://rankalytics.com/assets/images/demotwitter.png">
                        <div class="title">Twitter</div>
                        <div class="bigvalue"><?php echo number_format((float) $Twitter, 0, '.', ','); ?></div>
                         <!--div class="smallvalue">Avg. 273</div-->
                    </li>
                    <li>
                        <img class="icon" src="https://rankalytics.com/assets/images/demogoogle.png">
                        <div class="title">Google+</div>
                        <div class="bigvalue"><?php echo number_format((float) $Google, 0, '.', ','); ?></div>
                        <!--div class="smallvalue">Avg. 273</div-->
                    </li>
                </ul>
            </div>

        </div><!-- end demo-rightpanel -->

    </div><!-- end demowrapper -->





    <div class="bodywrapper">
    	<div class="homefeatures-smallline"></div>
	<div class="checkoutfeatures"><?= lang('apidemo.checkout');?></div>
	<a href="/products" class="featurescheck"></a>
</div>

<?php $this->load->view('include/mainfooter');?>
<?php $this->load->view('include/login-signup'); ?>

<style>
    #charts {
        float: left;
        margin-bottom: 11px;
        margin-top: 30px;
    }
    .grid .tick {
        opacity: 1 !important;
        shape-rendering: crispedges;
        stroke: #B1EAFF;
        stroke-width: 1;
    }
    path.line {
        fill: none;
        stroke: #00C0FB;
        stroke-width: 2;
    }
    .grid line {
        fill: none;
        shape-rendering: crispedges;
        stroke: #B1EAFF;
        stroke-width: 1;
    }
    .area {
        fill: #E5F9FF;
        stroke-width: 3;
    }
    .axis {
        stroke-width: 1;
    }
    .axis path, .axis line {
        fill: none;
        shape-rendering: crispedges;
        stroke: #E0F7FF;
        stroke-width: 1;
    }
    line {
        stroke: #CCCCCC;
    }
    .axis text {
        color: #000000;
        font-family: "proxima_nova_rgregular",Helvetica,Arial,sans-serif;
        font-size: 10px;
        opacity:0.4;
    }
    #tooltip {
        background-color: #FFFFFF;
        border: 1px solid #14C9FF;
        border-radius: 5px;
        color: #14C9FF;
        display: none;
        padding: 6px;
        position: absolute;
        text-align: center;
        width: 130px;
        z-index: 999;
    }
    .tooltipdate {
        font-family: "proxima_novasemibold",Helvetica,Arial,sans-serif;
        font-size: 16px;
    }

    .timebullet {
        display: inline-block;
        width: 57px;
        margin-top:6px;
    }

    .timebullet_label {
        font-family: "proxima_nova_rgregular",Helvetica,Arial,sans-serif;
        font-size: 11px;
        padding-left: 0;
        text-align: center;
        font-weight:300;
    }
    .timebullet_value {
        color: #50718C;
        font-family: "proxima_novalight",Helvetica,Arial,sans-serif;
        font-size: 11px;
        line-height: 100%;
        padding-left: 0;
        text-align: center;
        margin-top:4px;
        font-weight:300;
    }
    .gaugevalue {
        color: #3D5D77;
        float: right;
        font-family: "proxima_novalight",Helvetica,Arial,sans-serif;
        font-size: 27px;
        padding: 59px 0 0 13px;
        position: absolute;
        text-align: center;
        width: 60px;
    }
    .week_label {
        font-family: "proxima_nova_rgregular",Helvetica,Arial,sans-serif;
        font-size: 11px;
        margin-left: -10px;
        padding-left: 0;
        text-align: center;
        width: 60px;
        font-weight: 300;
    }
    .weekbullet {
        display: inline-block;
        padding-right: 16px;
        width: 24px;
        margin-top: 6px;
    }
    .weekbullet_value {
        color: #777777;
        font-family: "proxima_novalight",Helvetica,Arial,sans-serif;
        font-size: 11px;
        line-height: 100%;
        padding-left: 0;
        text-align: center;
        width: 40px;
        font-weight: 300;
        margin-top: 4px;
    }
    .bullet {
        width: 160px;
        float:left;
    }
    .container_a {
        border-left: 1px solid #DEDEDE;
        border-right: 1px dashed #CCCCCC;
        float: left;
        margin: 0;
        padding: 5px 0;
        width: 65px;
    }
    .bullet_a {
        background-color: #E4E4E4;
        height: 10px;
        margin: 0;
        padding: 0;
    }
    .container_b {
        float: left;
        margin: 0;
        padding: 5px 0;
        width: 65px;
    }
    .bullet_b {
        background-color: #54D7FF;
        height: 10px;
        margin: 0;
        padding: 0;
    }
</style>
<!-- end big graph css -->
</body>
</html>
<?php

function GetKeywordIdeasExample($domainurl, $key) {
    // Get the service, which loads the required classes.
    $user = new My_adwords_api();
    $targetingIdeaService = $user->GetService('TargetingIdeaService', ADWORDS_VERSION);

    // Create seed keyword.
    $keyword = $key;

    // Create selector.
    $keywords_details = array();
    $selector = new TargetingIdeaSelector();
    $selector->requestType = 'STATS';
    // $selector->requestType = 'IDEAS';
    $selector->ideaType = 'KEYWORD';

    $selector->requestedAttributeTypes = array('KEYWORD_TEXT', 'SEARCH_VOLUME', 'COMPETITION', 'AVERAGE_CPC');

    $languageParameter = new LanguageSearchParameter();
    $english = new Language();
    $english->id = 1001;
    $languageParameter->languages = array($english);

    $locationParameter = new LocationSearchParameter();
    $germany = new Location();
    $germany->id = 2276;
    $locationParameter->locations = array($germany);

    // Create related to query search parameter.
    $relatedToQuerySearchParameter = new RelatedToQuerySearchParameter();
    $relatedToQuerySearchParameter->queries = array($keyword);
    $selector->searchParameters[] = $relatedToQuerySearchParameter;
    $selector->searchParameters[] = $languageParameter;
    $selector->searchParameters[] = $locationParameter;

    // Set selector paging (required by this service).
    $selector->paging = new Paging(0, AdWordsConstants::RECOMMENDED_PAGE_SIZE);
    $info_array['volume'] = null;
    $info_array['competition'] = null;
    do {
        $page = $targetingIdeaService->get($selector);


        // Display results.
        if (isset($page->entries)) {
            foreach ($page->entries as $targetingIdea) {
                $data = MapUtils::GetMap($targetingIdea->data);
                $keyword = $data['KEYWORD_TEXT']->value;
                $search_volume = isset($data['SEARCH_VOLUME']->value) ? $data['SEARCH_VOLUME']->value : 0;
                //$targeted_monthly_searches = isset($data['TARGETED_MONTHLY_SEARCHES']->value) ? $data['TARGETED_MONTHLY_SEARCHES']->value : 0;
                $competition = isset($data['COMPETITION']->value) ? $data['COMPETITION']->value : 0;
                $avg_cpc = isset($data['AVERAGE_CPC']->value) ? $data['AVERAGE_CPC']->value->microAmount : 0;
                $rank=get_rank($domainurl, $keyword);
                $ert=ert($search_volume,$rank);
                $info_array['keywords'] = $keyword;
                $info_array['rank'] = $rank;
                $info_array['cpc'] = $avg_cpc;
                $info_array['volume'] = $search_volume;
                $info_array['competition'] = $competition;
                $info_array['ert'] = $ert;
                // $keywords_details[]=$info_array;
            }
        } else {
            unset($info_array);
            $info_array = array();
            $info_array['keywords'] = $keyword;
            $info_array['rank'] = get_rank($domainurl, $keyword);
            $info_array['cpc'] = 0;
            $info_array['volume'] = 0;
            $info_array['competition'] = 0;
            $info_array['ert'] = "Low Rank";
        }

        // Advance the paging index.
        $selector->paging->startIndex += AdWordsConstants::RECOMMENDED_PAGE_SIZE;
    } while ($page->totalNumEntries > $selector->paging->startIndex);

    return $info_array;
}

function get_rank($domain, $keywords) {
// Clean the post data and make usable


    $i = 1;
    $hit = 0;
    $domain = filter_var($domain, FILTER_SANITIZE_STRING);

    $keywords = filter_var($keywords, FILTER_SANITIZE_STRING);

// Remove begining http and trailing /

    $domain = substr($domain, 0, 7) == 'http://' ? substr($domain, 7) : $domain;

    $domain = substr($domain, -1) == '/' ? substr_replace($domain, '', -1) : $domain;

    $keywords = strstr($keywords, ' ') ? str_replace(' ', '+', $keywords) : $keywords;

    $html = new DOMDocument();

    @$html->loadHtmlFile('https://www.google.de/search?q=' . $keywords);

    $xpath = new DOMXPath($html);


    $nodes = $xpath->query('//div[1]/cite');

    $hit = 2;

    foreach ($nodes as $n) {
// echo '<div style="font-size:0.7em">'.$n->nodeValue.'<br /></div>'; // Show all links

        if (strstr($n->nodeValue, $domain)) {

            $message = $i;
            $hit = 1;
        } else {
            ++$i;
        }
    }
    if (isset($message)) {

        return $message;
    }
}

function GetGooglePlusShares($url) {
    error_reporting(E_STRICT | E_ALL);
    $url=ltrim($url,"http://");
    $url="https://plusone.google.com/_/+1/fastbutton?url=http://".$url;

    @$html =  file_get_contents($url) ;
    $doc = new DOMDocument();   @$doc->loadHTML(@$html);
    $counter=$doc->getElementById('aggregateCount');
    $string=$counter->nodeValue;

    if (is_numeric($string)){
    $return_val= rtrim($string,"+");
   }
    else{

     $cnt=rtrim($string,"k");

     $return_val= rtrim($cnt,"+")*1000;
    }

    return $return_val;

}
function ert($volume,$rank){

                            switch($rank){
                                case 1:
                                    $percent = 0.30;
                                    break;
                                case 2:
                                    $percent = 0.16;
                                    break;
                                case 3:
                                    $percent = 0.10;
                                    break;
                                case 4:
                                    $percent = 0.08;
                                    break;
                                case 5:
                                    $percent = 0.06;
                                    break;
                                case 6:
                                    $percent = 0.04;
                                    break;
                                case 7:
                                    $percent = 0.03;
                                    break;
                                case 8:
                                    $percent = 0.03;
                                    break;
                                case 9:
                                    $percent = 0.02;
                                    break;
                                case 10:
                                    $percent = 0.02;
                                    break;
                                case 11:
                                    $percent = 0.01;
                                    break;
                               case 12:
                                    $percent = 0.007;
                                    break;
                                case 13:
                                    $percent = 0.007;
                                    break;
                                case 14:
                                    $percent = 0.006;
                                    break;
                                case 15:
                                    $percent = 0.004;
                                    break;
                                case 16:
                                    $percent = 0.0035;
                                    break;
                                case 17:
                                    $percent = 0.0033;
                                    break;
                                case 18:
                                    $percent = 0.0027;
                                    break;
                                case 19:
                                    $percent = 0.0027;
                                    break;
                               case 20:
                                    $percent = 0.0029;
                                    break;
                                case 21:
                                    $percent = 0.001;
                                    break;
                                case 22:
                                    $percent = 0.001;
                                    break;
                                case 23:
                                    $percent = 0.0008;
                                    break;
                                case 24:
                                    $percent = 0.0006;
                                    break;
                                case 25:
                                    $percent = 0.0006;
                                    break;
                                case 26:
                                    $percent = 0.0005;
                                    break;
                                case 27:
                                    $percent = 0.0005;
                                    break;
                                case 28:
                                    $percent = 0.0005;
                                    break;
                                case 29:
                                    $percent = 0.0004;
                                    break;
                                case 30:
                                    $percent = 0.0006;
                                    break;
                                default:
                                    $percent=0;


                            }
                            if($percent==0){
                                $ERT = "Low Rank";

                            }else{
                                $ERT = $volume*$percent;
                            }
    return $ERT;
}