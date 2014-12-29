<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
Hello <?php echo $firstName." ".$lastName; ?>,<br />
Thank you for registering with us. Please click on the link below to verify your email address.<br />
<a href="<?php echo base_url() ?>users/verifyEmail/<?echo $userId?>/<?php echo $verificationCode;?>"><?php echo base_url() ?>users/verifyEmail/<?echo $userId?>/<?php echo $verificationCode;?></a>

Thanks,
Ranking Analysis

