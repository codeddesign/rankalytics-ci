<?php 

if(isset($keyword)){
// $adwords = new Ranktracker();

  
  $keyword=  urldecode($keyword);
 
 
  
  
  $keywords = array();
       $master_keywords = array();
       $child_keywords = array();
       if($keyword)
       {
        $proxy_array = $this->analytical->getRandomProxy();
           
        $proxy_ip = $proxy_array[0]['ip'];
        $uname_password = $proxy_array[0]['username'].":".$proxy_array[0]['password'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        // curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:28.0) Gecko/20100101 Firefox/28.0");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_PROXYTYPE, 'HTTP');
        curl_setopt($ch, CURLOPT_PROXY,$proxy_ip);
        curl_setopt($ch, CURLOPT_PROXYUSERPWD, $uname_password);
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);  
        //curl_setopt($ch, CURLOPT_URL, 
        //'http://suggestqueries.google.com/complete/search?output=firefox&client=firefox&hl=en_US&q='.urlencode($keyword));
        curl_setopt($ch, CURLOPT_URL, 'http://suggestqueries.google.com/complete/search?q='.urlencode($keyword).'&client=firefox&hl=de');

        $data = curl_exec($ch);
        $data = (string)$data;
        $data = utf8_encode($data);
        //$data = iconv(mb_detect_encoding($data, mb_detect_order(), true), "utf8", $data);
        if (($data = json_decode($data, true)) !== null) 
        {
            $master_keywords = $data[1];
        }  

        $proxy_array =  shuffle($proxy_array);
        $count = 0;
        foreach ($master_keywords as $key => $value) 
        {
            $proxy_ip = $proxy_array[$count]['ip'];
            $uname_password = $proxy_array[$count]['username'].":".$proxy_array[0]['password'];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
            // curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:28.0) Gecko/20100101 Firefox/28.0");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_PROXYTYPE, 'HTTP');
            curl_setopt($ch, CURLOPT_PROXY,$proxy_ip);
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $uname_password);
            curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);  
            curl_setopt($ch, CURLOPT_URL, 'http://suggestqueries.google.com/complete/search?q='.urlencode($value).'&client=firefox&hl=de');
            $data = curl_exec($ch);
            $data = (string)$data;
            $data = utf8_encode($data);
            if (($data = json_decode($data, true)) !== null) 
            {
                $child_keywords = $data[1];
            }
            $keywords[$key]=array("master_keyword" => $value, "child_keywords" =>$child_keywords);
            $count++;
        }
           
       }
       
   // print_r($keywords);
}
        ?>

<div class="yellowtopline"></div>
	<div class="topinfobar">
		<div class="weathericon">
			<img src="<?php echo base_url(); ?>assets/images/weather/sun.png"/>
		</div>
		<div class="weathertext">Google Temperature</div>
		
		<div class="toptitlebar">KEYWORD RANKINGS</div>
	</div>
	<div class="projectbackground">
		<div class="leftsidebar">
			<div class="leftsidebarbutton-one">
				<div class="ranksicons"></div>
			</div>
				<div class="leftsidebarout-one">Rankings</div>
			<div class="leftsidebarbutton-two">
				<div class="reportsicons"></div>
			</div>
				<div class="leftsidebarout-two">Reports</div>
			<div class="leftsidebarbutton-three"></div>
				<div class="leftsidebarout-three"></div>
			<div class="leftsidebarbutton-four"></div>
				<div class="leftsidebarout-four"></div>
		</div>
		<div class="dashcontent">
                    <form class="keywordresearch-form" method="post" id="search_form" onsubmit="return ValidateForm()">
				<div class="keywordresearch-titletop">begin by entering a starting keyword</div>
				<input class="keywordresearch-keyword" type="text" name="txt_keyword" placeholder="Enter keyword" onkeypress="onkey(this.value)" id="txt_keyword" value="<?php echo @$keyword?>">
				<div class="keywordresearch-titletop">type in your domain (without http://)</div>
				<input class="keywordresearch-domain" type="text" name="username" placeholder="Enter domain without http://">
				<input type="submit" value="" class="keywordresearch-button">
                    </form>
                        <?php if(!empty($keywords)):?>
			<div class="keywordresearch-wrap">
				<div class="keywordresearch-titlekeyword">KEYWORD</div>
				<div class="keywordresearch-titlesimiliar">SIMILARITY %</div>
				<div class="keywordresearch-titlemonthly">MONTHLY SEARCHES</div>
				<div class="keywordresearch-titlecompetition">COMPETITION</div>
				<div class="keywordresearch-titlecpc">CPC</div>
				
				<div class="keywordresearch-undertitleline"></div>
				
				<ul class="keywordresearch-keywordbg">
                                        <?php foreach ($keywords as $key => $value):?>
					<li>
                                            <?php 
                                            $keyword_detials= GetKeywordIdeasExample($value['master_keyword']);
                                            ?>
                                            
						<a href="javascript:toggle_visibility('<?php echo "item_".$key?>');" class="keywordresearch-checkboxarea">
							<div class="keywordresearch-drop"></div>
						</a>	
						<div class="keywordresults-keyword"><?php echo $value['master_keyword']?></div>
						<div class="keywordresults-similiar">87%</div>
						<div class="keywordresults-monthly"><?php echo $keyword_detials['volume']?></div>
						<div class="keywordresults-competition"><?php echo round( $keyword_detials['competition'],3)?></div>
						<div class="keywordresults-cpc">€<?php echo  round( $keyword_detials['cpc']/1000000,3)?></div>
					</li>
					<!-- on click display -->
                                        <div id="<?php echo "item_".$key?>" style="display: none;">
                                             <?php foreach ($value['child_keywords'] as $key => $value):?>
                                         
						<li >
                                                      <?php 
                                            //$keyword_childs= GetKeywordIdeasExample($value);
                                           ?>
							<div class="keywordresults-keyword"><?php echo $value?></div>
							<div id="<?php echo "similar_".$key?>" class="keywordresults-similiar">87%</div>
                                                        <div id="<?php echo "volume_".$key?>" class="keywordresults-monthly"><?php if(isset($keyword_childs)) {echo $keyword_childs['volume'] ;}?></div>
                                                        <div id="<?php echo "competition_".$key?>"class="keywordresults-competition"><?php if(isset($keyword_childs)) { echo round( $keyword_childs['competition'],3);}?></div>
                                                        <div id="<?php echo "cpc_".$key?>" class="keywordresults-cpc">€<?php if(isset($keyword_childs)) { echo  round( $keyword_childs['cpc']/1000000,3);}?></div>
						</li>
                                                <?php endforeach;?>
						</div>
					<!-- end on click display -->
                                        <?php endforeach;?>
				</ul>
				<div class="keywordresearch-addselected"></div>
                                <?php endif;?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bsn.AutoSuggest_2.1.3.js" charset="utf-8"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/autosuggest_inquisitor.css" />
<input type="text" id="testid" value="" style="font-size: 10px; width: 20px; display: none" disabled="disabled" />
	
<script type="text/javascript">
 
 
	var options = {
		script:"<?php echo base_url();  ?>pdf/get_suggestion/true/1000/",
		varname:"",
		json:true,
		shownoresults:false,
		maxresults:1000,
		callback: function (obj) { document.getElementById('testid').value = obj.id; }
	};
	var as_json = new bsn.AutoSuggest('txt_keyword', options);
	
	
	var options_xml = {
		script: function (input) { //return "get_suggestion/"+input+"/testid="+document.getElementById('testid').value; 
                                                         "test.php?input="+input+"&testid="+document.getElementById('testid').value;
                },
		varname:""
	};
	var as_xml = new bsn.AutoSuggest('testinput_xml', options_xml);
 
function ValidateForm()
{
    if($.trim($('#txt_keyword').val()) !="")
    {
        $('#txt_keyword').removeClass('validationError');
        $('#search_form').attr('action', "<?php echo base_url(); ?>ranktracker/keywordsuggestions/" + $('#txt_keyword').val());
        return true;
    }
    else
    {
        $('#txt_keyword').addClass('validationError');
        return false;
    }
}

    
<!--
    function toggle_visibility(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'block')
          e.style.display = 'none';
       else
          e.style.display = 'block';
    }
//-->
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
            print "No keywords ideas were found.\n";
            echo "<br/><br/><br/>";
        }
        
        // Advance the paging index.
        $selector->paging->startIndex += AdWordsConstants::RECOMMENDED_PAGE_SIZE;
    } while ($page->totalNumEntries > $selector->paging->startIndex);
   
    return $info_array;
}
?>
