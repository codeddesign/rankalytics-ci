<div class="userviewline">
    <div class="userviewtitle" id="user-full-name"><?php echo $details['details']['firstName'] . " " . $details['details']['lastName']; ?></div>
    <?php if ($curuser['userRole'] == "disabled") { ?>
        <div class="userviewtitle" id="user-full-name"><select name="upgradeUser" id="upgradeUser" onchange="upgradeUser('<?php echo $details['details']['id']; ?>');">
                <option>Upgrade User...</option>
                <option value="30">Upgrade for 30 Days</option>
                <option value="60">Upgrade for 60 Days</option>
                <option value="90">Upgrade for 90 Days</option>
            </select></div>
        <?php
        $action = base_url() . 'users/deleteAccountByAdmin';

    } else {
        $action = base_url() . 'users/deleteAccountByUser';
    } ?>
    <div class="admindeleteaccount" id="<?php echo $details['details']['id']; ?>"></div>
</div>
<div class="userviewextras">
    <div class="userviewaccounttopleft" id="usre-account-level">Account Level: <?php echo strtoupper($details['details']['accountType']); ?></div>
    <div class="userviewaccounttopright" id="user-join-date">Joined: <?php echo $details['details']['createdOn']; ?></div>
    <div class="userviewaccountbottomleft" id="user-email">E-mail: <?php echo $details['details']['emailAddress']; ?></div>
    <div class="userviewaccountbottomright" id="user-phone">Phone: <?php echo $details['details']['phoneNumber']; ?></div>
    <div style="clear:both;">
        <?php
        if (!is_array($subscriptions)) {
            echo 'User has no subscriptions';
        } else {
            // patterns:
            $rowPattern = '<tr><td>%s</td><td>%s</td><td>%d</td><td%s>%s</td><td>%s</td></tr>';
            $opPattern = '<input type="button" class="approveSub" value="Approve %s" id="%s">';
            $tdStatusId = ' id="status_%s"';

            // building:
            $data = '<table border="1"><tr><th>Service</th><th>Plan</th><th>Months</th><th>Status</th><th>Operation</th></tr>';
            foreach ($subscriptions as $s_no => $subscription) {
                if($subscription['status'] == 'pending') {
                    $operation = sprintf($opPattern, $subscription['operation'], $subscription['id']);
                    $tdId = sprintf($tdStatusId, $subscription['id']);
                } else {
                    $operation = $tdId = '';
                }

                $data .= sprintf($rowPattern, $subscription['service'], $subscription['plan'], $subscription['months'], $tdId, strtoupper($subscription['status']), $operation);
            }
            $data .= '<table>';

            // show result:
            echo $data;
        }
        ?>
    </div>
</div>
<script>
    $(".admindeleteaccount").click(function () {
        var ID = this.id;
        if (confirm("Are you sure. you would like to close this account?")) {
            $('#cancelsubscription-loading').hide();
            $.ajax({
                url: '<?php echo $action;?>',
                type: 'POST',
                data: {id: ID, by: "admin"},
                success: function (data) {
                    data = JSON.parse(data);
                    //$('#cancelsubscription-loading').hide();

                    if (!parseInt(data.error)) {
                        console.log(data.msg);
                        $("li#" + ID).hide();
                        $(".useraccountslist li").first().click();
                        //window.location.reload();
                    } else {
                        console.log(data.error);
                    }
                }
            });
        }
    });

    $('.approveSub').on('click', function() {
        var btn = $(this), subId = btn.attr('id'), tdId = $('#status_' + subId);

        $.ajax({
            url: '/users/approvesubscription/' + subId,
            dataType: 'JSON',
            success: function(resp) {
                btn.remove();
                tdId.html('APPROVED');
            }
        });
    });
</script>