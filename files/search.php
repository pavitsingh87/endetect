<?php 
include("commonfunctions.php");
if($_REQUEST['cutomisedate']=='1')
{
	$_REQUEST['from']=$_REQUEST['from'];
	$_REQUEST['to']=$_REQUEST['to'];
}
else
{
	$datewithin = $_REQUEST["datewithin"];
	
	switch ($datewithin)
	{
		case "1" :
		$filterdate="Filter by today";
		$fromdate = Date("d-M-Y",time());
		$fromdate = strtotime($fromdate);
		//$_REQUEST['from'] = Date("d-M-Y",strtotime("-1 day", $fromdate));
		$_REQUEST['from'] = Date("d-M-Y",time());
		$_REQUEST['to']=Date("d-M-Y",time());
		break;
		case "2" :
		$filterdate="Filter by this week";
		$fromdate = Date("d-M-Y",time());
		$fromdate = strtotime($fromdate);
		$_REQUEST['from'] = Date("d-M-Y",strtotime("-7 day", $fromdate));
		$_REQUEST['to']=Date("d-M-Y",time());
		break;
		case "3" :
		$filterdate="Filter by this month";
		$fromdate = Date("d-M-Y",time());
		$fromdate = strtotime($fromdate);
		$_REQUEST['from'] = Date("d-M-Y",strtotime("-1 month", $fromdate));
		$_REQUEST['to']=Date("d-M-Y",time());
		break;
		case "4" :
		$filterdate="Filter by this year";
		$fromdate = Date("d-M-Y",time());
		$fromdate = strtotime($fromdate);
		$_REQUEST['from'] = Date("d-M-Y",strtotime("-1 year", $fromdate));
		$_REQUEST['to']=Date("d-M-Y",time());
		break;
		case "5" :
		$filterdate="";
		$fromque = $conn->query("select jdt from U_endowners where sno='".$_SESSION['ownerid']."'");
		$fromquery = @$fromque->fetch_assoc();
		
		$fromda = strtotime($fromquery['jdt']);
		
		$_REQUEST['from'] =Date("d-M-Y",$fromda);
		$_REQUEST['to'] = Date("d-M-Y",time());
		break;
		default :
		$filterdate="";
		$fromque = $conn->query("select jdt from U_endowners where sno='".$_SESSION['ownerid']."'");
		$fromquery = @$fromque->fetch_assoc();
		
		$fromda = strtotime($fromquery['jdt']);
		
		$_REQUEST['from'] =Date("d-M-Y",$fromda);
		$_REQUEST['to'] = Date("d-M-Y",time());
		break	;
	} 
}
@$conn->close();


//$_REQUEST['q1']="searchexactword:".$_REQUEST['q1']."+".$_REQUEST['musthave'];
?>
<?php include("header.php");?>
<body
	onload="searchstream('<?php echo @$_REQUEST['getusersearchid'] ?>','<?php echo $_REQUEST['optionsearch'] ?>','<?php echo @$_REQUEST['q1'] ?>','<?php echo @$_REQUEST['musthave']; ?>','<?php echo @$_REQUEST['mustnothave']; ?>','<?php echo @$_REQUEST['from']; ?>','<?php echo @$_REQUEST['to']; ?>','<?php echo @$_REQUEST['ntincludeimg']; ?>','<?php echo $_REQUEST['searchtypes']; ?>')"
	ng-app="submitExample" id="pavdescription1">	
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

	<!-- content starts here -->
	<div id="content-main" ng-controller="ExampleController">
		<!-- display global alert messages -->
		<!-- sidebar template -->
		<script type="text/javascript" src="js/ajaxupload.3.5.js"></script>
		<script type="text/javascript" src="js/datepicker.js"></script>
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
		<?php
		if($_REQUEST["getusersearchid"]!="") { 
			userprofile_account($_REQUEST["getusersearchid"]); 
			leftbar_account($_REQUEST["getusersearchid"]);
			categories();
			deleteUserModal();
		} 
		else { 
			homemenuoptions(); 
			categories(); 
		} ?>
		<br /> <br />
		</div>
		<!-- main template -->
		<div id="pen2">

			<div id="pen2-left">
				<div class="col-md-12">
					<div class="col-md-5">
						<div class="bootstrap-tagsinput">
							<?php if($_REQUEST['q1']!='') { ?>
							<span class="tag label label-success" id="ddatasearchtag" alt="Search text">
								<?php echo $_REQUEST['q1'];?>
								<span class="glyphicon glyphicon-remove remove" onclick="resetdatasearch()"></span>
							</span>&nbsp;&nbsp; 
							<?php } ?>
							<?php if($_REQUEST['searchinobj']!='') {
								$switchcat = $_REQUEST["searchinobj"];
								switch ($switchcat)
								{
									case "11" :
									$_REQUEST["categoryname"]="Search in all data";
									break;
									case "12" :
									$_REQUEST["categoryname"]="Search in app title";
									break;
									case "2" :
									$_REQUEST["categoryname"]="Search in typed text";
									break;
									case "3" :
									$_REQUEST["categoryname"]="Search in copied text";
									break;
									case "4" :
									$_REQUEST["categoryname"]="Search in copied files";
									break;
									case "5" :
									$_REQUEST["categoryname"]="Search in pendrive files";
									break;
									case "13" :
									$_REQUEST["categoryname"]="Search in watchlist data";
									break;
								}
							?>
							<span class="tag label label-success label-important" id="categorytag"  alt="Category" title="Category">
								<?php echo $_REQUEST['categoryname'];?>
								<span class="glyphicon glyphicon-remove remove" onclick="resetcategory()"></span>
							</span>&nbsp;&nbsp;
							<?php } ?>
							<?php if($_REQUEST['musthave']!='') { ?>
							<span class="tag label label-primary" id="musthavetag" alt="Include words"  title="Include words">
								<?php echo $_REQUEST["musthave"];?>
								<span class="glyphicon glyphicon-remove remove" onclick="resetmusthave()"></span>
							</span>&nbsp;&nbsp;
							<?php } ?>
							<?php if($_REQUEST['mustnothave']!='') { ?>
							<span class="tag label label-danger" id="mustnothavetag"  alt="Exclude words"   title="Exclude words">
								<?php echo $_REQUEST['mustnothave'];?>
								<span class="glyphicon glyphicon-remove remove" onclick="resetmustnothave()"></span>
							</span>&nbsp;&nbsp; 
							<?php } ?>	
							<?php if($_REQUEST['ntincludeimg']=='1') { ?>
							<span class="tag label label-danger" id="excludeimagestag"  alt="Exclude images" title="Exclude images">
								Exclude images
								<span class="glyphicon glyphicon-remove remove" onclick="resetexcludeimages()"></span>
							</span>&nbsp;&nbsp; 
							<?php } ?>		
						</div>
						<div class="bootstrap-tagsinput" style="margin-top:5px;">
							<?php if($_REQUEST['cutomisedate']=='1') { ?>
							<span class="tag label label-warning" id="cutomisedatetag"  alt="Date Filter" title="Date filter">
								from <?php echo $_REQUEST['from']; ?> to <?php echo $_REQUEST['to']; ?>
								<span class="glyphicon glyphicon-remove remove" onclick="resetfilterdate()"></span>
							</span>&nbsp;&nbsp; 
							<?php }
							else 
							{
								if($filterdate!='') {
									?>
									<span class="tag label label-warning" id="cutomisedatetag"  alt="Date Filter" title="Date filter">
										<?php echo $filterdate; ?>
										<span class="glyphicon glyphicon-remove remove" onclick="resetfilterdate()"></span>
									</span>&nbsp;&nbsp; 
									<?php 
								}
							}
							?>					
						</div>
					</div>
					<?php if($_SERVER["REMOTE_ADDR"]=="124.123.245.68") {  ?>
					<div class="col-md-5 text-right"><b> Create an alert </b><input type="checkbox" id="trayIconToggle" data-height="22" data-width="68"></div>
					<div class="clearfix"></div>
						<script>
						  $(function() {
						  	$('#trayIconToggle').change(function() {
						      	val = $(this).prop('checked');
						  		/* angular.element(document.getElementById('load-content')).scope().settingsToggle(val,eid,"3"); */
						    });
						    $('#trayIconToggle').bootstrapToggle({
						      on: 'Enabled',
						      off: 'Disabled'
						    });
						  })
						</script>
						<style>
						.toggle.btn{min-width:59px;min-height:22px !important; margin: 2px;}
						#DeleteAllModal
						{
							font-family:"Segoe UI", Arial, sans-serif;
						}
						#DeleteAllModal h5
						{
							font-size:16px;
						}
						.m-t-10
						{
							font-size:13px;
						}
						
						#load-content label 
						{
							line-height:22px !important;
						}
						#lazyMinutesTimer
						{
							width:100px;
						}
						#screenShotTimer 
						{
							width:100px;
						}
						#pin 
						{
							width:80px;
						}
						#inputoverlapbutton
						{
							left:-15px;
						}
						.input-group
						{
							width:100px;
						}
						</style>
						<?php } ?>
				</div>
				<div class="clearfix"></div>
				<div class="message-container">
					<div class="message-form-content">
						<input type="hidden" id="streamuserres" name="streamuserres"
							value="0">
						<center style="position: fixed; top: 35%; left: 50%;">
							<div class="loadinggif" id="loadinggif">
								<img src="images/loading.gif">
							</div>
						</center>
						<br style="clear: both" ; />
						<div id="streamuser"></div>

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

	<!-- footer starts here 
	<div id="footerContainer"></div>-->
	<!-- footer ends here -->
	</div>


<?php include("footer.php")?>
