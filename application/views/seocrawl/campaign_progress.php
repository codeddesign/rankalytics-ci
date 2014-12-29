<?php
list($temp_date, $temp_hour) = explode(' ', $c_info['added_at']);
?>
<li data-ctitle="<?= strtolower($c_info['campaign_name']); ?>">
    <div class="crawlstatusicon-red"></div>
    <div class="crawlstatus-textwrap">
        <div class="title"><?= $c_info['campaign_name']; ?></div>
        <div class="sub"><?= $c_info['domain_url']; ?></div>
    </div>
    <div class="crawlstatus-postdate"><?= $temp_date; ?></div>
    <div class="crawlstatus-line"></div>
    <div class="crawlstatus-crawling">crawling…</div>
    <div class="crawlstatus-line"></div>
    <div class="crawlstatus-pagesfound">...</div>
    <div class="crawlstatus-line"></div>
    <div class="crawlstatus-nodownload" style="cursor:progress" title="crawling.."></div>
</li>