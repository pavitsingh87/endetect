<?php include("header.php");?>
<body onload="iframpn(<?php echo $_REQUEST['sno']?>,<?php echo $_REQUEST['enduserid'] ?>)"  ng-app="submitExample" id="pavdescription1">
<?php include("headerbar.php");?>

<script type="text/javascript">
$(document).ready(function()
{
	
	$("#notificationLink").click(function()
	{
		if(document.getElementById("notificationContainer").style.display=='none')
		{
			document.getElementById("notificationContainer").style.display="block";
			document.getElementById("notificationcount").style.display="none";
			document.getElementById("notifycnt").value="0";
			notify('<?php echo $_SESSION["ownerid"] ?>');
		}
		else
		{
			document.getElementById("notificationContainer").style.display="none";
		}
			
		
		$("#notification_count").fadeOut("slow");
		$("#logoutContainer").hide();
		return false;
	});
	$("#notificationClose").click(function()
	{		
	
		$("#notificationContainer").hide();
	});
	
	//Document Click
	$(document).click(function()
	{		
		$("#notificationContainer").hide();
	});
	//Popup Click
	$("#notificationContainer").click(function()
	{
		return false
	});
	$("#logoutLink").click(function()
	{
		$("#logoutContainer").fadeToggle(300);
		$("#notificationContainer").hide();
		return false;
	});

	//Document Click
	$(document).click(function()
	{
		$("#logoutContainer").hide();
	});
	//Popup Click
	$("#logoutContainer").click(function()
	{
		return false
	});
});
</script>
<script>
function nextresult()
{
    	 var x = document.getElementsByClassName("divboxr");
         
    	 var lengthp = parseInt(x.length)-1;
    	 alert(lengthp);
    	 nexttwo(x[lengthp].value,<?php echo $_REQUEST['enduserid'];?>);
    	 
    }
   function prevresult() {
		 
   	 	 var x = document.getElementsByClassName("divboxr");
		 var lengthp = parseInt(x.length)-1;
         prevtwo(x[0].value,<?php echo $_REQUEST['enduserid']?>);
         
            
    }

</script>
<script type="text/javascript">
function scrolltosno (sno)
{
$('html, body').animate({
    scrollTop: $("#endetect"+sno).offset().top
    }, 2000);
}

</script>
<!-- content starts here -->
<div id="content-main" ng-controller="ExampleController">
	<!-- display global alert messages -->
	<!-- sidebar template -->
	<script type="text/javascript" src="js/ajaxupload.3.5.js"></script>
	<style type="text/css">
		#profile-picture {
			position: relative;
			margin-left: 1px;
		}
		
		#profile-picture-caption {
			line-height: 26px;
			margin-bottom: -1px;
			margin-left: 4px;
			width: 180px;
		}
	</style>
	<div id="pen1">
		<div id="profile-picture"></div>
		<br class="clearfix">
		<?php include("sidemlinks.php");?>
		<br> <br>
	</div>
	<!-- main template -->
	<div id="pen2">
		
		<div id="pen2-left">	
			<div id="latestimages">
				
			</div>
			<div class="message-container">
				<div class="message-form-content"><input type="hidden" id="streamuserres" name="streamuserres" value="0">
					<center style="position:fixed;top:50%;left:50%;">
						<div class="loadinggif" id="loadinggif">
							<img src="images/loading.gif">
						</div>
					</center>
					<br style="clear:both"; />
					<div class="prevrecord" id="prevrecord"  style="cursor:pointer;" >
					<span onclick="prevresult()" style="padding-left:50%;padding-right:50%;"><span class="glyphicon glyphicon-open"> </span></span>
					</div>
					<div id="streamuser">
						
					</div>
					<div class="nextrecord" id="nextrecord" style="cursor:pointer;">
					<span onclick="nextresult()" style="padding-left:50%;padding-right:50%;"><span class="glyphicon glyphicon-save"> </span></span>
					</div>
				</div>
			</div>
			<div id="load-content"></div>
			<div id="messages">
				<div style="margin: 10px; float: right;"></div>
				<br clear="all">
				<div id="resultval"></div>
			</div>
			<center></center>
		</div>
		<br class="clearfix">
	</div>
    
    
<!-- header ends here -->

	<!-- pagination -->
</div>



<?php include("footer.php")?>
