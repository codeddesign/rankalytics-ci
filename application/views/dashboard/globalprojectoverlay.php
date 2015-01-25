<!-- TRACK NEW DOMAIN : OVERLAY -->
<div id="dashglobaloverlay">
    <div class="whiteoverlaybg">
        <div class="overlaytitle">Global Keyword Project</div>
        <div class="overlaysubtitle">Locate your top 5,000 keywords</div>
        <div class="overlaysubtitle" id="projectNM"></div>
        <div class="overlaysubtitle" style="margin-top:-41px;font-size:14px;font-weight:500;">
            ( <?php echo "REMAINING KEYWORDS: " . $key_used; ?> )
        </div>
        <div class="" id="form-msgs4"></div>
        <form id="global_project_form" class="overlayform" action="<?php echo base_url(); ?>project/save"
              target="upload_keyword" method="post" enctype="multipart/form-data">
            <input class="overlayproject" id="global_project_name" type="text" name="global_project_name"
                   placeholder="Project name">
            <input class="overlayurl" type="text" name="global_domainurl" value="www."
                   placeholder="Domain URL (without http://)" id="global_domainurl">
            <input class="overlayurl" type="text" name="global_location" placeholder="Local Search Location (ie New York)"
                   id="global_location">
            <!--textarea class="overlaykeywords" name="keywords" placeholder="Keywords: one per line"></textarea-->
            <input class="overlaysubmit" type="Button" value="Submit" onClick="javascript:save_global_project();">

            <div id="global-project-loading" align="left" class="save-loading" style="float: left;margin-top:15px">
                <div class="spinner"></div>
            </div>
        </form>
        <a href="javascript:dashglobaloverlayclose()">
            <div class="overlayclose"></div>
        </a>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("input[type=text],input[type=password]").blur(function () {
            if ($(this).val() != '') {
                $(this).removeClass("validationError");
            }
        });
    });
    function save_global_project() {
        $("#form-msgs4").hide();
        $("#global-project-loading").show();
        project = $("#global_project_name").val();
        domain = $("#global_domainurl").val();
        global_location = $("#global_location").val();


        $.ajax({
            url: "<?php echo base_url()?>project/saveGlobalProject",
            xhrFields: {
                onprogress: function (e) {
                    var text = e.target.response, data, jsoned, k, msg,
                        info_box = $('#form-msgs4');

                    info_box.show()
                        .css('color', '#ee613c');

                    //prepare data:
                    if (text.lastIndexOf(',') == text.length - 1) {
                        data = '{' + text.substring(0, text.length - 1) + '}';
                    } else {
                        data = '{' + text + '}';
                    }

                    //attempt:
                    try {
                        jsoned = JSON.parse(data);
                    } catch (e) {
                        //
                    }

                    //if not JSON
                    if (jsoned == null) {
                        return false;
                    }

                    //default to 0
                    if (jsoned.c == undefined) {
                        k = 0;
                    } else {
                        k = jsoned.c;
                    }

                    //show messages while loading ..
                    if (parseInt(jsoned.d) == 0) {
                        msg = 'Found ' + k + ' keywords so far ..';
                    } else {
                        msg = 'Found ' + k + ' keywords. Please wait, saving ..'
                    }

                    info_box.html(msg);
                }
            },
            type: "POST",
            data: {project_name: project, domainurl: domain, global_location: global_location},
            success: function (response) {
                var data, info_box = $("#form-msgs4");
                info_box.css('color', 'white');

                if (response.lastIndexOf('}') == response.length - 1) {
                    data = response;
                } else {
                    data = '{' + response + '}';
                }

                $("#global-project-loading").hide();
                data = JSON.parse(data);
                msg = data.msg;
                if (!parseInt(data.error)) {
                    info_box.removeClass("form-errors")
                        .addClass("form-success")
                        .show()
                        .html(msg);

                    $("#dashglobaloverlay").hide();
                    getProjects();
                }
                else {
                    info_box.removeClass("form-success")
                        .addClass("form-errors")
                        .show()
                        .html(msg);

                    $.each(data.ids, function (key, val) {
                        $('#global_' + val).addClass('validationError');
                    });
                }
            }
        });

    }
</script>
<!-- END TRACK NEW DOMAIN -->
