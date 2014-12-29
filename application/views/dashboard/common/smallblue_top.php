<?php

$query = $this->pgsql->query('SELECT * FROM tbl_project where "userId"=\'' . $user['id'] . '\'');
$project_id = $query->result_array();
$id_list = array();
foreach ($project_id as $id) {
    $id_list[] = "'".$id['id']."'";
}

if (count($id_list) > 0) {
    $query = $this->pgsql->query('SELECT DISTINCT keyword FROM tbl_project_keywords where project_id in ( '.implode(',', $id_list).') ');
    $keywordlist = $query->result_array();
    $total = count($keywordlist);
} else {
    $total = 0;
}

?>

<div class="settingsbluetop">
    <div class="subscription-accountlevel" style="margin-left:74px;">ACCOUNT LEVEL: <?php 
    echo (isset($user['level'])?$user['level']:"");?></div>
    <div class="subscription-keywordlimit">KEYWORD LIMIT: <span> <?php if($user['level']=='PRO'){ echo "Unlimited"; } else{ echo "100";} ?></span></div>
    <?php if(isset($type) && ($type=='pro' || $type=='enterprise') && isset($user['subscription']['id'])){ ?>
    <div class="subscription-billingrenewal" style="margin-top:29px;">NEXT PAYMENT: <span><?php echo date('d-m-Y',$user['subscription']['next_capture_at'])?></span> <?php echo '<a href="javascript:void(0);" onclick="cancel_subscription();">cancel subscription</a>'?></div>
    <?php } ?>
</div>
<script>
function cancel_subscription(){
    if(confirm("Are you sure want to cancel your subscription?")){
        //$('#cancelsubscription-loading').hide();
        $.ajax({
                url:'<?php echo base_url();?>users/cancel_subscription',
                type:'POST',
                data:{id:'<?php echo $user['id']?>'},
                success:function(data){
                    data = JSON.parse(data);
                   //$('#cancelsubscription-loading').hide();
                   if(!parseInt(data.error)){
                    alert(data.error);
                   }else{
                    window.reload()   ;
                   }
               }
            });
    }
}
</script>