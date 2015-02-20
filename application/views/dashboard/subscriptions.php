<?php
$selected = 1; // left tab;
$status   = 'active';

// ..
if ($user_database['companyLogo'] != '') {
    $uploads = 'uploads/logos/thumbnails/' . $user_database['companyLogo'];
    if (file_exists( $uploads )) {
        $logo_img = '<img src="' . base_url() . $uploads . '">';
    } else {
        $logo_img = '';
    }
} else {
    $logo_img = '';
}

$this->load->view( "include/settingsheader" );

?>
    <link href="<?php echo base_url() ?>assets/css/radiobuttons.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url() ?>assets/css/setting.css" rel="stylesheet" type="text/css"/>
    <div class="yellowtopline"></div>
    <div class="topinfobar">
        <a href="#" id="weather" <a href="#" onclick="toggle_visibility('weatherpopup');">
            <div class="weathericon">
                <img src="<?php echo base_url(); ?>assets/images/weather/sun.png">
            </div>
            <div class="weathertext">Google Weather</div>
        </a>
        <?php
        $temp              = "";
        $date              = "";
        $google_temps_data = "";
        $google_temps      = $this->analytical->getGoogleTemperature();
        foreach ($google_temps as $temps) {
            $temp = $temp . $temps['temperature'] . ",";
            $date = $date . "'" . $temps['date'] . "'" . ",";
        }
        $temp = rtrim( $temp, "," );
        $date = rtrim( $date, "," );
        $google_temps_data .= "var graphData = { temps: [" . $temp . "],dates: [" . $date . "]};";
        ?>
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
        <!-- end seo weather design -->
        <div class="toptitlebar">Subscriptions</div>
    </div>
    <div class="projectbackground">
        <?php $this->load->view( "dashboard/common/big_left_sidebar", array( "selected" => $selected ) ); ?>
        <div class="twodashcontent">
            <?php echo $this->load->view( 'dashboard/common/settingsblue_top', array( "user" => $user_database ) ); ?>

            <div class="subscriptionwrap">

                <!-- BEGIN TABS -->
                <?php
                $services      = Subscriptions_Lib::$_service_prices;
                $serviceNames  = Subscriptions_Lib::$_service_names;
                $serviceLimits = Subscriptions_Lib::$_service_limits;
                $currencySymbol = Subscriptions_Lib::$_currency_symbol
                ?>
                <ul id="tabs">
                    <?php
                    foreach ($services as $serviceName => $service) {
                        ?>
                        <li><a href="#<?= $serviceName; ?>tab"><?= $serviceNames[$serviceName] ?></a></li>
                    <?php } ?>
                </ul>

                <?php
                foreach ($services as $serviceName => $plans) {
                    $tempVar = $$serviceName;
                    ?>
                    <div class="tabContent" id="<?= $serviceName ?>tab">
                        <div class="promembership-wrap">
                            <div class="settings-whitearea">
                                <div class="<?= ( ! $tempVar['expired'] ) ? 'activesubscription' : 'nonactivesubscription'; ?>"></div>
                                <div class="<?= ( ! $tempVar['expired'] ) ? 'activesubscription-text' : 'nonactivesubscription-text'; ?>">
                                    ACCOUNT LEVEL: <b><?= strtoupper( $tempVar['plan'] ); ?></b> <?= ( $tempVar['pending'] ) ? '(until payment is confirmed)' : ''; ?><br/>
                                    NUMBER OF KEYWORDS: <b><?= $tempVar['usage_number']; ?></b>
                                    <?php if ($tempVar['expired']) : ?>
                                        <br/>Your subscription to <?= ucfirst( $serviceName ); ?> expired/canceled.
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="promembership-text"><?= strtoupper( $serviceNames[$serviceName] ) . ' subscription.' ?></div>
                            <div class="promembership-line"></div>
                            <div class="keywordsenough">Are you ready to change your plan?</div>
                            <div class="keywordsenough-small">
                                We have made it incredibly simple to do so. Just select your new plan on the right, choose your payment method, and then follow the payment instructions there after.
                            </div>
                            <div class="profile-keywordsenoughbottom" style="margin-top:24px;,margin-bottom:10px;">
                                Not sure what you get with each plan? No problem! You can view them <a href="http://rankalytics.com/<?= $serviceName; ?>" target="_blank" style="color:#6CC797;">Here</a>
                            </div>
                            <div class="profile-keywordsenoughbottomsmall">
                                *All paid plans will recur on a monthly basis. All Invoices are auto-generated, emailed to you, and downloadable from your 'Invoices' page.
                            </div>
                        </div>

                        <div class="promembership-formwrap">
                            <div id="form-msgs4-<?= $serviceName; ?>" class="form-errors"></div>
                            <div id="info-body-<?= $serviceName; ?>" class="form-extra-info"></div>

                            <!-- #for 2 plans - RANK TRACKER -->
                            <form action="/users/subscription" method="POST" class="subscription-form">
                                <div class="pricingcheckbox">
                                    <label>Subscription plan</label>
                                    <?php
                                    foreach ($plans as $planName => $amount) {
                                        $title = $serviceLimits[$serviceName][$planName]['text'] . ' Keywords';

                                        ?>
                                        <input type="radio" name="accountType" id="accountType<?= ucfirst( $serviceName ) . ucfirst( $planName ); ?>" value="<?= $planName ?>" data-amount='<?= $amount; ?>' title="<?= $title; ?>" class="css-checkbox subscription-plan"/>
                                        <label for="accountType<?= ucfirst( $serviceName ) . ucfirst( $planName ); ?>" title="<?= $title; ?>" class="css-label"><?= ucfirst( $planName ) ?> Plan
                                            (<?= $currencySymbol . $amount ?>)</label>
                                    <?php } ?>
                                </div>

                                <div class="pricingcheckbox paid<?= ucfirst( $serviceName ) ?>">
                                    <label>Payment method</label>
                                    <input type="radio" name="paymentType" id="paymentType<?= ucfirst( $serviceName ); ?>Paypal" value="paypal" title="PayPal Payment" class="css-checkbox payment-type paypal-cbx"/>
                                    <label for="paymentType<?= ucfirst( $serviceName ); ?>Paypal" title="PayPal Payment" class="css-label paypal-lbl">PayPal</label>
                                    <input type="radio" title="Stripe Payment" name="paymentType" value="Stripe" id="paymentType<?= ucfirst( $serviceName ); ?>Stripe" class="css-checkbox payment-type"/>
                                    <label for="paymentType<?= ucfirst( $serviceName ); ?>Stripe" title="Stripe Payment" class="css-label">Credit Card / Stripe</label>
                                </div>

                                <div class="profilesave right-sided">
                                    <div align="left" style="float:left ;margin-right: 42px;" id="billingInfo-loading" class="save-loading">
                                        <div class="spinner"></div>
                                    </div>
                                    <!--img src="<?php echo base_url() ?>assets/images/loading.gif" id="billingInfo-loading" align="left" class="save-loading"-->
                                    <input type="hidden" value="<?= $serviceName; ?>" name="service">
                                    <input type="submit" value="" id="submitBilling<?= ucfirst( $serviceName ); ?>" style="margin-top:12px;">
                                </div>
                            </form>
                        </div>

                    </div>
                    <!-- end last tab div -->
                <?php
                }
                ?>
            </div>
            <!-- END TABS -->
        </div>
        <!--class="subscriptionwrap" -->
    </div>
    <script>
        $(document).ready(function () {
            /* select subscription plans on load: */
            (function () {
                var current = <?= $current_options ?>, temp, forPayment = ['paidRanktracker', 'paidSeocrawl'], i;

                // first hide:
                for (i = 0; i < forPayment.length; i++) {
                    $('.' + forPayment[i]).hide();
                }

                for (i = 0; i < current.length; i++) {
                    temp = current[i];
                    $('#accountType' + temp['service'] + temp['plan']).attr('checked', 'checked');
                    $('#paymentType' + temp['service'] + temp['pType']).attr('checked', 'checked');

                    if (temp['isPaid']) {
                        $('.paid' + temp['service']).show();
                    }
                }
            })();

            $('.subscription-form').on('submit', function (e) {
                e.preventDefault();
                var theForm = $(this), data = theForm.serialize(), arr = [], info_msg, info_body;

                // accessiable values:
                $.each(theForm.serializeArray(), function (i, f) {
                    arr[f.name] = f.value;
                });

                // hide info msg:
                info_msg = $('#form-msgs4-' + arr.service);
                info_msg.hide().html('');

                info_body = $('#info-body-' + arr.service);
                info_body.hide().html('');

                $.ajax({
                    data: data,
                    method: 'POST',
                    dataType: 'json',
                    url: theForm.attr('action'),
                    success: function (response) {
                        var infoTxt = 'Unknown action';
                        if (typeof response.msg !== 'undefined') {
                            info_msg.html(response.msg).show();
                        }

                        if (response.error) {
                            return false;
                        }

                        if (typeof response.what !== 'undefined') {
                            switch (response.what) {
                                case 'downgrade':
                                    infoTxt = 'Your downgrade request has been saved!<br/>A member of our staff will get in touch with you shortly.<br/>Meanwhile the services will remain the same';
                                    break;
                                case 'paypal':
                                    infoTxt = 'Please wait.. You are being redirected to paypal';
                                    break;
                                case 'stripe':
                                    infoTxt = 'Loading stripe gateway.. Please wait..';
                                    break;
                                default:
                                    // ..
                                    break;
                            }

                            theForm.remove();
                            info_msg.html(infoTxt).show();

                            if (typeof response.body !== undefined) {
                                info_body.html(response.body).show();
                                return false;
                            }
                        }

                        if (typeof response.redirect_to !== 'undefined') {
                            location.href = response.redirect_to;
                        }
                    }
                });
            });

            $('.subscription-plan').on('click', function () {
                var i, j, tempSelector, tempAmount, whichPayed = [], tempService,
                    subPlans = $('.subscription-plan'), forPayment = ['paidRanktracker', 'paidSeocrawl'];

                for (i = 0; i < subPlans.length; i++) {
                    tempSelector = $(subPlans[i]);
                    tempAmount = tempSelector.attr('data-amount');
                    tempService = (tempSelector.attr('id').indexOf('Ranktracker') === -1) ? 'Seocrawl' : 'Ranktracker';

                    if (tempAmount != undefined && parseInt(tempAmount) > 0 && tempSelector.is(':checked')) {
                        whichPayed[whichPayed.length] = 'paid' + tempService;
                    }
                }

                for (i = 0; i < forPayment.length; i++) {
                    tempSelector = $('.' + forPayment[i]);
                    tempSelector.hide();

                    for (j = 0; j < whichPayed.length; j++) {
                        if ((forPayment[i] == whichPayed[j])) {
                            tempSelector.show();
                            break;
                        }
                    }
                }
            });

        });
    </script>

    <!-- JS FOR TABS -->
    <script type="text/javascript">
        //<![CDATA[

        var tabLinks = [];
        var contentDivs = [];

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

            // Assign on click events to the tab links, and
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

        $(document).ready(function () {
            init();
        });
        //]]>
    </script>
    <!-- END JS FOR TABS -->

    <style type="text/css">
        ul#tabs {
            list-style-type: none;
            margin: 23px 19px 8px;
            padding: 0 0 0.3em 0;
        }

        ul#tabs li {
            display: inline;
            font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
            font-size: 12px;
            color: #5F6778;
        }

        ul#tabs li a {
            background-color: #FFFFFF;
            border-bottom: none;
            text-decoration: none;
            color: #5F6778;
            padding: 13px 22px;
            font-weight: bold;
        }

        ul#tabs li a:hover {
            background-color: #FFFFFF;
        }

        ul#tabs li a.selected {
            background-color: #FFFFFF;
            font-weight: bold;
            padding: 15px 22px;
            border: 1px solid #EEEEEE;
            border-bottom: 1px solid #FFFFFF;
            -moz-box-shadow: 0px -4px 0px #78BDF5;
            -webkit-box-shadow: 0px -4px 0px #78BDF5;
            box-shadow: 0px -4px 0px #78BDF5;
            border-top: 0;
        }

        div.tabContent {
            border-top: 1px solid #E6E6E6;
            background: #FBFBFB;
            float: left;
            margin-left: -35px;
            width: 669px;
            padding: 20px 40px 0 40px;
            margin-top: -1px;
        }

        div.tabContent.hide {
            display: none;
        }

        .settings-whitearea {
            background: none repeat scroll 0 0 #ffffff;
            border-bottom: 1px solid #e6e6e6;
            float: left;
            height: 50px;
            width: 749px;
            margin-left: -40px;
            margin-top: -20px;
        }
    </style>
<?php $this->load->view( "dashboard/common/footer" ) ?>