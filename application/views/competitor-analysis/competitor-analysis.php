<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url(); ?>assets/css/jquery-ui.css"/>
<!-- start horizontal scroller css and js -->
<link rel="stylesheet" type="text/css" media="all"
      href="<?php echo base_url(); ?>assets/js/jquery.horizontal.scroll.css"/>
<script src="<?php echo base_url(); ?>assets/js/jquery.horizontal.scroll.js" type="text/javascript"></script>
<!-- end scroller -->
<style>
    .ui-autocomplete-loading {
        background: url("<?php echo base_url(); ?>assets/css/images/ui-anim_basic_16x16.gif") no-repeat scroll right center #FFFFFF;
    }
</style>

<?php
$temp = "";
$date = "";
$google_temps_data = "";
$google_temps = $this->analytical->getGoogleTemperature();
foreach ($google_temps as $temps) {
    $temp = $temp . $temps['temperature'] . ",";
    $date = $date . "'" . $temps['date'] . "'" . ",";
}
$temp = rtrim($temp, ",");
$date = rtrim($date, ",");
$google_temps_data .= "var graphData = { temps: [" . $temp . "],dates: [" . $date . "]};";
?>



<div class="overlay">
    <div id="spinner"></div>
</div>
<div class="yellowtopline"></div>

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
                <li class="row"
                    style="list-style: none outside none;  text-align: center; float: left;color: rgb(86, 179, 217);   font: bolder 12px;  width: 100px;">
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

</div>


<div class="topinfobar">
    <a href="#" onclick="toggle_visibility('weatherpopup');">
        <div class="weathericon">
            <img src="<?php echo base_url(); ?>assets/images/weather/sun.png"/>
        </div>
    </a>

    <div class="weathertext">Google Weather</div>

    <div class="toptitlebar">COMPETITOR ANALYSIS</div>
</div>

<div class="projectbackground">
    <div class="leftsidebar" style="width:52px;">
        <a href="/ranktracker/keywordsuggestions">
            <div class="leftsidebarbutton-one">
                <div class="ranksicons"></div>
            </div>
            <div class="leftsidebarout-one">KEYWORD SUGGESTIONS</div>
        </a>
        <a href="/ranktracker/competitoranalysis">
            <div class="leftsidebarbutton-two">
                <div class="reportsicons"></div>
            </div>
            <div class="leftsidebarout-two" style="height:50px;margin-top:50px;">COMPETITOR ANALYSIS</div>
        </a>
    </div>
    <div class="dashcontent">
        <form class="keywordresearch-form" action="" id="search_form" onsubmit="return ValidateForm();" method="post">
            <div class="keywordresearch-titletop" style="width:100%;">First, enter a starting keyword</div>
            <select name="drop_keywordresearch" class="keywordresearch-keyword"
                    style="padding:12px 13px 10px 12px;width:393px;" id="drop_keyword">
                <option value="0">[Select]</option>
                <?php foreach ($keywords as $key => $value): ?>
                    <?php if (isset($drop_keywordresearch) && $drop_keywordresearch == $value['keyword']): ?>
                        <option value="<?php echo $value['keyword'] ?>"
                                selected="selected"><?php echo $value['keyword'] ?></option>
                    <?php else: ?>
                        <option value="<?php echo $value['keyword'] ?>"><?php echo $value['keyword'] ?></option>
                    <?php endif ?>
                <?php endforeach; ?>
            </select>

            <div class="keywordresearch-titletop" style="width:100%;">or enter one of your own keywords into the box below
            </div>
            <input class="keywordresearch-domain" type="text" name="txt_keyword" id="txt_keyword"
                   placeholder="begin typing in one of your current keywords..."
                   value="<?php echo @utf8_encode($txt_keyword); ?>">
            <input type="submit" value="" class="keywordresearch-button" style="clear:none;">
        </form>

        <?php if ((isset($drop_keywordresearch) && $drop_keywordresearch) || (isset($txt_keyword) && $txt_keyword)): ?>
            <div class="compres-wrap">
                <div class="keywordresearch-titlekeyword" style="margin-left:40px;">KEYWORD</div>
                <div class="keywordresearch-titlesimiliar">&nbsp;</div>
                <div class="keywordresearch-titlemonthly">SEARCHES PER MONTH</div>
                <div class="keywordresearch-titlecompetition">COMPETITION</div>
                <div class="keywordresearch-titlecpc">CPC</div>
            </div>
            <div class="compres-grayback">
                <div class="keywordresults-keyword" style="margin-left:31px;width:389px;"
                     id="main_keyword"><?php echo @utf8_encode($drop_keywordresearch) ?><?php echo @utf8_encode($txt_keyword) ?></div>
                <div class="keywordresults-similiar">&nbsp;</div>
                <div
                    class="keywordresults-monthly"><?php echo @($adword_array[0]['volume']) ? $adword_array[0]['volume'] : "&nbsp;" ?></div>
                <div
                    class="keywordresults-competition"><?php echo @($adword_array[0]['competition']) ? number_format($adword_array[0]['competition'], 4, '.', ' ') : "&nbsp;" ?></div>
                <div class="keywordresults-cpc">
                    €<?php echo @($adword_array[0]['CPC']) ? number_format($adword_array[0]['CPC'] / 1000000, 2, '.', '') : "&nbsp;" ?></div>
            </div>
        <?php endif; ?>

        <?php


        if (!empty($top_ten_array)):?>
            <div id="horiz_container_outer">
                <div id="horiz_container">
                    <!-- start individual keyword list -->
                    <div class="keywordtopbar">
                        <div class="keywordtopbar-keyword" style="width:361px;">KEYWORD</div>
                        <div class="keywordtopbar-position" style="width:106px;text-align:center;">PA</div>
                        <div class="keywordtopbar-estimatedtraffic">DA</div>
                        <div class="keywordtopbar-estimatedtraffic">SIMILARITY</div>
                        <div class="keywordtopbar-estimatedtraffic">PR</div>
                        <div class="keywordtopbar-estimatedtraffic">PBL</div>
                        <div class="keywordtopbar-estimatedtraffic">DBL</div>
                        <div class="keywordtopbar-estimatedtraffic">IC</div>
                        <div class="keywordtopbar-estimatedtraffic" style="width:130px;">AGE</div>
                        <div class="keywordtopbar-estimatedtraffic">TITLE</div>
                        <div class="keywordtopbar-estimatedtraffic">URL</div>
                        <div class="keywordtopbar-estimatedtraffic">HEADER</div>
                    </div>
                    <?php $count = count($top_ten_array);
                    $id_count = 0;
                    $siteurl_array = array();

                    ?>
                    <?php foreach ($top_ten_array as $key => $value):

                        if (!in_array($value['site_url'], $siteurl_array)):

                            //if($id_count > 9)
                            //break;

                            $siteurl_array[] = $value['site_url'];
                            ?>
                            <?php $id_count++ ?>
                            <div class="keywordmainback">
                                <div class="rankbgblock">
                                    <div class="compres-rankcount"><?php echo $id_count ?></div>
                                </div>
                                <div class="keyword-pagemeta" style="width:350px;">
                                    <div class="keyword-pagemetawrap" style="width:324px;">
                                        <div
                                            class="keyword-pagemetatop"><?php echo utf8_encode($value['title']) ?></div>
                                        <a href="<?php echo $value['site_url'] ?>"
                                           class="keyword-pagemetabottom site_urls"
                                           id="site_<?php echo $id_count ?>"><?php echo $value['site_url'] ?></a>
                                    </div>
                                </div>
                                <?php $index = $key + $count ?>
                                <div
                                    class="keyword-kei"><?php echo $majesticSEOData_array[$key]['CitationFlow'] ?></div>
                                <div
                                    class="keyword-competingpages"><?php echo $majesticSEOData_array[$key]['TrustFlow'] ?></div>
                                <div class="keyword-searchvol" id="site_<?php echo $id_count ?>_similar"><img
                                        src="<?php echo base_url(); ?>assets/images/ajax_loading.png"
                                        style="margin:10px auto"></div>
                                <div class="keyword-cpc"><?php echo ($value['page_rank'] == '') ? '-' : $value['page_rank']?></div>

                                <div
                                    class="keyword-cpc keyword-cpcdark"><?php echo number_format($majesticSEOData_array[$key]['ExtBackLinks']); ?></div>

                                <div
                                    class="keyword-cpc"><?php echo number_format($majesticSEOData_array[$index]['ExtBackLinks']) ?></div>
                                <?php //$count --?>
                                <!--
                                <div class="keyword-cpc keyword-cpcdark"><?php echo $keyword_array[0]['total_records']; ?></div>
                                !-->
                                <div class="keyword-cpc keyword-cpcdark" id="site_<?php echo $id_count ?>_ic">
                                    <img src="<?php echo base_url(); ?>assets/images/ajax_loading.png"
                                         style="margin:10px auto">
                                    <?php
                                    // echo ($majesticSEODomainArray[$key]['IndexedURLs'] < 0) ? $majesticSEODomainArray[$key]['IndexedURLs'] * -1 : $majesticSEODomainArray[$key]['IndexedURLs'];
                                    ?>
                                </div>

                                <?php
                                //   $first_crawled_date = $majesticSEODomainArray[$key]["FirstCrawled"];
                                // $first_crawled_date = date("Y-m-d", strtotime($first_crawled_date));
                                //$age = date("Y-m-d") - $first_crawled_date;
                                ?>

                                <div class="keyword-cpc" id="site_<?php echo $id_count ?>_age" style="width:130px;">
                                    <img src="<?php echo base_url(); ?>assets/images/ajax_loading.png"
                                         style="margin:10px auto">
                                    <?php // echo $age; ?>
                                </div>
                                <div class="keyword-cpc">
                                    <?php $keyword = @utf8_encode($txt_keyword) . @utf8_encode($drop_keywordresearch); ?>
                                    <?php if ($value['title'] && (stripos(utf8_encode($value['title']), $keyword) !== false)): ?>
                                        <div class="compplus"></div>
                                    <?php else: ?>
                                        <div class="compneg"></div>
                                    <?php endif; ?>
                                </div>
                                <div class="keyword-cpc">
                                    <?php if (isset($value['site_url']) && (stripos($value['site_url'], $keyword) !== false)): ?>
                                        <div class="compplus"></div>
                                    <?php else: ?>
                                        <div class="compneg"></div>
                                    <?php endif; ?>
                                </div>
                                <div class="keyword-cpc">
                                    <?php if ($value['header_tags'] == 'NIl' || is_null($value['header_tags']) == true): ?>
                                        <div class="compneg"></div>
                                    <?php else: ?>
                                        <div class="compplus"></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php
                        endif;
                    endforeach;?>
                    <!-- end individual keyword list -->
                </div>
                <!-- end horiz_container -->
            </div><!-- end horiz_container_outer -->




            <div class="keywordlistbottom" style="height:30px;"></div>
            <div id="scrollbar" style="margin-top:424px;">
                <div id="track">
                    <div id="dragBar"></div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $this->load->view("dashboard/common/footer") ?>
<script type="text/javascript">

    function ValidateForm() {
        if ($.trim($('#txt_keyword').val()) == "" && $.trim($('#drop_keyword').val()) == "0") {
            $('#txt_keyword').addClass('validationError');
            $('#drop_keyword').addClass('validationError');
            //$('#search_form').attr('action', "
            <?php echo base_url(); ?>ranktracker_nan/competitoranalysis/" + $('#txt_keyword').val());
            return false;
        }
        else if ($.trim($('#txt_keyword').val()) == "" && $.trim($('#drop_keyword').val()) != "0") {
            $('#txt_keyword').removeClass('validationError');
            $('#drop_keyword').removeClass('validationError');
            $('#search_form').attr('action', "<?php echo base_url(); ?>ranktracker/competitoranalysis?keyword=" + $('#drop_keyword :selected').text());
            return true;
        }
        else if ($.trim($('#txt_keyword').val()) != "" && $.trim($('#drop_keyword').val()) == "0") {
            $('#txt_keyword').removeClass('validationError');
            $('#drop_keyword').removeClass('validationError');
            $('#search_form').attr('action', "<?php echo base_url(); ?>ranktracker/competitoranalysis?keyword=" + $('#txt_keyword').val());
            return true;
        }
        else {
            $('#txt_keyword').removeClass('validationError');
            $('#drop_keyword').removeClass('validationError');
            $('#search_form').attr('action', "<?php echo base_url(); ?>ranktracker/competitoranalysis?keyword=" + $('#drop_keyword :selected').text());
            return true;
        }
    }


    $(document).ready(function () {

        $('#horiz_container_outer').horizontalScroll();
        <?php if(!empty($top_ten_array)):?>
        $(".site_urls").each(function () {
            var id = $(this).attr("id");
            $.ajax({
                url: "<?php echo base_url();?>python_find_simil/ignitor.php",
                type: "post",
                dataType: "json",
                data: ({keyword: $('#main_keyword').text(), website: $.trim($(this).text())}),
                success: function (data) {
                    $("#" + id + "_similar").html(data.similarity_score);
                }
            });
        });
        <?php endif;?>
        <?php
            if (!empty($top_ten_array))
            {
                ?>
        $(".site_urls").each(function () {
            var htmlString = $(this).html();
            var id2 = $(this).attr("id");
            var age_response = '';
            $.ajax({
                url: "<?php echo base_url();?>domain_age/index.php",
                type: "post",
                dataType: "json",
                data: ({domain: htmlString}),
                success: function (response) {
                    // alert (response.age);
                    if (response.age == false) age_response = 'N/A';
                    else age_response = response.age;
                    // alert (age_response);
                    $("#" + id2 + "_age").html(age_response);
                }
            });
            // alert (htmlString);
        });
        <?php
    }
?>
        <?php
            if (!empty($top_ten_array))
            {
                ?>
        $(".site_urls").each(function () {
            var htmlString2 = $(this).html();
            var id3 = $(this).attr("id");
            var ic_response = '';
            $.ajax({
                url: "<?php echo base_url();?>index_count/index.php",
                type: "post",
                dataType: "json",
                data: ({page: htmlString2}),
                success: function (response) {
                    // alert (response.ic_count);
                    if (response.ic_count == false) ic_response = 'N/A';
                    else ic_response = response.ic_count;
                    // alert (ic_response);
                    $("#" + id3 + "_ic").html(ic_response);
                }
            });
            // alert (htmlString2);
        });
        <?php
    }
?>

        $("#txt_keyword").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "<?php echo base_url(); ?>ranktracker/getKeywordsJson/" + $("#txt_keyword").val(),
                    dataType: "json",
                    type: "POST",
                    data: {
                        val: $("#txt_keyword").val()
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            select: function (event, ui) {
                //$('#auto_id').val(ui.item.id);
                $("#txt_keyword").val(ui.item.label)
                //$("form").submit();
            }
        });
    })

    function base64_encode(data) {
        var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
        var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
            ac = 0,
            enc = '',
            tmp_arr = [];

        if (!data) {
            return data;
        }

        do { // pack three octets into four hexets
            o1 = data.charCodeAt(i++);
            o2 = data.charCodeAt(i++);
            o3 = data.charCodeAt(i++);

            bits = o1 << 16 | o2 << 8 | o3;

            h1 = bits >> 18 & 0x3f;
            h2 = bits >> 12 & 0x3f;
            h3 = bits >> 6 & 0x3f;
            h4 = bits & 0x3f;

            // use hexets to index into b64, and append result to encoded string
            tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
        } while (i < data.length);

        enc = tmp_arr.join('');

        var r = data.length % 3;

        return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);
    }
</script>
