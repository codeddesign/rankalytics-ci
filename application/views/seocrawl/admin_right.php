<?php
if (!isset($c_info)) : ?>
    <i>No project selected.</i>
<?php else : ?>
    <div class="crawlview-projecttitle">Project Title: <br><span><?= $c_info['campaign_name']; ?></span></div>
    <div class="crawlview-projecturl">Project URL: <br><span><?= $c_info['domain_url']; ?></span></div>

    <div class="crawlview-urlping">Ping URL's? <span><?= ($c_info['google_indexed']) ? 'Yes' : 'No'; ?></span></div>
    <div class="crawlview-checkgoogle">Google Check? <span><?= ($c_info['ping_non_indexed']) ? 'Yes' : 'No'; ?></span></div>
    <!-- you need to change next line's class=.. -->
    <div class="crawlview-checkgoogle">Depth: <span><?= $c_info['depth_level'];?></span></div>
    <form class="crawlview-formupload" action="/seocrawl/complete" id="form_campaign">
        <input type="hidden" name="prj_id" value="<?= $c_info['id']?>">

        <label for="dropbox">Dropbox URL:</label>
        <input type="text" name="dropbox" id="dropbox" value="<?= $c_info['dropbox'];?>"><br>
        <label for="pages_number">Number of links:</label>
        <input type="text" name="pages_number" id="pages_number" value="<?= $c_info['pages_number'];?>"><br>
        <input type="submit" name="submit" value="Submit">
    </form>

    <script>
        $('#form_campaign').on('submit', function(e) {
            e.preventDefault();

            var theForm = $(this),
                action = theForm.attr('action'),
                data = theForm.serialize();

            $('#pages_number, #dropbox').css('background-color', 'white');

            $.ajax({
                url: action,
                type: 'POST',
                dataType: 'json',
                data: data,
                success: function(response) {
                    // some error:
                    if(parseInt(response.error) == 1) {
                        if(response.input_id !== undefined) {
                            $('#'+response.input_id).css('background-color', '#FFFFCC');
                            return false;
                        }

                        console.log(response.msg);
                        return false;
                    }

                    // all good:
                    if(parseInt(response.error) == 0) {
                        console.log(response.msg);

                        return true;
                    }
                }
            })
        });
    </script>
<?php endif; ?>