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
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
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

            <div class="subscriptionwrap" style="margin-top:0;">

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

                    if ($serviceName == 'ranktracker') {
                        $numberOf = 'keywords';
                    }

                    if ($serviceName == 'seocrawl') {
                        $numberOf = 'links';
                    }

                    if ( ! isset( $numberOf )) {
                        $numberOf = 'unset';
                    }
                    ?>
                    <div class="tabContent" id="<?= $serviceName ?>tab">
                        <div class="promembership-wrap">
                            <div class="settings-whitearea">
                                <div class="<?= ( ! $tempVar['expired'] ) ? 'activesubscription' : 'nonactivesubscription'; ?>"></div>
                                <div class="<?= ( ! $tempVar['expired'] ) ? 'activesubscription-text' : 'nonactivesubscription-text'; ?>">
                                    ACCOUNT LEVEL: <b><?= strtoupper( $tempVar['plan'] ); ?></b> <?= ( $tempVar['pending'] ) ? '(until payment is confirmed)' : ''; ?><br/>
                                    NUMBER OF <?= strtoupper( $numberOf ); ?>: <b><?= $tempVar['usage_number']; ?></b>
                                </div>
                            </div>
                            <div class="promembership-text"><?= strtoupper( $serviceNames[$serviceName] ) . ' subscription'; ?></div>
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
                            <?php
                            if (( $flashMsg = $this->session->userdata( 'paypal_flash' ) )) {
                                $this->session->unset_userdata( 'paypal_flash' );
                            }
                            ?>
                            <div id="form-info-<?= $serviceName; ?>" class="form-errors" <?= ( $flashMsg ) ? 'style="display:block;"' : ''; ?>><?= $flashMsg ?></div>

                            <form action="/users/subscription" method="POST" class="subscription-form" data-service="<?= ucfirst( $serviceName ); ?>">
                                <div class="pricingcheckbox">
                                    <label>Subscription plan</label>
                                    <?php
                                    foreach ($plans as $planName => $amount) {
                                        $title = $serviceLimits[$serviceName][$planName]['text'] . ' ' . $numberOf;

                                        ?>
                                        <input type="radio" data-service="<?= ucfirst( $serviceName ); ?>" name="servicePlan" id="servicePlan<?= ucfirst( $serviceName ) . ucfirst( $planName ); ?>" value="<?= $planName ?>" data-amount='<?= $amount; ?>' title="<?= $title; ?>" class="css-checkbox subscription-plan"/>
                                        <label for="servicePlan<?= ucfirst( $serviceName ) . ucfirst( $planName ); ?>" title="<?= $title; ?>" class="css-label"><?= ucfirst( $planName ) ?> Plan
                                            (<?= $currencySymbol . $amount ?>)</label>
                                    <?php } ?>
                                </div>

                                <div class="pricingcheckbox paid<?= ucfirst( $serviceName ) ?>">
                                    <label>Payment method</label>
                                    <input type="radio" name="paymentType" id="paymentType<?= ucfirst( $serviceName ); ?>Paypal" data-service="<?= $serviceName; ?>" value="paypal" title="PayPal Payment" class="css-checkbox payment-type"/>
                                    <label for="paymentType<?= ucfirst( $serviceName ); ?>Paypal" title="PayPal Payment" class="css-label paypal-lbl">PayPal</label>

                                    <input type="radio" name="paymentType" id="paymentType<?= ucfirst( $serviceName ); ?>Stripe" data-service="<?= $serviceName; ?>" value="stripe" title="Stripe Payment" class="css-checkbox payment-type"/>
                                    <label for="paymentType<?= ucfirst( $serviceName ); ?>Stripe" title="Stripe Payment" class="css-label">Credit Card / Stripe</label>
                                </div>

                                <!-- stripe form <?= $serviceName; ?>-->
                                <div class="pricingcheckbox stripe-form" id="stripe-<?= $serviceName ?>" style="display:none;">
                                    <label>Your information</label>

                                    <div>
                                        <label>
                                            <input type="number" size="20" min="13" data-stripe="number" placeholder="Card Number"/>
                                        </label>
                                    </div>

                                    <div>
                                        <label>
                                            <input type="number" size="4" min="3" data-stripe="cvc" placeholder="CVC"/>
                                        </label>
                                    </div>

                                    <div>
                                        <label>
                                            <input type="text" size="2" min="2" max="2" data-stripe="exp-month" placeholder="MM"/>
                                            <span> &nbsp; </span>
                                            <input type="text" size="2" min="2" max="2" data-stripe="exp-year" placeholder="YY"/>
                                        </label>
                                    </div>
                                </div>
                                <!-- end - stripe form <?= $serviceName; ?>-->

                                <div class="profilesave right-sided">
                                    <div align="left" style="float:left ;margin-right: 42px;" id="billingInfo-loading" class="save-loading">
                                        <div class="spinner"></div>
                                    </div>
                                    <!--img src="<?php echo base_url() ?>assets/images/loading.gif" id="billingInfo-loading" align="left" class="save-loading"-->
                                    <input type="hidden" value="<?= $serviceName; ?>" name="serviceName">
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
<?php
foreach ($services as $serviceName => $plans) {
    $forPayment[] = 'paid' . ucfirst( $serviceName );
}

$forPayment = json_encode( $forPayment );
$stripe     = config_item( 'stripe_config' );
?>
    <script>
        $(document).ready(function () {
            Stripe.setPublishableKey('<?= $stripe['public_key'];?>');

            /* select subscription plans on load: */
            var forPayment = <?= $forPayment; ?>;
            (function () {
                var current = <?= $current_options; ?>, temp, i;

                // first hide:
                for (i = 0; i < forPayment.length; i++) {
                    $('.' + forPayment[i]).hide();
                }

                for (i = 0; i < current.length; i++) {
                    temp = current[i];
                    $('#servicePlan' + temp['service'] + temp['plan']).attr('checked', 'checked');
                    $('#paymentType' + temp['service'] + temp['pType']).attr('checked', 'checked');

                    if (temp['isPaid']) {
                        $('.paid' + temp['service']).show();
                    }
                }
            })();

            $('.subscription-plan').on('click', function () {
                var i, j, tempSelector, tempAmount, whichPayed = [], tempService,
                    subPlans = $('.subscription-plan');

                for (i = 0; i < subPlans.length; i++) {
                    tempSelector = $(subPlans[i]);
                    tempAmount = tempSelector.data('amount');
                    tempService = (tempSelector.data('service'));

                    if (tempAmount != undefined && parseInt(tempAmount) > 0 && tempSelector.is(':checked')) {
                        whichPayed[whichPayed.length] = 'paid' + tempService;
                    }
                }

                $('.stripe-form').hide();

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

            $('input[name=paymentType]').on('click', function () {
                var el = $(this),
                    val = el.val(),
                    service = el.data('service');

                $('.stripe-form').hide();

                if (val == 'stripe') {
                    $('#' + val + '-' + service).show();
                }
            });

            $('.subscription-form').submit(function (ev) {
                ev.preventDefault();

                var form = $(this);
                var service = form.data('service');
                var formData = form.serializeArray();
                var paymentType = form.find('input[name=paymentType]:checked').val();
                var btn = form.find('input[type=submit]');

                var formInfo = $('#form-info-' + service.toLowerCase());

                formInfo.hide();

                if (paymentType == 'stripe') {
                    Stripe.card.createToken(form, function (status, response) {
                        btn.prop('disabled', true);
                        if (response.error) {
                            formInfo.html(response.error.message).show();

                            btn.prop('disabled', false);
                        } else {
                            formInfo.html('Please wait. Processing.. ').show();

                            formData[formData.length] = {
                                name: 'stripeToken',
                                value: response.id
                            };

                            $.ajax({
                                url: '/users/handleStripe',
                                method: 'post',
                                data: formData,
                                success: function (response) {
                                    if (response.error) {
                                        formInfo.show().text(response.msg);
                                        return false;
                                    }

                                    formInfo.html(response.msg).show();
                                    form.find('div.stripe-form').hide();
                                }
                            })
                        }
                    });

                    return false;
                }

                if (paymentType == 'paypal') {
                    formInfo.html('Please wait.. Processing..').show();
                    btn.prop('disabled', true);

                    $.ajax({
                        url: '/users/paypalLink',
                        method: 'post',
                        data: formData,
                        success: function (response) {
                            if (response.error) {
                                formInfo.html(response.msg);
                                btn.prop('disabled', false);
                                return false;
                            }

                            if (typeof response.link !== 'undefined') {
                                formInfo.html('Please wait.. Redirecting..');
                                location.href = response.link;
                                return false;
                            }

                            console.log('internal error');
                        }
                    });

                    return false;
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
            margin: 20px 19px 8px;
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