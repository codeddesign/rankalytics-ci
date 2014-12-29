<!DOCTYPE HTML>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8"/>
    <meta name="robots" content="noindex">
    <title><?= lang('promembership.title');?></title>
    <link href="<?php echo base_url(); ?>assets/style.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>assets/home.css" rel="stylesheet" type="text/css">

    <script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
    <script type="text/javascript" src="https://use.typekit.net/pjn4zge.js"></script>
    <script type="text/javascript"> try {
            Typekit.load();
        } catch (e) { /*..*/
        } </script>
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url() ?>assets/css/radiobuttons.css" rel="stylesheet" type="text/css"/>
    <style>
        .block-display {
            display: block;
        }

        .none-display {
            display: none;
        }
    </style>
</head>
<body>

<div id="headerblue"></div>
<div class="ranktracker-purchase">
    <div class="bodywrapper">
        <div class="logo"></div>

        <div class="listheader"><?= lang('promembership.listheader');?></div>
        <a href='#' onclick='loginoverlay()'>
            <div class="listheader"><?= lang('promembership.dashlogin');?></div>
        </a>

        <div class="headernavwrap">
            <div class="aflag"></div>
            <div class="gflag"></div>
            <a href="#" class="navdotlink"><?= lang('promembership.contactus');?></a>
            <a href="#" class="navdotlink"><?= lang('promembership.products');?></a>
            <a href="#" class="navdotlink"><?= lang('promembership.developers');?></a>
        </div>
        <div class="ranktracker-topline"></div>
    </div>
</div>

<div class="bodywrapper">
    <div class="ranktracker-bottomwhitetitle"><?= lang('promembership.bottomwhitetitle');?></div>
    <div class="payment-monthlycharge"><?= lang('promembership.monthlycharge');?></div>
    <div class="ranktracker-bottomwhitewrapper">
        <?php if(isset($temp)) : ?>
        <div class="ranktracker-bottomwhitesubcontent"><?= lang('promembership.monthlycharge');?></div>
        <div class="billing-maintitle"><?= lang('promembership.billingtitle');?></div>

        <?php echo form_open("users/proMembershipSave", array("class" => "billing-form", "id" => "proMembership", "onsubmit" => "return false;")); ?>
        <input type="text" name="firstName" id="firstName" class="billing-forminputleft" placeholder="<?= lang('promembership.name');?>" value="<?php if (isset($temp['firstName'])) echo $temp['firstName']; ?>">
        <input type="text" name="lastName" id="lastName" class="billing-forminputright" placeholder="<?= lang('promembership.last');?>" value="<?php if (isset($temp['lastName'])) echo $temp['lastName']; ?>">
        <input type="text" name="phoneNumber" id="phoneNumber" class="billing-forminputleft" placeholder="<?= lang('promembership.telephone');?>" value="<?php if (isset($temp['phoneNumber'])) echo $temp['phoneNumber']; ?>">
        <input type="text" name="streetAddress" id="streetAddress" class="billing-forminputright" placeholder="<?= lang('promembership.streetaddress');?>" value="<?php if (isset($temp['streetAddress'])) echo $temp['streetAddress']; ?>">
        <input type="text" name="city" id="city" class="billing-forminputleft" placeholder="<?= lang('promembership.city');?>" value="<?php if (isset($temp['city'])) echo $temp['city']; ?>">
        <input type="text" name="zipCode" id="zipCode" class="billing-forminputrightshort" placeholder="<?= lang('promembership.zip');?>" value="<?php if (isset($temp['zipCode'])) echo $temp['zipCode']; ?>">
        <select name="country" id="country" class="billing-forminputleft">
            <option value=""><?= lang('promembership.country');?></option>
            <?php
            $pattern = '<option value="%s" data-pp="%s">%s</option>';
            foreach ($countries as $c_no => $country) {
                echo sprintf($pattern, $country['code'], $country['paypal'], $country['name']);
            }
            ?>
        </select>

        <div class="billing-maintitle" style="margin-top:39px;margin-bottom:23px;">
            <input type="radio" name="Ranktracker" id="Rankalytics" class="css-checkbox subscription-plan" checked="checked"/>
            <label for="Rankalytics" class="css-label">Ranktracker</label></div>
        <div class="pricingcheckbox">
            <input type="radio" name="accountTypeRanktracker" id="userTypePro" value="pro" class="css-checkbox subscription-plan" data-amount="99"/>
            <label for="userTypePro" class="css-label">Pro Plan (€99)</label>
            <input type="radio" name="accountTypeRanktracker" value="enterprise" id="userTypeEnterprise" class="css-checkbox subscription-plan" data-amount="299"/>
            <label for="userTypeEnterprise" class="css-label">Enterprise Plan (€299)</label>
            <input type="radio" name="accountTypeRanktracker" id="userTypeStarter" value="starter" class="css-checkbox subscription-plan" data-amount="0" checked="checked"/>
            <label for="userTypeStarter" class="css-label">Starter Plan</label>
        </div>
        <div id="paidRanktracker" style="display: none;">
            <?= lang('promembership.numberofmonths');?>
            <select name="monthsRanktracker" id="monthsRanktracker">
                <?php
                $pattern = '<option value="%s" %s>%s</option>';
                for ($i = 1; $i <= 12; $i++) {
                    if ($i == 1) {
                        $checked = ' selected';
                    } else {
                        $checked = '';
                    }

                    $j = ($i <= 9) ? '0'.$i : $i;

                    echo sprintf($pattern, $i, $checked, $j);
                }
                ?>
            </select>
        </div>

        <div class="billing-maintitle" style="margin-top:39px;margin-bottom:23px;">
            <input type="radio" name="Seocrawl" id="Seocrawl" class="css-checkbox subscription-plan" checked="checked"/>
            <label for="Seocrawl" class="css-label">Seocrawl</label></div>
        <div class="pricingcheckbox">
            <input type="radio" name="accountTypeSeocrawl" id="userTypeStarterSeo" value="starter" class="css-checkbox subscription-plan" data-amount="99"/>
            <label for="userTypeStarterSeo" class="css-label">Starter Plan (€99)</label>
            <input type="radio" name="accountTypeSeocrawl" id="userTypeProSeo" value="pro" class="css-checkbox subscription-plan" data-amount="249"/>
            <label for="userTypeProSeo" class="css-label">Pro Plan (€249)</label>
            <input type="radio" name="accountTypeSeocrawl" value="enterprise" id="userTypeEnterpriseSeo" class="css-checkbox subscription-plan" data-amount="399"/>
            <label for="userTypeEnterpriseSeo" class="css-label">Enterprise Plan(€399)</label>
            <input type="radio" name="accountTypeSeocrawl" id="userTypeFreeSeo" value="free" class="css-checkbox subscription-plan" data-amount="0" checked="checked"/>
            <label for="userTypeFreeSeo" class="css-label">Free Plan</label>
        </div>
        <div id="paidSeocrawl" style="display: none;">
            <?= lang('promembership.numberofmonths');?>
            <select name="monthsSeocrawl" id="monthsSeocrawl">
                <?php
                $pattern = '<option value="%s" %s>%s</option>';
                for ($i = 1; $i <= 12; $i++) {
                    if ($i == 1) {
                        $checked = ' selected';
                    } else {
                        $checked = '';
                    }

                    $j = ($i <= 9) ? '0'.$i : $i;

                    echo sprintf($pattern, $i, $checked, $j);
                }
                ?>
            </select>
        </div>
        <div id="forPayedPlan" style="display:none;">
            <div class="billing-maintitle" style="margin-top:39px;margin-bottom:23px;"><?= lang('promembership.choosepayment');?></div>
            <div class="pricingcheckbox">
                <input type="radio" name="paymentType" id="paymentTypePP" value="paypal" title="Paypal Payment" class="css-checkbox payment-type"/>
                <label for="paymentTypePP" title="Paypal Payment" class="css-label" id="labelPP">PayPal</label>
                <input type="radio" name="paymentType" id="paymentTypeManual" value="manual" title="Manual Payment" class="css-checkbox payment-type"/>
                <label for="paymentTypeManual" title="Manual Payment" class="css-label" id="labelManual"><?= lang('promembership.manualpayment');?></label>
            </div>
        </div>

        <div class="billing-securepayment"></div>
        <div class="billing-securepaymenttext"><?= lang('promembership.securepayment');?></div>

        <img src="<?php echo base_url() ?>assets/images/loading.gif" align="left" id="proMembership-loading" class="save-loading">
        <input type="submit" value="Submit" class="billing-createaccountbutton">
        </form>

        <?php endif; ?>
        <!-- FORM ERRORS: -->
        <div id="form-msgs1" class="form-errors" <?= isset($pp_msg) ? 'style="display:block;"' : '';?>><?= isset($pp_msg) ? $pp_msg : ''; ?></div>
    </div>

    <div class="homefeatures-smallline"></div>
    <div class="checkoutfeatures"><?= lang('promembership.checkoutfeatures');?></div>
    <div class="featurescheck"></div>
</div>

<?php $this->load->view('include/mainfooter'); ?>

<!-- Signup overlay -->
<div id="signupoverlay">
    <div class="whiteoverlaybg">
        <div class="overlaytitle"><?= lang('promembership.overlaytitle');?></div>
        <div class="overlaysubtitle"><?= lang('promembership.overlaysub');?></div>
        <form class="overlayform">
            <input class="overlaynameleft" type="text" name="domainurl" placeholder="<?= lang('promembership.overlayfirst');?>">
            <input class="overlaynameright" type="text" name="domainurl" placeholder="<?= lang('promembership.overlaylast');?>">
            <input class="overlayurl" type="text" name="domainurl" placeholder="<?= lang('promembership.overlayemail');?>">
            <input class="overlayurl" type="text" name="domainurl" placeholder="<?= lang('promembership.overlaypassword');?>">

            <div class="overlayneedaccount"><?= lang('promembership.needaccount');?></div>
            <input class="overlaysubmit" type="submit" value="Submit">
        </form>

        <a href="javascript:signupclose()">
            <div class="overlayclose"></div>
        </a>
    </div>
</div>
<!-- end Signup overlay -->

<!-- Login overlay -->
<div id="loginoverlay">
    <div class="whiteoverlaybg">
        <div class="overlaytitle"><?= lang('promembership.signintitle');?></div>
        <div class="overlaysubtitle"><?= lang('promembership.signinsub');?></div>
        <form class="overlayform">
            <input class="overlayurl" type="text" name="domainurl" placeholder="Email">
            <input class="overlayurl" type="text" name="domainurl" placeholder="Password">

            <div class="overlayremember">
                <input type="checkbox" name="rememberme" value="rememberme"><?= lang('promembership.rememberme');?>
            </div>
            <div class="overlayneedaccount"><?= lang('promembership.needaccount');?></div>
            <input class="overlaysubmit" type="submit" value="Submit">
        </form>
        <a href="javascript:loginclose()">
            <div class="overlayclose"></div>
        </a>
    </div>
</div>
<!-- end Login overlay -->
<?php if(isset($temp)) : ?>
<script type="text/javascript">
    $(document).ready(function () {
        var form_msg = $('#form-msgs1');

        $('.subscription-plan').on('click', function () {
            var i, j, tempSelector, tempAmount, whichPayed = [],
                paymentOpts = $('#forPayedPlan'), subPlans = $('.subscription-plan'), forPayment = ['paidRanktracker', 'paidSeocrawl'];

            for (i = 0; i < subPlans.length; i++) {
                tempSelector = $(subPlans[i]);
                tempAmount = tempSelector.attr('data-amount');

                if (tempAmount != undefined && parseInt(tempAmount) > 0 && tempSelector.is(':checked')) {
                    whichPayed[whichPayed.length] = 'paid'+tempSelector.attr('name').replace('accountType', '');
                }
            }

            for(i=0;i<forPayment.length;i++) {
                tempSelector = $('#' + forPayment[i]);
                tempSelector.hide();
                for(j=0;j<whichPayed.length;j++) {
                    if((forPayment[i] == whichPayed[j])) {
                        tempSelector.show();
                        break;
                    }
                }
            }

            if (whichPayed.length > 0) {
                paymentOpts.show();
            } else {
                $('input[name="paymentType"]').attr('checked', false);
                paymentOpts.hide();
            }
        });

        $('#country').on('change', function () {
            var pp = $('#country option:selected').attr('data-pp');

            // enable/disable PP option:
            if (pp == '0') {
                $('#paymentTypePP')
                    .attr('checked', false)
                    .attr('disabled', true);
                $('#labelPP').attr('style', 'text-decoration: line-through');
            } else {
                $('#paymentTypePP').attr('disabled', false);
                $('#labelPP').attr('style', 'text-decoration: none');
            }
        });

        function checkFields() {
            var i, tempSec, err = false,
                field_ids = ['firstname', 'lastName', 'phoneNumber', 'streetAddress', 'city', 'zipCode'],
                ranka = $('input[name="accountTypeRanktracker"]:checked'), seoc = $('input[name="accountTypeSeocrawl"]:checked'), pType = $('input[name="paymentType"]:checked'),
                country = $('#country'), paid = false;

            // validate input fields
            for (i = 0; i < field_ids.length; i++) {
                tempSec = $('#' + field_ids[i]);

                if (tempSec.val() == '') {
                    tempSec.addClass('validationError');
                    err = true;
                } else {
                    tempSec.removeClass('validationError');
                }
            }

            // validate country select
            if (country.val() == '') {
                country.addClass('validationError');
                err = true;
            } else {
                country.removeClass('validationError');
            }

            // behavior:
            form_msg.html('').hide();

            if (err) {
                form_msg.show().addClass('form-errors').html('<?= lang('promembership.failedinfo');?>');
                return false;
            }

            if ((ranka.val() !== 'starter' || seoc.val() !== 'free') && pType.val() == undefined) {
                form_msg.show().addClass('form-errors').html('<?= lang('promembership.failedpayment');?>');
                return false;
            } else {
                pType = pType.val();
                paid = true;
            }

            return {what: pType, paid: paid};
        }

        $('#proMembership').on('submit', function (e) {
            e.preventDefault();
            var theForm = $(this), data = theForm.serialize(), infoMsg, resp;

            if ((resp = checkFields()) !== false) {
                window.scrollTo(0, 0);

                infoMsg = '<?= lang('promembership.infomsgone');?><?= (isset($temp['emailAddress'])) ? $temp['emailAddress'] : ''; ?><?= lang('promembership.infomsgtwo');?>';

                if (resp.paid == true) {
                    if (resp.what == 'paypal') {
                        infoMsg += '<?= lang('promembership.ifpaid');?>';

                    }

                    if (resp.what == 'manual') {
                        infoMsg += '<?= lang('promembership.ifmanual');?>';
                    }
                }

                // ..
                $('div.payment-monthlycharge, div.ranktracker-bottomwhitesubcontent, div.billing-maintitle').remove();
                theForm.remove();
                form_msg.show().addClass('form-errors').html(infoMsg);

                $.ajax({
                    type: 'POST',
                    url: '/users/save',
                    data: data,
                    dataType: 'json',
                    success: function (resp) {
                        // handle result:
                        if (resp.redirect_to !== undefined && resp.what == 'paypal') {
                            location.href = resp.redirect_to;
                        }
                    }
                });
            }
        });
    });
</script>
<!-- end text rotator -->
<?php endif; ?>
</body>
</html>