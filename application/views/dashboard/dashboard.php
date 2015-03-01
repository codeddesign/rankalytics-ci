<?php echo validation_errors(); ?>
<script>
    function dashglobaloverlay() {
        $("#dashglobaloverlay").show();
    }
    function dashglobaloverlayclose() {
        $("#dashglobaloverlay").hide();
    }
    function closeSelf() {
        $("#dashoverlay").css("visibility", "hidden");
    }
    $(document).ready(function () {
        $(".addnewkeywordbutton").click(function () {
            $("#keywordaddoverlay").show();
            $("#project_id").val((this.id).replace("project_", ""));
            $("#userfile").val('');
            $("#kewords").val('');
            $("#projectNM").text($("#project-name_" + (this.id).replace("project_", "")).text());
        });
        $("#project_current").change(function () {
            if ($("#project_current").val() == "" || $("#project_current").val() == "0") {
                return false;
            }
            $.ajax({
                url: "<?php echo base_url()?>project/showproject",
                type: "POST",
                data: {
                    id: $('#project_current').val()
                },
                success: function (data) {
                    $("#all_project").html(data);
                    $(".addnewkeywordbutton").click(function () {
                        $("#keywordaddoverlay").show();
                        $("#project_id").val((this.id).replace("project_", ""));
                        $("#userfile").val('');
                        $("#kewords").val('');
                        $("#projectNM").text($("#project-name_" + (this.id).replace("project_", "")).text());
                    }); // for add keywords
                    $(".campaigndelete").click(function () {
                        del_id = this.id.replace("delete_", "");
                        if (confirm("<?= lang('rankdash.reallydelete');?>")) {
                            $.ajax({
                                url: "<?php echo base_url()?>project/delete",
                                dataType: "json",
                                type: "POST",
                                data: {
                                    id: del_id
                                },
                                success: function (data) {

                                    if (parseInt(data.error)) {
                                        alert("<?= lang('rankdash.error');?>");
                                    }
                                    else {
                                        $("#project_row_" + del_id).hide("slow");
                                    }
                                }
                            });
                        }
                    });// for delete project
                }
            });
        });


    });


    function showmessage(error, msg, project_id, keyword_count) {
        $('#keywords-loading').hide();
        if (error == 1) {
            $("#form-msgs2").removeClass("form-success");
            $("#form-msgs2").addClass("form-errors");
            $("#form-msgs2").show();
            $("#form-msgs2").html(msg);
        } else if (error == 0) {
            $("#form-msgs2").removeClass("form-errors");
            $("#form-msgs2").addClass("form-success");
            $("#form-msgs2").show();
            $("#form-msgs2").html(msg);

            keyword_crawl(project_id, keyword_count);
        }
    }

</script>
<link href="<?php echo base_url(); ?>assets/pagination.css" rel="stylesheet" type="text/css"/>
<!-- PROJECT LIST START -->
<div class="choosebelowtitle"><?= lang('rankdash.campover');?></div>
<!-- <button id="startTourBtn" class="dashtourbutton">START TOUR</button> -->
<div style="clear: both;"></div>
<div class="alert alert-success" style="<?= ((isset($message) && $message != '') ? 'display:block;' : 'display:none;') ?>">
    <?php echo (isset($message) && $message != '') ? $message : ""; ?>
</div>

<div class="alert alert-danger" style="<?= ((isset($error) && $error != '') ? 'display:block;' : 'display:none;') ?>">
</div>

<div class="choosecurrentline"></div>

<div class="dashyourdomains"><?= lang('rankdash.campname');?></div>
<div class="dashavgposition"><?= lang('rankdash.avgpos');?></div>
<div class="dashesttraffic"><?= lang('rankdash.esttraf');?></div>
<div class="dashvisibility"><?= lang('rankdash.visibility');?></div>
<div id="all_project">
    <?php
    $this->load->view("dashboard/project_row", array('project_data' => $project_data));
    ?>
</div>
<div id="pagination-admin"><?php echo $this->pagination->create_links(); ?></div>
<a href='javascript:void(0);' onclick='dashoverlay()'>
    <div class="createnewproject"><?= lang('rankdash.newcampaign');?></div>
</a>
<?php if ($allowedOpt) { ?>
    <a href='javascript:void(0);' onclick='dashglobaloverlay()'>
        <div class="createnewproject" style="width:230px;"><?= lang('rankdash.newglobalcamp');?></div>
    </a>
<?php } ?>
<div class="loadmoreprojects" style="display:none"><?= lang('rankdash.morecamps');?></div>
<div class="quicksearchwrapper">
    <form class="dashsearchform">
        <input type="text" name="quicksearch" value="<?= lang('rankdash.quicksearch');?>" style="display:none">
        <select onchange="" name="project_current" id="project_current" style="background: transparent;color: #7E92A9;width: 247px;height: 38px;">
            <option value=""><?= lang('rankdash.select');?></option>
            <?php foreach ($project_data as $key => $value) {
                echo "<option value='" . $value['id'] . "'>" . $value['project_name'] . "</option>";
            }
            ?>
        </select>
    </form>
</div>
<?php $this->load->view("dashboard/common/footer") ?>

<!-- PROJECT LIST END -->
<?php /*
	<!-- DOMAIN LIST START -->
	<div class="choosebelowtitle">Campaign List</div>
	
	<div class="choosecurrentline"></div>
	
	<div class="dashyourdomains">YOUR CAMPAIGNS</div>
	<div class="dashavgposition">AVG POSITION</div>
	<div class="dashesttraffic">EST. TRAFFIC</div>
	<div class="dashvisibility">VISIBILITY</div>
<?php foreach ($project_data as $key => $value):?>
	<div class="ranktracker-editbox">
		<div class="addnewkeywordbutton"></div>
	</div>
	<div class="projectlist">
		<div class="projectlist-name"><?php echo strtoupper($value['project_name'])?></div>
		<div class="projectlist-avgposition">5</div>
		<div class="projectlist-esttraffic">1,500</div>
		<div class="projectlist-visibility">54%</div>
	</div>
	<div class="ranktracker-deletebox">
		<div class="campaigndelete"></div>
	</div>
<?php endforeach ?>
	
	<a href='#' onclick='campaignoverlay()'>
		<div class="createnewproject">CREATE NEW CAMPAIGN</div>
	</a>
	<div class="loadmoreprojects">LOAD MORE CAMPAIGNS</div>
	<div class="quicksearchwrapper">
		<form class="dashsearchform">
			<input type="text" name="quicksearch" value="DOMAIN QUICK SEARCH...">
		</form>
	</div>
	<div class="dashbottomline"></div>
</div>
                   */
?>
<!-- TRACK NEW DOMAIN : OVERLAY -->
<div id="dashoverlay">
    <div class="whiteoverlaybg">
        <div class="overlaytitle"><?= lang('rankdash.newdomain');?></div>
        <div class="overlaysubtitle"><?= lang('rankdash.enterupload');?></div>
        <div class="overlaysubtitle" style="margin-top:-33px;font-size:14px;font-weight:500;">( <?php echo "REMAINING KEYWORDS: " . $key_used; ?> )</div>
        <div class="" id="form-msgs3"></div>

        <form id="project_form" class="overlayform" action="<?php echo base_url(); ?>project/save" target="upload_keyword" method="post" enctype="multipart/form-data">
            <input class="overlayproject" id="project_name" type="text" name="project_name" placeholder="<?= lang('rankdash.placeholderone');?>">
            <input class="overlayurl" type="text" name="domainurl" placeholder="<?= lang('rankdash.placeholdertwo');?>" id="domainurl">
            <input class="overlayurl" type="text" name="location" placeholder="<?= lang('rankdash.placeholderthree');?>" id="location">
            <span id="errors" style="color:#EED3D7"></span>
            <textarea class="overlaykeywords" type="text" id="keywords" name="keywords" placeholder="<?= lang('rankdash.placeholderfour');?>"></textarea>
            <input id="uploadFile" class="overlayuploadarea" placeholder="<?= lang('rankdash.placeholderfive');?>" disabled="disabled"/>
            <input type="hidden" name="js_function" value="project_saved"/>

            <div class="overlayuploadbutton">
                <span><?= lang('rankdash.upload');?></span>
                <input id="csv" type="file" class="upload" name="userfile"/>
            </div>

            <input class="overlaysubmit" type="Button" value="Submit" onClick="javascript:formValidate();">

            <div style="float:left;margin-left:184px;margin-top:18px;margin-right:0px;" id="project-loading" align="left" class="save-loading">
                <div class="spinner"></div>
            </div>
        </form>
        <script type="text/javascript">
            document.getElementById("csv").onchange = function () {
                document.getElementById("uploadFile").value = this.value;
            };
            function check_limit() {

                $.ajax({
                    type: "post",
                    datatype: "json",
                    url: "<?php echo base_url();?>/adwords/check",
                    success: function (result) {
                        if (result == 1) {
                            $("#keyword_form").submit();
                        }
                        else {
                            alert("<?= lang('rankdash.pleaseupgrade');?>");
                            return false;
                        }
                    }
                })
            }
        </script>

        <a href="javascript:closeSelf()">
            <div class="overlayclose"></div>
        </a>
    </div>
</div>
<!-- END TRACK NEW DOMAIN 
<?php
if ($allowedOpt) {
    $this->load->view("dashboard/globalprojectoverlay", array('key_used' => $key_used));
}
?>
<!-- Add keywords to projects -->
<iframe name="upload_keyword" id="upload_keyword" style="height:100%;width:400px;display:none"></iframe>
<div id="keywordaddoverlay">
    <div class="whiteoverlaybg">
        <div class="overlaytitle"><?= lang('rankdash.addkey');?></div>
        <div class="overlaysubtitle" id="projectNM"></div>
        <div style="float: left; font-size: 13px;  margin-top: -29px; text-align: center;  width:100%; font-family:'brandon-grotesque',sans-serif;">( <?php echo "LIMIT:" . $key_used; ?>)
        </div>

        <div class="" id="form-msgs2"></div>
        <?php echo form_open_multipart(base_url() . "project/projectkeywordsave", array("id" => "keyword_form", "class" => "overlayform", "target" => "upload_keyword", "onsubmit" => "$('#keywords-loading').show();return true;")); ?>
        <input id="project_id" type="hidden" name="project_id">
        <span id="errors" style="color:#EED3D7"></span>
        <textarea class="overlaykeywords" name="keywords" id="addkey" placeholder="<?= lang('rankdash.keyplace');?>"></textarea>
        <input id="uploadKeywordFile" class="overlayuploadarea" placeholder="<?= lang('rankdash.keyplacetwo');?>" disabled="disabled"/>

        <div class="overlayuploadbutton">
            <span><?= lang('rankdash.upload');?></span>
            <input id="userfile" type="file" class="upload" name="userfile"/>
        </div>
        <div style="float:left" id="keywords-loading" align="left" class="save-loading">
            <div class="spinner"></div>
        </div>
        <input class="overlaysubmit" type="Submit" onclick="return check_file();" value="Submit">
        </form>
        <script type="text/javascript">
            document.getElementById("userfile").onchange = function () {
                document.getElementById("uploadKeywordFile").value = document.getElementById("userfile").value;
            }
        </script>
        <a href="javascript:keywordaddoverlayclose()">
            <div class="overlayclose"></div>
        </a>
    </div>
</div>
<!-- END Add keywords to projects -->


<script type="text/javascript">

    function project_saved(error, msg, project_id, keyword_count) {
        $('#project-loading').hide();
        if (error == 1) {
            $("#form-msgs3").removeClass("form-success");
            $("#form-msgs3").addClass("form-errors");
            $("#form-msgs3").show();
            $("#form-msgs3").html(msg);
        } else if (error == 0) {
            $("#form-msgs3").removeClass("form-errors");
            $("#form-msgs3").addClass("form-success");
            $("#form-msgs3").show();
            $("#form-msgs3").html(msg);

            keyword_crawl(project_id, keyword_count);
            getProjects();
        }
    }
    function keyword_crawl(project_id, keyword_count) {
        //alert(project_id);
        if (project_id.toString() == '0') {
            return false;
        }
        //alert(project_id,keyword_count);
        $.ajax({
            url: "<?php echo base_url(); ?>project/callCrawler",
            type: "POST",
            data: {projectId: project_id},
            success: function (data) {
                //alert(data);
            }
        });
        $.ajax({
            url: "<?php echo base_url(); ?>project/callAdwordSave",
            type: "POST",
            data: {projectId: project_id},
            success: function (data) {
                //alert(data);
            }
        });
    }
    function getProjects() {

        $("#all_project").append('<img src="<?php echo base_url()?>assets/images/loading.gif" align="left" >');
        $.ajax({
            url: "<?php echo base_url()?>project/showprojectByuserid",
            type: "POST",
            data: {
                userid: '0'
            },
            success: function (data) {

                data = JSON.parse(data);
                if (data.error == 1) {
                    alert(data.msg);
                    return false
                }
                $("#all_project").html(data.html);
                $(".addnewkeywordbutton").click(function () {
                    $("#keywordaddoverlay").show();
                    $("#project_id").val((this.id).replace("project_", ""));
                    $("#userfile").val('');
                    $("#kewords").val('');
                    $("#projectNM").text($("#project-name_" + (this.id).replace("project_", "")).text());
                });

                $(".campaigndelete").click(function () {
                    del_id = this.id.replace("delete_", "");
                    if (confirm("<?= lang('rankdash.keyconfirm');?>")) {
                        $.ajax({
                            url: "<?php echo base_url()?>project/delete",
                            dataType: "json",
                            type: "POST",
                            data: {
                                id: del_id
                            },
                            success: function (data) {

                                if (parseInt(data.error)) {
                                    alert("<?= lang('rankdash.deleteerror');?>");
                                }
                                else {
                                    $("#project_row_" + del_id).hide("slow");
                                }
                            }
                        });
                    }
                });// for delete project
            }
        });

    }
    function keywordaddoverlayclose() {
        $("#keywordaddoverlay").hide();
    }

    $(".campaigndelete").click(function () {
        //alert(this.id);
        del_id = this.id.replace("delete_", "");
        if (confirm("<?= lang('rankdash.deleteproj');?>")) {
            $.ajax({
                url: "<?php echo base_url()?>project/delete",
                dataType: "json",
                type: "POST",
                data: {
                    id: del_id

                },
                success: function (data) {

                    if (parseInt(data.error)) {
                        alert("<?= lang('rankdash.deleteerror');?>");
                    }
                    else {
                        $("#project_row_" + del_id).hide("slow");
                    }
                }
            });
        }
    });
    function check_file() {
        $('#addkey').css('background', '');
        $('#userfile').css('background', '')
        var key = $('#addkey').val().trim();
        var file = $('#userfile').val().trim();
        if (file == "" && key == "") {
            $('#addkey').css('background', '#F2DEDE');
            alert("<?= lang('rankdash.keyupload');?>");
            return false;
        }
        ;
        var ext = $('#userfile').val().split('.').pop().toLowerCase();
        if ($.inArray(ext, ['csv', 'txt']) == -1 && key == "") {
            $('#userfile').css('background', '#F2DEDE')
            alert('<?= lang('rankdash.upfile');?>');
            return false;
        }

    }
    function formValidate() {
        $('#project_name').css('background', '');
        $('#domainurl').css('background', '');
        $('#keywords').css('background', '');
        var project_name = $('#project_name').val().trim();
        var domainurl = $('#domainurl').val().trim();
        if (project_name == "") {
            $('#project_name').css('background', '#F2DEDE');
        }
        if (domainurl == "") {
            $('#domainurl').css('background', '#F2DEDE');
        }

        var key = $('#keywords').val().trim();
        var file = $('#csv').val().trim();
        if (file == "" && key == "") {
            $('#keywords').css('background', '#F2DEDE');
        }
        ;
        if (project_name == "" || domainurl == "" || (file == "" && key == "")) {
            alert("<?= lang('rankdash.enterred');?>");
            return false;
        }
        var ext = $('#csv').val().split('.').pop().toLowerCase();
        if ($.inArray(ext, ['csv', 'txt']) == -1 && key == "") {
            alert('<?= lang('rankdash.uptype');?>');
            return false;
        }
        $('#errors').html('')
        $.ajax({
            url: "<?php echo base_url()?>project/newProjectValidate",
            dataType: "json",
            type: "POST",
            data: {
                project_name: $('#project_name').val(),
                domainurl: $('#domainurl').val()
            },
            success: function (data) {
                if (!parseInt(data.error)) {
                    $('#project-loading').show();
                    $('#project_form').submit();
                }
                else {
                    $('#errors').html(data.msg)
                    $.each(data.ids, function (key, val) {
                        $('#' + val).css('background', '#F2DEDE');
                    });
                }
            }
        });
    }


</script>