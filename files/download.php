<?php 
@session_start();
$title="Activity Log | EnDetect";
include("commonfunctions.php");
checkOwnerSession();
include("header.php");
//echo $_SESSION['ownerid'];
?>
<body ng-app="submitExample" id="pavdescription1" onload="homestreamdata();notifycount(<?php echo $_SESSION['ownerid'] ?>);latestimages('')">
<?php include("headerbar.php");?>
<div style="clear:both"></div>
<div class="main-container">
<div class="row" ng-controller="ExampleController">
	<div class="col-md-12" style="background-color: #fff;">
		<div class="col-md-3">
			<?php
				homemenuoptions();
				//useroptions();
				categories();
			?>
		</div>
		<div class="col-md-6">
			<div id="latestimages">
			</div>
			<br />
			<div class="col-md-12">
				<div class="col-md-4">
						<h3 class="">Activity Log</h3>
				</div>
				<div class="col-md-6 text-right" style="float: right;">
						<a href="javascript:void(0);" id="auto-refresh" onclick="autorefresh()" class="btn btn-primary btn-xs">Auto Refresh <i class="glyphicon glyphicon-refresh"></i></a><a href="javascript:void(0);" id="exit-auto-refresh" onclick="exitautorefresh()" class="btn btn-primary btn-xs hide">Exit Auto Refresh <i class="glyphicon glyphicon-refresh"></i></a><!-- &nbsp;&nbsp;<a href="javascript:void(0)" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#DeleteAllModal">Delete <i class="glyphicon glyphicon-trash"></i></a> -->
				</div>
			</div>
			<div class="clearfix"></div>
			<hr>	
			<div class="clearfix"></div>
			<div class="message-container">
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
			</div>
			<div id="load-content"></div>
			<div id="messages">
				<div style="margin: 10px; float: right;"></div>
				<br clear="all">
				<div id="resultval"></div>
			</div>
			<center></center>
		</div>
		<div class="col-md-3">
			<?php 
				footerContent();
			?>

		</div>
	</div>
</div>
</div>
</div>
<?php include("footer.php"); ?>
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
	<style>
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
		</style>

