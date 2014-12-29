<?php 

$data['current']="dashboard";
$this->load->view("include/header",$data); ?>
<!--script src="<?php echo base_url();?>assets/js/modernizer-custom.js" type="text/javascript"></script-->
<link  href="<?php echo base_url();?>assets/pagination.css" rel="stylesheet" type="text/css" />		

<style>
    .userviewsearchform {
    background: none repeat scroll 0 0 #FFFFFF !important;
    border: 1px solid #E6E6E6!important;
    color: #888888!important;
    float: left!important;
    height: 32px!important;
    margin-left: 43px!important;
    margin-top: 11px!important;
    padding: 0 12px!important;
    width: 343px!important;
}
#upgradeUser{
    display: none;
}
.admindeleteaccount{
    margin-top: -6px; 
}

 .overlay img{
    left: 50%;
    position: relative;
    top: 50%;
}
.overlay{
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	 z-index: 99999;
	background-color: rgba(0,0,0,0.5); /*dim the background*/
	display: none;  
}
</style>
 <div class="yellowtopline"></div>

<div class="bodywrap">
	
	<div class="topinfobar">
		<a href="#" onclick="toggle_visibility('adduserpopup');">
		<div class="weathericon">
			<img src="<?php echo base_url();?>assets/images/weather/sun.png">
		</div>
		<div class="weathertext">NEW USER ACCOUNT</div>
		</a>
		
<!-- toggle seo weather -->
<script type="text/javascript">
<!--
    function toggle_visibility(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'block')
          e.style.display = 'none';
       else
          e.style.display = 'block';
    }
//-->
  function userSubmit()
        {
            $('#reg-error').html(''); // Empty reg error div
            $('#reg-error').css('display','none'); // hiding reg error div
            
            

            firstName= $('#user').val().trim();
            password = $('#pass').val().trim();
            emailAddress=$('#email').val().trim();
           
            
            val_error=0;
            // checking/validating fields for values 
            if(firstName==''){
                $('#user').addClass('validationError');
                val_error++;
            }
            
            if(emailAddress==''){
                $('#email').addClass('validationError');
                val_error++;
            }
            if(password==''){
                $('#pass').addClass('validationError');
                val_error++;
            }
           
            if(val_error>=1){
                
                //$('#errors').html('Please correct the errors below');
                $('#reg-error').css('display','block');
                $('#reg-error').html('Error! Please correct the errors below');
                return false;
            }
            else if(!isValidEmailAddress(emailAddress))
            {
                $('#email').addClass('validationError');
                //$('#errors').html('Please enter valid email address');
                $('#reg-error').css('display','block');
                $('#reg-error').html('Error! Please enter valid email address');
                return false;
            }
             $(".overlay").show();
            // if no error user data gets submitted 
            $.ajax({ 
                url: "<?php echo base_url()?>users/addsubuser",
                dataType: "json",
                type: "POST",
                data: {
                        username              : firstName,
                        emailAddress           : emailAddress,
                        password               : password
                       
                },
            success: function( data )
            {
                  $(".overlay").hide();
                 if(!parseInt(data.error))
                    {
                        window.location="<?php echo base_url()?>"+data.redirect;
                    }
                    else
                    {
                         
                        $('#errors').html(data.msg);
                        $.each(data.ids, function(key, val)
                        {
                          if(val=='success') {
                               $('#user').val("");
                               $('#pass').val("");
                               $('#email').val("");
                               $('#reg-error').css('display','block');
                               $('#reg-error').css('background-color','green');
                               $('#reg-error').append('User Has Been Added Successfully ');
                             
                              return;
                          } 
                         if(val=='emailAddressExists')
                            {
                                $('#reg-error').css('display','block');
                                $('#reg-error').append('Error! Email address already exists...');
                                //$('#emailAddress').css('background', '#F2DEDE');
                                $('#emailAddress').addClass('validationError');
                            }else if(val=='userNameExists'){
                                $('#reg-error').css('display','block');
                                $('#reg-error').append('Error! Username already exists...');
                                //$('#emailAddress').css('background', '#F2DEDE');
                                $('#emailAddress').addClass('validationError');
                            }
                            else
                            {
                                $('#reg-error').html('Error! Please correct the errors below');
                                $('#'+val).addClass('validationError');
                            }
                        });
                    }
                }
            });
        }
        function isValidEmailAddress(emailAddress)
        {
            var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
            return pattern.test(emailAddress);
        };
</script>
<style>
    #reg-error {
    background: none repeat scroll 0 0 rgb(238, 97, 60);
    color: rgb(255, 255, 255);
    display: none;
    font-family: "brandon-grotesque",sans-serif;
    height: 30px;
   
    padding: 15px;
    margin-top: 57px;
     text-align: center;
    width: 340px
}
</style>
<!-- end toggle -->

<!-- seo weather popup design -->
<div id="adduserpopup">
	<div class="adduserpopup-top"></div>
     
            
	<div class="adduserpopup-bg">
            
		<div class="adduserpopup-title">NEW USER ACCOUNT</div>
                <div id="reg-error"></div> <div id="reg-error"></div>
		<form name="input" action="#" method="get" class="adduserpopup-form">
			<input class="overlayurl" type="text" id="user" name="user" placeholder="username">
			<input class="overlayurl" type="passwort" id="pass" name="pass" placeholder="password">
			<input class="overlayurl" type="text" id="email" name="email" placeholder="email">
                       
			<input type="button"  onclick="javascript:userSubmit()" value="Submit" class="addusersubmit">
		</form>
	</div>
</div>
<!-- end seo weather design -->			
	
		<div class="toptitlebar">USER ACCOUNTS</div>
	</div>
	<div class="projectbackground">
		<div class="reportsdashcontent">
			 <?php if(isset($notpro) && $notpro==1){ // user is not a pro 
                          echo $error;  
                        } else{ // user is pro ?>
			<div class="useraccountswrap">
				<div class="useraccountsbox">
                                    
                                    <form id="quicksearch" action="<?php echo base_url()?>ranktracker/users">
                                        <div class="userviewaccountstitle">USER ACCOUNT SELECT</div>
                                        <!--input name="searchString" id="searchString"class="userviewsearchform" placeholder="search users..." onkeypress=""-->
                                       <input name="searchString" id="searchString" class="userviewsearchform" onkeypress="" placeholder="search for user account...">
                                        
                                        <input type="hidden" name="isAjax" id="isAjax" value="0" />
                                        </form>
					
					
				</div>
				     <div id="userlist">
                                      
                            <?php echo $this->load->view("dashboard/userlist",array("usres"=>$users));?>
                            </div>
                            <div id="pagination-admin"><?php echo $this->pagination->create_links();?></div>
                        
			</div>
			
			<div class="userviewwrap">
                                <div id="user-details">
                                    
                                </div>
                                <div id="user-history">
				
                                </div>
			</div>
                     <?php } // if user is pro?>
		</div>
	</div>
	<?php $this->load->view("dashboard/common/footer") ?>
</div>
<script >
   function upgradeUser(userId){
     upgradeFor=$("#upgradeUser").val();   
     if(upgradeFor==''){
         return false;
     }
        $.ajax({
          url:'<?php echo base_url();?>users/upgradeUser',
          type:'POST',
          data:{id:userId,upgradeFor:upgradeFor},
          success:function(response){
                response = JSON.parse(response);
                if(response.error==0){
                    alert(response.msg);
                   window.location.reload();
                }else{
                    alert(response.msg);
                }
          }
       });
     
    }
var onuserliclick = function(){
    userId= this.id;
    $("ul.useraccountslist li").removeClass("active");
    $("#"+userId).addClass("active");
    $(".overlay").show();
    $.ajax({
       url:'<?php echo base_url();?>users/getUserDetails',
       type:'POST',
       data:{id:userId},
       success:function(response){
            response = JSON.parse(response);
            //$('#cancelsubscription-loading').hide();
            $("#user-details").html(response.details);
            $("#user-history").html(response.history);
            $(".overlay").hide();
       }
    });
    }
    
        $(document).ready(function(){
        
        
        $("input[type=text],input[type=password]").blur(function()
     {
         if($(this).val()!='')
         {
             $(this).removeClass("validationError");
         }
     });
                        $(".useraccountslist li").click(onuserliclick);
                        $(".useraccountslist li").first().click();
                         quicksearchActionOriginal = $("#quicksearch").attr("action");
                         
                         $(".pagination a").click(function(){
                            
                             $(".overlay").show();
                            $("#quicksearch").attr("action",$(this).attr("href"));
                            $("#quicksearch").submit();
                             $(".overlay").hide();
                             return false;
                         });
                         
                         $('#quicksearch').submit(function(e){
                             
                            e.preventDefault();
                           
                            $("#isAjax").val(1);
//                            $("#form-loading").show();
                            //$("#rank_data_outer .overlay").show();
                            $.post($(this).attr('action'), $(this).serialize(), function( data ) {
                                //$("#form-loading").hide();
                              //  $("#rank_data_outer .overlay").hide();
                              //alert(data);
                                if(parseInt(data.error))
                                        {
                                            //alert("in error")
                                          
                                        }
                                        else
                                        {
                                           // alert(data.html);
                                            $("#userlist").html( data.html);
                                            $(".useraccountslist li").click(onuserliclick);
                                            $(".useraccountslist li").first().click();
                                            $("#quicksearch").attr("action",quicksearchActionOriginal);
                                            
                                            $("#pagination-admin").html( data.pagination);
                                             $(".pagination a").click(function(){
                                                $("#quicksearch").attr("action",$(this).attr("href"));
                                                $("#quicksearch").submit();
                                                $(".overlay").hide();
                                                return false;
                                             });
                                        }

                            }, 'json');
                           
                            return false;
                            
                       });
                       
                   });
                   
function doSearch(e){

    if(e.keyCode === 13){
        
    if($("#searchString").val()==''){
        return false;
    }
     $(".overlay").show();
            $.ajax({
       url:'<?php echo base_url();?>users/getUsersFromSearch',
       type:'POST',
       data:{searchString:userId},
       success:function(response){
            response = JSON.parse(response);
            //$('#cancelsubscription-loading').hide();
            $("#user-details").html(response.details);
            $("#user-history").html(response.history);
             $(".overlay").hide();
       }
    });
        }
}
</script>
 <div class="overlay"><div id="spinner"></div><!--img src="<?php echo base_url();?>assets/images/loading.gif" style="margin:10px auto"--></div>      
</body>
</html>