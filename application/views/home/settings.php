<?php $this->load->view("home/common/header");
        $status = $user_database['status']=='0'?'INACTIVE':'ACTIVE';
        $level = $user_database['isPaid']=="yes"?"PRO":"FREE";
        
        ?>
<?php //print_r($user_database);
if ($user_database['companyLogo']!=''){
    $uploads = 'uploads/logos/thumbnails/'.$user_database['companyLogo'];
    if(file_exists($uploads)){
        $logo_img = '<img src="'.base_url().$uploads.'">';
    }else{
        $logo_img = '';
    }
}else{
        $logo_img = '';
    }
?>

        
	<div class="topinfobar">
		<div class="weathericon">
			<img src="<?php echo base_url(); ?>assets/images/weather/sun.png">
		</div>
		<div class="weathertext">Google Weather</div>
		<div class="toptitlebar">ACCOUNT SETTINGS</div>
	</div>
	<div class="projectbackground">
		<div class="bigleftsidebar">
			<div class="bigleftsidebarbutton-one">
				<div class="ranksicons"></div>
			</div>
				<a href="#" class="bigleftsidebarout-one">Settings</a>
			<div class="bigleftsidebarbutton-two">
				<div class="reportsicons"></div>
			</div>
				<a href="#" class="bigleftsidebarout-two">Invoices</a>
		</div>
		<div class="twodashcontent">
			<div class="settingsbluetop">
                            <div class="subscription-accountlevel">ACCOUNT LEVEL: <?php echo $level;?></div>
				<div class="subscription-keywordlimit">KEYWORD LIMIT: <span>100</span></div>
				<div class="subscription-keywordsused">KEYWORDS USED: <span>57</span></div>
                                <?php if($level=='PRO'){ ?>
				<div class="subscription-billingrenewal">BILLING RENEWAL: <span>03/27/2014</span></div>
                                <?php } ?>
			</div>
			<div class="subscriptiontextlocation">
				<div class="subscriptiontext">YOUR PROFILE SETTINGS</div>
			</div>
                    <div id="closeAccount-msgs" class=""></div>
			<div class="settings-userlevel">YOUR ACCOUNT IS <?php echo $status; ?> : <a href="javascript:void(0)" onclick="closeAccount()">CLOSE ACCOUNT</a><img src="<?php echo base_url()?>assets/images/loading.gif" align="left" id="closeAccount-loading" class="save-loading"></div>
			<div class="subscriptionwrap">
				<?php echo form_open('users/saveSection', array('class' => 'ajax-form','id'=>'emailPassword','onsubmit'=>'return false;')); ?>
                            <div id="form-msgs1" class="form-errors"></div>
					<div class="leftusernamefields">
						<label for="emailAddress">email address</label>
						<input type="text" name="emailAddress" id="emailAddress" value="<?php echo $user_database['emailAddress'];?>">
					</div>
					<div class="rightusernamefields">
						<label for="password">setup a new password</label>
						<input type="password" name="password" id="password" value="">
					</div>
					<div class="rightusernamefields">
						<label for="confirmPassword">password confirmation</label>
						<input type="password" name="confirmPassword"  id="confirmPassword" value="">
					</div>
					<div class="profilesave right-sided" >
                                            <img src="<?php echo base_url()?>assets/images/loading.gif" align="left" id="emailPassword-loading" class="save-loading">
						<input type="submit" value="" >
					</div>
                                        <input type="hidden" id="" name="section" value="emailPassword">
				</form>
			</div>
			
			<div class="subscriptiontextlocation">
				<div class="subscriptiontext">CUSTOM WHITE-LABEL REPORTS</div>
			</div>
			<div class="profile-titleline"></div>
			<div class="subscriptionwrap">
                            <iframe id="upload-companylogo" name="upload-companylogo" style="display:none;" ></iframe>
                        <?php echo form_open_multipart('users/uploadLogo', array('class' => 'upload-logo ajax-form','id'=>'uploadLogo','target'=>"upload-companylogo" )); ?>
				<div class="uploadlogowrap">
                                    <div class="uploadlogo-text">upload logo</div>
                                    <div class="uploadlogo-box"><?php echo $logo_img ; ?></div>
                                    
                                    <span class="right-sided" id="delete-logo" <?php if($logo_img=='') echo 'style="display:none"';?>>x</span>
                                    
                                    <div class="upload-logofile">
					<div class="fileUpload btn btn-primary">
    					<span>Upload</span>
                                        <input type="file" class="upload" name="userfile" onchange="uploadLogo()"/>
					</div>
                                    </div>
                                    
                                    <input id="frmLogoSbmt" type="submit" value="" style="display:none" >
                                            <img id="companyLogo-loading" src="<?php echo base_url()?>assets/images/loading.gif" align="left" id="companyInfo-loading" class="save-loading" style="margin-top:0px;">
                                                                            
                                        

				</div>
                            </form>
				<?php echo form_open('users/saveSection', array('class' => 'upload-companyname ajax-form','id'=>'companyInfo','onsubmit'=>'return false;')); ?>
                                        <div id="form-msgs2" class="form-errors"></div>
					<label for="companyName">Company Name</label>
					<input type="text" id="companyName" name="companyName" value="<?php echo $user_database['companyName'];?>">
					<div class="upload-additionaltext">Your logo and company name will be displayed on all of your reports.</div>
                                        <input type="hidden" id="" name="section" value="companyInfo">
                                        <input type="hidden" id="companyLogo" name="companyLogo" value="">
                                        <div class="profilesave right-sided" >
                                            <img src="<?php echo base_url()?>assets/images/loading.gif" align="left" id="companyInfo-loading" class="save-loading">
                                            <input type="submit" value="">
                                        </div>
					
				</form>
			</div>
			
			<div class="subscriptiontextlocation">
				<div class="subscriptiontext">YOUR BILLING INFORMATION</div>
			</div>
			<div class="profile-titleline"></div>
			<div class="subscriptionwrap">
				<?php echo form_open('users/saveSection', array('class' => 'ajax-form','id'=>'userInfo','onsubmit'=>'return false;')); ?>
                                        <div id="form-msgs3" class="form-errors"></div>
					<div class="leftusernamefields">
						<label for="username">first name</label>
						<input type="text" name="firstName" id="firstName" value="<?php echo $user_database['firstName'];?>">
					</div>
					<div class="rightusernamefields">
						<label for="lastName">last name</label>
						<input type="text" name="lastName" id="lastName" value="<?php echo $user_database['lastName'];?>">
					</div>
					<div class="leftusernamefields">
						<label for="phoneNumber">phone number</label>
						<input type="text" name="phoneNumber" id="phoneNumber" value="<?php echo $user_database['phoneNumber'];?>">
					</div>
					<div class="rightusernamefields">
						<label for="streetAddress">street address</label>
						<input type="text" name="streetAddress" id="streetAddress" value="<?php echo $user_database['streetAddress'];?>">
					</div>
					<div class="leftusernamefields">
						<label for="city">city</label>
						<input type="text" name="city" id="city" value="<?php echo $user_database['city'];?>">
					</div>
					<div class="rightzipcodefields">
						<label for="zipCode">zip code</label>
						<input type="text" name="zipCode" id="zipCode" value="<?php echo $user_database['zipCode'];?>">
					</div>
					<div class="leftusernamefields">
						<label for="vatNumber">VAT number</label>
						<input type="text" name="vatNumber" id="vatNumber" value="<?php echo $user_database['vatNumber'];?>">
					</div>
					<div class="rightusernamefields">
						<label for="country">country</label>
						<input type="text" name="country" id="country" value="<?php echo $user_database['country'];?>">
					</div>
                                        <input type="hidden" name="section" value="userInfo" />
					<div class="profilesave right-sided" >
                                            <img src="<?php echo base_url()?>assets/images/loading.gif" align="left" id="userInfo-loading"  class="save-loading">
						<input type="submit" value="">
					</div>
				</form>
			</div>
			
			<div class="subscriptiontextlocation">
				<div class="subscriptiontext">YOUR BILLING INFORMATION</div>
			</div>
			<div class="profile-titleline"></div>
			<div class="subscriptionwrap">
				<div class="promembership-wrap">
					<div class="creditcards"></div>
					<div class="promembership-text">PRO MEMBERSHIP</div>
					<div class="promembership-line"></div>
					<div class="keywordsenough">100 Keywords not enough?</div>
					<div class="keywordsenough-small">A Pro membership provides you with unlimited keywords for an unlimited number of domains.</div>
					<div class="profile-whatyouget"></div>
					<div class="profile-keywordsenoughbottom">Receive real-time unlimited keyword rank tracking for ONLY €35 per month.</div>
					<div class="profile-keywordsenoughbottomsmall">*Pro subscriptions are recurring payments of €35.00 on a <br>monthly basis billed to your credit card on file.</div>
				</div>
				<div class="promembership-formwrap">
                                    
					
                                            <?php echo form_open('users/saveSection', array('class' => 'promembershipform ajax-form','id'=>'billingInfo','onsubmit'=>'return false;')); ?>
                                            <div id="form-msgs4" class="form-errors"></div>
						<label for="cardHolderName">card holder name</label>
						<input type="text" name="cardHolderName" id="cardHolderName" value="<?php echo $user_database['cardHolderName'];?>">
						
						<label for="creditCardNumber">credit card #</label>
						<input type="text" name="creditCardNumber" id="creditCardNumber" value="<?php echo $user_database['creditCardNumber'];?>">
						
						<div class="proexpiremonth">
							<label for="expireMonth" class="proexpiremonth">expire month</label>
							<input type="text" name="expireMonth" id="expireMonth" value="<?php echo $user_database['expireMonth']!=0?$user_database['expireMonth']:'';?>" class="proexpiremonth">
						</div>
						
						<div class="proexpireday">
							<label for="expireDay" class="proexpiremonth">expire day</label>
							<input type="text" name="expireDay" id="expireDay" value="<?php echo $user_database['expireDay']!=0?$user_database['expireDay']:'';?>" class="proexpiremonth">
						</div>
						
						<label for="cvvCvc">cvv / cvc</label>
						<input type="text" name="cvvCvc" id="cvvCvc" value="<?php echo $user_database['cvvCvc'];?>">
						
						<div class="encryptlock"></div>
						<div class="encryptlock-text">Payment secured with 256-bit level encryption</div>
						<input type="hidden" name="section" value="billingInfo" />
						<div class="profilesave right-sided" >
                                                    <img src="<?php echo base_url()?>assets/images/loading.gif" id="billingInfo-loading" align="left" class="save-loading">
							<input type="submit" value="" style="margin-top:12px;">
						</div>
					</form>
				</div>
			</div> <!--class="subscriptionwrap" -->
		</div> <!-- class="twodashcontent" -->
	</div> <!-- class="projectbackground" -->
<script>
    $(document).ready(function(){
            $("input[type=text],input[type=password]").blur(function(){
                if($(this).val()!=''){
                    $(this).removeClass("validationError");
                }
            });
            $('#emailPassword').submit(function(){
                current_emailAddress = "<?php echo $user_database['emailAddress']?>";
                if($("#emailAddress").val()!=current_emailAddress){
                    if(!confirm("Are you sure to change the email address?")){
                        return false;
                    }
                }else if( $("#password").val()==''){
                    $('#form-msgs1').show();
                    $("#form-msgs1").html('Please either enter new password or change email address to save.');
                    return false;
                }
                $('#form-msgs1').hide();
                $("#form-msgs1").html('');
                $("#emailPassword-loading").show();
                $.post($(this).attr('action'), $(this).serialize(), function( data ) {
                    $("#emailPassword-loading").hide();
                    if(!parseInt(data.error))
                            {
                                $("#form-msgs1").show();
                                $("#form-msgs1").removeClass("form-errors");
                                $("#form-msgs1").addClass("form-success");
                                $("#form-msgs1").html("Changes Saved");
                                current_emailAddress = $("#emailAddress").val();
                                if($("#emailAddress").val()!=current_emailAddress){
                                    window.location.reload();
                                }
                            }
                            else
                            {
                                $("#form-msgs1").show();
                                $("#form-msgs1").removeClass("form-success");
                                $("#form-msgs1").addClass("form-errors");
                                $.each(data.msg, function(key, val) {
                                        $('#form-msgs1').append(val);
                                        $('#'+key).addClass('validationError');
                                });
                            }
                }, 'json');
                return false;			
           });
            $('#companyInfo').submit(function(){
                $('#form-msgs2').hide();
                $("#form-msgs2").html('');
                $("#companyInfo-loading").show();
                $.post($(this).attr('action'), $(this).serialize(), function( data ) {
                $("#companyInfo-loading").hide();
                    if(!parseInt(data.error))
                            {
                                $("#form-msgs2").show();
                                $("#form-msgs2").removeClass("form-errors");
                                $("#form-msgs2").addClass("form-success");
                                $("#form-msgs2").html("Changes Saved");
                            }
                            else
                            {
                                $("#form-msgs2").show();
                                $("#form-msgs2").removeClass("form-success");
                                $("#form-msgs2").addClass("form-errors");
                                $.each(data.msg, function(key, val) {
                                    
                                        $('#form-msgs2').append(val);
                                        $('#'+key).addClass('validationError');
                                });
                            }
                }, 'json');
                return false;			
           });
            $('#userInfo').submit(function(){
            $("#form-msgs3").hide();
            $("#form-msgs3").html('');
            $("#userInfo-loading").show();
                $.post($(this).attr('action'), $(this).serialize(), function( data ) {
                    
                    $("#userInfo-loading").hide();
                    if(!parseInt(data.error))
                            {
                                $("#form-msgs3").show();
                                $("#form-msgs3").removeClass("form-errors");
                                $("#form-msgs3").addClass("form-success");
                                $("#form-msgs3").html("Changes Saved");
                            }
                            else
                            {
                                $("#form-msgs3").show();
                                $("#form-msgs3").removeClass("form-success");
                                $("#form-msgs3").addClass("form-errors");
                                $.each(data.msg, function(key, val) {
                                        $('#form-msgs3').append(val);
                                        $('#'+key).addClass('validationError');
                                });                            }
                }, 'json');
                return false;			
           });
            $('#billingInfo').submit(function(){
                $("#form-msgs4").hide();
                $("#form-msgs4").html('');
                $("#billingInfo-loading").show();
                $.post($(this).attr('action'), $(this).serialize(), function( data ) {
                    $("#billingInfo-loading").hide();
                    if(!parseInt(data.error))
                            {
                                $("#form-msgs4").show();
                                $("#form-msgs4").removeClass("form-errors");
                                $("#form-msgs4").addClass("form-success");
                                $("#form-msgs4").html("Changes Saved");
                            }
                            else
                            {
                                $("#form-msgs4").show();
                                $("#form-msgs4").removeClass("form-success");
                                $("#form-msgs4").addClass("form-errors");
                                $.each(data.msg, function(key, val) {
                                        $('#form-msgs4').append(val);
                                        $('#'+key).addClass('validationError');
                                });                            
                            }
                            
                }, 'json');
                return false;			
           });
           $("#delete-logo").click(function(){
                $('#form-msgs2').html('');
                if(!confirm("Are you sure to delete the Logo?")){
                    return false;
                }
                $('#companyLogo-loading').show();
                $.ajax({
                    url:'<?php echo base_url();?>users/deleteLogo',
                    type:'POST',
                    data:{id:'<?php echo $user_database['id']?>'},
                    success:function(data){
                       data = JSON.parse(data);
                       $('#companyLogo-loading').hide();
                       if(!parseInt(data.error)){
                           $("#form-msgs2").show();
                                   $("#form-msgs2").removeClass("form-errors");
                                   $("#form-msgs2").addClass("form-success");
                                     $("#delete-logo").hide();
                                     $('.uploadlogo-box').html('');
                                    $('#companyLogo').val('');

                       }else{
                           $("#form-msgs4").show();
                                   $("#form-msgs2").removeClass("form-success");
                                   $("#form-msgs2").addClass("form-errors");
                       }
                        $.each(data.msg, function(key, val) {
                             $('#form-msgs2').append(val+"<BR />");
                         });
                   }
                 
                });
           });
   });
   function closeAccount(){
        if(!confirm("Are you sure to close the account?")){
             return false;
         }
         $('#closeAccount-loading').show();
         $.ajax({
             url:'<?php echo base_url();?>users/closeAccount',
             type:'POST',
             data:{id:'<?php echo $user_database['id']?>'},
             success:function(data){
                data = JSON.parse(data);
                $('#closeAccount-loading').hide();
                if(!parseInt(data.error)){
                    
                            $("#closeAccount-msgs").removeClass("form-errors");
                            $("#closeAccount-msgs").addClass("form-success");
                            $("#closeAccount-msgs").show();
                            $.each(data.msg, function(key, val) {
                                
                                
                            $('#closeAccount-msgs').html(val+"<BR />");
                            setTimeout(window.location="/ranktracker",4000);
                  });
                }else{
                            $("#closeAccount-msgs").removeClass("form-success");
                            $("#closeAccount-msgs").addClass("form-errors");
                            $("#closeAccount-msgs").show();
                            $.each(data.msg, function(key, val) {
                                $('#closeAccount-msgs').html(val+"<BR />");
                            });
                            
                }
                 
            }
         });
   }
   function uploadLogo(){
        $('#companyLogo-loading').show();
        $('#uploadLogo').attr('target','upload-companylogo');
        $('#uploadLogo').submit();
   }
   function showmessage(error,msg){
       
        $('#companyLogo-loading').hide();
       if(error==1){
           $("#form-msgs2").show();
            $("#form-msgs2").removeClass("form-success");
            $("#form-msgs2").addClass("form-errors");
            $("#form-msgs2").html(msg);
       }else if(error==0){
            $("#form-msgs2").show();
            $("#form-msgs2").removeClass("form-errors");
            $("#form-msgs2").addClass("form-success");
            $("#form-msgs2").html("File Uploaded Successfully");
           thumb="<?php echo base_url(); ?>uploads/logos/thumbnails/"+msg;
            $("#delete-logo").show();
           $('.uploadlogo-box').html('<img src="'+thumb+'" >');
           $('#companyLogo').val(msg);
       }
       
       
   }
   
       
</script>
            <?php $this->load->view("home/common/footer") ?>