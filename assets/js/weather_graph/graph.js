$(function() {
  var chart;
  if(typeof graphData !== 'undefined') {
    chart = new Highcharts.Chart({
      chart: {
        renderTo: $('.chart-thirtyday')[0],
        type: 'column'
      },
      title: {
        text: 'Weather for Past 30 Days'
      },
      xAxis: {
        categories: graphData.dates,
        labels: {
          enabled: false
        }
      },
      yAxis: {
        min: 1,
        tickInterval: 10,
        title: {
          text: 'Google Temperature'
        }
      },
      legend: {
        enabled: false
      },
      credits: {
        enabled: false
      },
      tooltip: {
        useHTML: true,
        formatter: function() {
          return ''+
            this.x +': '+ this.y +'&deg;';
        }
      },
      plotOptions: {
        column: {
          pointPadding: 0.1,
          borderWidth: 0
        }
      },
        series: [{
          type: 'column',
          name: 'Temperature',
          data: graphData.temps
        }
      ]
    });
  }
});
