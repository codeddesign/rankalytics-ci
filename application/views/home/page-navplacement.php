<div class="productsdrop">
	<div class="producticon-wrap">
		<a href="/products/roadmap">
			<div class="roadmap-producticon"></div>
		</a>
		<a href="/developers">
			<div class="rankalyticsapi-producticon"></div>
		</a>
		<a href="/seocrawl">
			<div class="seocrawl-producticon"></div>
		</a>
		<a href="/ranktracker">
			<div class="ranktracker-producticon"></div>
		</a>
		
		<div class="whatisamodule"></div>
	</div>
</div>

<script> 
$(document).ready(function(){
  $("#queueproductsdrop").click(function(){
    $(".productsdrop").slideToggle("slow");
  });
});
</script>



<div id="headerblue"></div>
<div class="ranktracker-purchase">
	<div class="bodywrapper">
		<a href="/" class="logo"></a>
		
		<a href="/contactus">
			<div class="listheader">Support</div>
		</a>
		<a href='#' onclick='loginoverlay()'>
			<div class="listheader">Dashboard Login</div>
		</a>	
		<div class="headernavwrap">
			<!--
			<a href="/en">
				<div class="aflag"></div>
			</a>
			<a href="/de">
				<div class="gflag"></div>
			</a>
			-->
			
			<a href="/contactus" class="navdotlink" style="margin-right:0px;">CONTACT</a>
			<div class="navdotdrop" id="queueproductsdrop">PRODUCTS</div>
			<!--<a href="/demo" class="navdotlink">API DEMO!</a>-->
			<!--<a href="/features" class="navdotlink">FEATURES</a>-->
			<a href="/developers" class="navdotlink">DEVELOPERS</a>
		</div>