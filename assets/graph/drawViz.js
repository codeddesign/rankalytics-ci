//load csv data
//d3.csv("/assets/graph/graph_odesk_csv_data.csv", ready);
d3.csv(csvFileUrl, ready);

//set options
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
  rows: ["Rankings", "ERT", "KEI", "Google Wetter"],
  startRow: 0, //zero based index of which row to initially highlight
  //formats to use when displaying the data for each of the rows defined above
  rowFormats: [d3.format(",.0f"), d3.format(",.0f"), d3.format(",.0f"), d3.format(".c")],
  rowColors: ["#029dc8", "#e87777", "#fea501", "#a169a6"], //colors for the rows defined above
  toolColor: "#029dc8", //color of tooltip element
  t: 500, //transition time in ms
  interp: "linear" //interpolation mode for line / area generators
};

options.calcWidth = options.width - options.margins.left - options.margins.right;
options.calcHeight = options.height - options.margins.top - options.margins.bottom -
    options.uiHeight - options.buttonHeight;

var viz = d3.select("#viz").style("width", options.width + "px");

//add ui div
var ui = viz.append("div")
    .attr("id", "ui")
    .attr("height", options.uiHeight + "px");
ui.append("span").text("SEO GRAPH").attr("id", "chartTitle");
ui.append("input").attr({type: "text", id: "endDate"});
ui.append("input").attr({type: "text", id: "startDate"});
ui.append("img").attr("id", "dateImg").attr("src", "/assets/graph/calendar.jpg");

//add svg
var svg = viz.append("div")
    .attr("id", "chart")
  .append("svg")
    .attr("width", options.width)
    .attr("height", options.height - options.uiHeight - options.buttonHeight)
  .append("g")
    .attr("transform", "translate(" + options.margins.left + "," + options.margins.top + ")");
var tooltip = viz.select("#chart")
    .append("div").attr("class", "tooltip")
    .style("opacity", 0);

//add buttons div
var buttons = viz.append("div")
    .attr("id", "buttons")
    .attr("height", options.buttonHeight + "px");

//define d3 scales, formats, etc.
var
  x = d3.time.scale()
    .range([0, options.calcWidth]),
  xAxis = d3.svg.axis()
    .scale(x)
    .innerTickSize(-options.calcHeight)
    .outerTickSize(0)
    .ticks(d3.time.day)
    .tickFormat(d3.time.format("%d %b"))
    .tickPadding(10)
    .orient("bottom"),
  drag = d3.behavior.drag()
    .on("dragstart", function() {d3.select("svg").style("cursor", "grabbing");})
    .on("drag", dragged)
    .on("dragend", function() {d3.select("svg").style("cursor", "grab");}),
  voro = d3.geom.voronoi()
    .x(function(d) {return x(d.Date);})
    .y(function(d) {return ys[options.rows.indexOf(d.category)](d.value);})
    .clipExtent([[-options.margins.left, -options.margins.top],
      [options.width, options.height - options.uiHeight - options.buttonHeight]]),
  ys = [],
  yAxes = [],
  lines = [],
  areas = [],
  actualRows = [],
  data = [], allData = [],
  minDate, maxDate,
  minDatePlus = new Date,
  minDatePlusTwo = new Date;

svg.call(drag);

var xAxisSel = svg.append("g").attr("class", "x axis")
      .attr("transform", "translate(0," + options.calcHeight + ")");
var yAxisSel = svg.append("g").attr("class", "y axis");
var vertLine = svg.append("g").attr("class", "vertLine");
var chart = svg.append("g").attr("class", "chart");

//function to run when all external data loads
function ready(error, inputData) {
  if (error) console.warn("ERROR loading data:", error);

  data = inputData;

  //compile list of variables from options that actually exist in data file
  var myKeys = d3.keys(data[0]);
  var latestInfo = d3.values(data[0]);
  latestInfoArr = latestInfo.toString().split(",");
  var lastDayInfo = d3.values(data[1]);
  lastDayInfoArr = lastDayInfo.toString().split(",");
  
  totalkeys = 4;
  totalkeys2 = 4;
  totalkeys3 = 4;
  
  options.rows.forEach(function(r) {if (myKeys.indexOf(r) >= 0) actualRows.push(r);});

  data.forEach(function(d) {
    d.Date = d3.time.format("%x").parse(d.Date);
    actualRows.forEach(function(r) {
      d[r] = +d[r];
      allData.push({Date: d.Date, category: r, value: d[r]});
    });
  });
  x.domain(d3.extent(data, function(d) {return d.Date;}));
  maxDate = d3.max(data, function(d) {return d.Date;});
  minDate = d3.min(data, function(d) {return d.Date;});
  minDatePlus.setDate(minDate.getDate() + 1);
  minDatePlusTwo.setDate(minDate.getDate() + 2);
  var pickStart = new Pikaday({
    field: document.getElementById("startDate"),
    minDate: minDate,
    maxDate: maxDate,
    defaultDate: minDate,
    onSelect: goDate
  });
  var pickEnd = new Pikaday({
    field: document.getElementById("endDate"),
    minDate: minDate,
    maxDate: maxDate,
    defaultDate: maxDate,
    onSelect: goDate
  });
  d3.select("#startDate").property("value", d3.time.format("%a %b %d %Y")(minDate));
  d3.select("#endDate").property("value", d3.time.format("%a %b %d %Y")(maxDate));

  actualRows.forEach(function(r, i) {
    ys[i] = d3.scale.linear()
      .range([options.calcHeight, 0])
      .domain(d3.extent(data, function(d) {return d[r];}))
      .nice();
    yAxes[i] = d3.svg.axis()
      .scale(ys[i])
      .innerTickSize(-options.calcWidth)
      .tickFormat(options.rowFormats[options.rows.indexOf(r)])
      .tickPadding(10)
      .outerTickSize(options.margins.left)
      .ticks(5)
      .orient("left");
    lines[i] = d3.svg.line()
      .x(function(d) {return x(d.Date);})
      .y(function(d) {return ys[i](d[r]);})
      .interpolate(options.interp);
    areas[i] = d3.svg.area()
      .x(function(d) {return x(d.Date);})
      .y0(options.calcHeight)
      .y1(function(d) {return ys[i](d[r]);})
      .interpolate(options.interp);
  });

  var lineGroups = chart.selectAll("g.lineGroup")
      .data(actualRows)
    .enter().append("g")
      .attr("class", function(d) {return "lineGroup " + d.replace(" ", "");});

  lineGroups.append("path")
      .attr("d", function(d, i) {return areas[i](data);})
      .attr("class", "area")
      .style("fill", function(d, i) {return options.rowColors[i];})
      .style("fill-opacity", 1e-6);

  lineGroups.append("path")
      .attr("d", function(d, i) {return lines[i](data);})
      .attr("class", "line")
      .style("stroke", function(d, i) {return options.rowColors[i];});

  var pointGroups = lineGroups.append("g").attr("class", "pointGroup");
  pointGroups.selectAll("circle.point")
      .data(data)
    .enter().append("circle")
      .attr({"class": "point",
              cx: function(d) {return x(d.Date);},
              cy: function(d, i, j) {return ys[j](d[options.rows[j]]);},
              r: 5})
      .style("fill", function(d, i, j) {return options.rowColors[j];})
      .style("opacity", 0);

  chart.append("g").attr("class", "voro")
    .selectAll("path.selector")
      .data(voro(allData).filter(function(d) {return d;}))
    .enter().append("path")
      .attr("class", "selector")
      .attr("d", polygon)
      .on("mouseover", drawTool)
      .on("mousemove", drawTool)
      .on("mouseout", function() {
        //hide all points & tooltip
        d3.selectAll("circle.point").style("opacity", 0);
        tooltip.style("opacity", 0);
        vertLine.selectAll("line").remove();
      });

  xAxisSel.call(xAxis);
  var tickDist = x(minDatePlusTwo) - x(minDatePlus);
  xAxisSel.selectAll(".tick text")
      .attr("transform", "translate(" + (-tickDist / 2) + ",0)");

  yAxisSel.call(yAxes[0]);
  var tickLen = yAxisSel.selectAll(".tick").data().length;
  yAxisSel.selectAll(".tick text").filter(function(d, i) {return i === 0 || i === tickLen - 1;})
      .style("opacity", 0);

  //button display
  var lineButtons = buttons.selectAll("div.button")
      .data(actualRows.reverse());
  var btnEnter = lineButtons.enter();
  btnEnter.append("div")
      .attr("class", "button")
      .style("border-bottom", function(d) {return "4px solid" + options.rowColors[options.rows.indexOf(d)];})
      .style("width", options.btnDims[0] + "px")
      .style("height", options.btnDims[1] + "px")
      .on("click", selectLine);

  var leftBtn = lineButtons.append("div")
      .attr("class", "leftBtn")
      .style("width", "70%")
      .style("left", 0)
      .style("height", options.btnDims[1] + "px");
  leftBtn.append("div")
      .attr("class", "topLeft")
      .style("width", "100%")
      .style("height", "40%")
      .style("top", 0)
      .text(function(dd){totalkeys2-- ;return numberWithCommas(latestInfoArr[totalkeys2]);});
      
      //.text("???");
      
  leftBtn.append("div")
      .attr("class", "btmLeft condensed")
      .style("width", "100%")
      .style("height", "60%")
      .style("padding-bottom", "4px")
      .style("bottom", 0)
      .text(function(d) {return d.toUpperCase();});

  var rightBtn = lineButtons.append("div")
      .attr("class", "rightBtn")
      .style("width", "30%")
      .style("right", 0)
      .style("height", options.btnDims[1] + "px");
  rightBtn.append("div")
      .attr("class", "topRight")
      .style("width", "100%")
      .style("height", "40%")
      .style("top", 0)
      .text(function(dd){totalkeys3-- ; 
          if(latestInfoArr[totalkeys3]<lastDayInfoArr[totalkeys3]){
            return "▼";
          }else{
            return "▲";
          }
          })
      //.text("▲");   // ▼
  rightBtn.append("div")
      .attr("class", "btmRight condensed")
      .style("width", "100%")
      .style("height", "60%")
      .style("padding-bottom", "4px")
      .style("bottom", 0)
      .text(function(dd){totalkeys-- ; 
            
          if(latestInfoArr[totalkeys]>lastDayInfoArr[totalkeys]){
              diff=latestInfoArr[totalkeys]-lastDayInfoArr[totalkeys];
              diffPercent = 100*diff/latestInfoArr[totalkeys];
              diffPercent = diffPercent.toFixed(2).toString();
          }else if(latestInfoArr[totalkeys]<lastDayInfoArr[totalkeys]){
              
              diff=lastDayInfoArr[totalkeys]-latestInfoArr[totalkeys];
              diffPercent = 100*diff/lastDayInfoArr[totalkeys];
              diffPercent = diffPercent.toFixed(2).toString();
          }else{
      
              diffPercent="0";
              
          }
          return Math.floor(diffPercent)+"%";})
      
  //tooltip display
  var toolGroups = tooltip.selectAll("div.toolGroup")
      .data(actualRows.reverse());
  var tgEnter = toolGroups.enter();
  tgEnter.append("div")
      .attr("class", "toolGroup")
      .style("width", options.ttGroupDims[0] + "px")
      .style("height", options.ttGroupDims[1] + "px");

  var leftTt = toolGroups.append("div")
      .attr("class", "leftTt")
      .style("width", "70%")
      .style("left", 0)
      .style("height", "100%");
  leftTt.append("div")
      .attr("class", "topLeft")
      .style("width", "100%")
      .style("height", "50%")
      .style("top", 0)
      .text("000");
  leftTt.append("div")
      .attr("class", "btmLeft condensed")
      .style("width", "100%")
      .style("height", "50%")
      .style("padding-bottom", "4px")
      .style("bottom", 0)
      .text(function(d) {return d.toUpperCase();});

  var rightTt = toolGroups.append("div")
      .attr("class", "rightTt")
      .style("width", "30%")
      .style("right", 0)
      .style("height", "100%");
  rightTt.append("div")
      .attr("class", "topRight")
      .style("width", "100%")
      .style("height", "50%")
      .style("top", 0)
      .text("▲");   // ▼
  rightTt.append("div")
      .attr("class", "btmRight condensed")
      .style("width", "100%")
      .style("height", "50%")
      .style("padding-bottom", "4px")
      .style("bottom", 0)
      .text("00%");

  function drawTool(d) {
    //show point & tooltip
    var selectedPoint = d3.selectAll("circle.point")
        .filter(function(g) {
          return g.Date === d.point.Date &&
            d3.select(this.parentNode.parentNode).classed(d.point.category.replace(" ", ""));
        })
        .style("opacity", 0);
    tooltip.style("opacity", 1);

    //change tooltip colors
    var myColor = options.toolColor;
    var darker = d3.rgb(myColor).darker();
    var brighter = d3.rgb(myColor).brighter();
    tooltip.style("background", myColor)
      .style("border-color", darker)
      .selectAll(".rightTt").style("color", darker);
    tooltip.selectAll(".btmLeft").style("color", brighter);

    //change tooltip text
    var myData = selectedPoint.data()[0];
    tooltip.selectAll("div.toolGroup")
        .each(function(d) {
          var me = d3.select(this);
          var myIndex = options.rows.indexOf(d);
            if(myIndex == 3) // Only for google weather for adding °C to the end
            {
                 me.select(".topLeft").text(options.rowFormats[myIndex](myData[d])+" °C");
            }
            else
            {
                 me.select(".topLeft").text(options.rowFormats[myIndex](myData[d])); // common for all
            }
          //me.select(".topLeft").text(options.rowFormats[myIndex](myData[d])+"test");
          me.select(".topRight").text("▲"); // ▼
          me.select(".btmRight").text("▼");
        });

    //change position of tooltip
    var tHeight = tooltip.property("offsetHeight"),
        tWidth = tooltip.property("offsetWidth"),
        tMargin = 7,
        tOffset = 12;

    var pointCoord = [d3.mouse(this.parentNode)[0], options.margins.top + options.calcHeight / 2];
    var newLeft = pointCoord[0] - tWidth / 2;
    var newTop = pointCoord[1];

    if (newLeft < tOffset) newLeft = tOffset;
    if (newLeft > options.width - tWidth - tOffset) newLeft = options.width - tWidth - tOffset;

    tooltip
        .style("top", newTop + "px")
        .style("left", newLeft + "px");

    //add vertical line
    vertLine.selectAll("line").remove();
    vertLine.append("line")
        .attr({x1: pointCoord[0],
                y1: 0,
                x2: pointCoord[0],
                y2: options.calcHeight + options.margins.bottom})
        .style("stroke", myColor);
  }

  //initially highlight line
  var thisObj = d3.selectAll("div.button")
      .filter(function(d, i) {return d === options.rows[options.startRow];});
  selectLine.apply(thisObj.node(), [options.rows[options.startRow]]);

}//end ready function

function selectLine(lineName) {
  var noSpace = lineName.replace(" ", ""),
      index = options.rows.indexOf(lineName);

  if (!d3.select(this).classed("btnSelect")) {
    unSelect(lineName);
    d3.select(this).classed("btnSelect", true);
    svg.select("g.lineGroup." + noSpace)
        .classed("selected", true)
      .select("path.area")
      .transition().duration(options.t)
        .style("fill-opacity", 0.2);
  }
  else unSelect();

  yAxisSel.call(yAxes[index]);
  var tickLen = yAxisSel.selectAll(".tick").data().length;
  yAxisSel.selectAll(".tick text").filter(function(d, i) {return i === 0 || i === tickLen - 1;})
      .style("opacity", 0);
}

function unSelect(exceptMe) {
  svg.selectAll("g.lineGroup.selected path.area")
    .filter(function(d) {return d !== exceptMe;})
      .classed("selected", false)
    .transition().duration(options.t)
      .style("fill-opacity", 1e-6);
  buttons.selectAll("div.button.btnSelect")
    .filter(function(d) {return d !== exceptMe;})
      .classed("btnSelect", false);
}

function dragged() {
  var shiftX = x.invert(d3.event.dx) - x.domain()[0];
  x.domain(x.domain().map(function(d) {return d - shiftX;}));
  var tickDist = x(minDatePlusTwo) - x(minDatePlus);
  xAxisSel.call(xAxis).selectAll(".tick text")
    .attr("transform", "translate(" + (-tickDist / 2) + ",0)");
  chart.selectAll("path.area").attr("d", function(d, i) {return areas[i](data);});
  chart.selectAll("path.line").attr("d", function(d, i) {return lines[i](data);});
  chart.selectAll("g.pointGroup").selectAll("circle.point")
    .attr("cx", function(d) {return x(d.Date);})
    .attr("cy", function(d, i, j) {return ys[j](d[options.rows[j]]);});
  chart.select("g.voro").selectAll("path.selector")
    .data(voro(allData).filter(function(d) {return d;}))
    .attr("d", polygon);
  d3.selectAll("circle.point").style("opacity", 0);
  tooltip.style("opacity", 0);
  vertLine.selectAll("line").remove();
}

function goDate() {
  var startDate = new Date(d3.select("#startDate").property("value"));
  var endDate = new Date(d3.select("#endDate").property("value"));
  if (isNaN(startDate) || startDate < minDate) startDate = minDate;
  if (isNaN(endDate) || endDate > maxDate) endDate = maxDate;
  if (startDate >= endDate) endDate.setDate(startDate.getDate() + 1);

  x.domain([startDate, endDate]);
  d3.select("#startDate").property("value", d3.time.format("%a %b %d %Y")(startDate));
  d3.select("#endDate").property("value", d3.time.format("%a %b %d %Y")(endDate));

  var t = svg.transition().duration(options.t);
  var tickDist = x(minDatePlusTwo) - x(minDatePlus);
  t.select(".x.axis").call(xAxis).selectAll(".tick text")
    .attr("transform", "translate(" + (-tickDist / 2) + ",0)");
  t.selectAll("path.area").attr("d", function(d, i) {return areas[i](data);});
  t.selectAll("path.line").attr("d", function(d, i) {return lines[i](data);});
  t.selectAll("g.pointGroup").selectAll("circle.point")
      .attr("cx", function(d) {return x(d.Date);})
      .attr("cy", function(d, i, j) {return ys[j](d[options.rows[j]]);});
  chart.select("g.voro").selectAll("path.selector")
      .data(voro(allData).filter(function(d) {return d;}))
      .attr("d", polygon);
}

function polygon(d) {
  return d.length > 0 ? "M" + d.join("L") + "Z" : "M0,0";
}

/*
 * @used for convert number to comma seperated n
 * commas as thousands separators
 */
function numberWithCommas(x) {
    if(parseInt(x) <=0)
    {
        return "0,00";
    }
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
