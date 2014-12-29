<ul class="useraccountslist">
    <?php
    $isfirst = 1;
    foreach ($users as $user) {
        if ($isfirst == 1) {
            $firstId = $user['id'];
            $isfirst = 0;
        }

        ?>
        <li id="<?= $user['id'] ?>">
            <div
                class="useraccountsname"><?= (($user['firstName'] == '' || $user['firstName'] == null) && ($user['lastName'] == '' || $user['lastName'] == null)) ? $user['emailAddress'] : $user['firstName'] . " " . $user['lastName']; ?></div>
            <div class="useraccountslevel">User Role: <?= (isset($user['userRole']) && $user['userType'] != '') ? strtoupper($user['userRole']) : "NONE"; ?></div>
            <div class="useraccountsjoined">Joined: <?= $user['createdOn']; ?></div>
        </li>
    <?php } ?>
</ul>
<script>
    $(document).ready(function () {
        $(".useraccountslist li").first().click();
    });

</script>
