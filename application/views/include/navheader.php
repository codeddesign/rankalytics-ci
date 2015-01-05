<?php
$this->load->library('session');
$user = $this->session->userdata('logged_in');
if (isset($user['0']['id']) && $user['0']['id'] >= 1)
{
?>

    <ul>
        <!-- accountpopup -->
        <div id="accountpopup" class="link_toggle">
            <div class="accountpopup-text accountpopup-top"><a href="<?php echo base_url(); ?>users/settings" style="color:#000">SETTINGS</a></div>
            <div class="accountpopup-text"><a href="<?php echo base_url(); ?>users/search" style="color:#000">USERS</a></div>
            <div class="accountpopup-text"><a href="<?php echo base_url(); ?>users/logout" style="color:#000">LOGOUT</a></div>
        </div>

        <!-- modules popup -->
        <div id="modulespopup" class="link_toggle">
            <div class="largeaccountpopup-text accountpopup-top">
                <a href="<?php echo base_url(); ?>ranktracker/dashboard" style="color:#000">RANK TRACKER</a>
            </div>
            <div class="largeaccountpopup-text">
                <a href="<?php echo base_url(); ?>seocrawl/dashboard" style="color:#000">SEO CRAWL</a>
            </div>
            <div class="largeaccountpopup-text">
                <a href="<?php echo base_url(); ?>roadmap" target="_blank" style="color:#000">ROADMAP</a>
            </div>
        </div>


        <a style="cursor:pointer;" onclick="toggle_visibility('modulespopup');">
            <li>Modules
                <div class="headerarrow"></div>
            </li>
        </a>

        <a style="cursor:pointer;" onclick="toggle_visibility('accountpopup');">
            <li>Account
                <div class="headerarrow"></div>
            </li>
        </a>

        <a href="/contactus">
            <li <?php echo($current == "support" ? 'class="active"' : ''); ?> >Support</li>
        </a>
        <a href="<?php echo base_url(); ?>ranktracker/reports">
            <li <?php echo($current == "reports" ? 'class="active"' : ''); ?> >Reports</li>
        </a>
        <a href="<?php echo base_url(); ?>ranktracker/dashboard">
            <li <?php echo($current == "dashboard" ? 'class="active"' : ''); ?>>Dashboard</li>
        </a>
    </ul>


<?php } else { ?>
    <ul>
        <a href='#' onclick='loginoverlay()'>
            <li>Login</li>
        </a>
        <a href="/contactus" target="_blank">
            <li <?php echo($current == "support" ? 'class="active"' : ''); ?> >Support</li>
        </a>
    </ul>
    <?php

    include_once("application/views/dashboard/common/common_login.php");
}
?>
