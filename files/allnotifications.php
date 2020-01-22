<?php 
@session_start();
$title="Notifications | EnDetect";
include("commonfunctions.php");
include("header.php");
//echo $_SESSION['ownerid'];
?>
<body onload="notistreamdata();" ng-app="submitExample" id="pavdescription1">	
<?php include("headerbar.php");?>
<div style="clear:both"></div>
<!-- content starts here -->
<div id="content-main" ng-controller="ExampleController">
	<!-- display global alert messages -->
	<!-- sidebar template -->
	<script type="text/javascript" src="js/ajaxupload.3.5.js?<?php echo time(); ?>"></script>
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
		<?php homemenuoptions();
			//useroptions();
			categories(); ?>
		<br> <br>
	</div>
	<!-- main template -->
	<div id="pen2">
		
		<div id="pen2-left">
			<div class="col-md-12">
				<div class="col-md-4">
						<h3>All Notifications</h3>
				</div>
			</div>	
			<div class="clearfix"></div>
			<div class="message-container">
				<div id="ascrec" class="hide">
						<h4 >
							<center style="cursor: pointer;">No records found.</center>
						</h4>
				</div>
				<div class="message-form-content"><input type="hidden" id="streamuserres" name="streamuserres" value="0">
					<center style="position:fixed;top:35%;left:50%;">
						<div class="loadinggif" id="loadinggif">
							<img src="images/loading.gif">
						</div>
					</center>
					<br style="clear:both"; />
					<div id="streamuser">
						
					</div>
				</div>

				<div id="load-content" class="panel-heading hide" onclick="notistreamdata()" style='cursor:pointer;'><a href="javascript:void()"><h4 class="panel-title"><center> --Load More-- </center></h4></a></div>
				<div id="messages">
					<div style="margin: 10px; float: right;"></div>
					<br clear="all">
					<div id="resultval"></div>
				</div>
			</div>
		</div>
		<br class="clearfix">
	</div>
</div>
</div>


<?php include("footer.php")?>
