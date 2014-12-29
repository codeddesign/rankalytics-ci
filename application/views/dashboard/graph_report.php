<!DOCTYPE html>
<html lang="en">
<head>
<script src="http://d3js.org/d3.v3.min.js"></script>


<?php
   $this->pgsql = $this->load->database('pgsql', true);
   $query = $this->pgsql->query('SELECT * FROM tbl_project where id=\'' . $domain_id . '\'');
        $project_data = $query->result_array();
        $domain_name = $project_data[0]['domain_url'];
        $project_name = $project_data[0]['project_name'];
        $key_query="SELECT * FROM tbl_project_keywords as pk join project_keyword_relation as pkr on pkr.keyword_id=pk.unique_id where pkr.project_id='".$domain_id."'";
        $query = $this->pgsql->query($key_query);
        $keyword_data = $query->result_array();
        $data= array();
        foreach ($keyword_data as $row) {

            $keyword = $row['keyword'];
            $unique_id = $row['unique_id'];
            $graph_val= array();
            $query = $this->pgsql->query('SELECT avg(rank::int) as avg FROM crawled_sites  where keyword_id=\''.$unique_id.'\' and  host = \''.$domain_name. '\' and crawled_date >=\''. $date .' 00:00:00\'  and crawled_date <=\''. $end_date .' 23:59:59\'  group by keyword_id' );
            $keyword_rank = $query->result_array();
            if(!empty($keyword_rank)){
            
            $avg_rank=$keyword_rank[0]['avg'];
                if (isset($keyword_rank[0]['crawled_date'])) {
                    $crawled_date = $keyword_rank[0]['crawled_date'];
                    $data[] = '{"date": "' . $crawled_date . '","value":' . $avg_rank . ' }';
                }
           
            }else{
        $data[] =  '{"date": "2014-03-19","value":30 }';
        $data[] =  '{"date": "2014-03-20","value":20 }';
        $data[] =  '{"date": "2014-03-21","value":22 }';
        $data[] =  '{"date": "2014-03-22","value":24 }';
        $data[] =  '{"date": "2014-03-23","value":26 }';
        $data[] =  '{"date": "2014-03-24","value":18 }';
        $data[] =  '{"date": "2014-03-25","value":10 }';
                
            }
            
        }
       
        
    $data_string= implode(" , ",$data);
    
    ?>
<script type="text/javascript">
    
// Clean dates of extraneous timezone-specific information
function cleanDate(d) {
	var date = new Date(d.date);
	date.setHours(0);
	date.setMinutes(0);
	return date;
}

//var data = [ {'date': "2014-03-20", 'value': 20}, {'date': "2014-03-21", 'value': 15}, {'date': "2014-03-22", 'value': 30}, {'date': "2014-03-23", 'value': 20}, {'date': "2014-03-24", 'value': 15}, {'date': "2014-03-25", 'value': 15}, {'date': "2014-03-26", 'value': 10}];
var   data = [ <?php echo $data_string; ?>];
var drawGraph = function() {
	// Set margins, width and height of graph
	var m = [40, 40, 40, 100];
	var w = 800 - m[1] - m[3];
	var h = 350 - m[0] - m[2];
	var xPadding = 10;
	var yPadding = 29;

	// Set scales for X and Y axes
	var x = d3.time.scale().domain([cleanDate(data[0]), cleanDate(data[data.length-1])]).range([0, w]);
	var y = d3.scale.linear().domain([40, -5]).range([h, 0]);

	// Convert the data[] array into X and Y points
	var line = d3.svg.line()
		.x(function(d, i) { return x(cleanDate(d)); })
		.y(function(d) { return y(d.value); });

	function xx(e) { return x(cleanDate(e)); };
	function yy(e) { return y(e.value); };

	// Create an SVG element inside the DIV with the ID "graph"
	var graph = d3.select("#graph").append("svg:svg")
		.attr("width", w + m[1] + m[3])
		.attr("height", h + m[0] + m[2])
		.append("svg:g")
		.attr("transform", "translate(" + m[3] + "," + m[0] + ")");

	// Create horizontal axis
	var xAxis = d3.svg.axis().scale(x).ticks(d3.time.day, 1).tickFormat(d3.time.format("%-m/%d")).tickSize(-h - xPadding);
	
	// Add the horizontal axis to the graph
	graph.append("svg:g")
		.attr("class", "x axis")
		.attr("transform", "translate(0, " + (h + xPadding) + ")")
		.call(xAxis)
		.selectAll("text") 
		.attr("y", "14px"); //set Y padding here

	// Create vertical axis
	var yAxis = d3.svg.axis().scale(y).tickValues([-5, 0, 5, 10, 15, 20, 25, 30, 35, 40]).orient("left").tickSize(-w - yPadding);
	
	// Add the vertical axis to the graph
	graph.append("svg:g")
		.attr("class", "y axis")
		.attr("transform", "translate(" + (-yPadding) + ", 0)")
		.call(yAxis)
		.selectAll("text") 
		.attr("x", "-23px"); //set X padding here

	// Select the horizontal ticks at the top and bottom and save them in a variable
	var specialTicks = d3.selectAll('g.tick')
		.filter(function(d){ return d == -5 || d == 40;} );

	// Make those ticks stretch further to the left
	specialTicks.select('line').attr("x1", -64);

	// Make those ticks display no text
	specialTicks.select('text').text([]);
	
	// Select the horizontal tick for the value of 0 and make it display 1 instead, as per the original graph
	d3.selectAll('g.tick')
		.filter(function(d){ return d == 0;} )
		.select('text')
		.text([1]);

	// Add circles for every graph value
	graph.selectAll("circle")
		.data(data)
		.enter().append("circle")
		.attr("fill", "black")
		.attr("r", 5)
		.attr("cx", xx)
		.attr("cy", yy);

	// Draw the graph line
	graph.append("svg:path").attr("d", line(data));
	
	// Add text in the upper left of the graph
	graph.append("svg:text")
		.attr("x", -90)
		.attr("y", -11)
		.attr("class", "graph-title")
		.text("POSITION AVERAGES");
}
</script>
<style>
#graph {
	font-family: Arial, sans-serif;
	font-size: 0.75em;
	font-weight: bold;
}
#graph path {
	stroke: black;
	stroke-width: 1;
	fill: none;
}
.graph-title { font-size: 90%; }
.axis { shape-rendering: crispEdges; }
.x.axis line, .y.axis line { stroke: lightgrey; }
.x.axis path, .y.axis path { display: none; }
</style>
</head>
<body onload="drawGraph();">
<div id="graph" style="width:775px;"></div>
</body>
</html>