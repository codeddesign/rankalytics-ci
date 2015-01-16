<?php
$user_info = $this->session->all_userdata();
$stripe    = $user_info['stripe'];

$subscriptions_no = 0;
$amount           = 0;
foreach ($stripe['subscriptions'] as $s_no => $s) {
    if ($s['payment_type'] == 'stripe') {
        $subscriptions_no ++;
        $subs[] = $s['service'] . '-' . $s['plan'];

        $amount += Subscriptions_Lib::getPaidAmount( $s );
    }
}

$stripe['amount'] = ( $amount * 100 );
$this->session->set_userdata( array( 'stripe' => $stripe ) );

$description = $subscriptions_no . ' subscription' . ( ( count( $subs ) > 1 ) ? 's' : '' ) . ' (' . implode( ', ', $subs ) . ')';
?>

<script>
    (function () {
        var handler = StripeCheckout.configure({
            key: 'pk_test_LEIvpepolPcX6uZxzMWqUy5Q',
            image: '/square-image.png',
            token: function (token) {
                var ids = ['#span_loading', '#form-msgs4-ranktracker', '#form-msgs4-seocrawl'], i, info_msg;

                for (i = 0; i < ids.length; i++) {
                    info_msg = $(ids[i]);
                    if (info_msg.length && info_msg.css('display').toLowerCase() == 'block') {
                        break;
                    }
                }

                info_msg.html('Please wait.. processing..');

                $.ajax({
                    url: '/users/handleStripe/',
                    type: 'post',
                    data: token,
                    success: function (response) {
                        if (response.error) {
                            info_msg.html(response.msg);
                            return false;
                        }

                        if (!response.paid) {
                            info_msg.html(response.msg);
                            return false;
                        }

                        info_msg.html("Your payment has been successfully processed.").show();
                    }
                });
            }
        });

        handler.open({
            name: 'Rankalytics',
            email: '<?= $stripe['user_info']['emailAddress'];?>',
            description: '<?= $description; ?>',
            amount: <?= ($amount*100);?>,
            opened: function () {
                $('#span_loading').html('').hide();
            }
        });

        // Close Checkout on page navigation
        $(window).on('popstate', function () {
            handler.close();
        });
    })();
</script>