<ul class="useraccountslist">
    <?php
    $isfirst=1;
    foreach($users as $user){ 
        if($isfirst==1){
            $firstId =  $user['id'] ;
            $isfirst=0;
        }
        
        ?>
        <li id="<?php echo $user['id'] ?>">
                <div class="useraccountsname"><?php echo  (($user['firstName']=='' || $user['firstName']==null) && ($user['lastName']=='' || $user['lastName']==null))?$user['emailAddress']:$user['firstName']." ". $user['lastName'] ;?></div>
                <div class="useraccountslevel">ACCOUNT LEVEL: <?php echo (isset($user['accountType'])&& $user['accountType']!='')?$user['accountType']:"FREE";?></div>
                <div class="useraccountsjoined">JOINED: <?php echo $user['createdOn'] ?></div>
        </li>
    <?php } ?>
</ul>
<script>
$(document).ready(function(){
    
    $(".useraccountslist li").first().click();
    
});
    
</script>
