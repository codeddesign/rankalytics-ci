<div class="weatherpopup-top"></div>
	<div class="weatherpopup-bg" >
            
    	<ul class="nav five-day" style=" margin-left: 32px;">
		<?php 
		$i=1;
		foreach($res as $temp){
		?>
<li class="row" style="list-style: none outside none;  text-align: center; float: left;color: rgb(86, 179, 217);   font: bolder 12px;  width: 100px;">
				<div class="span2 icon">
					<img src="/assets/images/sunrise.png" >
				</div>
				<div class="span2 temp" style="font-size:30px;padding-left: 10px;">
					<?php echo $temp['t'.$i];?> 
				</div>
				<div class="span2 date" style="margin-left: 5px;   padding-top: 10px;">
					<?php echo $temp['d'.$i];?>
				</div>
			</li>
			<?php $i++; }?>	
        </ul>
    	<br><img  style="margin-top:45px; margin-left: 40px;" src="<?php echo base_url(); ?>assets/screen/temp.png"/>
	</div>