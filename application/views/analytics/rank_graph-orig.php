	<div class="yellowtopline"></div>
	<div class="topinfobar">
		<div class="weathericon">
			<img src="<?php echo base_url(); ?>assets/images/weather/sun.png">
		</div>
		<div class="weathertext">Google Temperature</div>
		
		<div class="toptitlebar">KEYWORD RANKINGS</div>
	</div>
	<div class="projectbackground">
		<div class="leftsidebar">
			<div class="leftsidebarbutton-one">
				<div class="ranksicons"></div>
			</div>
				<div class="leftsidebarout-one">Keyword Analysis</div>
			<div class="leftsidebarbutton-two">
				<div class="reportsicons"></div>
			</div>
				<div class="leftsidebarout-two">Competitor Analysis</div>
			<div class="leftsidebarbutton-three"></div>
				<div class="leftsidebarout-three"></div>
			<div class="leftsidebarbutton-four"></div>
				<div class="leftsidebarout-four"></div>
		</div>
		<div class="dashcontent">
			<div class="keywordrankgraph">
				<div class="keywordrankgraph-title">SEO GRAPH</div>
				<div class="keywordrankgraph-filters">
					<input type="text" id="start_date" name="start_date" value="Start Date" />-<input type="text" name="end_date" id="end_date" value="End Date" />
				    <input type="submit" value="GO" /><!-- &nbsp;&nbsp;Zoom: <button id="zoom100">100%</button> <button id="zoom150">150%</button> <button id="zoom200">200%</button>-->
				</div>
			</div>
			<div class="rankingschartarea">
        			<div id="chart" style="width: 900px; height: 450px;"></div>
      			<script type="text/javascript">
	      			var chart = c3.generate({
	      			    data: {
	      			        columns: [
	      			            ['data1', 2400,3600,1900,1100,12000],
	      			            ['data2', 1000,2700,5400,2000,7600],
	      			          	['data3', 1200,2000,5000,4000,7000]
	      			        ]
	      			    },
		      			zoom: {
		      		        enabled: false
		      		    },
		      		  	size: {
		      	        	width: 839
		      	    	},
		      	    	legend: {
		      	          show: false
		      	      	},
			      	    axis: {
			      	        x: {
			      	            type: 'categorized',
			      	            categories: ['01 Feb', '02 Feb', '03 Feb', '04 Feb', '05 Feb', '06 Feb', '07 Feb', '08 Feb']
			      	        },
			      	      y: {
			                  padding: {bottom:0},
			                  // Range includes padding, set 0 if no padding needed
			                  // padding: {top:0, bottom:0}
			              }
			      	    },
		      	    	grid: {
		      	          x: {
		      	              show: true
		      	          },
		      	          y: {
		      	              show: true
		      	          }
		      	      }
	      			});
	
	      			/**setTimeout(function () {
	      			    chart.load({
	      			        columns: [
	      			            ['data1', 230, 190, 300, 500, 300, 400]
	      			        ]
	      			    });
	      			}, 1000);
	
	      			setTimeout(function () {
	      			    chart.load({
	      			        columns: [
	      			            ['data3', 130, 150, 200, 300, 200, 100]
	      			        ]
	      			    });
	      			}, 1500);
	
	      			setTimeout(function () {
	      			    chart.unload('data1');
	      			}, 2000);**/
      			</script>
				<!-- <canvas id="canvas" height="450" width="1050" style="margin-left:-13px;"></canvas>
					<script type="text/javascript">
						var lineChartData = {
							labels : ["01 Feb","02 Feb","03 Feb","04 Feb","05 Feb",""],
							datasets : [
								{
								fillColor : "rgba(220,220,220,0.5)",
								strokeColor : "#febb2c",
								pointColor : "rgba(220,220,220,1)",
								pointStrokeColor : "#fff",
								data : [65,59,90,81,56,55,40],
								//data : [400,150,900,1100,12000],
								},
								{
								fillColor : "rgba(151,187,205,0.5)",
								strokeColor : "rgba(151,187,205,1)",
								pointColor : "rgba(151,187,205,1)",
								pointStrokeColor : "#fff",
								data : [28,48,40,19,96,27,100],
								//data : [1000,700,400,2000,7600],
								}
							]
			
						}
						var opts = { bezierCurve : false, showTooltips : true };
						var myLine = new Chart(document.getElementById("canvas").getContext("2d")).Line(lineChartData, opts);
					</script>-->
					<script type="text/javascript">
						$("#start_date").datepicker();
						$("#end_date").datepicker();
						/**$('#zoom100').click(function(){
						    $('body').css('zoom', '100%');
						});
						$('#zoom150').click(function(){
						    $('body').css('zoom', '150%');
						});
						$('#zoom200').click(function(){
						    $('body').css('zoom', '200%');
						});**/
					</script>
			</div>
			
			<div class="keywordrankings">
				<div class="keywordrankings-title">KEYWORD RANKINGS</div>
			</div>
		<div id="horiz_container_outer">
		<div id="horiz_container">	
			<!-- start individual keyword list -->
			<div class="keywordtopbar">
				<div class="keywordtopbar-keyword">KEYWORD</div>
				<div class="keywordtopbar-position">POSITION</div>
				<div class="keywordtopbar-estimatedtraffic">7-DAY</div>
				<div class="keywordtopbar-estimatedtraffic">28-DAY</div>
				<div class="keywordtopbar-group">GROUP</div>
				<div class="keywordtopbar-estimatedtraffic">ERT</div>
				<div class="keywordtopbar-competition">COMPETITION</div>
				<div class="keywordtopbar-pagemeta">PAGE META</div>
				<div class="keywordtopbar-kei">KEI</div>
				<div class="keywordtopbar-competingpages">COMP. PAGES</div>
				<div class="keywordtopbar-searchvol">SEARCH VOLUME</div>
				<div class="keywordtopbar-cpc">CPC</div>
			</div>
                        <?php foreach ($keywords_array as $key => $value):?>
                        <!-- start individual keyword list -->
			<div class="keywordmainback">
				<div class="rankbgblock">
					<input type="checkbox" name="#" value="#">
				</div>
				<div class="seerankings"></div>
				<div class="keyword-keyword"><?php echo $value['keyword']?></div>
				<div class="keyword-position">
					<div class="keyword-positionarrow">
						<img src="<?php echo base_url(); ?>assets/images/arrows/arrowup.png">
					</div>
					<div class="keyword-positiontext">11</div>
				</div>
				<div class="keyword-estimatedtraffic">12</div>
				<div class="keyword-estimatedtraffic">50</div>
				<div class="keyword-group">group 1</div>
				<div class="keyword-estimatedtraffic">238</div>
				<div class="keyword-competition"></div>
				<div class="keyword-pagemeta">
					<div class="keyword-pagemetawrap">
						<div class="keyword-pagemetatop">Installation List : Bugzilla : bugzilla.org Installation List : Bugzilla : bugzilla.org</div>
						<a href="#" class="keyword-pagemetabottom">/installation-list/installation-list/installation-list/installation-list</a>
					</div>	
				</div>
				<div class="keyword-kei">2,354</div>
				<div class="keyword-competingpages">300,543</div>
				<div class="keyword-searchvol">834</div>
				<div class="keyword-cpc">€2.64</div>
			</div>
			<!-- end individual keyword list -->
<?php endforeach?>

		</div><!-- end horiz_container -->	
		</div><!-- end horiz_container_outer -->
		<div class="keywordlistbottom">
			<div class="compareselected"></div>
			<form id="quicksearch">
				<input type="text" name="quicksearch" placeholder="filter by keyword...">
			</form>
			<div class="keywordsetfilters"></div>
		</div>
		<div id="scrollbar">
    		<div id="track">
         		<div id="dragBar"></div>
    		</div>
		</div>
		</div>
	</div>