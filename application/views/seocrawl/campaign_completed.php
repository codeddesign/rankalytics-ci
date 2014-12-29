<?php
list($temp_date, $temp_hour) = explode(' ', $c_info['added_at']);
?>
<li class="completed" data-ctitle="<?= strtolower($c_info['campaign_name']); ?>">
    <div class="crawlstatusicon-green"></div>
    <div class="crawlstatus-textwrap">
        <div class="title"><?= $c_info['campaign_name']; ?></div>
        <div class="sub"><?= $c_info['domain_url']; ?></div>
    </div>
    <div class="crawlstatus-postdate"><?= $temp_date; ?></div>
    <div class="crawlstatus-line"></div>
    <div class="crawlstatus-crawling">completed</div>
    <div class="crawlstatus-line"></div>
    <div class="crawlstatus-pagesfound"><?= ($c_info['pages_number']=='') ? '..' : number_format($c_info['pages_number']);?> pages found</div>
    <div class="crawlstatus-line"></div>
    <a href="<?= $c_info['dropbox'];?>" target="_blank"><div class="crawlstatus-download" style="cursor: pointer;"></div></a>
</li>