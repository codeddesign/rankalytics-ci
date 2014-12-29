<?php
if(1 == 2) :

$query = $this->db->query('SELECT accountType FROM users where id="' . $user['id'] . '"');
$acctyep = $query->result_array();
$type = $acctyep['0']['accountType'];
if ($type == "") {
    $type = "free";
}
$plan = $this->config->item('subscription_plans');
$limit = $plan[$type]['keywordsAllowed'];


$query = $this->pgsql->query('SELECT * FROM tbl_project where "userId"=\'' . $user['id'] . '\'');
$project_id = $query->result_array();
$id_list = array();
foreach ($project_id as $id) {
    $id_list[] = "'" . $id['id'] . "'";
}

if(count($id_list) > 0) {
$key_query = 'SELECT  keyword FROM tbl_project_keywords as pk join project_keyword_relation as pkr on pkr.keyword_id=pk.unique_id where pkr.project_id in (  ' . implode(',', $id_list) . ') ';
$query = $this->pgsql->query($key_query);
$keywordlist = $query->result_array();
$total = count($keywordlist);
} else {
    $total = 0;
}
//print_r($user);

?>

<div class="settingsbluetop">
    <div class="subscription-accountlevel" style="margin-top:29px;margin-left:78px;width:193px;">ACCOUNT LEVEL: <?php 
    echo (isset($type)?$type:"");?></div>
    <div class="subscription-keywordlimit" style="margin-top:29px;width:193px;">
    	KEYWORD LIMIT: <span> <?php echo $limit ?></span>
    	USED KEYWORDS: <span><?php echo $total; ?></span>
    </div>
    <?php if(isset($type) && ($type=='pro' || $type=='enterprise') && isset($user['subscription']['id'])){ ?>
    <div class="subscription-billingrenewal" style="margin-top:29px;">NEXT PAYMENT: <span><?php echo date('d-m-Y',$user['subscription']['next_capture_at'])?></span> <?php echo '<a href="javascript:void(0);" onclick="cancel_subscription();">cancel subscription</a>'?></div>
    <?php } ?>
</div>

<script>
function cancel_subscription(){
    if(confirm(" you sure want to cancel your subscription?")){
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
<?php endif; ?>