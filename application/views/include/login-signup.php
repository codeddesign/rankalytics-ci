<script src="<?php echo base_url(); ?>assets/js/spiner.js" type="text/javascript"></script>
<!-- Mailchimp js scripts -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/mailchimp/js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/mailchimp/js/jquery.validate.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		// jQuery Validation
		$("#signup").validate({
			// if valid, post data via AJAX
			submitHandler: function(form) {
				$.post("<?php echo base_url(); ?>assets/mailchimp/subscribe.php", { email: $("#email").val() }, function(data) {
					$('#signup input').hide();
					$('#response').html(data);
				});
			},
			// all fields are required
			rules: {
				email: {
					required: true,
					email: true
				}
			}
		});
	});
</script>
<!-- end Mailchimp js scripts -->
<div class="overlay">
    <div id="spinner"></div>
</div>
<!-- Mailchimp overlay -->
<div id="mailchimpoverlay">
    <div class="whiteoverlaybg">
        <div id="reg-error"></div>
        <!--div style="background:none repeat scroll 0 0 #EE613C;height:30px;width:423px;padding:15px;position:absolute;top:-65px;left:0px"></div-->
        <div class="overlaytitle">Beta Sign up</div>
        <div class="overlaysubtitle">Sign up to be added to the waitlist</div>

        <form id="signup" class="formee" action="<?php echo base_url(); ?>assets/mailchimp/subscribe.php" method="post">
			<input name="email" id="email" class="overlayurl" type="text" placeholder="Enter your Email Address..."/>
			<input class="mailchimpoverlaysubmit" type="submit" title="Sign Up" value="Send" />
		</form>
		<div id="response"></div>
		<div id="badresponse"></div>

        <a href="javascript:signupclose()">
            <div class="overlayclose"></div>
        </a>
    </div>
</div>
<!-- end Mailchimp overlay -->
<!-- Signup overlay -->
<div id="signupoverlay">
    <div class="whiteoverlaybg">
        <div id="reg-error"></div>
        <!--div style="background:none repeat scroll 0 0 #EE613C;height:30px;width:423px;padding:15px;position:absolute;top:-65px;left:0px"></div-->
        <div class="overlaytitle">Create an account</div>
        <div class="overlaysubtitle">it takes less than a minute</div>

        <form class="overlayform" method="post" action="/ranktracker/promembership">
            <input class="overlaynameleft" type="text" name="firstName" id="firstName" placeholder="First Name">
            <input class="overlaynameright" type="text" name="lastName" id="lastName" placeholder="Last Name">
            <input class="overlayurl" type="text" name="emailAddress" id="emailAddress" placeholder="Email">
            <input class="overlayurl" type="text" name="userName" id="userName" placeholder="Username">
            <input class="overlayurl" type="password" name="password" id="password" placeholder="Password">

            <div class="pricingcheckbox">
                <!--<input type="radio" name="userType" title="30 Keywords" id="userTypeFree" class="css-checkbox" checked="checked"/>
                <label for="userTypeFree" title="30 Keywords"  class="css-label">Free Plan</label>
                <input type="radio" name="userType" id="userTypePro"  title="10,000 Keywords" class="css-checkbox"/>
                <label for="userTypePro"  title="10,000 Keywords" class="css-label">Pro PLan (€99)</label>
                <input type="radio"  title="Unlimited Keywords" name="userType" id="userTypeEnterprise" class="css-checkbox" />
                <label for="userTypeEnterprise" title="Unlimited Keywords" class="css-label">Enterprise Plan(€299)</label>-->
            </div>
            <div class="overlayneedaccount">Have an account?<a href="#" onclick='loginoverlay(); return false;'>Sign in.</a></div>
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
        <div class="overlaytitle">Sign in to your account</div>
        <div class="overlaysubtitle">and access your real-time SEO data</div>
        <form class="overlayform" id="overlayform_login">
            <input class="overlayurl" type="text" name="loginemail" id="loginemail" placeholder="Email">
            <input class="overlayurl" type="password" name="loginpassword" id="loginpassword" placeholder="Password">

            <div class="overlayremember">
                <input type="checkbox" id="rememberme" name="rememberme" value="rememberme">Remember me next time.
            </div>
            <div class="overlayneedaccount">Need an account?<a href="#" onclick='signupoverlay(); return false;'>Sign up.</a></div>
            <input class="overlaysubmit" type="button" onclick="userLogin()" value="Login">
        </form>

        <a href="javascript:loginclose()">
            <div class="overlayclose"></div>
        </a>
    </div>
</div>
<!-- end Login overlay -->

<!-- javascript text rotator -->
<script type="text/javascript">
function signupoverlay() {
    loginclose();
    el = document.getElementById("signupoverlay");
    el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";
}
function signupclose() {
    document.getElementById("signupoverlay").style.visibility = 'hidden';
    document.getElementById("mailchimpoverlay").style.visibility = 'hidden';
}
function loginoverlay() {
    signupclose();
    el = document.getElementById("loginoverlay");
    el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";
}

function loginclose() {
    document.getElementById("loginoverlay").style.visibility = 'hidden';
}
function mailchimpoverlay() {
    document.getElementById("mailchimpoverlay").style.visibility = 'visible';
}
$(document).ready(function () {
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

function resendVerification() {
    $.ajax({
        url: '/users/resendVerification',
        success: function(resp) {
            $('#login-error').html('').hide();
        }
    });
}

function userLogin() {
    var loginErrMsg = $('#login-error'),
        loginemail = $('#loginemail'),
        loginpassword = $('#loginpassword'),
        login_error = false, remember, overlay = $('.overlay');

    loginErrMsg.html('').hide();

    if (loginemail.val() == '') {
        loginemail.addClass('validationError');
        login_error = true;
    }
    if (loginpassword.val() == '') {
        loginpassword.addClass('validationError');
        login_error = true;
    }

    remember = ($("#rememberme").is(":checked")) ? 1 : 0;

    if (login_error) {
        loginErrMsg.css('display', 'block').html('Error! Please correct the errors below');
        return false;
    }

    // if no error user data gets submitted
    overlay.show();

    $.ajax({
        url: "<?= $this->lang->langLink();?>/users/login",
        dataType: "json",
        type: "POST",
        data: {
            loginemail: loginemail.val(),
            loginpassword: loginpassword.val(),
            remember: remember
        },
        success: function (data) {
            console.log(data);
            if(data.error == 1) {
                loginErrMsg.html(data.message).show();
                overlay.hide();
                return false;
            }

            location.href = data.redirect_to;
            return false;
        }
    });
}

function userSubmit() {
    $('#reg-error').html(''); // Empty reg error div
    $('#reg-error').css('display', 'none'); // hiding reg error div

    var firstName = $('#firstName').val(), lastName = $('#lastName').val(),
    emailAddress = $('#emailAddress').val(),  userName = $('#userName').val(),
    password = $('#password').val(),  val_error = 0;

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
    if (userName == '') {
        $('#userName').addClass('validationError');
        val_error++;
    }
    if (password == '') {
        $('#password').addClass('validationError');
        val_error++;
    }

    if (val_error >= 1) {
        $('#reg-error').css('display', 'block')
            .html('Error! Please correct the errors below');
        return false;
    }
    else if (!isValidEmailAddress(emailAddress)) {
        $('#emailAddress').addClass('validationError');
        $('#reg-error').css('display', 'block')
            .html('Error! Please enter valid email address');

        return false;
    }

    $(".overlay").show();

    // if no error user data gets submitted
    $.ajax({
        url: "<?php echo base_url()?>users/prevalidation",
        dataType: "json",
        type: "POST",
        data: {
            firstName: firstName,
            lastName: lastName,
            emailAddress: emailAddress,
            userName: userName,
            password: password
        },
        success: function (data) {
            if (!parseInt(data.error)) {
                $(".overlay").hide();
                window.location = "<?php echo base_url()?>" + data.redirect;
            }
            else {
                $(".overlay").hide();
                $('#errors').html(data.msg);
                $.each(data.ids, function (key, val) {
                    if (val == 'emailAddressExists') {
                        $('#reg-error').css('display', 'block');
                        $('#reg-error').append('Error! Email address already exists...<br/>');
                        $('#emailAddress').addClass('validationError');
                    } else if (val == 'userNameExists') {
                        $('#reg-error').css('display', 'block');
                        $('#reg-error').append('Error! Username already exists...<br/>');
                        $('#userName').addClass('validationError');
                    }
                    else {
                        $('#reg-error').html('Error! Please correct the errors below<br/>');
                        $('#' + val).addClass('validationError');
                    }
                });
            }
        }
    });
    return true;
}
</script>
<style>
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
