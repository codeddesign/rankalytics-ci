<?php
if (!is_array($all_data)) {
    ?>
    <div class="keywordmainback">No active campaign's. Please create a new campaign now.</div>
<?php
} else foreach ($all_data as $key => $value):
    $keyword_id = isset($value['keyword_id']) ? $value['keyword_id'] : $value['unique_id'];
    $domain_url = $value['domain_url'];
    $project_id = $value['project_id'];

    //sets:
    $keyword_info = $value['adwordInfo'];
    $ERT = $value['ERT'];

    $info7 = ($value['days7'] == '') ? ' - ' : $value['days7'];
    $info28 = ($value['days28'] == '') ? ' - ' : $value['days28'];

    // link:
    if (!isset($value['site_url']) OR trim($value['site_url']) == '') {
        $preview_link = '#';
        $preview_target = '';
    } else {
        $preview_link = '/ranktracker/preview/'.$value['unique_id'];
        $preview_target = 'target="_blank"';
    }
    ?>
    <!-- start individual keyword list -->
    <div class="keywordmainback" id="<?= $keyword_id . "-" . $project_id; ?>">
        <div class="rankbgblock" style="display:none;">
            <input type="checkbox" name="#" value="#">
        </div>
        <div class="rankbgblock">
            <div class="campaigndelete" onclick="delete_keyword('<?php echo $keyword_id ?>','<?php echo $value['project_id']; ?>','<?php echo addslashes($value['keyword']); ?>')"
                 style="margin-top:27px;"></div>
        </div>
        <a href="<?= $preview_link; ?>" <?= $preview_target; ?>>
            <div class="exactrankicon"></div>
        </a>
        <a href="<?php echo base_url(); ?>ranktracker/rankings/<?php echo $username . "/" . $project_name_raw . "/" . urlencode($value['keyword']); ?>" style="width:100%">
            <div class="seerankings"></div>
        </a>

        <div class="rankiconsep"></div>
        <div class="keyword-keyword"><?php echo $value['keyword'] ?></div>
        <div class="keyword-position">
            <div class="keyword-positiontext"><?php echo isset($value['rank']) ? $value['rank'] : ' - ' ?></div>
        </div>


        <div class="keyword-estimatedtraffic"><?= $info7; ?></div>
        <div class="keyword-estimatedtraffic"><?= $info28; ?></div>

        <div class="keyword-estimatedtraffic"><?php echo isset($value['google_com_rank']) ? $value['google_com_rank'] : ' - ' ?></div>
        <div class="keyword-estimatedtraffic"><?php echo isset($value['rank_local']) ? $value['rank_local'] : ' - ' ?></div>


        <div class="keyword-estimatedtraffic"><?php echo isset($value['pg_rank_news']) ? $value['pg_rank_news'] : ' - ' ?></div>
        <div class="keyword-estimatedtraffic"><?php echo isset($value['pg_rank_video']) ? $value['pg_rank_video'] : ' - ' ?></div>
        <div class="keyword-estimatedtraffic"><?php echo isset($value['pg_rank_shop']) ? $value['pg_rank_shop'] : ' - ' ?></div>


        <div class="keyword-estimatedtraffic"><?php if(is_numeric($ERT)) echo number_format($ERT,0,'.', ','); else echo 'Not available';?></div>
        <div
            class="keyword-competition"><?php echo isset($keyword_info['competition']) ? "<img class='comp' src=" . base_url() . "assets/images/competition-" . ltrim(round($keyword_info['competition'], 1), '0.') . ".jpg alt='" . round($keyword_info['competition'], 1) . "'>" : '<img class="comp" src="http://rankalytics.com/assets/images/competition-.jpg">' ?></div>
        <div class="keyword-pagemeta">
            <div class="keyword-pagemetawrap">
                <div class="keyword-pagemetatop"><?php echo isset($value['title']) ? $value['title'] : ' - ' ?></div>
                <a href="<?php echo isset($value['site_url']) ? urldecode($value['site_url']) : '#' ?>"
                   class="keyword-pagemetabottom"><?php echo isset($value['site_url']) ? urldecode($value['site_url']) : ' - ' ?></a>
            </div>
        </div>
        <?php

        ?>
        <div class="keyword-kei">
            <?php
            if (isset($keyword_info['volume']) && $keyword_info['volume'] != 0 and isset($value['total_records'])) {
                $remove = array(',', '.');
                $temp_rec = str_replace($remove, '', $value['total_records']);
                $temp_vol = str_replace($remove, '', $keyword_info['volume']);
                echo number_format($temp_vol * 2 / $temp_rec, 9, '.', ',');
            } else {
                echo "Not available";
            } ?>
        </div>
        <?php

        if (isset($keyword_info['volume'])) {
            $temp_volume = $keyword_info['volume'];

            if (!is_numeric($temp_volume)) {
                $temp_volume = 'Not available';
            } else {
                $temp_volume = number_format($temp_volume, 0, '.', ',');
            }
        } else {
            $temp_volume = '-';
        }
        ?>
        <div class="keyword-competingpages">
            <?php if (isset( $value['total_records'] ) AND $value['total_records'] != 0) {
                echo $value['total_records'];
            } else {
                echo " - ";
            } ?>
        </div>
        <div class="keyword-searchvol"><?php echo $temp_volume; ?></div>
        <div class="keyword-cpc">
            <?php
            if (isset($keyword_info['CPC'])) {
                echo "&euro;" . number_format(round($keyword_info['CPC'] / 1000000, 3), 3, '.', ',');
            } else {
                echo ' -';
            }
            ?></div>
    </div>
    <!-- end individual keyword list -->
<?php endforeach ?>

<style>
    .comp {
        margin: 30px;
    }
</style>