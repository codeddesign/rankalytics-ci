<?php 
$user = $this->session->userdata('logged_in');
        if(!isset($user['0']['id']) || $user['0']['id']==0 ||  $user['0']['id']==''){ // redirect to rantracker if not logged in
            redirect('ranktracker');
            return;
        }
?>

<?php
$temp = "";
$date = "";
$google_temps_data ="";
$google_temps = $this->analytical->getGoogleTemperature();
foreach ($google_temps as $temps) 
{
    $temp = $temp.$temps['temperature'].",";
    $date = $date."'".$temps['date']."'".",";
}
$temp = rtrim($temp, ",");
$date = rtrim($date, ",");
$google_temps_data.="var graphData = { temps: [".$temp."],dates: [".$date."]};";
?>

<div class="yellowtopline"></div>

<div id="weatherpopup" class="link_toggle">
            <div class="weatherpopup-top"></div>
<div class="weatherpopup-bg" >
            
<ul class="nav five-day" style=" margin-left: 32px;">
<?php 
    $temps_array = array();
    $count = 1;
    foreach ($google_temps as $value)
    {
        $temps_array[]=$value;
        if($count >=5)
        {
            break;
        }
        $count++;
    }
krsort($temps_array);     
?>   
            
        <?php foreach ($temps_array as $value):?>
        <li class="row" style="list-style: none outside none;  text-align: center; float: left;color: rgb(86, 179, 217);   font: bolder 12px;  width: 100px;">
        <div class="span2 icon">
        <?php if($value['temperature'] <=15) {   
        echo '<img src="/assets/images/sunny.png" >';
   		
   		} elseif ($value['temperature'] >15 AND $value['temperature'] <=21) {    
        echo '<img src="/assets/images/sunny_cloudy.png">';
        
   		} elseif($value['temperature'] >21 AND $value['temperature'] <=26) {   
        echo '<img src="/assets/images/cloudy.png">';
        
   		} elseif($value['temperature'] >26) {   
        echo '<img src="/assets/images/thunder.png">';
        
   		}; ?>
        </div>
            <div class="span2 temp" style="font-size:30px;padding-left: 10px;">
                <?php echo $value['temperature']?> °C
            </div>
            
            <div class="span2 date" style="margin-left: 5px;   padding-top: 10px;">
                <?php echo $value['date']?>
            </div>
        </li>
        <?php endforeach;?>
        </ul>
    	<br/>
        <div style="clear: both;"></div>
        
        
  <script>
            <?php echo $google_temps_data?>
        graphData.temps.reverse();
        graphData.dates.reverse();
  </script>
<div class="chart chart-thirtyday"></div>
<script src="<?php echo base_url(); ?>assets/js/weather_graph/highcharts.js"></script>
<script src="<?php echo base_url(); ?>assets/js/weather_graph/graph.js"></script>  
</div>
       
</div>




	<div class="topinfobar">
            <a href="#" onclick="toggle_visibility('weatherpopup');">
		<div class="weathericon">
			<img src="<?php echo base_url(); ?>assets/images/weather/sun.png"/>
		</div>
            </a>
		<div class="weathertext">Google Weather</div>
		
		<div class="toptitlebar">KEYWORD SUGGESTIONS</div>
	</div>
	<div class="projectbackground">
		<div class="leftsidebar" style="width:52px;">
			<a href="/ranktracker/keywordsuggestions">
				<div class="leftsidebarbutton-one">
					<div class="ranksicons"></div>
				</div>
				<div class="leftsidebarout-one">KEYWORD SUGGESTIONS</div>
			</a>
			<a href="/ranktracker/competitoranalysis">
				<div class="leftsidebarbutton-two">
					<div class="reportsicons"></div>
				</div>
				<div class="leftsidebarout-two" style="height:50px;margin-top:50px;">COMPETITOR ANALYSIS</div>
			</a>
		</div>
		<div class="dashcontent">
                    <form class="keywordresearch-form" method="post" id="search_form" onsubmit="return ValidateForm()">
						<div class="keywordresearch-leftwrap">
							<div class="keywordresearch-titletop">Enter a starting keyword</div>
                    		<input class="keywordresearch-keyword" type="text" name="txt_keyword" placeholder="Enter keyword" onkeypress="onkey(this.value)" id="txt_keyword" value="<?php echo (@$keyword) ? @$keyword :""?>">
						</div>
						<div class="keywordresearch-rightwrap">
							<div class="keywordresearch-titletop">Enter domain (without http://)</div>
							<input class="keywordresearch-domain" type="text" name="site_name" placeholder="Enter domain without http://" id="site_name" value="<?php echo (@$web) ? @$web :""?>">
						</div>
				
						<input type="submit" value="" class="keywordresearch-button">
                    </form>
                        <?php if(!empty($keyword_array)):?>
			<div class="keywordresearch-wrap">
				<div class="keywordresearch-titlekeyword">KEYWORD</div>
				<div class="keywordresearch-titlesimiliar">SIMILARITY %</div>
				<div class="keywordresearch-titlemonthly">SEARCH VOLUME</div>
				<div class="keywordresearch-titlecompetition">COMPETITION</div>
				<div class="keywordresearch-titlecpc">CPC</div>
				
				<div class="keywordresearch-undertitleline"></div>
				
				<ul class="keywordresearch-keywordbg">
                                    <?php
                                    #$handle = fopen("csv/download_csv.csv", "w+");
                                       #fwrite($handle, "" );
                                       #fclose($handle);
                                    #$target="csv/download_csv.csv";
                                         #$handle = fopen($target, "a"); 
                                  #$data="KEYWORD ,SIMILARITY % ,MONTHLY SEARCHES ,COMPETITION ,CPC";
                                    #fwrite($handle, $data."\n" );   
                                    $count=0;                           
                                    ?>
                                        <?php foreach ($keyword_array as $key => $value):?>
					
                                        <?php 
                                        $count ++;
                                        #$keyword_detials =array();
                                            //$keyword_detials= GetKeywordIdeasExample($key);
                                        #if(!empty($keyword_detials)){ 
                                         #$data=$keyword_detials['keywords'].", 87% ,".$keyword_detials['volume'].",".$keyword_detials['competition'].",".$keyword_detials['cpc'];

                                         #fwrite($handle, $data."\n" );
                                        #}
     
                                        
                                            

                                          ?>
                                            <li>
						<a data="<?php echo "item_".$count?>" href="javascript:toggle_visibility('<?php echo "item_".$count?>');" class="load keywordresearch-checkboxarea">
							<div class="keywordresearch-drop"></div>
						</a>	
						<div class="keywordresults-keyword master_keyword" id="master_key_<?php echo $count?>"><?php echo urldecode($key)?></div>
						<div class="keywordresults-similiar" id="master_key_<?php echo $count?>_similar"><img src="<?php echo base_url();?>assets/images/_ajax_loading.gif" style="margin:10px auto"></div>
                                                <div class="keywordresults-monthly" id="master_key_<?php echo $count?>_volume"><img src="<?php echo base_url();?>assets/images/_ajax_loading.gif" style="margin:10px auto"></div>
						<div class="keywordresults-competition" id="master_key_<?php echo $count?>_competition"><img src="<?php echo base_url();?>assets/images/_ajax_loading.gif" style="margin:10px auto"></div>
						<div class="keywordresults-cpc" id="master_key_<?php echo $count?>_cpc"><img src="<?php echo base_url();?>assets/images/_ajax_loading.gif" style="margin:10px auto"></div>
					  </li>
                                   
                                              
					<!-- on click display -->
                                        <div id="<?php echo "item_".$count?>" data="" style="display: none;">
                                          
                                            
                                             <?php
                                             foreach ($value as $key2 => $value2):?>
                                         
                                           <?php  if(true){ $value2= urldecode($value2)?>
						<li class="<?php echo "item_".$key2?>" id="<?php echo $value2 ?>" >
                                                      <?php 
                                                      $value1=str_replace('.', '', $value2);
                                            //$keyword_childs= GetKeywordIdeasExample($value);
                                           ?>
							<div style="margin-left:19px;" class="keywordresults-keyword"><?php echo $value2?></div>
							<div id="<?php echo "similar_".str_replace(' ', '', $value1);?>" class="keywordresults-similiar"><img src="<?php echo base_url();?>assets/images/_ajax_loading.gif" style="margin:10px auto"></div>
                                                        <div id="<?php echo "volume_".str_replace(' ', '', $value1) ?>" class="keywordresults-monthly"><img src="<?php echo base_url();?>assets/images/_ajax_loading.gif" style="margin:10px auto"></div>
                                                        <div id="<?php echo "competition_".str_replace(' ', '', $value1) ?>"class="keywordresults-competition"><img src="<?php echo base_url();?>assets/images/_ajax_loading.gif" style="margin:10px auto"></div>
                                                        <div id="<?php echo "cpc_".str_replace(' ', '', $value1)?>" class="keywordresults-cpc"><img src="<?php echo base_url();?>assets/images/_ajax_loading.gif" style="margin:10px auto"></div>
						</li>
                                                <?php } endforeach;?>
						</div>
					<!-- end on click display -->
                                        <?php endforeach;?>
				</ul>
                                <div id ="download_csv" class="keywordresearch-addselected"></div>
                                <?php  endif;   ?>
			</div>
		</div>
		</div>
		<?php $this->load->view("dashboard/common/footer") ?>
</div>
   <div class="overlay"><img src="<?php echo base_url();?>assets/images/_ajax_loading.gif" style="margin:10px auto"></div>   
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bsn.AutoSuggest_2.1.3.js" charset="utf-8"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/autosuggest_inquisitor.css" />
<input type="text" id="testid" value="" style="font-size: 10px; width: 20px; display: none" disabled="disabled" />
<style>
    .keywordresults-cpc {
        width:auto;
    }
      .overlay img{
    left: 50%;
    position: relative;
    top: 50%;
}
.overlay{
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	 z-index: 99999;
	background-color: rgba(0,0,0,0.5); /*dim the background*/
	display: none;  
}
.keywordresults-similiar {
  width: 80px;  
}
    </style>
<script type="text/javascript">
    $(document).ready(function () {

        $(".master_keyword").each(function () {
            var id = $(this).attr("id");
            findSimilarities(id, $(this).text());
            $.ajax({
                url: "<?php echo base_url();?>adwords/GetKeywordIdeasExample",
                type: "post",
                data: ({keyword: $(this).text()}),
                success: function (result) {

                    var obj = $.parseJSON(result);
                    $("#" + id + "_volume").html(obj.volume);
                    $("#" + id + "_competition").html(obj.competition.toFixed(3));
                    $("#" + id + "_cpc").html("€" + (obj.cpc / 1000000).toFixed(3));

                }
            });
        });


        $("#download_csv").click(function () {

            $.ajax({
                url: "<?php echo base_url();?>ranktracker/savekeywords",
                type: "post",
                data: ({keyword: $("#txt_keyword").val(), pageurl: $("#site_name").val()}),
                success: function (result) {

                    alert('You will get the csv shortly by e-mail');

                }
            });
        });

        $(".load").click(function () {
            id = $(this).attr('data');
            dis = $("#" + id).css('display');
            loaded = $("#" + id).attr('data');
            if (dis == "block" || loaded != "") {
                $(".overlay").hide();
            }
            else {
                count = $("#" + id + "  li").length;
                cnt = 0;
                $("#" + id).attr('data', "loaded");
                $("#" + id + "  li").each(function () {
                    keyword = $(this).attr('id');
                    findSimilarities(false, keyword, $(this));

                    $.ajax({
                        url: "<?php echo base_url();?>adwords/GetKeywordIdeasExample",
                        type: "post",
                        data: ({keyword: keyword}),
                        success: function (result) {

                            var obj = $.parseJSON(result);
                            li_id = obj.keywords.replace(/\s/g, "").replace(".", "");
                            ;

                            //$("#"+id+ " #similar_"+li_id).html('78%');
                            $("#" + id + " #volume_" + li_id).html(obj.volume);
                            $("#" + id + " #competition_" + li_id).html(obj.competition.toFixed(3));
                            $("#" + id + " #cpc_" + li_id).html("€" + (obj.cpc / 1000000).toFixed(3));
                            cnt = cnt + 1;
                            //if(cnt==count){$(".overlay").hide();}

                        }
                    });

                });
            }

        });
    });
    var options = {
        script: "<?php echo base_url();  ?>pdf/get_suggestion/true/1000/",
        varname: "",
        json: true,
        shownoresults: false,
        maxresults: 1000,
        callback: function (obj) {
            document.getElementById('testid').value = obj.id;
        }
    };
    var as_json = new bsn.AutoSuggest('txt_keyword', options);


    var options_xml = {
        script: function (input) { //return "get_suggestion/"+input+"/testid="+document.getElementById('testid').value;
            "test.php?input=" + input + "&testid=" + document.getElementById('testid').value;
        },
        varname: ""
    };
    var as_xml = new bsn.AutoSuggest('testinput_xml', options_xml);

    function ValidateForm() {
        if ($.trim($('#txt_keyword').val()) != "" && $.trim($('#site_name').val()) != "") {
            $('#txt_keyword').removeClass('validationError');
            $('#site_name').removeClass('validationError');
            $('#search_form').attr('action', "<?php echo base_url(); ?>ranktracker/keywordsuggestions/" + urlencode($('#txt_keyword').val()));
            return true;
        }
        else {
            if ($.trim($('#txt_keyword').val()) == "")
                $('#txt_keyword').addClass('validationError');
            if ($.trim($('#site_name').val()) == "")
                $('#site_name').addClass('validationError');
            return false;
        }
    }

    function urlencode(str) {
        str = (str + '').toString();

        // Tilde should be allowed unescaped in future versions of PHP (as reflected below), but if you want to reflect current
        // PHP behavior, you would need to add ".replace(/~/g, '%7E');" to the following.
        return encodeURIComponent(str)
            .replace(/!/g, '%21')
            .replace(/'/g, '%27')
            .replace(/\(/g, '%28')
            .
            replace(/\)/g, '%29')
            .replace(/\*/g, '%2A')
            .replace(/%20/g, '+');
    }


    function findSimilarities(id, keyword, obj) {
        $.ajax({
            url: "<?php echo base_url();?>python_find_simil/ignitor.php",
            type: "post",
            dataType: "json",
            data: ({keyword: keyword, website: $('#site_name').val()}),
            success: function (data) {
                if (id) {
                    $("#" + id + "_similar").html(data.similarity_score);
                }
                else {
                    obj.find(".keywordresults-similiar").html(data.similarity_score);
                }

            }
        });
    }

</script>
<?php 
function GetKeywordIdeasExample($key ) {
    // Get the service, which loads the required classes.
     $user = new My_adwords_api();
    $targetingIdeaService = $user->GetService('TargetingIdeaService', ADWORDS_VERSION);

    // Create seed keyword.
    $keyword = $key;
    // Create selector.
    $keywords_details= array();
    $selector = new TargetingIdeaSelector();
    $selector->requestType = 'STATS';
   // $selector->requestType = 'IDEAS';
    $selector->ideaType = 'KEYWORD';

    $selector->requestedAttributeTypes = array('KEYWORD_TEXT', 'SEARCH_VOLUME', 'COMPETITION','AVERAGE_CPC');

     $languageParameter = new LanguageSearchParameter();
    $english = new Language();
    $english->id = 1001;
    $languageParameter->languages = array($english);
    
    $locationParameter = new LocationSearchParameter();
    $germany = new Location();
    $germany->id = 2276;
    $locationParameter->locations = array($germany);

    // Create related to query search parameter.
    $relatedToQuerySearchParameter = new RelatedToQuerySearchParameter();
    $relatedToQuerySearchParameter->queries = array($keyword);
    $selector->searchParameters[] = $relatedToQuerySearchParameter;
    $selector->searchParameters[] = $languageParameter;
    $selector->searchParameters[] = $locationParameter;

    // Set selector paging (required by this service).
    $selector->paging = new Paging(0, AdWordsConstants::RECOMMENDED_PAGE_SIZE);
    $info_array['volume']=null;
    $info_array['competition']=null;
    do {
        $page = $targetingIdeaService->get($selector);
        

        // Display results.
        if (isset($page->entries)) {
            foreach ($page->entries as $targetingIdea) {
                $data = MapUtils::GetMap($targetingIdea->data);
                $keyword = $data['KEYWORD_TEXT']->value;
                $search_volume = isset($data['SEARCH_VOLUME']->value) ? $data['SEARCH_VOLUME']->value : 0;
                //$targeted_monthly_searches = isset($data['TARGETED_MONTHLY_SEARCHES']->value) ? $data['TARGETED_MONTHLY_SEARCHES']->value : 0;
                $competition = isset($data['COMPETITION']->value) ? $data['COMPETITION']->value : 0;
                $avg_cpc = isset($data['AVERAGE_CPC']->value) ? $data['AVERAGE_CPC']->value->microAmount : 0;
//                $categoryIds = implode(', ', $data['CATEGORY_PRODUCTS_AND_SERVICES']->value);
//                printf("Keyword idea with text '%s', category IDs (%s) and average "
//                        . "monthly search volume '%s' was found.\n", $keyword, $categoryIds, $search_volume);
                /*printf("Keyword with text '%s', Average CPC '%s' average "
                        . "monthly search volume '%s' and COMPETITION '%s' was found.\n", $keyword, $avg_cpc, $search_volume, $competition);                
                echo "<br/><br/><br/>";*/
                $info_array['keywords']=$keyword;
                $info_array['cpc']=$avg_cpc;
                $info_array['volume']=$search_volume;
                $info_array['competition']=$competition;
               // $keywords_details[]=$info_array;
            }
        } else {
          unset($info_array);
                $info_array = array();
                $info_array['keywords']=$keyword;
                $info_array['cpc']=0;
                $info_array['volume']=0;
                $info_array['competition']=0;
        }
        
        // Advance the paging index.
        $selector->paging->startIndex += AdWordsConstants::RECOMMENDED_PAGE_SIZE;
    } while ($page->totalNumEntries > $selector->paging->startIndex);
   
    return $info_array;
}
?>
