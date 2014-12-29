<?php 


if(is_array($project_data) && !empty($project_data)) {foreach ($project_data as $key => $value):
    
    $userNamestr=$value['userName'];
    ?>
        <div id="project_row_<?php echo $value['id'];?>">
        <div class="ranktracker-editbox">
        <div class="addkeywordtip"></div>
            <div class="addnewkeywordbutton" onclick="" id="project_<?php echo $value['id'];?>"></div>
            
        </div>
    <a href="<?php echo base_url()?>ranktracker/rankings/<?php echo $userNamestr."/".str_replace("+", "-", urlencode($value['project_name']))?>">
	<div class="projectlist">
		<div class="projectlist-name" id="project-name_<?php echo $value['id'];?>"><?php echo strtoupper($value['project_name'])?></div>
                <?php
                $project_keyword_details=$this->project_keyword_details_from_cron_model->project_keyword_details_by_project_id($value['id']);
                ?>
            <div class="projectlist-avgposition"><?php echo isset($project_keyword_details['0']['average_position'])?ceil($project_keyword_details['0']['average_position']):"na"; ?></div>
            <div class="projectlist-esttraffic"><?php echo isset($project_keyword_details['0']['estimated_traffic'])?ceil($project_keyword_details['0']['estimated_traffic']):"na"; ?></div>
            <div class="projectlist-visibility"><?php echo isset($project_keyword_details['0']['visibility'])?ceil($project_keyword_details['0']['visibility']):"na"; ?></div>
	</div>
	</a>
	<div class="ranktracker-deletebox">
		<div class="deleteprojecttip"></div>
		<div class="campaigndelete" id="delete_<?php echo $value['id'];?>"></div>
	</div>
        </div>
<?php endforeach;
}else{
    echo '<div class="nocurrentprojects" ><div class="glowalert"></div> You have no active projects. Please create a new project to begin.</div>';
    
}

?>
