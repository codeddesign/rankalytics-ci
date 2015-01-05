<?php
$status = $user_database['status'] == '0' ? 'INACTIVE' : $user_database['status'] == '5' ? 'CLOSED' : 'ACTIVE';
$isPaid = $user_database['isPaid'];
//if($user_database[''])
//$level =$user_database['level'] = $user_database['isPaid']=="yes"?"PRO":"FREE";
$level = $user_database['level'] = $user_database['userType'];
$selected = 1;
?>

<?php
/*
error_reporting(E_ERROR | E_PARSE);
$domain="http://mozcast.com/";

$output = file_get_contents($domain);
 $dom = new DOMDocument();
$dom->loadHTML($output);
$domx = new DOMXPath($dom);
//$entries = $domx->evaluate("//li");
$entries = $domx->evaluate('//li[@class="row"]');
$arr = array();
$res = array();
$i=1;
foreach ($entries as $entry) {
    $arr["t".$i] = substr(trim($entry->nodeValue),0,5) ;
    $arr["d".$i] = substr(trim($entry->nodeValue),4) ;
    $res[]=$arr;
    $i++;
}


include("assets/screen/GrabzItClient.class.php");
$grabzIt = new GrabzItClient("YTNkNjM0YmE2NDE0NDk0NTg5ODgxYzM5ZjNjODAxNDM=", "GSc/bD8leQlsNgc/LhJOPz90Tj8FLT9qGBohP2NAPz8=");
$grabzIt->SetImageOptions($domain, null, null, null, 545, 400, "png", null, "highcharts-0" );
$grabzIt->SaveTo("assets/screen/temp.png");
*/

?>

<?php //print_r($user_database);
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
<div class="settings-userlevel">IRH ACCOUNT IST <?php echo $status; ?> :
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
    <div class="subscriptiontext">CUSTOM WHITE LABEL REPORTS</div>
</div>
<div class="profile-titleline"></div>
<div class="subscriptionwrap">
    <iframe id="upload-companylogo" name="upload-companylogo" style="display:none;"></iframe>
    <?php echo form_open_multipart('users/uploadLogo', array('class' => 'upload-logo ajax-form', 'id' => 'uploadLogo', 'target' => "upload-companylogo")); ?>
    <div class="uploadlogowrap">
        <div class="uploadlogo-text">logo upload</div>
        <div class="uploadlogo-box"><?php echo $logo_img; ?></div>

        <span class="right-sided" id="delete-logo" <?php if ($logo_img == '') echo 'style="display:none"'; ?>>x</span>

        <div class="upload-logofile">
            <div class="fileUpload btn btn-primary">
                <span>Upload</span>
                <input type="file" class="upload" name="userfile" onchange="uploadLogo()"/>
                <input type="hidden" name="userid" value="<?php echo $user_database['id'] ?>"/>
            </div>
        </div>

        <input id="frmLogoSbmt" type="submit" value="" style="display:none">

        <div id="companyLogo-loading" align="left" id="companyInfo-loading" class="save-loading"
             style="margin-top:0px; float: left">
            <div class="spinner"></div>
        </div>


    </div>
    </form>
    <?php echo form_open('users/saveSection', array('class' => 'upload-companyname ajax-form', 'id' => 'companyInfo', 'onsubmit' => 'return false;')); ?>
    <div id="form-msgs2" class="form-errors"></div>
    <label for="companyName">Company Name</label>
    <input type="text" id="companyName" name="companyName" value="<?php echo $user_database['companyName']; ?>">

    <div class="upload-additionaltext">Your logo and company name will appear on all reports</div>
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
    <div class="subscriptiontext">ACCESS TOKEN</div>
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
    <div class="subscriptiontext">ACCOUNT INFORMATION</div>
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
        <label for="username">first name</label>
        <input type="text" name="firstName" id="firstName" value="<?php echo $user_database['firstName']; ?>">
    </div>
    <div class="rightusernamefields">
        <label for="lastName">last name</label>
        <input type="text" name="lastName" id="lastName" value="<?php echo $user_database['lastName']; ?>">
    </div>
    <div class="leftusernamefields">
        <label for="phoneNumber">telephone</label>
        <input type="text" name="phoneNumber" id="phoneNumber" value="<?php echo $user_database['phoneNumber']; ?>">
    </div>
    <div class="rightusernamefields">
        <label for="streetAddress">street</label>
        <input type="text" name="streetAddress" id="streetAddress"
               value="<?php echo $user_database['streetAddress']; ?>">
    </div>
    <div class="leftusernamefields">
        <label for="city">city</label>
        <input type="text" name="city" id="city" value="<?php echo $user_database['city']; ?>">
    </div>
    <div class="rightzipcodefields" style="width:290px;">
        <label for="zipCode">zip code</label>
        <input type="text" name="zipCode" id="zipCode" value="<?php echo $user_database['zipCode']; ?>">
    </div>
    <div class="leftusernamefields">
        <label for="vatNumber">VAT #</label>
        <input type="text" name="vatNumber" id="vatNumber" value="<?php echo $user_database['vatNumber']; ?>">
    </div>
    <div class="rightusernamefields">
        <label for="country">country</label>
        <input type="text" name="country" id="country" value="<?php echo $user_database['country']; ?>">
    </div>
    <input type="hidden" name="section" value="userInfo"/>

    <div class="profilesave right-sided">
        <div align="left" style="float:left ;margin-right: 42px;" id="userInfo-loading" class="save-loading">
            <div class="spinner"></div>
        </div>
        <!--img src="<?php echo base_url() ?>assets/images/loading.gif" align="left" id="userInfo-loading"  class="save-loading"-->
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
            <div class="activesubscription"></div>
            <div class="activesubscription-text">You have an active subscription for Rank Tracker</div>
        </div>
        <!--<div class="creditcards"></div>-->
        <div class="promembership-text">RANK TRACKER SUBSCRIPTION</div>
        <div class="promembership-line"></div>
        <div class="keywordsenough">30 keywords are not sufficient?</div>
        <div class="keywordsenough-small">Our Pro plan allows you up to 10 keywords with an unlimited number of domains
        </div>
        <div class="profile-whatyouget"></div>
        <div class="profile-keywordsenoughbottom">Enterprise members receive unlimited real-time keyword rank tracking for only € 299 per month.
        </div>
        <div class="profile-keywordsenoughbottomsmall">* Your account is charged monthly based on your subscription level.
        </div>
    </div>
    <div class="promembership-formwrap">


        <div id="form-msgs4" class="form-errors"></div>

        <div class="pricingcheckbox">
            <label>subscription plan</label>
            <?php $amount = $user_database['accountType'] == 'pro' ? '99' : ($user_database['accountType'] == 'enterprise' ? "299" : "0"); ?>

            <input type="radio" name="accountType" id="userTypePro" value="pro" title="10,000 Keywords"
                   class="css-checkbox subscription-plan" <?php echo $user_database['accountType'] == 'pro' ? ' checked="checked" ' : ''; ?> />
            <label for="userTypePro" title="10,000 Keywords" class="css-label">Pro Plan (€99)</label>
            <input type="radio" title="Unlimited Keywords" name="accountType" value="enterprise" id="userTypeEnterprise"
                   class="css-checkbox subscription-plan" <?php echo $user_database['accountType'] == 'enterprise' ? ' checked="checked" ' : ''; ?> />
            <label for="userTypeEnterprise" title="Unlimited Keywords" class="css-label">Enterprise Plan (€299)</label>
        </div>
        <div class="pricingcheckbox">
            <label>payment method</label>
            <?php
            $payment_type = '';

            if (strtolower($user_database['paymentType']) == 'elv') {
                $elv_selected = ' checked="checked" ';
                $cc_selected = ' ';
                $payment_type = 'ELV';
                $cc_class = 'none-display';
                $elv_class = 'block-display';
            } else {
                $elv_selected = ' ';
                $cc_selected = ' checked="checked" ';
                $cc_class = 'block-display';
                $elv_class = 'none-display';
                $payment_type = 'CC';
            }

            ?>
            <input <?php echo $cc_selected ?> type="radio" name="paymentType" id="paymentTypeCC" value="cc"
                                              title="Credeit Card Payment"
                                              class="css-checkbox payment-type" <?php //echo $user_database['paymentType']=='cc'?' checked="checked" ':'';?> />
            <label for="paymentTypeCC" title="Credeit Card Payment" class="css-label">Credit card payment</label>
            <input <?php echo $elv_selected ?>  type="radio" title="ELV Payment" name="paymentType" value="elv"
                                                id="paymentTypeELV"
                                                class="css-checkbox payment-type" <?php //echo $user_database['paymentType']=='elv'?' checked="checked" ':'';?> />
            <label for="paymentTypeELV" title="ELV Payment" class="css-label">ELV Bank Transfer</label>
        </div>
        <?php echo form_open('users/saveSection', array('class' => 'promembershipform ajax-form ' . $cc_class, 'id' => 'CC-Form', 'onsubmit' => 'return false;')); ?>
        <input type="hidden" name="accountType" id="CC-accountType" value="none"/>
        <input type="hidden" name="paymentType" value="CC">
        <label for="cardHolderName">Karteninhaber</label>
        <input type="text" name="cardHolderName" id="cardHolderName"
               value="<?php echo $user_database['cardHolderName']; ?>">

        <label for="creditCardNumber"># Kartennummer</label>
        <input type="text" name="creditCardNumber" id="creditCardNumber"
               value="<?php echo $user_database['creditCardNumber'] != '' ? '************' . $user_database['creditCardNumber'] : ''; ?>">

        <div class="proexpiremonth">
            <label for="expireMonth" class="proexpiremonth">gültig Monat</label>
            <input type="text" name="expireMonth" id="expireMonth"
                   value="<?php echo $user_database['expireMonth'] != 0 ? $user_database['expireMonth'] : ''; ?>"
                   class="proexpiremonth">
        </div>
        <div class="proexpireYear">
            <label for="expireYear" class="proexpiremonth">gültig Jahr</label>
            <input type="text" name="expireYear" id="expireYear"
                   value="<?php echo $user_database['expireYear'] != 0 ? $user_database['expireYear'] : ''; ?>"
                   class="proexpiremonth">
        </div>
        <label for="cvvCvc">cvv / cvc</label>
        <input type="text" name="cvvCvc" id="cvvCvc" value="<?php echo $user_database['cvvCvc']; ?>">

        <div class="encryptlock"></div>
        <div class="encryptlock-text">Ihre Zahlung wird mit 2056-bit Verschlüsselung übertragen</div>
        <input type="hidden" name="section" value="billingInfo"/>
        <input type="hidden" id="card-amount-int" name="card-amount-int" value="<?php echo $amount; ?>"/>
        <input type="hidden" name="card-currency" id="card-currency" value="EUR"/>
        <input type="hidden" name="token" id="token"/>

        <div class="profilesave right-sided">
            <div align="left" style="float:left ;margin-right: 42px;" id="billingInfo-loading" class="save-loading">
                <div class="spinner"></div>
            </div>
            <!--img src="<?php echo base_url() ?>assets/images/loading.gif" id="billingInfo-loading" align="left" class="save-loading"-->
            <input type="submit" value="" id="submitBilling" style="margin-top:12px;">
        </div>
        </form>
        <?php echo form_open('users/saveSection', array('class' => 'promembershipform ajax-form ' . $elv_class, 'id' => 'ELV-Form', 'onsubmit' => 'return false;')); ?>
        <input type="hidden" id="ELV-amount-int" name="amount-int" value="<?php echo $amount; ?>"/>
        <input type="hidden" name="accountType" id="ELV-accountType" value="none"/>
        <input type="hidden" name="section" value="billingInfo"/>
        <input type="hidden" name="token" id="ELV-token"/>
        <input type="hidden" name="paymentType" value="ELV">
        <label for="accountHolderName">Kontoinhaber</label>
        <input type="text" name="ELV_accountHolderName" id="ELV_accountHolderName"
               value="<?php echo isset($user_database['ELV_accountHolderName']) ? $user_database['ELV_accountHolderName'] : ''; ?>">
        <label for="ELV_accountNumber">Kontonummer</label>
        <input type="text" name="ELV_accountNumber" id="ELV_accountNumber"
               value="<?php echo isset($user_database['ELV_accountHolderName']) ? $user_database['ELV_accountNumber'] : ''; ?>">
        <label for="ELV_bankIdentificationNumber">Bankleitzahl (Blz.)</label>
        <input type="text" name="ELV_bankIdentificationNumber" id="ELV_bankIdentificationNumber"
               value="<?php echo isset($user_database['ELV_bankIdentificationNumber']) ? $user_database['ELV_bankIdentificationNumber'] : ''; ?>">

        <div class="profilesave right-sided">
            <div align="left" style="float:left ;margin-right: 42px;" id="ELV-billingInfo-loading" class="save-loading">
                <div class="spinner"></div>
            </div>
            <!--img src="<?php echo base_url() ?>assets/images/loading.gif" id="billingInfo-loading" align="left" class="save-loading"-->
            <input type="submit" value="" id="submitBilling-ELV" style="margin-top:12px;">
        </div>


        </form>
    </div>

</div>
<!-- end last tab div -->


<div class="tabContent" id="seocrawltab">

    <div class="promembership-wrap">
        <div class="settings-whitearea">
            <div class="nonactivesubscription"></div>
            <div class="nonactivesubscription-text">You do not have an active subscription for Rank Tracker</div>
        </div>
        <!--<div class="creditcards"></div>-->
        <div class="promembership-text">SEO CRAWL SUBSCRIPTION</div>
        <div class="promembership-line"></div>
        <div class="keywordsenough">30 Keywords sind nicht ausreichend?</div>
        <div class="keywordsenough-small">Einer unserer Pro Plans ermöglicht Ihnen 10.000 oder unlimited Keywords für
            eine unbegrenzte Anzahl Domains
        </div>
        <div class="profile-whatyouget"></div>
        <div class="profile-keywordsenoughbottom">Sie erhalten unlimited real-time Keyword Rank-Tracking für nur €299
            pro Monat.
        </div>
        <div class="profile-keywordsenoughbottomsmall">*Für unsere Pro und Enterprise Pläne wird Ihre Kreditkarte oder
            Konto monatlich, wiederkehrend <br>mit €99 bzw. €299 belastet.
        </div>
    </div>
    <div class="promembership-formwrap">


        <div id="form-msgs4" class="form-errors"></div>

        <div class="pricingcheckbox">
            <label>subscription plan</label>
            <?php $amount = $user_database['accountType'] == 'pro' ? '99' : ($user_database['accountType'] == 'enterprise' ? "299" : "0"); ?>

            <input type="radio" name="accountType" id="userTypePro" value="pro" title="SEO Crawl Starter"
                   class="css-checkbox subscription-plan" <?php echo $user_database['accountType'] == 'pro' ? ' checked="checked" ' : ''; ?> />
            <label for="userTypePro" title="SEO Crawl Starter" class="css-label">Starter PLan (€99)</label>
            <input type="radio" title="SEO Crawl Pro" name="accountType" value="enterprise" id="userTypeEnterprise"
                   class="css-checkbox subscription-plan" <?php echo $user_database['accountType'] == 'enterprise' ? ' checked="checked" ' : ''; ?> />
            <label for="userTypeEnterprise" title="SEO Crawl Pro" class="css-label">Pro Plan(€249)</label>
            <input type="radio" title="Seo Crawl Enterprise" name="accountType" value="enterprise"
                   id="userTypeEnterprise"
                   class="css-checkbox subscription-plan" <?php echo $user_database['accountType'] == 'enterprise' ? ' checked="checked" ' : ''; ?> />
            <label for="userTypeEnterprise" title="SEO Crawl Enterprise" class="css-label">Enterprise Plan(€399)</label
        </div>
        <div class="pricingcheckbox">
            <label>Zahlungsmethode</label>
            <?php
            $payment_type = '';

            if (strtolower($user_database['paymentType']) == 'elv') {
                $elv_selected = ' checked="checked" ';
                $cc_selected = ' ';
                $payment_type = 'ELV';
                $cc_class = 'none-display';
                $elv_class = 'block-display';
            } else {
                $elv_selected = ' ';
                $cc_selected = ' checked="checked" ';
                $cc_class = 'block-display';
                $elv_class = 'none-display';
                $payment_type = 'CC';
            }

            ?>
            <input <?php echo $cc_selected ?> type="radio" name="paymentType" id="paymentTypeCC" value="cc"
                                              title="Credeit Card Payment"
                                              class="css-checkbox payment-type" <?php //echo $user_database['paymentType']=='cc'?' checked="checked" ':'';?> />
            <label for="paymentTypeCC" title="Credeit Card Payment" class="css-label">Kreditkartenzahlung</label>
            <input <?php echo $elv_selected ?>  type="radio" title="ELV Payment" name="paymentType" value="elv"
                                                id="paymentTypeELV"
                                                class="css-checkbox payment-type" <?php //echo $user_database['paymentType']=='elv'?' checked="checked" ':'';?> />
            <label for="paymentTypeELV" title="ELV Payment" class="css-label">ELV Lastschriftverfahren</label>
        </div>
        <?php echo form_open('users/saveSection', array('class' => 'promembershipform ajax-form ' . $cc_class, 'id' => 'CC-Form', 'onsubmit' => 'return false;')); ?>
        <input type="hidden" name="accountType" id="CC-accountType" value="none"/>
        <input type="hidden" name="paymentType" value="CC">
        <label for="cardHolderName">Karteninhaber</label>
        <input type="text" name="cardHolderName" id="cardHolderName"
               value="<?php echo $user_database['cardHolderName']; ?>">

        <label for="creditCardNumber"># Kartennummer</label>
        <input type="text" name="creditCardNumber" id="creditCardNumber"
               value="<?php echo $user_database['creditCardNumber'] != '' ? '************' . $user_database['creditCardNumber'] : ''; ?>">

        <div class="proexpiremonth">
            <label for="expireMonth" class="proexpiremonth">gültig Monat</label>
            <input type="text" name="expireMonth" id="expireMonth"
                   value="<?php echo $user_database['expireMonth'] != 0 ? $user_database['expireMonth'] : ''; ?>"
                   class="proexpiremonth">
        </div>
        <div class="proexpireYear">
            <label for="expireYear" class="proexpiremonth">gültig Jahr</label>
            <input type="text" name="expireYear" id="expireYear"
                   value="<?php echo $user_database['expireYear'] != 0 ? $user_database['expireYear'] : ''; ?>"
                   class="proexpiremonth">
        </div>
        <label for="cvvCvc">cvv / cvc</label>
        <input type="text" name="cvvCvc" id="cvvCvc" value="<?php echo $user_database['cvvCvc']; ?>">

        <div class="encryptlock"></div>
        <div class="encryptlock-text">Ihre Zahlung wird mit 2056-bit Verschlüsselung übertragen</div>
        <input type="hidden" name="section" value="billingInfo"/>
        <input type="hidden" id="card-amount-int" name="card-amount-int" value="<?php echo $amount; ?>"/>
        <input type="hidden" name="card-currency" id="card-currency" value="EUR"/>
        <input type="hidden" name="token" id="token"/>

        <div class="profilesave right-sided">
            <div align="left" style="float:left ;margin-right: 42px;" id="billingInfo-loading" class="save-loading">
                <div class="spinner"></div>
            </div>
            <!--img src="<?php echo base_url() ?>assets/images/loading.gif" id="billingInfo-loading" align="left" class="save-loading"-->
            <input type="submit" value="" id="submitBilling" style="margin-top:12px;">
        </div>
        </form>
        <?php echo form_open('users/saveSection', array('class' => 'promembershipform ajax-form ' . $elv_class, 'id' => 'ELV-Form', 'onsubmit' => 'return false;')); ?>
        <input type="hidden" id="ELV-amount-int" name="amount-int" value="<?php echo $amount; ?>"/>
        <input type="hidden" name="accountType" id="ELV-accountType" value="none"/>
        <input type="hidden" name="section" value="billingInfo"/>
        <input type="hidden" name="token" id="ELV-token"/>
        <input type="hidden" name="paymentType" value="ELV">
        <label for="accountHolderName">Kontoinhaber</label>
        <input type="text" name="ELV_accountHolderName" id="ELV_accountHolderName"
               value="<?php echo isset($user_database['ELV_accountHolderName']) ? $user_database['ELV_accountHolderName'] : ''; ?>">
        <label for="ELV_accountNumber">Kontonummer</label>
        <input type="text" name="ELV_accountNumber" id="ELV_accountNumber"
               value="<?php echo isset($user_database['ELV_accountHolderName']) ? $user_database['ELV_accountNumber'] : ''; ?>">
        <label for="ELV_bankIdentificationNumber">Bankleitzahl (Blz.)</label>
        <input type="text" name="ELV_bankIdentificationNumber" id="ELV_bankIdentificationNumber"
               value="<?php echo isset($user_database['ELV_bankIdentificationNumber']) ? $user_database['ELV_bankIdentificationNumber'] : ''; ?>">

        <div class="profilesave right-sided">
            <div align="left" style="float:left ;margin-right: 42px;" id="ELV-billingInfo-loading" class="save-loading">
                <div class="spinner"></div>
            </div>
            <!--img src="<?php echo base_url() ?>assets/images/loading.gif" id="billingInfo-loading" align="left" class="save-loading"-->
            <input type="submit" value="" id="submitBilling-ELV" style="margin-top:12px;">
        </div>


        </form>
    </div>

</div>
<!-- end last tab div -->

</div>
<!-- END TABS -->


</div>
<!--class="subscriptionwrap" -->
</div> <!-- class="twodashcontent" -->
</div> <!-- class="projectbackground" -->
<!-- PAYMILL INTEGRATION -->
<script type="text/javascript">
    var PAYMILL_PUBLIC_KEY = '9837430574672955d89a4f914ea08b82';
</script>
<script type="text/javascript" src="https://bridge.paymill.com/"></script>
<!-- PAYMILL INTEGRATION -->
<script>

$(document).ready(function () {


    if ('<?php echo $amount?>' == '0') {


        $("#userTypePro").click();

        $("#paymentTypeCC").click();
        //alert(1);

        $("#card-amount-int").val("99");

        $("#ELV-amount-int").val("99");
    }

    $(".subscription-plan").click(function () { // set the subscription plan according to selected option
        if ($("#userTypePro").is(':checked')) {
            $("#card-amount-int").val("99");
            $("#ELV-amount-int").val("99");
            $("#CC-accountType").val("pro");
            $("#ELV-accountType").val("pro");
        }
        if ($("#userTypeEnterprise").is(':checked')) {
            $("#card-amount-int").val("299");
            $("#ELV-amount-int").val("299");
            $("#CC-accountType").val("enterprise");
            $("#ELV-accountType").val("enterprise");
        }
    });
    if ($("#userTypePro").is(':checked')) {
        $("#card-amount-int").val("99");
        $("#ELV-amount-int").val("99");
        $("#CC-accountType").val("pro");
        $("#ELV-accountType").val("pro");
    }
    if ($("#userTypeEnterprise").is(':checked')) {
        $("#card-amount-int").val("299");
        $("#ELV-amount-int").val("299");
        $("#CC-accountType").val("enterprise");
        $("#ELV-accountType").val("enterprise");
    }
    $(".payment-type").click(function () { // Displays payment form depending on selected payment type
        if ($("#paymentTypeCC").is(':checked')) {
            $("#ELV-Form").hide();
            $("#CC-Form").show();
        }
        if ($("#paymentTypeELV").is(':checked')) {
            $("#ELV-Form").show();
            $("#CC-Form").hide();
        }
    });


    $("input[type=text],input[type=password]").blur(function () {
        if ($(this).val() != '') {
            $(this).removeClass("validationError");
        }
    });

    $('#emailPassword').submit(function () { // submitting email and password information
        current_emailAddress = "<?php echo $user_database['emailAddress']?>";
        if ($("#emailAddress").val() != current_emailAddress) {
            if (!confirm("Are you sure to change the email address?")) {
                return false;
            }
        } else if ($("#password").val() == '') {
            $('#form-msgs1').show();
            $("#form-msgs1").html('Please either enter new password or change email address to save.');
            return false;
        }
        $('#form-msgs1').hide();
        $("#form-msgs1").html('');
        $("#emailPassword-loading").show();
        $.post($(this).attr('action'), $(this).serialize(), function (data) {
            $("#emailPassword-loading").hide();
            if (!parseInt(data.error)) {
                $("#form-msgs1").show();
                $("#form-msgs1").removeClass("form-errors");
                $("#form-msgs1").addClass("form-success");
                $("#form-msgs1").html("Changes Saved");
                current_emailAddress = $("#emailAddress").val();
                if ($("#emailAddress").val() != current_emailAddress) {
                    window.location.reload();
                }
            }
            else {
                $("#form-msgs1").show();
                $("#form-msgs1").removeClass("form-success");
                $("#form-msgs1").addClass("form-errors");
                $.each(data.msg, function (key, val) {
                    $('#form-msgs1').append(val);

                    $('#' + key).addClass('validationError');
                });
            }
        }, 'json');
        return false;
    });

    $('#companyInfo').submit(function () {// submitting company information
        $('#form-msgs2').hide();
        $("#form-msgs2").html('');
        $("#companyInfo-loading").show();
        $.post($(this).attr('action'), $(this).serialize(), function (data) {
            $("#companyInfo-loading").hide();
            if (!parseInt(data.error)) {
                $("#form-msgs2").show();
                $("#form-msgs2").removeClass("form-errors");
                $("#form-msgs2").addClass("form-success");
                $("#form-msgs2").html("Changes Saved");
            }
            else {
                $("#form-msgs2").show();
                $("#form-msgs2").removeClass("form-success");
                $("#form-msgs2").addClass("form-errors");
                $.each(data.msg, function (key, val) {

                    $('#form-msgs2').append(val);
                    $('#' + key).addClass('validationError');
                });
            }
        }, 'json');
        return false;
    });

    $('#userInfo').submit(function () {// submitting user information
        $("#form-msgs3").hide();
        $("#form-msgs3").html('');
        $("#userInfo-loading").show();
        $.post($(this).attr('action'), $(this).serialize(), function (data) {

            $("#userInfo-loading").hide();
            if (!parseInt(data.error)) {
                $("#form-msgs3").show();
                $("#form-msgs3").removeClass("form-errors");
                $("#form-msgs3").addClass("form-success");
                $("#form-msgs3").html("Changes Saved");
            }
            else {
                $("#form-msgs3").show();
                $("#form-msgs3").removeClass("form-success");
                $("#form-msgs3").addClass("form-errors");
                $.each(data.msg, function (key, val) {
                    $('#form-msgs3').append(val);
                    $('#' + key).addClass('validationError');
                });
            }
        }, 'json');
        return false;
    });

    $('#ELV-Form').submit(function () { // submitting billing information
        if ("<?php echo $isPaid ?>" == "yes") {
            if (!confirm("Updating your credit card info will charge your credit card for new subscription. We will refund you the remaining amount. Do you want to continue?")) {
                return false;
            }
        }
        planType = '';
        if ($("#userTypePro").is(':checked')) {
            planType = $("#userTypePro").val();
        }
        if ($("#userTypeEnterprise").is(':checked')) {
            planType = $("#userTypeEnterprise").val();
        }
        //alert(planType);
        if (planType == '') {
            $("#form-msgs4").show();
            $("#form-msgs4").removeClass("form-success");
            $("#form-msgs4").addClass("form-errors");
            $("#form-msgs4").html("Please select the plan");
            $("#form-msgs4").focus();
            return false;
        }
        $("#submitBilling-ELV").attr("disabled", "disabled");
        $("#form-msgs4").hide();
        $("#form-msgs4").html('');
        $("#ELV-billingInfo-loading").show();
        //$("#proMembership-loading").show();
        //alert("in token");
        paymill.createToken({
            accountholder: $('#ELV_accountHolderName').val(),  // required, ohne Leerzeichen und Bindestriche
            number: $('#ELV_accountNumber').val(),   // required
            bank: $('#ELV_bankIdentificationNumber').val()     // required, vierstellig z.B. "2016"

        }, PaymillResponseHandler_ELV);                   // Info dazu weiter unten

        return false;
    });
    function PaymillResponseHandler_ELV(error, result) { //payment response handler
        $("#submitBilling-ELV").removeAttr("disabled");
        if (error) {

            err_msg = error.apierror;
            alert(err_msg);
            $("#ELV-billingInfo-loading").hide();
            $("#form-msgs4").show();
            $("#form-msgs4").removeClass("form-success");
            $("#form-msgs4").addClass("form-errors");
            $("#form-msgs4").html(err_msg);
            return false;
        } else {
            var token = result.token;

            // Insert token into form in order to submit to server
            $("#ELV-token").val(token);
            $("#token").val(token);
            $.post($("#ELV-Form").attr('action'), $("#ELV-Form").serialize(), function (data) {
                $("#billingInfo-loading").hide();
                $("#submitBilling").removeAttr("disabled");
                if (!parseInt(data.error)) {
                    $("#form-msgs4").show();
                    $("#form-msgs4").focus();
                    $("#form-msgs4").removeClass("form-errors");
                    $("#form-msgs4").addClass("form-success");
                    $("#form-msgs4").html("Subscription Connected...");
                    //setTimeout(window.location.reload(),3000);
                }
                else {
                    $("#form-msgs4").show();
                    $("#form-msgs4").focus();
                    $("#form-msgs4").removeClass("form-success");
                    $("#form-msgs4").addClass("form-errors");
                    $.each(data.msg, function (key, val) {
                        $('#form-msgs4').append(val);
                        $('#' + key).addClass('validationError');
                    });
                }
            }, 'json');
        }
    }// ELV payment response handler


    $('#CC-Form').submit(function () { // submitting billing information
        if ("<?php echo $isPaid ?>" == "yes") {
            if (!confirm("Updating your credit card info will charge your credit card for new subscription. We will refund you the remaining amount. Do you want to continue?")) {
                return false;
            }
        }
        planType = '';
        if ($("#userTypePro").is(':checked')) {
            planType = $("#userTypePro").val();
        }
        if ($("#userTypeEnterprise").is(':checked')) {
            planType = $("#userTypeEnterprise").val();
        }
        if (planType == '') {
            $("#form-msgs4").show();
            $("#form-msgs4").removeClass("form-success");
            $("#form-msgs4").addClass("form-errors");
            $("#form-msgs4").html("Please select the plan");
            $("#form-msgs4").focus();
            return false;
        }
        $("#submitBilling").attr("disabled", "disabled");
        $("#form-msgs4").hide();
        $("#form-msgs4").html('');
        $("#billingInfo-loading").show();
        $("#proMembership-loading").show();
        paymill.createToken({
            number: $('#creditCardNumber').val(),  // required, ohne Leerzeichen und Bindestriche
            exp_month: $('#expireMonth').val(),   // required
            exp_year: $('#expireYear').val(),     // required, vierstellig z.B. "2016"
            cvc: $('#cvvCvc').val(),                  // required
            amount_int: $('#card-amount-int').val(),    // required, integer, z.B. "15" für 0,15 Euro
            currency: $('#card-currency').val(),    // required, ISO 4217 z.B. "EUR" od. "GBP"
            cardholder: $('#cardHolderName').val() // optional
        }, PaymillResponseHandler);                   // Info dazu weiter unten

        return false;
    });

    function PaymillResponseHandler(error, result) { //credit card payment response handler

        $("#submitBilling").removeAttr("disabled");
        if (error) {
            err_msg = '';
            if (error.apierror == 'field_invalid_card_number') {
                err_msg = err_msg + 'Invalid Card Number<br />';
            }
            if (error.apierror == 'field_invalid_card_exp') {
                err_msg = err_msg + 'Invalid Card Expiry<br />';
            }
            if (error.apierror == 'field_invalid_card_cvc') {
                err_msg = err_msg + 'Invalid Card CVC number<br />';
            }
            if (err_msg == '') {
                err_msg = error.apierror;
            }
            $("#billingInfo-loading").hide();
            $("#form-msgs4").show();
            $("#form-msgs4").removeClass("form-success");
            $("#form-msgs4").addClass("form-errors");
            $("#form-msgs4").html(err_msg);
            return false;
        } else {
            var token = result.token;
            // Insert token into form in order to submit to server
            $("#token").val(token);
            $.post($("#CC-Form").attr('action'), $("#CC-Form").serialize(), function (data) {
                $("#billingInfo-loading").hide();
                $("#submitBilling").removeAttr("disabled");
                if (!parseInt(data.error)) {
                    $("#form-msgs4").show();
                    $("#form-msgs4").focus();
                    $("#form-msgs4").removeClass("form-errors");
                    $("#form-msgs4").addClass("form-success");
                    $("#form-msgs4").html("Subscription Connected...");
                    //setTimeout(window.location.reload(),3000);
                }
                else {
                    $("#form-msgs4").show();
                    $("#form-msgs4").focus();
                    $("#form-msgs4").removeClass("form-success");
                    $("#form-msgs4").addClass("form-errors");
                    $.each(data.msg, function (key, val) {
                        $('#form-msgs4').append(val);
                        $('#' + key).addClass('validationError');
                    });
                }

            }, 'json');


        }
    } // response handler for cc payment


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
                    $("#form-msgs2").show();
                    $("#form-msgs2").removeClass("form-errors");
                    $("#form-msgs2").addClass("form-success");
                    $("#delete-logo").hide();
                    $('.uploadlogo-box').html('');
                    $('#companyLogo').val('');

                } else {
                    $("#form-msgs4").show();
                    $("#form-msgs2").removeClass("form-success");
                    $("#form-msgs2").addClass("form-errors");
                }
                $.each(data.msg, function (key, val) {
                    $('#form-msgs2').append(val + "<BR />");
                });
            }

        });
    });
    //        $(".subscription-plan").click();
    /*$.ajax({  // to fetch google weather on page load
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

                $("#closeAccount-msgs").removeClass("form-errors");
                $("#closeAccount-msgs").addClass("form-success");
                $("#closeAccount-msgs").show();
                $.each(data.msg, function (key, val) {


                    $('#closeAccount-msgs').html(val + "<BR />");
                    setTimeout(window.location = "/ranktracker", 4000);
                });
            } else {
                $("#closeAccount-msgs").removeClass("form-success");
                $("#closeAccount-msgs").addClass("form-errors");
                $("#closeAccount-msgs").show();
                $.each(data.msg, function (key, val) {
                    $('#closeAccount-msgs').html(val + "<BR />");
                });

            }

        }
    });
}
function uploadLogo() {
    $('#companyLogo-loading').show();
    $('#uploadLogo').attr('target', 'upload-companylogo');
    $('#uploadLogo').submit();
}
function showmessage(error, msg) {

    $('#companyLogo-loading').hide();
    if (error == 1) {
        $("#form-msgs2").show();
        $("#form-msgs2").removeClass("form-success");
        $("#form-msgs2").addClass("form-errors");
        $("#form-msgs2").html(msg);
    } else if (error == 0) {
        $("#form-msgs2").show();
        $("#form-msgs2").removeClass("form-errors");
        $("#form-msgs2").addClass("form-success");
        $("#form-msgs2").html("File Uploaded Successfully");
        thumb = "<?php echo base_url(); ?>uploads/logos/thumbnails/" + msg;
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
