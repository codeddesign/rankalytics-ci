<script type="text/javascript">


function signupoverlay() {
    loginclose();
    el = document.getElementById("signupoverlay");
    el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";
}
function signupclose() {
    document.getElementById("signupoverlay").style.visibility = 'hidden';
}
function loginoverlay() {
    signupclose();
    el = document.getElementById("loginoverlay");
    el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";
}

function loginclose() {
    document.getElementById("loginoverlay").style.visibility = 'hidden';
}
$(document).ready(function () {
    $('#horiz_container_outer').horizontalScroll();
    $("input[type=text],input[type=password]").blur(function () {
        if ($(this).val() != '') {
            $(this).removeClass("validationError");
        }
    });
    $("form#overlayform_login").bind("keydown", function (e) {
        if (e.keyCode === 13) {
            userLogin();
        }
    });
});
function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
}
;
function userLogin() {
    $('#login-error').html('');
    $('#login-error').css('display', 'none');
    loginemail = $('#loginemail').val();
    loginpassword = $('#loginpassword').val();
    login_error = 0;
    if (loginemail == '') {
        $('#loginemail').addClass('validationError');
        login_error++;
    }
    if (loginpassword == '') {
        $('#loginpassword').addClass('validationError');
        login_error++;
    }
    if ($("#rememberme").is(":checked")) {
        remember = 1;
    } else {
        remember = 0;
    }
    //alert(loginemail);
    if (login_error >= 1) {
        //$('#errors').html('Please correct the errors below');

        $('#login-error').css('display', 'block');
        $('#login-error').html('Fehler! Bitte beheben Sie die folgenden Fehler');
        return false;
    }
    else if (!isValidEmailAddress(loginemail)) {
        $('#loginemail').addClass('validationError');
        $('#login-error').css('display', 'block');
        $('#login-error').html('Fehler! Bitte geben Sie eine gültige E-Mail-Adresse ein');
        return false;
    }
    $(".overlay").show();
    // if no error user data gets submitted
    $.ajax({
        url: "<?php echo base_url()?>users/login",
        dataType: "json",
        type: "POST",
        data: {
            loginemail: loginemail,
            loginpassword: loginpassword,
            remember: remember
        },
        success: function (data) {
            if (!parseInt(data.error)) {
                if (data.redirect == 'ranktracker') {
                    window.location = "<?php echo base_url()?>ranktracker/dashboard";
                } else {
                    window.location = "<?php echo base_url()?>seocrawl/dashboard";
                }
            }
            else {
                $(".overlay").hide();
                $.each(data.ids, function (key, val) {
                    if (val == 'valid') {
                        /*$('#login-error').css('display','block');
                         $('#login-error').append('Error! Your acount is inactive...');*/
                        window.location = '<?php echo base_url() ?>dashboard';
                    } else if (val == 'invalid') {
                        $('#login-error').html('Fehler! Ihre Zugangsdaten sind ungültig! ');
                        $('#login-error').css('display', 'block');
                    } else {
                        $('#login-error').css('display', 'block');
                        $('#login-error').html('Fehler! Bitte korrigieren Sie die folgenden Felder ');
                        $('#' + val).addClass('validationError');
                    }
                });
            }
        }
    });

}
function userSubmit() {
    $('#reg-error').html(''); // Empty reg error div
    $('#reg-error').css('display', 'none'); // hiding reg error div

    if ($("#userTypeFree").is(':checked')) {
        isPaid = "no";
    }
    if ($("#userTypePaid").is(':checked')) {
        isPaid = "yes";
    }

    firstName = $('#firstName').val();
    lastName = $('#lastName').val();
    emailAddress = $('#emailAddress').val();
    password = $('#password').val();
    val_error = 0;
    // checking/validating fields for values
    if (firstName == '') {
        $('#firstName').addClass('validationError');
        val_error++;
    }
    if (lastName == '') {
        $('#lastName').addClass('validationError');
        val_error++;
    }
    if (emailAddress == '') {
        $('#emailAddress').addClass('validationError');
        val_error++;
    }
    if (password == '') {
        $('#password').addClass('validationError');
        val_error++;
    }
    userType = isPaid;
    if (val_error >= 1) {
        //$('#errors').html('Please correct the errors below');
        $('#reg-error').css('display', 'block');
        $('#reg-error').html('Fehler! Bitte beheben Sie die folgenden Fehler');
        return false;
    }
    else if (!isValidEmailAddress(emailAddress)) {
        $('#emailAddress').addClass('validationError');
        //$('#errors').html('Please enter valid email address');
        $('#reg-error').css('display', 'block');
        $('#reg-error').html('Error! Please enter a valid email address');
        return false;
    }
    // if no error user data gets submitted
    $(".overlay").show();
    $.ajax({
        url: "<?php echo base_url()?>users/save",
        dataType: "json",
        type: "POST",
        data: {
            firstName: firstName,
            lastName: lastName,
            emailAddress: emailAddress,
            password: password,
            isPaid: isPaid
        },
        success: function (data) {
            if (!parseInt(data.error)) {
                window.location = "<?php echo base_url()?>" + data.redirect;
            }
            else {
                $(".overlay").hide();
                $('#errors').html(data.msg);
                $.each(data.ids, function (key, val) {
                    if (val == 'emailAddressExists') {
                        $('#reg-error').css('display', 'block');
                        $('#reg-error').append('Error! This e-mail address already exists ...');
                        //$('#emailAddress').css('background', '#F2DEDE');
                        $('#emailAddress').addClass('validationError');
                    }
                    else {
                        $('#reg-error').html('Error! Please correct the following errors');
                        $('#' + val).addClass('validationError');
                    }
                });
            }
        }
    });
}
</script>
<style>
    body {
        background: none repeat scroll 0 0 #394453 !important;
        margin: 0;
    }

    .overlay img {
        left: 50%;
        position: relative;
        top: 50%;
    }

    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 99999;
        background-color: rgba(0, 0, 0, 0.5); /*dim the background*/
        display: none;
    }
</style>

<link href="<?php echo base_url(); ?>assets/home.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url(); ?>assets/css/radiobuttons.css" rel="stylesheet" type="text/css">
<div class="overlay"><img src="<?php echo base_url(); ?>assets/images/loading.gif" style="margin:10px auto"></div>
<!-- Signup overlay -->
<div id="signupoverlay">
    <div class="whiteoverlaybg">
        <div id="reg-error"></div>
        <!--div style="background:none repeat scroll 0 0 #EE613C;height:30px;width:423px;padding:15px;position:absolute;top:-65px;left:0px"></div-->
        <div class="overlaytitle">Create an account</div>
        <div class="overlaysubtitle">…in less than a second</div>

        <form class="overlayform" method="post" action="">
            <input class="overlaynameleft" type="text" name="firstName" id="firstName" placeholder="First Name">
            <input class="overlaynameright" type="text" name="lastName" id="lastName" placeholder="Last Name">
            <input class="overlayurl" type="text" name="emailAddress" id="emailAddress" placeholder="Email">
            <input class="overlayurl" type="password" name="password" id="password" placeholder="Password">

            <div class="pricingcheckbox">
                <td>
                    <input type="radio" name="userType" id="userTypeFree" class="css-checkbox" checked="checked"/>
                    <label for="userTypeFree" class="css-label">Free Plan</label>
                </td>
                <td>
                    <input type="radio" name="userType" id="userTypePaid" class="css-checkbox"/>
                    <label for="userTypePaid" class="css-label">Pro Plan (€99)</label>
                </td>
            </div>
            <div class="overlayneedaccount">You already have an account?<a href="#" onclick='loginoverlay(); return false;'>Sign in</a></div>
            <input class="overlaysubmit" type="button" value="Sign Up" onclick="javascript:userSubmit()">
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
        <div id="login-error"></div>
        <!--div style="background:none repeat scroll 0 0 #EE613C;height:30px;width:423px;padding:15px;position:absolute;top:-65px;left:0px"></div-->
        <div class="overlaytitle">Log in to your account</div>
        <div class="overlaysubtitle">real-time SEO dashboard</div>
        <form class="overlayform" id="overlayform_login">
            <input class="overlayurl" type="text" name="loginemail" id="loginemail" placeholder="Email">
            <input class="overlayurl" type="password" name="loginpassword" id="loginpassword" placeholder="Password">

            <div class="overlayremember">
                <input type="checkbox" id="rememberme" name="rememberme" value="rememberme"><label for="rememberme"/> remember me</label>
            </div>
            <div class="overlayneedaccount">Don't have an account?<a href="#" onclick='signupoverlay(); return false;'>Register.</a></div>
            <input class="overlaysubmit" type="button" onclick="userLogin()" value="Login">
        </form>

        <a href="javascript:loginclose()">
            <div class="overlayclose"></div>
        </a>
    </div>
</div>
<!-- end Login overlay -->