<?php 
if(!isset($thirdParameter)){
    $thirdParameter='';
}
if(isset($js_function) && $js_function!=''){
    ?>
<script language="javascript">
    window.parent.<?php echo $js_function;?>("<?php echo $isError;?>","<?php echo $msg;?>"<?php echo $thirdParameter;?>);
</script>
<?php } else{ ?>
<script language="javascript">
    window.parent.showmessage("<?php echo $isError;?>","<?php echo $msg;?>"<?php echo $thirdParameter;?>);
</script>
<?php } ?>
