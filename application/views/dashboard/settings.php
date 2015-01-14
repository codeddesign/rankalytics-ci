<?php
$selected = 1; // left tab;
$status = 'active';

// ..
if ($user_database['companyLogo'] != '') {
    $uploads = 'uploads/logos/thumbnails/' . $user_database['companyLogo'];
    if (file_exists($uploads)) {
        $logo_img = '<img src="' . base_url() . $uploads . '">';
    } else {
        $logo_img = '';
    }
} else {
    $logo_img = '';
}

$this->load->view("include/settingsheader");

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
    <!-- seo weather popup design -->
    <div id="weatherpopup" class="link_toggle">
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
    <!-- end seo weather design -->
    <div class="toptitlebar">ACCOUNT SETTINGS</div>
</div>
<div class="projectbackground">
<?php $this->load->view("dashboard/common/big_left_sidebar", array("selected" => $selected)); ?>
<div class="twodashcontent">
<?php echo $this->load->view('dashboard/common/settingsblue_top', array("user" => $user_database)); ?>
<div class="subscriptiontextlocation">
    <div class="subscriptiontext">PROFILE SETTINGS</div>
</div>
<div id="closeAccount-msgs" class=""></div>
<div class="settings-userlevel">YOUR ACCOUNT IS <?php echo $status; ?> :
    <?php if ($status != 5) { // status 5 is closed account
        ?> <a href="javascript:void(0)" onclick="closeAccount()">DEACTIVATE ACCOUNT</a>
        <div style="float:left" align="left" id="closeAccount-loading" class="save-loading">
            <div class="spinner"></div>
        </div>
    <?php } else { ?><a href="javascript:void(0)"> CLOSED</a> <?php } ?></div>
<div class="subscriptionwrap">
    <?php echo form_open('users/saveSection', array('class' => 'ajax-form', 'id' => 'emailPassword', 'onsubmit' => 'return false;')); ?>
    <div id="form-msgs1" class="form-errors"></div>
    <div class="leftusernamefields">
        <label for="emailAddress">E-mail Address</label>
        <input type="text" name="emailAddress" id="emailAddress" value="<?php echo $user_database['emailAddress']; ?>">
    </div>
    <div class="rightusernamefields">
        <label for="password">Change password</label>
        <input type="password" name="password" id="password" value="">
    </div>
    <div class="rightusernamefields">
        <label for="confirmPassword">Confirm password</label>
        <input type="password" name="confirmPassword" id="confirmPassword" value="">
    </div>
    <div class="profilesave right-sided">
        <div align="left" style="float:left ;margin-right: 42px;" id="emailPassword-loading" class="save-loading">
            <div class="spinner"></div>
        </div>
        <!--img src="<?php echo base_url() ?>assets/images/loading.gif" align="left" id="emailPassword-loading" class="save-loading"-->
        <input type="submit" value="">
    </div>
    <input type="hidden" id="" name="section" value="emailPassword">
    </form>
</div>

<div class="subscriptiontextlocation">
    <div class="subscriptiontext">CUSTOM WHITE-LABEL REPORTS</div>
</div>
<div class="profile-titleline"></div>
<div class="subscriptionwrap">
    <iframe id="upload-companylogo" name="upload-companylogo" style="display:none;"></iframe>
    <?php echo form_open_multipart('users/uploadLogo', array('class' => 'upload-logo ajax-form', 'id' => 'uploadLogo', 'target' => "upload-companylogo")); ?>
    <div class="uploadlogowrap">
        <div class="uploadlogo-text">Upload logo</div>
        <div class="uploadlogo-box"><?php echo $logo_img; ?></div>

        <span class="right-sided" id="delete-logo" <?php if ($logo_img == '') {
            echo 'style="display:none"';
        } ?>>x</span>

        <div class="upload-logofile">
            <div class="fileUpload btn btn-primary">
                <span>Upload</span>
                <input type="file" class="upload" name="userfile" onchange="uploadLogo()"/>
                <input type="hidden" name="userid" value="<?php echo $user_database['id'] ?>"/>
            </div>
        </div>

        <input id="frmLogoSbmt" type="submit" value="" style="display:none">

        <div id="companyLogo-loading" align="left" class="save-loading" style="margin-top:0px; float: left">
            <div class="spinner"></div>
        </div>


    </div>
    </form>
    <?php echo form_open('users/saveSection', array('class' => 'upload-companyname ajax-form', 'id' => 'companyInfo', 'onsubmit' => 'return false;')); ?>
    <div id="form-msgs2" class="form-errors"></div>
    <label for="companyName">Company Name</label>
    <input type="text" id="companyName" name="companyName" value="<?php echo $user_database['companyName']; ?>">

    <div class="upload-additionaltext">Ihr Logo und Firmenname wird auf allen ihren Reports angezeigt</div>
    <input type="hidden" id="" name="section" value="companyInfo">
    <input type="hidden" id="companyLogo" name="companyLogo" value="">
    <input type="hidden" id="mainId" name="mainId" value="<?php echo $user_database['mainId']; ?>">

    <div class="profilesave right-sided">
        <div align="left" style="float:left ;margin-right: 42px;" id="companyInfo-loading" class="save-loading">
            <div id="spinner"></div>
        </div>
        <input type="submit" value="">
    </div>

    </form>
</div>


<div class="subscriptiontextlocation">
    <div class="subscriptiontext">ACCESS TOKENS</div>
</div>
<div class="profile-titleline"></div>

<div class="subscriptionwrap">


    <div class="leftusernamefields">
        <label for="acessToken">Rank Tracker Access Token</label>
        <input type="text" name="acessToken" id="acessToken" value="<?php echo $user_database['access_token']; ?>">
    </div>


    <div class="profilesave left-sided" style="float:left;margin-top: 50px;margin-left: 50px;">

        <input type="button" value="Access Token generieren" id="generate_accesstoken">
    </div>

</div>


<div class="subscriptiontextlocation">
    <div class="subscriptiontext">RECHNUNGSINFORMATIONEN</div>
</div>
<div class="profile-titleline"></div>
<div class="subscriptionwrap">
    <?php echo form_open('users/saveSection', array('class' => 'ajax-form', 'id' => 'userInfo', 'onsubmit' => 'return false;')); ?>
    <div id="form-msgs3" class="form-errors"></div>
    <div class="leftusernamefields" style="width:100%;">
        <label for="username" style="width:100%;">Company Name</label>
        <input type="text" name="firstName" id="firstName" value="<?php echo $user_database['firstName']; ?>">
    </div>
    <div class="leftusernamefields">
        <label for="username">Vorname</label>
        <input type="text" name="firstName" id="firstName" value="<?php echo $user_database['firstName']; ?>">
    </div>
    <div class="rightusernamefields">
        <label for="lastName">Nachname</label>
        <input type="text" name="lastName" id="lastName" value="<?php echo $user_database['lastName']; ?>">
    </div>
    <div class="leftusernamefields">
        <label for="phoneNumber">Telefon</label>
        <input type="text" name="phoneNumber" id="phoneNumber" value="<?php echo $user_database['phoneNumber']; ?>">
    </div>
    <div class="rightusernamefields">
        <label for="streetAddress">Strasse</label>
        <input type="text" name="streetAddress" id="streetAddress"
               value="<?php echo $user_database['streetAddress']; ?>">
    </div>
    <div class="leftusernamefields">
        <label for="city">Stadt</label>
        <input type="text" name="city" id="city" value="<?php echo $user_database['city']; ?>">
    </div>
    <div class="rightzipcodefields" style="width:290px;">
        <label for="zipCode">Plz</label>
        <input type="text" name="zipCode" id="zipCode" value="<?php echo $user_database['zipCode']; ?>">
    </div>
    <div class="rightusernamefields">
        <label for="country">Land</label>
        <select name="country" id="country">
            <?php
            $pattern = '<option value="%s" data-pp="%s" %s>%s</option>';
            foreach ($countries as $c_no => $country) {
                $selected = ($country['code'] == $user_database['country']) ? 'selected' : '';
                echo sprintf($pattern, $country['code'], $country['paypal'], $selected, strtoupper($country['name']));
            }
            ?>
        </select>
    </div>
    <input type="hidden" name="section" value="userInfo"/>

    <div class="profilesave right-sided">
        <div align="left" style="float:left ;margin-right: 42px;" id="userInfo-loading" class="save-loading">
            <div class="spinner"></div>
        </div>
        <input type="submit" value="">
    </div>
    </form>
</div>

<div class="subscriptiontextlocation">
    <div class="subscriptiontext">SUBSCRIPTIONS</div>
</div>
<div class="profile-titleline"></div>
<div class="subscriptionwrap">
    <!-- BEGIN TABS -->
    <ul id="tabs">
        <li><a href="#ranktrackertab">Rank Tracker</a></li>
        <li><a href="#seocrawltab">SEO Crawl</a></li>
    </ul>

    <div class="tabContent" id="ranktrackertab">
        <div class="promembership-wrap">
            <div class="settings-whitearea">
                <div class="<?= (!$ranktracker['expired']) ? 'activesubscription' : 'nonactivesubscription'; ?>"></div>
                <div class="<?= (!$ranktracker['expired']) ? 'activesubscription-text' : 'nonactivesubscription-text'; ?>">
                    ACCOUNT LEVEL: <b><?= strtoupper($ranktracker['plan']); ?></b> <?= ($ranktracker['pending']) ? '(until payment is confirmed)' : ''; ?>
                    <?= (isset($ranktracker['expires_on']) AND $ranktracker['plan'] !== 'starter') ? ' | VALID UNTIL: <b>' . date('d/m/Y', $ranktracker['expires_on']) . '</b>' : ''; ?><br/>
                    NUMBER OF KEYWORDS: <b><?= $ranktracker['usage_number']; ?></b>
                    <?php if ($ranktracker['expired']) : ?>
                        <br/>Your paid subscription to Ranktracker expired.
                    <?php endif; ?>
                </div>
            </div>
            <div class="promembership-text">RANK TRACKER SUBSCRIPTION</div>
            <div class="promembership-line"></div>
            <div class="keywordsenough">30 Keywords sind nicht ausreichend?</div>
            <div class="keywordsenough-small">
                Einer unserer Pro Plans ermöglicht Ihnen 10.000 oder unlimited Keywords für eine unbegrenzte Anzahl Domains
            </div>
            <div class="profile-whatyouget"></div>
            <div class="profile-keywordsenoughbottom">
                Sie erhalten unlimited real-time Keyword Rank-Tracking für nur €299 pro Monat.
            </div>
            <div class="profile-keywordsenoughbottomsmall">
                *Für unsere Pro und Enterprise Pläne wird Ihre Kreditkarte oder Konto monatlich, wiederkehrend <br>mit €99 bzw. €299 belastet.
            </div>
        </div>

        <div class="promembership-formwrap">
            <div id="form-msgs4-ranktracker" class="form-errors"></div>
            <!-- #for 2 plans - RANK TRACKER -->
            <form action="/users/subscription" method="POST" class="subscription-form">
                <div class="pricingcheckbox">
                    <label>Subscription plan</label>
                    <input type="radio" name="accountType" id="accountTypeRanktrackerPro" value="pro" data-amount='<?= Subscriptions_Lib::$_service_prices['ranktracker']['pro']; ?>' title="<?= Subscriptions_Lib::$_service_limits['ranktracker']['pro']['text']; ?> Keywords" class="css-checkbox subscription-plan"/>
                    <label for="accountTypeRanktrackerPro" title="<?= Subscriptions_Lib::$_service_limits['ranktracker']['pro']['text']; ?> Keywords" class="css-label">Pro Plan
                        (<?= Subscriptions_Lib::$_currency_symbol . Subscriptions_Lib::$_service_prices['ranktracker']['pro']; ?>)</label>
                    <input type="radio" title="Unlimited Keywords" name="accountType" data-amount='<?= Subscriptions_Lib::$_service_prices['ranktracker']['enterprise']; ?>' value="enterprise" id="accountTypeRanktrackerEnterprise"
                           class="css-checkbox subscription-plan"/>
                    <label for="accountTypeRanktrackerEnterprise" title="<?= Subscriptions_Lib::$_service_limits['ranktracker']['enterprise']['text']; ?> Keywords" class="css-label">Enterprise
                        Plan(<?= Subscriptions_Lib::$_currency_symbol . Subscriptions_Lib::$_service_prices['ranktracker']['enterprise']; ?>)</label>
                    <input type="radio" title="Rank Tracker Starter" name="accountType" data-amount='0' value="starter" id="accountTypeRanktrackerStarter"
                           class="css-checkbox subscription-plan"/>
                    <label for="accountTypeRanktrackerStarter" title="Rank Tracker Starter" class="css-label">Starter Plan</label>
                </div>

                <div class="pricingcheckbox paidRanktracker">
                    <label>Payment method</label>
                    <input type="radio" name="paymentType" id="paymentTypeRanktrackerPaypal" value="paypal" title="PayPal Payment" class="css-checkbox payment-type paypal-cbx"/>
                    <label for="paymentTypeRanktrackerPaypal" title="PayPal Payment" class="css-label paypal-lbl">PayPal</label>
                    <input type="radio" title="Stripe Payment" name="paymentType" value="Stripe" id="paymentTypeRanktrackerStripe" class="css-checkbox payment-type"/>
                    <label for="paymentTypeRanktrackerStripe" title="Stripe Payment" class="css-label">Stripe</label>
                </div>

                <div class="pricingcheckbox paidRanktracker">
                    <label for="monthsSeocrawl" title="Number of months">Number of months</label>
                    <select name="months" id="monthsSeocrawl">
                        <?php
                        $pattern = '<option value="%s" %s>%s</option>';
                        for ($i = 1; $i <= 12; $i++) {
                            if ($i == 1) {
                                $checked = ' selected';
                            } else {
                                $checked = '';
                            }

                            $j = ($i <= 9) ? '0' . $i : $i;

                            echo sprintf($pattern, $i, $checked, $j);
                        }
                        ?>
                    </select>
                </div>

                <div class="profilesave right-sided">
                    <div align="left" style="float:left ;margin-right: 42px;" id="billingInfo-loading" class="save-loading">
                        <div class="spinner"></div>
                    </div>
                    <!--img src="<?php echo base_url() ?>assets/images/loading.gif" id="billingInfo-loading" align="left" class="save-loading"-->
                    <input type="hidden" value="ranktracker" name="service">
                    <input type="submit" value="" id="submitBillingRanktracker" style="margin-top:12px;">
                </div>
            </form>
        </div>

    </div>
    <!-- end last tab div -->

    <div class="tabContent" id="seocrawltab">
        <div class="promembership-wrap">
            <div class="settings-whitearea">
                <div class="<?= (!$seocrawl['expired']) ? 'activesubscription' : 'nonactivesubscription'; ?>"></div>
                <div class="<?= (!$seocrawl['expired']) ? 'activesubscription-text' : 'nonactivesubscription-text'; ?>">
                    ACCOUNT LEVEL: <b><?= strtoupper($seocrawl['plan']); ?></b> <?= ($seocrawl['pending']) ? '(until payment is confirmed)' : ''; ?>
                    <?= (isset($seocrawl['expires_on']) AND $seocrawl['plan'] !== 'free') ? ' | VALID UNTIL: <b>' . date('d/m/Y', $seocrawl['expires_on']) . '</b>' : ''; ?><br/>
                    NUMBER OF PROJECTS: <b><?= $seocrawl['usage_number']; ?></b>
                    <?php if ($seocrawl['expired']) : ?>
                        <br/>Your paid subscription to Seocrawl expired.
                    <?php endif; ?>
                </div>
            </div>
            <div class="promembership-text">SEO CRAWL SUBSCRIPTION</div>
            <div class="promembership-line"></div>
            <div class="keywordsenough">30 Keywords sind nicht ausreichend?</div>
            <div class="keywordsenough-small">Einer unserer Pro Plans ermöglicht Ihnen 10.000 oder unlimited Keywords für eine unbegrenzte Anzahl Domains</div>
            <div class="profile-whatyouget"></div>
            <div class="profile-keywordsenoughbottom">Sie erhalten unlimited real-time Keyword Rank-Tracking für nur <?= Subscriptions_Lib::$_currency_symbol; ?>299 pro Monat.</div>
            <div class="profile-keywordsenoughbottomsmall">*Für unsere Pro und Enterprise Pläne wird Ihre Kreditkarte oder Konto monatlich, wiederkehrend <br>mit <?= Subscriptions_Lib::$_currency_symbol; ?>99
                bzw. <?= Subscriptions_Lib::$_currency_symbol; ?>299 belastet.
            </div>
        </div>

        <div class="promembership-formwrap">
            <div id="form-msgs4-seocrawl" class="form-errors"></div>
            <form action="/users/subscription" method="POST" class="subscription-form">
                <div class="pricingcheckbox">
                    <label>Subscription plan</label>
                    <input type="radio" name="accountType" id="accountTypeSeocrawlStarter" value="starter" data-amount='<?= Subscriptions_Lib::$_service_prices['seocrawl']['starter']; ?>' title="SEO Crawl Starter"
                           class="css-checkbox subscription-plan"/>
                    <label for="accountTypeSeocrawlStarter" title="SEO Crawl Starter" class="css-label">Starter PLan (<?= Subscriptions_Lib::$_currency_symbol . Subscriptions_Lib::$_service_prices['seocrawl']['starter']; ?>)</label>
                    <input type="radio" title="SEO Crawl Pro" name="accountType" value="pro" data-amount='<?= Subscriptions_Lib::$_service_prices['seocrawl']['pro']; ?>' id="accountTypeSeocrawlPro" class="css-checkbox subscription-plan"/>
                    <label for="accountTypeSeocrawlPro" title="SEO Crawl Pro" class="css-label">Pro Plan(<?= Subscriptions_Lib::$_currency_symbol . Subscriptions_Lib::$_service_prices['seocrawl']['pro']; ?>)</label>
                    <input type="radio" name="accountType" id="accountTypeSeocrawlEnterprise" value="enterprise" data-amount='<?= Subscriptions_Lib::$_service_prices['seocrawl']['enterprise']; ?>' title="Seo Crawl Enterprise"
                           class="css-checkbox subscription-plan"/>
                    <label for="accountTypeSeocrawlEnterprise" title="SEO Crawl Enterprise" class="css-label">Enterprise Plan(<?= Subscriptions_Lib::$_currency_symbol . Subscriptions_Lib::$_service_prices['seocrawl']['enterprise']; ?>)</label>
                    <input type="radio" name="accountType" id="accountTypeSeocrawlFree" value="free" data-amount='0' title="Seo Crawl Free" class="css-checkbox subscription-plan"/>
                    <label for="accountTypeSeocrawlFree" title="SEO Crawl Free" class="css-label">Free Plan</label>
                </div>

                <div class="pricingcheckbox paidSeocrawl">
                    <label>Payment method</label>
                    <input type="radio" name="paymentType" id="paymentTypeSeocrawlPaypal" value="paypal" title="Paypal Payment" class="css-checkbox payment-type paypal-cbx"/>
                    <label for="paymentTypeSeocrawlPaypal" title="Paypal Payment" class="css-label paypal-lbl">Paypal</label>
                    <input type="radio" title="Stripe Payment" name="paymentType" value="Stripe" id="paymentTypeSeocrawlStripe" class="css-checkbox payment-type"/>
                    <label for="paymentTypeSeocrawlStripe" title="Stripe Payment" class="css-label">Stripe</label>
                </div>

                <div class="pricingcheckbox paidSeocrawl">
                    <label for="monthsRanktracker" title="Number of months">Number of months</label>
                    <select name="months" id="monthsRanktracker">
                        <?php
                        $pattern = '<option value="%s" %s>%s</option>';
                        for ($i = 1; $i <= 12; $i++) {
                            if ($i == 1) {
                                $checked = ' selected';
                            } else {
                                $checked = '';
                            }

                            $j = ($i <= 9) ? '0' . $i : $i;

                            echo sprintf($pattern, $i, $checked, $j);
                        }
                        ?>
                    </select>
                </div>

                <div class="profilesave right-sided">
                    <div align="left" style="float:left ;margin-right: 42px;" id="billingInfo-loading" class="save-loading">
                        <div class="spinner"></div>
                    </div>
                    <!--img src="<?php echo base_url() ?>assets/images/loading.gif" id="billingInfo-loading" align="left" class="save-loading"-->
                    <input type="hidden" value="seocrawl" name="service">
                    <input type="submit" value="" id="submitBilling" style="margin-top:12px;">
                </div>
            </form>
        </div>

    </div>
    <!-- end last tab div -->

</div>
<!-- END TABS -->
</div>
<!--class="subscriptionwrap" -->
</div>
<!-- class="twodashcontent" -->
</div>

<!-- class="projectbackground" -->
<script type="text/javascript">
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
        var theForm = $(this), data = theForm.serialize(), arr = [], info_msg;

        // accessiable values:
        $.each(theForm.serializeArray(), function (i, f) {
            arr[f.name] = f.value;
        });

        // hide info msg:
        info_msg = $('#form-msgs4-' + arr.service);
        info_msg.hide().html('');

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
                        case 'manual':
                            infoTxt = 'A member of the rankalytics team will be in contact with you shortly to finalize your subscription.';
                            break;
                        default:
                            // ..
                            break;
                    }

                    info_msg.html(infoTxt).show();
                }

                if (typeof response.redirect_to !== 'undefined') {
                    location.href = response.redirect_to;
                }

                theForm.remove();
            }
        });
    });

    $('.subscription-plan').on('click', function () {
        var i, j, tempSelector, tempAmount, whichPayed = [], paymentOpts, tempService,
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

    $('#country').on('change', function () {
        var pp = $('#country option:selected').attr('data-pp'),
            paypalCheckbox = $('.paypal-cbx'),
            paypalLabel = $('.paypal-lbl');

        if (pp == '0') {
            paypalCheckbox
                .attr('checked', false)
                .attr('disabled', true);
            paypalLabel.attr('style', 'text-decoration: line-through');
        } else {
            paypalCheckbox.attr('disabled', false);
            paypalLabel.attr('style', 'text-decoration: none');
        }
    });

    /* other functions: */
    $("input[type=text],input[type=password]").blur(function () {
        if ($(this).val() != '') {
            $(this).removeClass("validationError");
        }
    });

    $('#emailPassword').submit(function () {
        // submitting email and password information
        var current_emailAddress = "<?php echo $user_database['emailAddress']?>";
        if ($("#emailAddress").val() != current_emailAddress) {
            if (!confirm("Are you sure to change the email address?")) {
                return false;
            }
        } else if ($("#password").val() == '') {
            $('#form-msgs1').show();
            $("#form-msgs1").html('Please either enter new password or change email address to save.');
            return false;
        }

        $('#form-msgs1').hide().html('');

        $("#emailPassword-loading").show();

        $.post($(this).attr('action'), $(this).serialize(), function (data) {
            $("#emailPassword-loading").hide();
            if (!parseInt(data.error)) {
                $("#form-msgs1").show()
                    .removeClass("form-errors")
                    .addClass("form-success")
                    .html("Changes Saved");

                current_emailAddress = $("#emailAddress").val();
                if ($("#emailAddress").val() != current_emailAddress) {
                    window.location.reload();
                }
            }
            else {
                $("#form-msgs1").show()
                    .removeClass("form-success")
                    .addClass("form-errors");

                $.each(data.msg, function (key, val) {
                    $('#form-msgs1').append(val);

                    $('#' + key).addClass('validationError');
                });
            }
        }, 'json');
        return false;
    });

    $('#companyInfo').submit(function () {// submitting company information
        $('#form-msgs2').hide().html('');
        $("#companyInfo-loading").show();
        $.post($(this).attr('action'), $(this).serialize(), function (data) {
            $("#companyInfo-loading").hide();
            if (!parseInt(data.error)) {
                $("#form-msgs2").show()
                    .removeClass("form-errors")
                    .addClass("form-success")
                    .html("Changes Saved");
            }
            else {
                $("#form-msgs2").show()
                    .removeClass("form-success")
                    .addClass("form-errors");

                $.each(data.msg, function (key, val) {

                    $('#form-msgs2').append(val);
                    $('#' + key).addClass('validationError');
                });
            }
        }, 'json');
        return false;
    });

    $('#userInfo').submit(function () {// submitting user information
        $("#form-msgs3").hide().html('');
        $("#userInfo-loading").show();

        $.post($(this).attr('action'), $(this).serialize(), function (data) {

            $("#userInfo-loading").hide();
            if (!parseInt(data.error)) {
                $("#form-msgs3").show()
                    .removeClass("form-errors")
                    .addClass("form-success")
                    .html("Changes Saved");
            }
            else {
                $("#form-msgs3").show()
                    .removeClass("form-success")
                    .addClass("form-errors");

                $.each(data.msg, function (key, val) {
                    $('#form-msgs3').append(val);
                    $('#' + key).addClass('validationError');
                });
            }
        }, 'json');
        return false;
    });

    /* needed functions: */
    $("#delete-logo").click(function () {
        $('#form-msgs2').html('');

        if (!confirm("Are you sure to delete the Logo?")) {
            return false;
        }

        $('#companyLogo-loading').show();

        $.ajax({
            url: '<?php echo base_url();?>users/deleteLogo',
            type: 'POST',
            data: {id: '<?php echo $user_database['id']?>'},
            success: function (data) {
                data = JSON.parse(data);
                $('#companyLogo-loading').hide();
                if (!parseInt(data.error)) {
                    $("#form-msgs2").show().removeClass("form-errors").addClass("form-success");

                    $("#delete-logo").hide();
                    $('.uploadlogo-box').html('');
                    $('#companyLogo').val('');

                } else {
                    $("#form-msgs4").show();
                    $("#form-msgs2").removeClass("form-success").addClass("form-errors");
                }
                $.each(data.msg, function (key, val) {
                    $('#form-msgs2').append(val + "<BR />");
                });
            }

        });
    });
});

function closeAccount() {
    if (!confirm("Are you sure to close the account?")) {
        return false;
    }

    $('#closeAccount-loading').show();

    $.ajax({
        url: '<?php echo base_url();?>users/closeAccount',
        type: 'POST',
        data: {id: '<?php echo $user_database['id']?>'},
        success: function (data) {
            data = JSON.parse(data);
            $('#closeAccount-loading').hide();
            if (!parseInt(data.error)) {

                $("#closeAccount-msgs").removeClass("form-errors").addClass("form-success").show();

                $.each(data.msg, function (key, val) {
                    $('#closeAccount-msgs').html(val + "<BR />");
                    setTimeout(window.location = "/ranktracker", 4000);
                });
            } else {
                $("#closeAccount-msgs").removeClass("form-success").addClass("form-errors").show();

                $.each(data.msg, function (key, val) {
                    $('#closeAccount-msgs').html(val + "<BR />");
                });
            }
        }
    });

    return true;
}

function uploadLogo() {
    $('#companyLogo-loading').show();
    $('#uploadLogo').attr('target', 'upload-companylogo').submit();
}

function showmessage(error, msg) {
    $('#companyLogo-loading').hide();
    if (error == 1) {
        $("#form-msgs2").show()
            .removeClass("form-success")
            .addClass("form-errors")
            .html(msg);
    } else if (error == 0) {
        $("#form-msgs2").show()
            .removeClass("form-errors")
            .addClass("form-success")
            .html("File Uploaded Successfully");

        var thumb = "<?php echo base_url(); ?>uploads/logos/thumbnails/" + msg;
        $("#delete-logo").show();
        $('.uploadlogo-box').html('<img src="' + thumb + '" >');
        $('#companyLogo').val(msg);
    }
}

$("#generate_accesstoken").click(function () {
    $.ajax({
        url: "<?php echo base_url();?>ranktracker/saveaccesstoken",
        type: "post",
        data: ({user_id: '<?php echo $user_database['id']?>', user_email: '<?php echo $user_database['emailAddress']?>'}),
        success: function (result) {
            $('#acessToken').val(result);
        }
    });
});

</script>

<!-- JS FOR TABS -->
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
        padding: 14px 22px;
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
<?php $this->load->view("dashboard/common/footer") ?>
