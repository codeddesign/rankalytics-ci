<!DOCTYPE HTML>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8"/>
    <meta name="robots" content="noindex">
    <title><?= lang( 'promembership.title' ); ?></title>
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
    <script src="https://checkout.stripe.com/checkout.js"></script>
    <style>
        .block-display {
            display: block;
        }

        .none-display {
            display: none;
        }

        #span_loading {
            font-weight: bold;
        }
    </style>
</head>
<body>

<div id="headerblue"></div>
<div class="ranktracker-purchase">
    <div class="bodywrapper">
        <div class="logo"></div>

        <div class="listheader"><?= lang( 'promembership.listheader' ); ?></div>
        <a href='#' onclick='loginoverlay()'>
            <div class="listheader"><?= lang( 'promembership.dashlogin' ); ?></div>
        </a>


        <div class="headernavwrap">
            <!--
            <div class="aflag"></div>
            <div class="gflag"></div>
            -->
            <a href="#" class="navdotlink"><?= lang( 'promembership.contactus' ); ?></a>
            <a href="#" class="navdotlink"><?= lang( 'promembership.products' ); ?></a>
            <a href="#" class="navdotlink"><?= lang( 'promembership.developers' ); ?></a>
        </div>
        <div class="ranktracker-topline"></div>
    </div>
</div>

<div class="bodywrapper">
    <div class="ranktracker-bottomwhitetitle">Rankalytics Membership Signup</div>
    <div class="payment-monthlycharge paymentremoveme">
        <?= ( isset( $temp ) ) ? 'Enter your account details' : ''; ?>
        <?= ( isset( $paymentData ) ) ? 'Please select a payment method' : ''; ?>
    </div>
    <div class="ranktracker-bottomwhitewrapper">
        <?php if (isset( $temp )) : ?>
        <div class="billing-maintitle" style="font-weight: bold;">Account Details</div>

        <?php echo form_open( "users/save", array( "class" => "billing-form", "id" => "proMembership", "onsubmit" => "return false;" ) ); ?>
        <input type="text" name="firstName" id="firstName" class="billing-forminputleft" placeholder="<?= lang( 'promembership.name' ); ?>" value="<?php if (isset( $temp['firstName'] )) {
            echo $temp['firstName'];
        } ?>">
        <input type="text" name="lastName" id="lastName" class="billing-forminputright" placeholder="<?= lang( 'promembership.last' ); ?>" value="<?php if (isset( $temp['lastName'] )) {
            echo $temp['lastName'];
        } ?>">
        <input type="text" name="phoneNumber" id="phoneNumber" class="billing-forminputleft" placeholder="<?= lang( 'promembership.telephone' ); ?>" value="<?php if (isset( $temp['phoneNumber'] )) {
            echo $temp['phoneNumber'];
        } ?>">
        <input type="text" name="streetAddress" id="streetAddress" class="billing-forminputright" placeholder="<?= lang( 'promembership.streetaddress' ); ?>" value="<?php if (isset( $temp['streetAddress'] )) {
            echo $temp['streetAddress'];
        } ?>">
        <input type="text" name="city" id="city" class="billing-forminputleft" placeholder="<?= lang( 'promembership.city' ); ?>" value="<?php if (isset( $temp['city'] )) {
            echo $temp['city'];
        } ?>">
        <input type="text" name="zipCode" id="zipCode" class="billing-forminputrightshort" placeholder="<?= lang( 'promembership.zip' ); ?>" value="<?php if (isset( $temp['zipCode'] )) {
            echo $temp['zipCode'];
        } ?>">
        <select name="country" id="country" class="billing-forminputleft" style="float:left;">
            <option value=""><?= lang( 'promembership.country' ); ?></option>
            <?php
            $pattern = '<option value="%s" data-pp="%s" %s>%s</option>';
            foreach ($countries as $c_no => $country) {
                $selected = ( $country['code'] == ( isset( $temp['country'] ) ? $temp['country'] : '' ) ) ? 'selected' : '';
                echo sprintf( $pattern, $country['code'], $country['paypal'], $selected, $country['name'] );
            }
            ?>
        </select>

        <?php
        $_disabled = true;
        if ( ! $_disabled):
            ?>
            <!-- modules -->
            <div class="billing-maintitle" style="font-weight: bold;margin-top:40px;">Available modules</div>

            <div class="available-modules">
                <label class="signin-rtmodule" for="RanktrackerPlan">
                    <div class="modulelogo"></div>
                    <div class="moduletitle">Rank Tracker R/T</div>
                </label>

                <label class="signin-scmodule" for="SeocrawlPlan">
                    <div class="modulelogo"></div>
                    <div class="moduletitle">SEO Crawl</div>
                </label>

                <div style="clear: both;"></div>
                <style type="text/css">
                    .service-plans {
                        width: 207px;
                        margin-right: 11px;
                        border-color: #303A45;
                        border-top: 0;
                    }
                </style>
                <div style="display: inline-block;">
                    <?php
                    $option = '<option value="%s" data-amount="%s" %s>%s Plan ($%s)</option>';
                    foreach (Subscriptions_Lib::$_service_prices as $service => $s_info) {
                        asort( $s_info, SORT_NATURAL );
                        $selected = 'selected';
                        ?>
                        <select class="service-plans" id="<?= ucfirst( $service ) . 'Plan' ?>" name="<?= ucfirst( $service ) . 'Plan' ?>">
                            <?php
                            foreach ($s_info as $plan => $amount) {
                                echo sprintf( $option, $plan, $amount, $selected, ucfirst( $plan ), $amount );
                                if ($selected !== false) {
                                    $selected = false;
                                }
                            }
                            ?>
                        </select>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <!-- end-modules -->
            <div class="billing-securepayment"></div>
            <div class="billing-securepaymenttext"><?= lang( 'promembership.securepayment' ); ?></div>
        <?php
            /* end disabled */
        endif;
        ?>
        <img src="<?php echo base_url() ?>assets/images/loading.gif" align="left" id="proMembership-loading" class="save-loading">
        <input type="submit" value="Submit" class="billing-createaccountbutton">
        </form>
    </div>

    <?php endif; ?>
    <!-- FORM ERRORS: -->

    <div class="ranktracker-bottomwhitewrapper">
        <?php
        if (isset( $paymentData )) {
            $this->load->view( 'ranktracker/payment_options', array( 'paymentData' => $paymentData ) );
        }
        ?>
    </div>
    <?php
    if (isset( $paymentData ) or isset( $registered )) {
        $emailAddress = isset( $paymentData ) ? $paymentData['emailAddress'] : $registered;
        $infoMsg      = lang( 'promembership.infomsgone' ) . ' ' . $emailAddress . '' . lang( 'promembership.infomsgtwo' );
    }
    ?>
    <div id="form-msgs1" class="form-errors" style="margin-top:100px;margin-bottom:100px;text-align:center;font-size:19px;<?= isset( $registered ) ? 'display: block;' : ''; ?>">
        <?= isset( $infoMsg ) ? $infoMsg : ''; ?>
    </div>

    <div class="homefeatures-smallline"></div>
    <div class="checkoutfeatures">There are many good reasons to choose Rankalytics. <a href="/products"><span>All products at a glance.</span></a></div>
    <div class="featurescheck"></div>
</div>

</div> <?php $this->load->view( 'include/mainfooter' ); ?>

<!-- Signup overlay -->
<div id="signupoverlay">
    <div class="whiteoverlaybg">
        <div class="overlaytitle"><?= lang( 'promembership.overlaytitle' ); ?></div>
        <div class="overlaysubtitle"><?= lang( 'promembership.overlaysub' ); ?></div>
        <form class="overlayform">
            <input class="overlaynameleft" type="text" name="domainurl" placeholder="<?= lang( 'promembership.overlayfirst' ); ?>">
            <input class="overlaynameright" type="text" name="domainurl" placeholder="<?= lang( 'promembership.overlaylast' ); ?>">
            <input class="overlayurl" type="text" name="domainurl" placeholder="<?= lang( 'promembership.overlayemail' ); ?>">
            <input class="overlayurl" type="text" name="domainurl" placeholder="<?= lang( 'promembership.overlaypassword' ); ?>">

            <div class="overlayneedaccount"><?= lang( 'promembership.needaccount' ); ?></div>
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
        <div class="overlaytitle"><?= lang( 'promembership.signintitle' ); ?></div>
        <div class="overlaysubtitle"><?= lang( 'promembership.signinsub' ); ?></div>
        <form class="overlayform">
            <input class="overlayurl" type="text" name="domainurl" placeholder="Email">
            <input class="overlayurl" type="text" name="domainurl" placeholder="Password">

            <div class="overlayremember">
                <input type="checkbox" name="rememberme" value="rememberme"><?= lang( 'promembership.rememberme' ); ?>
            </div>
            <div class="overlayneedaccount"><?= lang( 'promembership.needaccount' ); ?></div>
            <input class="overlaysubmit" type="submit" value="Submit">
        </form>
        <a href="javascript:loginclose()">
            <div class="overlayclose"></div>
        </a>
    </div>
</div>
<!-- end Login overlay -->
<?php if (isset( $temp )) : ?>
    <script type="text/javascript">
        $(document).ready(function () {
            var form_msg = $('#form-msgs1');

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
                var i, tempSec, err = false, paid = false,
                    field_ids = ['firstname', 'lastName', 'phoneNumber', 'streetAddress', 'city', 'zipCode'],
                    country = $('#country'),
                    raa = $('#RanktrackerPlan'), seoc = $('#SeocrawlPlan')
                    ;

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

                if (raa.val() !== 'starter' || seoc.val() !== 'free') {
                    paid = true;
                }

                return {
                    paid: paid
                }
            }

            $('#proMembership').on('submit', function (e) {
                e.preventDefault();
                var theForm = $(this), data, infoMsg;

                if (checkFields() !== false) {
                    window.scrollTo(0, 0);

                    data = theForm.serialize();

                    infoMsg = 'Please wait.. Saving your information!';

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
                            var alternativeMsg = '<?= lang('promembership.infomsgone');?><?= (isset($temp['emailAddress'])) ? $temp['emailAddress'] : ''; ?><?= lang('promembership.infomsgtwo');?>';

                            if (typeof resp.redirect_to !== 'undefined') {
                                location.href = resp.redirect_to;
                                return false;
                            }

                            if (typeof resp.paid !== 'undefined' && !resp.paid) {
                                form_msg.html(alternativeMsg);
                                return false;
                            }
                        }
                    });
                }
            });

            $('.service-plans').on('change', function () {
                var amount = 0;

                $('.service-plans').each(function (index, el) {
                    amount += $(el).find(':selected').data('amount');
                });

                console.log('Total: $' + amount);
            });
        });
    </script>
<?php endif; ?>
</body>
</html>