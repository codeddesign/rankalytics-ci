<link href="<?php echo base_url(); ?>assets/pagination.css" rel="stylesheet" type="text/css"/>

<div class="yellowtopline"></div>
<div class="topinfobar">
    <div class="toptitlebar">ADMIN ACCOUNT CONTROL</div>
</div>
<div class="projectbackground">
    <div class="reportsdashcontent">
        <div class="useraccountswrap" style="width:555px;border-right:1px solid #EEEEEE;">
            <div class="useraccountsbox" style="height:50px;width:100%;">
                <div class="sortby">Sort by:</div>
                <select>
                    <option value="volvo">Waiting</option>
                    <option value="saab">Completed</option>
                </select>
            </div>

            <div id="userlist">
                <ul class="crawlaccountslist" style="width:100%;">
                    <?php
                    if (is_array($campaigns)) {
                        foreach ($campaigns as $c_no => $c_info) {
                            $el_class = 'waiting';
                            if ($c_info['admin_viewed']) {
                                $el_class = '';
                            }
                            ?>
                            <li id="seoc_<?= $c_info['id']; ?>" <?= ($el_class !== '') ? 'class="' . $el_class . '"' : ''; ?>>
                                <div class="crawlaccountslevel"><?= $c_info['campaign_name'] ?></div>
                                <div class="crawlaccountsjoined"><?= $c_info['domain_url'] ?></div>
                            </li>
                        <?php
                        }
                    }

                    //complete the list:
                    $total = count($campaigns);
                    $maxi = 10; // !
                    if ($total < $maxi) {
                        for ($i = $total; $i < $maxi; $i++) {
                            ?>
                            <li style="cursor: default;">
                                <div class="crawlaccountslevel"></div>
                                <div class="crawlaccountsjoined"></div>
                            </li>
                        <?php
                        }
                    }
                    ?>
                </ul>
            </div>
            <div id="pagination-admin" style="background:#5f6978;margin-bottom:0px;margin-left:0;width:100%;margin-top:0;">
                <div class="pagination" style="margin-top:4px;margin-right:9px;">
                    <ul>
                        <?php
                        //for tests: $total = 21;
                        $pages_no = ceil($total_campaigns / $maxi);

                        // handle the number of pages if more than ..
                        $top_limit = 7; //.. ^
                        $limited = false;
                        if ($pages_no > $top_limit) {
                            $pages_no = $top_limit;
                            $limited = true;
                        }

                        // list number of pages:
                        for ($i = 1; $i <= $pages_no; $i++) {
                            $el_class = ($i == $current_page) ? 'active' : 'page';
                            $last_i = $i;

                            echo '<li class="' . $el_class . '">';
                            echo ($i != $current_page) ? '<a href="?p=' . $i . '">' . $i . '</a>' : $i;
                            echo '</li>';
                        }

                        if($limited) {
                            echo '<span style="color: white;"> ... </span>';
                        }

                        // handle multiple pages extra ops:
                        if($pages_no > 1) :
                        $next_page = ($current_page == $last_i) ? '#' : '?p=' . ($current_page + 1);
                        ?>
                        <li class="next page"><a href="<?= $next_page; ?>">Next &rarr;</a></li>
                        <li class="nextpage"><a href="?p=<?= $last_i; ?>">Last</a></li>
                        <?php endif;?>
                    </ul>
                </div>
                <!--pagination-->
            </div>
        </div>

        <div class="crawlviewwrap">
            <?php $this->load->view('seocrawl/admin_right'); ?>
        </div>

    </div>
</div>

<script>
    function init() {
        // ..
    }

    $(document).ready(function () {
        $(".useraccountslist li").first().click();


        // attach click event for project list:
        $('li[id^="seoc_"]').on('click', function () {
            var el = $(this), el_id = el.attr('id').split('_')[1],
                target = $('div.crawlviewwrap'),
                restOfEl = $('li[id^="seoc_"]'), i, temp, currentClass = el.attr('class'), viewed;

            viewed = (currentClass == 'waiting') ? 'no' : 'yes';

            // un-mark the active one
            for (i = 0; i < restOfEl.length; i++) {
                temp = $(restOfEl[i]);
                if (temp.attr('class') == 'active') {
                    temp.attr('class', '');
                }
            }

            // mark as active the clicked one:
            el.attr('class', 'active');

            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: '/seocrawl/viewcampaigna/?id=' + el_id + '&viewed=' + viewed,
                success: function (response2) {
                    if (parseInt(response2.error) == 0) {
                        target.html(response2.html);
                    } else {
                        // do nothing ..
                    }
                }
            });
        });
    });
</script>