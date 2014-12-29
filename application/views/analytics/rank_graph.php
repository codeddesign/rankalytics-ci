<?php
error_reporting(E_ERROR | E_PARSE);
?>
    <!-- start horizontal scroller css and js -->
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url(); ?>assets/js/jquery.horizontal.scroll.css"/>
    <script src="<?php echo base_url(); ?>assets/js/jquery.horizontal.scroll.js" type="text/javascript"></script>
    <!-- end scroller -->
    <div class="yellowtopline"></div>
    <div class="topinfobar">
        <a href="#" onclick="toggle_visibility('weatherpopup');">
            <div class="weathericon">

                <img src="<?php echo base_url(); ?>assets/images/weather/sun.png">
            </div>
            <div class="weathertext"><?= lang('rankgraph.weather');?></div>
        </a>

        <!-- toggle seo weather -->
        <script type="text/javascript">
            function toggle_visibility(id) {
                var e = document.getElementById(id);
                if (e.style.display == 'block')
                    e.style.display = 'none';
                else
                    e.style.display = 'block';
            }
            $(document).ready(function () {
                $('#horiz_container_outer').horizontalScroll();

            });
        </script>
        <!-- end toggle -->

        <?php
        $temp = "";
        $date = "";
        $google_temps_data = "";
        foreach ($google_temps as $temps) {
            $temp = $temp . $temps['temperature'] . ",";
            $date = $date . "'" . $temps['date'] . "'" . ",";
        }
        $temp = rtrim($temp, ",");
        $date = rtrim($date, ",");
        $google_temps_data .= "var graphData = { temps: [" . $temp . "],dates: [" . $date . "]};";
        ?>


        <!-- seo weather popup design -->
        <div id="weatherpopup">
            <div class="weatherpopup-top"></div>
            <div class="weatherpopup-bg">

                <ul class="nav five-day" style=" margin-left: 32px;">
                    <?php
                    $temps_array = array();
                    $count = 1;
                    foreach ($google_temps as $value) {
                        $temps_array[] = $value;
                        if ($count >= 5) {
                            break;
                        }
                        $count++;
                    }
                    krsort($temps_array);
                    ?>

                    <?php foreach ($temps_array as $value): ?>
                        <li class="row" style="list-style: none outside none;  text-align: center; float: left;color: rgb(86, 179, 217);   font: bolder 12px;  width: 100px;">
                            <div class="span2 icon">
                                <?php if ($value['temperature'] <= 15) {
                                    echo '<img src="/assets/images/sunny.png" >';

                                } elseif ($value['temperature'] > 15 AND $value['temperature'] <= 21) {
                                    echo '<img src="/assets/images/sunny_cloudy.png">';

                                } elseif ($value['temperature'] > 21 AND $value['temperature'] <= 26) {
                                    echo '<img src="/assets/images/cloudy.png">';

                                } elseif ($value['temperature'] > 26) {
                                    echo '<img src="/assets/images/thunder.png">';

                                }; ?>
                            </div>
                            <div class="span2 temp" style="font-size:30px;padding-left: 10px;">
                                <?php echo $value['temperature'] ?> °C
                            </div>

                            <div class="span2 date" style="margin-left: 5px;   padding-top: 10px;">
                                <?php echo $value['date'] ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <br/>

                <div style="clear: both;"></div>


                <script>
                    <?php echo $google_temps_data?>
                    graphData.temps.reverse();
                    graphData.dates.reverse();
                </script>
                <div class="chart chart-thirtyday"></div>
                <script src="<?php echo base_url(); ?>assets/js/weather_graph/highcharts.js"></script>
                <script src="<?php echo base_url(); ?>assets/js/weather_graph/graph.js"></script>
            </div>
            <!-- end seo weather design -->
        </div>
        <div class="toptitlebar"><?= lang('rankgraph.keyrank');?> <?php echo isset($domain_url['domain_url']) ? $domain_url['domain_url'] : 'Domain.com'; ?></div>
    </div>
    <div class="projectbackground">
        <div class="leftsidebar">
            <a href="/ranktracker/keywordsuggestions">
                <div class="leftsidebarbutton-one">
                    <div class="ranksicons"></div>
                </div>
                <div class="leftsidebarout-one"><?= lang('rankgraph.keysug');?></div>
            </a>
            <a href="/ranktracker/competitoranalysis">
                <div class="leftsidebarbutton-two">
                    <div class="reportsicons"></div>
                </div>
                <div class="leftsidebarout-two"><?= lang('rankgraph.companal');?></div>
            </a>
        </div>
        <div class="dashcontent">
            <div class="keywordrankgraph">
                <!--
                <div class="keywordrankgraph-title">SEO GRAPH</div>
                <div class="keywordrankgraph-filters">
                    <input type="text" id="start_date" name="start_date" value="Start Date" />-<input type="text" name="end_date" id="end_date" value="End Date" />
                    <input type="submit" value="GO" /><!-- &nbsp;&nbsp;Zoom: <button id="zoom100">100%</button> <button id="zoom150">150%</button> <button id="zoom200">200%</button>
                </div>
                -->
            </div>
            <div class="rankingschartarea">
                <!--div id="chart" style="width: 1050px; height: 450px;"></div-->
                <?php $this->load->view('analytics/graph/index2', $csv) ?>
            </div>
            <?php
            $data["keywords_array"] = $keywords_array;
            $data["quicksearch"] = $quicksearch;
            $data["username"] = $username;
            $data["project_name_raw"] = $project_name_raw;
            ?>
            <link href="<?php echo base_url(); ?>assets/pagination.css" rel="stylesheet" type="text/css"/>
            <style>
                #scrollbar {
                    margin-top: 35px;
                }

                #rankdata {
                    position: relative;
                }

                .overlay {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    z-index: 10;
                    background-color: rgba(0, 0, 0, 0.5); /*dim the background*/
                    display: none;

                }

                .overlay img {
                    left: 50%;
                    position: relative;
                    top: 50%;
                }
            </style>
            <div class="keywordrankings">
                <div class="keywordrankings-title"><?= lang('rankgraph.keyrank');?> <?php echo isset($domain_url['domain_url']) ? $domain_url['domain_url'] : 'Domain.com'; ?></div>
            </div>

            <div id="rank_data_outer" style="position:relative;float:left; ">
                <div class="overlay">
                    <div id="spinner"></div>
                    <!--img src="<?php echo base_url(); ?>assets/images/loading.gif" style="margin:10px auto"--></div>
                <div id="horiz_container_outer">
                    <div id="horiz_container">
                        <!-- start individual keyword list -->
                        <div class="keywordtopbar">
                            <div class="keywordtopbar-keyword"><?= lang('rankgraph.keyword');?></div>
                            <div class="keywordtopbar-position"><?= lang('rankgraph.position');?></div>
                            <div class="keywordtopbar-estimatedtraffic"><?= lang('rankgraph.sevenday');?></div>
                            <div class="keywordtopbar-estimatedtraffic"><?= lang('rankgraph.twoeightday');?></div>
                            <div class="keywordtopbar-estimatedtraffic"><?= lang('rankgraph.com');?></div>
                            <div class="keywordtopbar-estimatedtraffic"><?= lang('rankgraph.local');?></div>
                            <div class="keywordtopbar-estimatedtraffic"><?= lang('rankgraph.news');?></div>
                            <div class="keywordtopbar-estimatedtraffic"><?= lang('rankgraph.video');?></div>
                            <div class="keywordtopbar-estimatedtraffic"><?= lang('rankgraph.shopping');?></div>
                            <!--div class="keywordtopbar-group">GROUP</div-->
                            <div class="keywordtopbar-estimatedtraffic">ERT</div>
                            <div class="keywordtopbar-competition"><?= lang('rankgraph.competition');?></div>
                            <div class="keywordtopbar-pagemeta"><?= lang('rankgraph.pagemeta');?></div>
                            <div class="keywordtopbar-kei">KEI</div>
                            <div class="keywordtopbar-competingpages">COMP. PAGES</div>
                            <div class="keywordtopbar-searchvol"><?= lang('rankgraph.searchvolume');?></div>
                            <div class="keywordtopbar-cpc">CPC</div>
                        </div>
                        <div id="rankData">
                            <?php
                            $this->load->view("analytics/rank_data", $data);
                            ?>
                        </div>
                    </div>
                    <!-- end horiz_container -->
                    <div id="scrollbar">
                        <div id="track">
                            <div id="dragBar"></div>
                        </div>
                    </div>

                </div>
                <!-- end horiz_container_outer -->
                <div class="keywordlistbottom">
                    <!--<div class="compareselected"></div>-->
                    <form id="quicksearch" method="post" action="<?php echo base_url() ?>ranktracker/rankings/<?php echo $username . "/" . $project_name_raw ?>">
                        <input type="text" name="quicksearch" placeholder="filter by keyword..." value="<?php echo isset($quicksearch) ? $quicksearch : ''; ?>">
                        <input type="hidden" name="isAjax" id="isAjax" value="0"/>
                    </form>
                    <!--div class="keywordsetfilters"></div-->
                    <div id="paging"><?php //echo $this->pagination->create_links(); ?></div>
                </div>
            </div>
            <script>
                $(document).ready(function () {
                    quicksearchActionOriginal = $("#quicksearch").attr("action");
                    $(".pagination a").click(function () {
                        $("#quicksearch").attr("action", $(this).attr("href"));
                        $("#quicksearch").submit();
                        return false;
                    });

                    $('#quicksearch').submit(function (e) {
                        e.preventDefault();

                        $("#isAjax").val(1);
//                            $("#form-loading").show();
                        $("#rank_data_outer .overlay").show();
                        $.post($(this).attr('action'), $(this).serialize(), function (data) {
                            //$("#form-loading").hide();
                            $("#rank_data_outer .overlay").hide();
                            if (parseInt(data.error)) {
                                //alert("in error")
                            }
                            else {
                                $("#quicksearch").attr("action", quicksearchActionOriginal);
                                $("#rankData").html(data.html);
                                $("#paging").html(data.pagination);
                                $(".pagination a").click(function () {
                                    $("#quicksearch").attr("action", $(this).attr("href"));
                                    $("#quicksearch").submit();
                                    return false;
                                });
                            }

                        }, 'json');
                        return false;
                    });
                    $("#quicksearch").submit();
                    /*$.ajax({
                     url:'
                    <?php echo base_url();?>ranktracker/updateWeatherImage',
                     type:'POST',
                     data:{},
                     success:function(response){
                     //alert(response);
                     response = JSON.parse(response);
                     //alert(response);
                     $("#weatherpopup").html(response.html);
                     }
                     });*/
                });
                function delete_keyword(keyword_id, project_id, keywordstr) {
                    if (confirm("<?= lang('rankgraph.sure');?> '" + keywordstr + "'")) {
                        $("#rank_data_outer .overlay").show();
                        $.ajax({
                            url: '<?php echo base_url();?>project/delete_project_keyword',
                            type: 'POST',
                            data: {keyword_id: keyword_id, project_id: project_id},
                            success: function (response) {
                                //           response = JSON.parse(response);
                                $("#rank_data_outer .overlay").hide();
                                //alert(response)
                                if (response == '1') {
                                    $("#" + keyword_id + "-" + project_id).hide();
                                }
                            }
                        });
                    }
                }
                <?php /*if(isset($js_function)){ ?>
                     function updateWeatherImage(){

                     }
                     updateWeatherImage();
                <?php } */?>
            </script>

        </div>
    </div>
<?php $this->load->view("dashboard/common/footer") ?>