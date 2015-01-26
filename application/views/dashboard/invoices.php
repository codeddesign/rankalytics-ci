<?php
$selected = 1;
$data['current'] = "dashboard";
$this->load->view("include/header", $data);
?>
<div class="yellowtopline"></div>
<div class="topinfobar">
    <a id="weather" href="#" onclick="toggle_visibility('weatherpopup');">
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
                    <li class="row" style="list-style: none outside none;  text-align: center; float: left;color: rgb(86, 179, 217);   font: bolder 12px;  width: 100px;">
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
                            <?php echo $value['temperature'] ?> °F
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

    <div class="toptitlebar">INVOICES</div>
</div>
<div class="projectbackground">
    <?php $this->load->view("dashboard/common/big_left_sidebar", array("selected" => $selected)); ?>

    <div class="twodashcontent">
        <div class="subscriptiontextlocation">
            <div class="subscriptiontext">INVOICES</div>
        </div>
        <div class="subscriptionwrap">
            <div class="subscription-cardcharge">Type</div>
            <div class="subscription-chargedate">Date</div>
            <div class="subscription-chargeamount">Amount</div>
            <div class="subscription-chargeline"></div>

            <!-- subscription separated lines -->
            <?php
            $hasPaidSubs = FALSE;
            if (isset($subscriptions) AND count($subscriptions) > 0) {
                $hasPaidSubs = TRUE;
                foreach ($subscriptions as $s_no => $sub) {
                    ?>
                    <div class="subscription-innerwrap">
                        <div class="subscription-amount"><?= strtoupper($sub['service']); ?></div>
                        <div class="subscription-date"><?= date('m/d/Y', strtotime($sub['started_on'])); ?></div>
                        <div class="subscription-cost">&euro;<?= $sub['paid']; ?></div>
                    </div>
                <?php
                }
            } ?>
            <!-- end subscription separated lines -->

            <div class="subscription-chargeline"></div>
            <a href="<?= ($hasPaidSubs) ? base_url() . 'users/downloadInvoicereport' : 'javascript:;'; ?>" <?= ($hasPaidSubs) ? '' : 'onclick="nonePaid();"' ?>>
                <div class="invoicebutton"></div>
            </a>
        </div>
    </div>
</div>
<div class="rtfooter">
    <ul>
        <a href="/privacy" target="_blank">
            <li>Privacy</li>
        </a>
        <a href="/termsofservice" target="_blank">
            <li>Terms of Service</li>
        </a>
        <li>&copy <?php echo date('Y'); ?> Rankalytics.com</li>
    </ul>
</div>
</div>
<script>
    function nonePaid() {
        alert('You don\'t have any paid services!');
        return false;
    }

    function generateInvoice() {
        $.ajax({
            url: '<?php echo base_url();?>users/downloadInvoicereport',
            type: 'POST',
            data: {id: '<?php echo $user_database['id']?>'},
            success: function (data) {
                data = JSON.parse(data);
                //$('#cancelsubscription-loading').hide();
                if (!parseInt(data.error)) {
                    alert(data.error);
                } else {
                    window.reload();
                }
            }
        });
    }
</script>
</body>
</html>