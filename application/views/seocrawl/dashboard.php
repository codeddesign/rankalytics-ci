<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url(); ?>assets/css/jquery-ui.css"/>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url(); ?>assets/css/radiobuttons.css"/>
<!-- start horizontal scroller css and js -->
<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url(); ?>assets/js/jquery.horizontal.scroll.css"/>
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
<div class="topinfobar">
    <a href="#" id="weather" <a href="#" onclick="toggle_visibility('weatherpopup');">
        <div class="weathericon">
            <img src="<?php echo base_url(); ?>assets/images/weather/sun.png">
        </div>
        <div class="weathertext">Google Weather</div>
    </a>
    
    <!-- seo weather popup design -->
    <div id="weatherpopup" class="link_toggle">
        <div class="weatherpopup-top"></div>
        <div class="weatherpopup-bg">

            <ul class="nav five-day" style=" margin-left: 32px;">
                <?php
                $temps_array = array();
                $count       = 1;
                foreach ($google_temps as $value) {
                    $temps_array[] = $value;
                    if ($count >= 5) {
                        break;
                    }
                    $count ++;
                }
                krsort( $temps_array );
                ?>

                <?php foreach ($temps_array as $value): ?>
                    <li class="row"
                        style="list-style: none outside none;  text-align: center; float: left;color: rgb(86, 179, 217);   font: bolder 12px;  width: 100px;">
                        <div class="span2 icon">
                            <?php if ($value['temperature'] <= 59) {
                            echo '<img src="/assets/images/sunny.png" >';

	                        } else if ($value['temperature'] > 59 AND $value['temperature'] <= 69) {
	                            echo '<img src="/assets/images/sunny_cloudy.png">';
	
	                        } elseif ($value['temperature'] > 69 AND $value['temperature'] <= 78) {
	                            echo '<img src="/assets/images/cloudy.png">';
	
	                        } elseif ($value['temperature'] > 78) {
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
    <!-- end seo weather design -->

    <div class="toptitlebar">seo crawl</div>
</div>

<div class="settingsbluetop">
    <div class="subscription-accountlevel" style="margin-left:69px;">Level: <?= strtoupper($sub_info['plan']); ?></div>
    <div class="subscription-keywordlimit">Crawl limit: <span><?= $sub_info['crawl_limit']; ?></span></div>
    <div class="subscription-keywordsused">Projects created: <span id="total_projects"><?= $campaigns_no; ?></span></div>
    <?php if($sub_info['plan'] !== 'free'): ?>
    <div class="subscription-billingrenewal" style="width:223px;">
        <?php
        if (!$sub_info['expired']) {
            echo 'Valid until: <span>' . date('d/m/Y', $sub_info['expires_on']);
        } else {
            echo 'Expired';
        }
        ?>
    </div>
    <?php endif; ?>
</div>

<div class="projectbackground">
    <div class="leftsidebar" style="width:52px;">
        <a href="/seocrawl/dashboard">
            <div class="leftsidebarbutton-one">
                <div class="ranksicons"></div>
            </div>
            <div class="leftsidebarout-one">SEO CRAWL</div>
        </a>
    </div>
    <div class="dashcontent">
        <div class="newcrawl-bg">
            <div class="newcrawl-textwrap">
                <div class="newcrawl-texttitle">Setup a new crawl campaign</div>
                <div class="newcrawl-textsub">Create a new SEO crawl campaign is as easy as deciding what domain URL you would like to crawl.</div>
            </div>
            <div class="createnewcampaign-button link_toggle" onclick="toggle_visibility('toggle');">Create New Campaign</div>
        </div>

        <div id="toggle" class="newcrawl-formwrap link_toggle" style="display:none">
            <form class="newcrawl-form" action="/seocrawl/savecampaign" id="seocrawl_form">
                <input class="newcrawl-input" name="campaignName" placeholder="Campaign Name">
                <input class="newcrawl-input" name="domainURL" style="width:456px;" placeholder="Enter your domain URL">
                <input class="newcrawl-input" name="depthLevel" placeholder="Depth Level (i.e. 20, max 999)">

                <div class="googleindexchecked">
                    <label style="width:192px;">Google Index Check?</label>
                    <input id="googleIndexCheckYes" class="css-checkbox subscription-plan" type="radio" checked="checked" title="Google Index Check" value="1" name="googleIndexCheck">
                    <label class="css-label" title="Google Index Check" for="googleIndexCheckYes">Yes</label>
                    <input id="googleIndexCheckNo" class="css-checkbox subscription-plan" type="radio" value="0" name="googleIndexCheck" title="No Google Check">
                    <label class="css-label" title="No Google Check" for="googleIndexCheckNo">No</label>
                </div>

                <div class="googleindexchecked">
                    <label style="width:192px;">Ping Non-Indexed URL's?</label>
                    <input id="pingNonIndexedPro" class="css-checkbox subscription-plan" type="radio" checked="checked" title="Ping Urls" value="1" name="pingNonIndexed">
                    <label class="css-label" title="Ping URLs" for="pingNonIndexedPro">Yes</label>
                    <input id="pingNonIndexedNo" class="css-checkbox subscription-plan" type="radio" value="0" name="pingNonIndexed" title="No Ping URLs">
                    <label class="css-label" title="No Ping URLs" for="pingNonIndexedNo">No</label>

                    <div id="form-msgs4" class="form-errors" style="display: none;"></div>
                    <button class="createnewcampaign-button">Submit New Campaign</button>
                    <div id="global-project-loading" align="left" class="save-loading" style="float: left;margin-top:15px">
                        <div class="spinner" style="background-position: -47px 0px;"></div>
                    </div>
                </div>
            </form>
        </div>

        <div class="seocrawl-pagetitle">SEO CRAWL</div>

        <!-- tab area -->
        <div class="seocrawl-tabswrap">
            <ul id="tabs">
                <li><a href="#crawllist">Current Crawls</a></li>
                <li><a href="#featurerequests">Feature Requests</a></li>
            </ul>

            <div class="tabContent" id="crawllist">
                <div class="seocrawl-whitearea">
                    <div class="seocrawl-infoicon"></div>
                    <div class="seocrawl-infotext">SEO crawls may take up to 4 days to complete depending on the size of the website you are crawling.</div>
                </div>
                <ul class="listofcrawl">
                    <?php
                    if (is_array($campaigns)) {
                        foreach ($campaigns as $c_no => $c_info) {
                            $extras = array('c_info' => $c_info);

                            if ($c_info['completed'] == 0) {
                                $this->load->view('seocrawl/campaign_progress', $extras);
                            } else {
                                $this->load->view('seocrawl/campaign_completed', $extras);
                            }
                        }
                    }
                    ?>
                </ul>


                <div class="keywordlistbottom">
                    <!--<div class="compareselected"></div>-->
                    <form id="quicksearch" method="post" action="#" onsubmit="return false;">
                        <input type="text" id="quicksearchI" placeholder="filter by keyword…" value="" style="width:303px;">
                    </form>
                    <!--div class="keywordsetfilters"></div-->
                    <div id="paging"></div>
                </div>
            </div>

            <div class="tabContent" id="featurerequests">
                <div class="seocrawl-whitearea">
                    <div class="seocrawl-infoicon"></div>
                    <div class="seocrawl-infotext">Please feel free to send your suggestions so that we can improve this module</div>
                </div>
                <form class="newcrawl-form" action="/seocrawl/sendrequest" method="POST" id="seocrawl_request">
                    <input type="text" placeholder="Message subject" class="newcrawl-input" name="msg_subject" id="msg_subject">

                    <div style="clear:both;"></div>
                    <textarea class="newcrawl-input" name="msg_content" id="msg_content" placeholder="Your  message here .." style="width: 500px; height: 90px;max-width: 500px;"></textarea>

                    <div style="clear:both;"></div>
                    <button class="createnewcampaign-button">Send Request</button>
                    <div id="send-request-loading" align="left" class="save-loading" style="float: left;margin-top:15px">
                        <div class="spinner" style="background-position: -47px 0px;"></div>
                    </div>
                </form>
                <div id="form-msgs5" class="form-errors" style="clear:both;display: none;"></div>
                <div class="seocrawl-requestwrap"></div>
            </div>
        </div>
        <!-- end seocrawl-tabswrap -->
        <!-- end tab area -->
    </div>
</div>
<?php $this->load->view("dashboard/common/footer") ?>

<script type="text/javascript">
    $('#quicksearchI').on('keyup', function () {
        var search = $(this).val().toLowerCase(),
            campaigns_rows = $('li[data-ctitle]'),
            temp_name, temp_selector;

        if (search.length == 0) {
            campaigns_rows.show();
            return false;
        }

        for (var i = 0; i < campaigns_rows.length; i++) {
            temp_selector = $(campaigns_rows[i]);
            temp_name = temp_selector.attr('data-ctitle');
            if (temp_name.indexOf(search) == -1) {
                temp_selector.hide();
            } else {
                temp_selector.show();
            }
        }

        return true;
    });

    $("#seocrawl_form").on('submit', function (e) {
        e.preventDefault();

        var theForm = $(this), formAction = theForm.attr('action'),
            loading = $('#global-project-loading'),
            error = $('#form-msgs4'),
            total_prjs = $('#total_projects'),
            list_container = $('ul.listofcrawl');

        loading.show();
        error.hide();

        $.ajax({
            type: 'POST',
            url: formAction,
            dataType: 'json',
            data: theForm.serialize(),
            success: function (response) {
                if (parseInt(response.error) == 0) {
                    toggle_visibility('toggle'); // hide form
                    theForm[0].reset(); // reset form
                    total_prjs.html(response.current_total); //change number of projects

                    //add the new one to list:
                    $.ajax({
                        type: 'GET',
                        dataType: 'json',
                        url: '/seocrawl/viewcampaign/?id=' + response.saved_as,
                        success: function (response2) {
                            if (parseInt(response2.error) == 0) {
                                $(response2.html).prependTo(list_container);
                            } else {
                                // do nothing ..
                            }
                        }
                    });
                } else {
                    //failed:
                    error.html(response.msg).show();
                }

                loading.hide();
            }
        });
    });

    $("#seocrawl_request").on('submit', function (e) {
        e.preventDefault();

        var theForm = $(this), formAction = theForm.attr('action'),
            loading = $('#send-request-loading'),
            error = $('#form-msgs5');

        loading.show();
        error.hide();

        $.ajax({
            type: 'POST',
            url: formAction,
            dataType: 'json',
            data: theForm.serialize(),
            success: function (response) {
                if(response.error) {
                    error.html(response.msg).show();
                    return false;
                }

                theForm.remove();
                error.html(response.msg).show();
            },
            complete: function () {
                loading.hide();
            }
        });
    });
</script>

<!-- TAB AREA JS -->
<script type="text/javascript">
    //<![CDATA[

    var tabLinks = new Array();
    var contentDivs = new Array();

    function init() {

        // Grab the tab links and content divs from the page
        var tabListItems = document.getElementById('tabs').childNodes;
        for (var i = 0; i < tabListItems.length; i++) {
            if (tabListItems[i].nodeName == "LI") {
                var tabLink = getFirstChildWithTagName(tabListItems[i], 'A');
                var id = getHash(tabLink.getAttribute('href'));
                tabLinks[id] = tabLink;
                contentDivs[id] = document.getElementById(id);
            }
        }

        // Assign onclick events to the tab links, and
        // highlight the first tab
        var i = 0;

        for (var id in tabLinks) {
            tabLinks[id].onclick = showTab;
            tabLinks[id].onfocus = function () {
                this.blur()
            };
            if (i == 0) tabLinks[id].className = 'selected';
            i++;
        }

        // Hide all content divs except the first
        var i = 0;

        for (var id in contentDivs) {
            if (i != 0) contentDivs[id].className = 'tabContent hide';
            i++;
        }
    }

    function showTab() {
        var selectedId = getHash(this.getAttribute('href'));

        // Highlight the selected tab, and dim all others.
        // Also show the selected content div, and hide all others.
        for (var id in contentDivs) {
            if (id == selectedId) {
                tabLinks[id].className = 'selected';
                contentDivs[id].className = 'tabContent';
            } else {
                tabLinks[id].className = '';
                contentDivs[id].className = 'tabContent hide';
            }
        }

        // Stop the browser following the link
        return false;
    }

    function getFirstChildWithTagName(element, tagName) {
        for (var i = 0; i < element.childNodes.length; i++) {
            if (element.childNodes[i].nodeName == tagName) return element.childNodes[i];
        }
    }

    function getHash(url) {
        var hashPos = url.lastIndexOf('#');
        return url.substring(hashPos + 1);
    }

    //]]>
</script>
<!-- END TAB AREA JS -->