<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<META http-equiv="Cache-Control" content="no-cache">
<META http-equiv="Pragma" content="no-cache">
<META http-equiv="Expires" content="0">

<title><?php echo (isset($meta_title)&&$meta_title!='')?$meta_title:"Ranktracker"; ?></title>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url(); ?>assets/style.css"/>

<!--link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url(); ?>assets/js/jquery.horizontal.scroll.css"/-->
<link href="http://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
<link href="<?php echo base_url(); ?>assets/js/jquery-ui-datepicker/css/smoothness/jquery-ui-1.10.3.custom.css" rel="stylesheet" type="text/css">

<!-- Google CDN jQuery with fallback to local -->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery-1.11.0.min.js" type="text/javascript"></script>
<script type="text/javascript" src="//use.typekit.net/pjn4zge.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<!--script src="<?php echo base_url(); ?>assets/js/jquery.horizontal.scroll.js" type="text/javascript"></script-->
<script src="<?php echo base_url(); ?>assets/js/jquery-ui.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/modernizer-custom.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/spiner.js" type="text/javascript"></script>
  		
<script type="text/javascript">
   
    function toggle_visibility(id) {

       var e = document.getElementById(id);
      
       if(e.style.display == 'block'){
          e.style.display = 'none';
      }
       else{
          
          e.style.display = 'block';}
    }
     
        
	
	function dashoverlay(){
	el = document.getElementById("dashoverlay");
	el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";
	}

	function closeSelf() {
     document.getElementById("dash overlay").style.visibility = 'hidden';
	}
	
	function dashglobaloverlay(){
	el = document.getElementById("dashglobaloverlay");
	el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";
	}

	function closeSelf() {
     document.getElementById("dashglobaloverlay").style.visibility = 'hidden';
	}

   
       
</script>
<style type="text/css">
		label {margin-right:20px;}
                 body {
    background: none repeat scroll 0 0 #394453 !important;
    margin: 0;
}



</style>
</head>
<body onload="init()">

<div class="headerline"></div>
<div class="bodywrap">
    <a href="<?php echo base_url(); ?>"><div class="logo"></div></a>
	<div class="headernav">
	<?php	
                  if(isset($current)){
                  $data['current']=$current;
                  }
                  else{
                   $data['current']="";
                  }
           echo  $this->load->view("include/seocrawlnavheader" ,$data);
            ?>
	</div>
    
    
    
    
     