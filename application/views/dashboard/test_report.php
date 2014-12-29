<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
    xmlns:svg="http://www.w3.org/2000/svg"
    xmlns:xlink="http://www.w3.org/1999/xlink">
<head><meta http-equiv="Content-Type" content="svg/xml" />
<script src="http://d3js.org/d3.v3.min.js"></script>
<script type="text/javascript">
// Clean dates of extraneous timezone-specific information
function cleanDate(d) {
	var date = new Date(d.date);
	date.setHours(0);
	date.setMinutes(0);
	return date;
}

var data = [ {'date': "2014-03-20", 'value': 20}, {'date': "2014-03-21", 'value': 15}, {'date': "2014-03-22", 'value': 30}, {'date': "2014-03-23", 'value': 20}, {'date': "2014-03-24", 'value': 15}, {'date': "2014-03-25", 'value': 15}, {'date': "2014-03-26", 'value': 10}];

var drawGraph = function() {
	// Set margins, width and height of graph
	var m = [40, 40, 40, 100];
	var w = 693 - m[1] - m[3];
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
	fill: solid;
}
.graph-title { font-size: 90%; }
.axis { shape-rendering: crispEdges; }
.x.axis line, .y.axis line { stroke: lightgrey; }
.x.axis path, .y.axis path { display: none; }
</style>
</head>
<body onload="drawGraph();">
<div id="graph"></div>
</body>
</html>