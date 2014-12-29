<?php
if (empty($keywords_array)) {
    ?>
    <div class="keywordmainback">No keywords have been added to this campaign.</div>
<?php
}

foreach ($keywords_array as $key => $value):

    $unique_id = $value['unique_id'];
    $domain_url = $value['domain_url'];
    $date = date("Y-m-d");
    $date7 = date('Y-m-d', strtotime('-7 days'));
    $date28 = date('Y-m-d', strtotime('-28 days'));

    $info = $this->crawled_sites_model->getCrawledInfo(array('keyword_id' => $unique_id, 'crawled_date' => $date, 'host'=>$domain_url));
    $info7 = $this->crawled_sites_model->getCrawledInfo(array('keyword_id' => $unique_id, 'crawled_date' => $date7, 'host'=>$domain_url));
    $info28 = $this->crawled_sites_model->getCrawledInfo(array('keyword_id' => $unique_id, 'crawled_date' => $date28, 'host'=>$domain_url));
    $keyword_info = $this->project_keywords_adwordinfo->get_latest_keywordinfo_by_keywordid($unique_id);

    switch ($info['rank']) {
        case 1:
            $percent = 0.30;
            break;
        case 2:
            $percent = 0.16;
            break;
        case 3:
            $percent = 0.10;
            break;
        case 4:
            $percent = 0.08;
            break;
        case 5:
            $percent = 0.06;
            break;
        case 6:
            $percent = 0.04;
            break;
        case 7:
            $percent = 0.03;
            break;
        case 8:
            $percent = 0.03;
            break;
        case 9:
            $percent = 0.02;
            break;
        case 10:
            $percent = 0.02;
            break;
        case 11:
            $percent = 0.01;
            break;
        case 12:
            $percent = 0.007;
            break;
        case 13:
            $percent = 0.007;
            break;
        case 14:
            $percent = 0.006;
            break;
        case 15:
            $percent = 0.004;
            break;
        case 16:
            $percent = 0.0035;
            break;
        case 17:
            $percent = 0.0033;
            break;
        case 18:
            $percent = 0.0027;
            break;
        case 19:
            $percent = 0.0027;
            break;
        case 20:
            $percent = 0.0029;
            break;
        case 21:
            $percent = 0.001;
            break;
        case 22:
            $percent = 0.001;
            break;
        case 23:
            $percent = 0.0008;
            break;
        case 24:
            $percent = 0.0006;
            break;
        case 25:
            $percent = 0.0006;
            break;
        case 26:
            $percent = 0.0005;
            break;
        case 27:
            $percent = 0.0005;
            break;
        case 28:
            $percent = 0.0005;
            break;
        case 29:
            $percent = 0.0004;
            break;
        case 30:
            $percent = 0.0006;
            break;
        default:
            $percent = 0;


    }
    if ($percent == 0) {
        $ERT = "Low Rank";

    } else {
        $ERT = $keyword_info['volume'] * $percent;
    }

    $preview_link = '/ranktracker/preview?word='.$value['keyword'].'&domain='.$info['site_url'];
    $preview_target = 'target="_blank"';
    if(trim($info['site_url']) == '') {
        $preview_link = '#';
        $preview_target = '';
    }
    ?>
    <!-- start individual keyword list -->
    <div class="keywordmainback" id="<?php echo $value['unique_id'] . "-" . $value['project_id']; ?>">
        <div class="rankbgblock" style="display:none;">
            <input type="checkbox" name="#" value="#">
        </div>
        <div class="rankbgblock">
            <div class="campaigndelete" onclick="delete_keyword('<?php echo $value['unique_id'] ?>','<?php echo $value['project_id']; ?>','<?php echo addslashes($value['keyword']); ?>')"
                 style="margin-top:27px;"></div>
        </div>
        <a href="<?= $preview_link; ?>" <?= $preview_target;?>>
            <div class="exactrankicon"></div>
        </a>

        <div class="rankiconsep" style="margin-left:17px;"></div>
        <div class="keyword-keyword" style="width:370px;"><?php echo $value['keyword'] ?></div>
        <div class="keyword-position">
            <div class="keyword-positiontext"><?php echo isset($info['rank']) ? $info['rank'] : ' - ' ?></div>
        </div>
        <div class="keyword-estimatedtraffic"><?php echo isset($info7['rank']) ? $info7['rank'] : ' - ' ?></div>
        <div class="keyword-estimatedtraffic"><?php echo isset($info28['rank']) ? $info28['rank'] : ' - ' ?></div>
        <div class="keyword-estimatedtraffic"><?php echo isset($info['google_com_rank']) ? $info['google_com_rank'] : ' - ' ?></div>
        <div class="keyword-estimatedtraffic"><?php echo isset($info['rank_local']) ? $info['rank_local'] : ' - ' ?></div>
        <div class="keyword-estimatedtraffic"><?php echo isset($info['pg_rank_news']) ? $info['pg_rank_news'] : ' - ' ?></div>
        <div class="keyword-estimatedtraffic"><?php echo isset($info['pg_rank_video']) ? $info['pg_rank_video'] : ' - ' ?></div>
        <div class="keyword-estimatedtraffic"><?php echo isset($info['pg_rank_shop']) ? $info['pg_rank_shop'] : ' - ' ?></div>


        <div class="keyword-estimatedtraffic"><?php echo $ERT; ?></div>
        <div
            class="keyword-competition"><?php echo isset($keyword_info['competition']) ? "<img class='comp' src=" . base_url() . "assets/images/competition-" . ltrim(round($keyword_info['competition'], 1), '0.') . ".jpg alt='" . round($keyword_info['competition'], 1) . "'>" : '<img class="comp" src="http://rankalytics.com/assets/images/competition-.jpg">' ?></div>
        <div class="keyword-pagemeta">
            <div class="keyword-pagemetawrap">
                <div class="keyword-pagemetatop"><?php echo isset($info['title']) ? $info['title'] : ' - ' ?></div>
                <a href="<?php echo isset($info['site_url']) ? $info['site_url'] : '#' ?>"
                   class="keyword-pagemetabottom"><?php echo isset($info['site_url']) ? $info['site_url'] : ' - ' ?></a>
            </div>
        </div>
        <?php
        $out_kei = ' - ';
        if (isset($keyword_info['volume']) && is_numeric($keyword_info['volume']) && $keyword_info['volume'] != 0)
            $out_kei = number_format(round($info['total_records'] / $keyword_info['volume'], 2), 0, '.', ',');

        $competingpages = ' - ';
        if ($info['total_records'] != 0 && is_numeric($info['total_records']))
            $competingpages = number_format(round($info['total_records'], 2), 0, '.', ',');

        $search_vol = (isset($keyword_info['volume']) && is_numeric($keyword_info['volume'])) ? number_format($keyword_info['volume'], 0, '.', ',') : ' - ';
        ?>
        <div class="keyword-kei"><?= $out_kei; ?></div>
        <?php
        /*
        [4/28/2014 12:20:27 PM] Sudhir: i think KEI = total_records/total_search
        [4/28/2014 12:20:46 PM] Coded Design, Inc.: comp. pages / search volume
                */
        ?>
        <div class="keyword-competingpages"><?= $competingpages; ?></div>
        <div class="keyword-searchvol"><?= $search_vol; ?></div>
        <div class="keyword-cpc"><?php echo isset($keyword_info['CPC']) ? "&euro;" . round($keyword_info['CPC'] / 1000000, 3) : ' - '; ?></div>
    </div>
    <!-- end individual keyword list -->
<?php endforeach ?>

<style>
    .comp {
        margin: 30px;
    }
</style>