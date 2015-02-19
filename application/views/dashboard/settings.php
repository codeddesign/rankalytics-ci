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
        <div class="toptitlebar">ACCOUNT SETTINGS</div>
    </div>
    <div class="projectbackground">
    <?php $this->load->view( "dashboard/common/big_left_sidebar", array( "selected" => $selected ) ); ?>
    <div class="twodashcontent">
    <?php echo $this->load->view( 'dashboard/common/settingsblue_top', array( "user" => $user_database ) ); ?>

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
        <?php echo form_open( 'users/saveSection', array( 'class' => 'ajax-form', 'id' => 'emailPassword', 'onsubmit' => 'return false;' ) ); ?>
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
        <?php echo form_open_multipart( 'users/uploadLogo', array( 'class' => 'upload-logo ajax-form', 'id' => 'uploadLogo', 'target' => "upload-companylogo" ) ); ?>
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
        <?php echo form_open( 'users/saveSection', array( 'class' => 'upload-companyname ajax-form', 'id' => 'companyInfo', 'onsubmit' => 'return false;' ) ); ?>
        <div id="form-msgs2" class="form-errors"></div>
        <label for="companyName">Company Name</label>
        <input type="text" id="companyName" name="companyName" value="<?php echo $user_database['companyName']; ?>">

        <div class="upload-additionaltext">Your logo and company name will appear on all your reports.</div>
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

            <input type="button" value="Generate Access Token" id="generate_accesstoken">
        </div>

    </div>


    <div class="subscriptiontextlocation">
        <div class="subscriptiontext">ACCOUNT INFORMATION</div>
    </div>
    <div class="profile-titleline"></div>
    <div class="subscriptionwrap">
        <?php echo form_open( 'users/saveSection', array( 'class' => 'ajax-form', 'id' => 'userInfo', 'onsubmit' => 'return false;' ) ); ?>
        <div id="form-msgs3" class="form-errors"></div>
        <div class="leftusernamefields" style="width:100%;">
            <label for="username" style="width:100%;">Company Name</label>
            <input type="text" name="firstName" id="firstName" value="<?php echo $user_database['firstName']; ?>">
        </div>
        <div class="leftusernamefields">
            <label for="username">First Name</label>
            <input type="text" name="firstName" id="firstName" value="<?php echo $user_database['firstName']; ?>">
        </div>
        <div class="rightusernamefields">
            <label for="lastName">Last Name</label>
            <input type="text" name="lastName" id="lastName" value="<?php echo $user_database['lastName']; ?>">
        </div>
        <div class="leftusernamefields">
            <label for="phoneNumber">Telephone #</label>
            <input type="text" name="phoneNumber" id="phoneNumber" value="<?php echo $user_database['phoneNumber']; ?>">
        </div>
        <div class="rightusernamefields">
            <label for="streetAddress">Street Address</label>
            <input type="text" name="streetAddress" id="streetAddress"
                   value="<?php echo $user_database['streetAddress']; ?>">
        </div>
        <div class="leftusernamefields">
            <label for="city">State</label>
            <input type="text" name="city" id="city" value="<?php echo $user_database['city']; ?>">
        </div>
        <div class="rightzipcodefields" style="width:290px;">
            <label for="zipCode">Zip Code</label>
            <input type="text" name="zipCode" id="zipCode" value="<?php echo $user_database['zipCode']; ?>">
        </div>
        <div class="rightusernamefields">
            <label for="country">Country</label>
            <select name="country" id="country">
                <?php
                $pattern = '<option value="%s" data-pp="%s" %s>%s</option>';
                foreach ($countries as $c_no => $country) {
                    $selected = ( $country['code'] == $user_database['country'] ) ? 'selected' : '';
                    echo sprintf( $pattern, $country['code'], $country['paypal'], $selected, strtoupper( $country['name'] ) );
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

    <!-- class="twodashcontent" -->
    </div>

    <!-- class="projectbackground" -->
    <script type="text/javascript">
    $(document).ready(function () {
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

<?php $this->load->view( "dashboard/common/footer" ) ?>