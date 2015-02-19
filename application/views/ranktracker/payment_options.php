<?php
$subscriptions = $paymentData['paymentSubscriptions'];
$amount        = Subscriptions_Lib::getTotalAmount( $subscriptions );
$stripe        = config_item( 'stripe_config' );
?>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

<div class="paymentremoveme" style="text-align: center;font-weight: bold;font-family: museo-sans,sans-serif">Total: $<?= $amount; ?>/month</div>
<div class="ranktracker-bottomwhitesubcontent paymentremoveme">
    <a href="#paypal" class="contactus-supportbutton payment-option" data-option="paypal" data-ready="no" style="margin-right: 20px;">PayPal</a>
    <a href="#stripe" class="contactus-supportbutton payment-option" data-option="stripe" style="float:right;">Stripe</a>
</div>

<!-- paypal -->
<div id="payment-paypal" style="display: none;">
    <span class="form-errors"></div>
<a id="link-paypal" href="#" style="display:none;"> Click here to go to your paypal account</a>
</div>

<!-- stripe  -->
<form action="" method="POST" id="payment-stripe" style="display:none;">
    <span class="form-errors"></span>

    <div class="form-row">
        <label>
            <span>Card Number</span>
            <input type="text" size="20" data-stripe="number"/>
        </label>
    </div>

    <div class="form-row">
        <label>
            <span>CVC</span>
            <input type="text" size="4" data-stripe="cvc"/>
        </label>
    </div>

    <div class="form-row">
        <label>
            <span>Expiration (MM/YY)</span>
            <input type="text" size="2" data-stripe="exp-month"/>
        </label>
        <span> / </span>
        <input type="text" size="2" data-stripe="exp-year"/>
    </div>

    <button type="submit">Submit Payment</button>
</form>

<script type="text/javascript">
    $(document).ready(function () {
        Stripe.setPublishableKey('<?= $stripe['public_key'];?>');

        function stripeResponseHandler(status, response) {
            var form = $('#payment-stripe');
            var formMsg = form.find('span.form-errors');
            var formInfo = $('#form-msgs1');

            formMsg.hide();

            if (response.error) {
                // Show the errors on the form
                formMsg.text(response.error.message).show();
                form.find('button').prop('disabled', false);
            } else {
                formMsg.text('Please wait. Processing.. ');

                $.ajax({
                    url: '/users/handlestripe',
                    method: 'post',
                    data: {
                        stripeToken: response.id
                    },
                    success: function (response) {
                        if (response.error) {
                            $formMsg.text(response.msg);
                            return false;
                        }

                        formInfo.html(response.msg + '<br/>' + formInfo.html()).show();
                        form.remove();
                        $('.paymentremoveme').remove();
                    }
                })
            }
        }

        $('#payment-stripe').submit(function (event) {
            var form = $(this);

            // Disable the submit button to prevent repeated clicks
            form.find('button').prop('disabled', true);

            Stripe.card.createToken(form, stripeResponseHandler);

            // Prevent the form from submitting with the default action
            return false;
        });

        $('.payment-option').on('click', function (ev) {
            var el = $(this), ready;
            var option = el.data('option');
            var section = $('#payment-' + option);
            var linkMsg = section.find('span.form-errors');
            var linkpp = $('#link-paypal');

            linkMsg.hide();
            $('#payment-paypal').hide();
            $('#payment-stripe').hide();

            section.show();

            if (option == 'paypal') {
                ready = el.data('ready');

                if (ready == 'no') {
                    linkMsg.text('Please wait. Preparing...').show();

                    $.ajax({
                        url: '/users/paypalLink',
                        dataType: 'json',
                        success: function (response) {
                            if (response.error) {
                                linkMsg.text(response.msg);
                                return false;
                            }

                            linkMsg.hide();

                            linkpp
                                .attr('href', response.link)
                                .show();

                            el.attr('data-ready', 'yes');
                        }
                    });

                    return false;
                }

                linkpp.show();
            }

            ev.preventDefault();
        });
    });
</script>
<!-- end-stripe-->

<div class="ranktracker-bottomwhitesubcontent paymentremoveme" style="margin-top:200px;"></div>