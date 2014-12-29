<?php
$data['current'] = "dashboard";
$this->load->view("include/header", $data);
?>
<link href="<?php echo base_url(); ?>assets/pagination.css" rel="stylesheet" type="text/css"/>

<div class="yellowtopline"></div>
<div class="topinfobar">
    <div class="toptitlebar">ADMIN ACCOUNT CONTROL</div>
</div>
<div class="projectbackground">
    <div class="reportsdashcontent">
        <?php if (isset($notAdmin) && $notAdmin == 1) { // user is not an admin
            echo $error;
        } else { // user is admin
            ?>
            <div class="useraccountswrap">
                <div class="useraccountsbox">
                    <form id="quicksearch" action="<?php echo base_url() ?>users/admin">
                        <div class="userviewaccountstitle">USER ACCOUNTS</div>
                        <input name="searchString" id="searchString" class="userviewsearchform" placeholder="search for user account..." onkeypress="" style="width: 303px;">
                        <input type="hidden" name="isAjax" id="isAjax" value="0"/>
                    </form>
                </div>

                <div id="userlist">
                    <?php echo $this->load->view("ranktracker/admin/userlist", array("usres" => $users)); ?>
                </div>
                <div id="pagination-admin"><?php echo $this->pagination->create_links(); ?></div>
            </div>
            <div class="userviewwrap">
                <div id="user-details"></div>
            </div>
        <?php } // if user is admin?>
    </div>
</div>

<script>
    function upgradeUser(userId) {
        var upgradeFor = $("#upgradeUser").val();
        if (upgradeFor == '') {
            return false;
        }

        $(".overlay").show();

        $.ajax({
            url: '<?php echo base_url();?>users/upgradeUser',
            type: 'POST',
            data: {id: userId, upgradeFor: upgradeFor},
            success: function (response) {

                response = JSON.parse(response);
                $(".overlay").hide();
                if (response.error == 0) {
                    console.log('b ->' + response.msg);
                    window.location.reload();
                } else {
                    console.log('c ->' + response.msg);
                }
            }
        });
    }

    var onuserliclick = function () {
        var userId = this.id;

        $("ul.useraccountslist li").removeClass("active");
        $("#" + userId).addClass("active");
        $(".overlay").show();
        $.ajax({
            url: '<?php echo base_url();?>users/getUserDetails',
            type: 'POST',
            data: {id: userId},
            success: function (response) {
                $(".overlay").hide();
                $("#user-details").html(response.details);
            }
        });
    };

    $(".useraccountslist li").click(onuserliclick);
    $(document).ready(function () {
        var quickSearch = $("#quicksearch"), quicksAction = quickSearch.attr("action");

        $(".pagination a").click(function () {
            quicksAction.attr("action", $(this).attr("href")).submit();
            return false;
        });

        quickSearch.submit(function (e) {
            e.preventDefault();

            $("#isAjax").val(1);
            $(".overlay").show();
            $.post($(this).attr('action'), $(this).serialize(), function (data) {
                if (parseInt(data.error)) {
                    //console.log("in error")
                }
                else {
                    $(".overlay").hide();
                    $("#userlist").html(data.html);
                    $(".useraccountslist li").click(onuserliclick).first().click();
                    $("#quicksearch").attr("action", quicksAction);

                    $("#pagination-admin").html(data.pagination);
                    $(".pagination a").click(function () {
                        $("#quicksearch").attr("action", $(this).attr("href")).submit();
                        return false;
                    });
                }

            }, 'json');
            return false;
        });

    });
</script>
<style>
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 99999;
        background-color: rgba(0, 0, 0, 0.5); /*dim the background*/
        display: none;
    }
</style>
<div class="overlay">
    <div id="spinner"></div>
</div>
</body>
</html>