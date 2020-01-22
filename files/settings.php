<?php
$title="Settings | EnDetect";
if(isset($_REQUEST['eid'])) {
	$eid = $_REQUEST["eid"];
	$enduserid = base64_decode($_REQUEST["eid"]);
} else {
	$enduserid = '';
}

include_once("commonfunctions.php");
checkOwnerSession();
include_once("header.php");
//echo $_SESSION['ownerid'];
?>
<body ng-app="submitExample" id="pavdescription1">
<?php include_once("headerbar.php"); ?>
<div style="clear:both"></div>
<div id="content-main" ng-controller="ExampleController">
	<div id="pen1">
		<div id="profile-picture"></div>
		<br class="clearfix">
		<?php
			if($_REQUEST["eid"]!="")
			{
				userprofile_account($enduserid);
				leftbar_withoutlive_account($enduserid);
				deleteUserModal();
			}
			else
			{
				homemenuoptions();
				//categories();
			}
		?>
		<br> <br>
	</div>
	<div id="pen2">
		<div id="pen2-left">
			<div class="col-md-12">
				<div class="col-md-8">
					<h3><?php
						if(isset($_GET['eid'])) {
							echo "User Settings";
						} else {
							echo "Global Settings";
						}
					?></h3>
				</div>
				<div class="col-md- text-right" style="float: right;">
				</div>
			</div>
			<div class="clearfix"></div>
			<hr>
			<div class="clearfix"></div>
			<div id="load-content" class="hide" ng-init="getSettingValues('<?php echo @$_GET['eid']; ?>')">
				<br>
				<div class="col-md-12">
				 	<form action="">
				 		<div class="col-md-12">
				 			<div class="col-md-4" style="line-height:33px;"><b>Screenshot</b> <i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="top" title="Hooray!"></i></div>
				 			<div class="col-md-6"><input type="checkbox" id="screenShotToggle" data-height="15" data-width="68"></div>
				 		</div>
				 		<div class="col-md-12 hide" id="screenshottimeinterval">
				 			<div class="col-md-4" style="line-height:33px;"><b>Screenshot Frequency</b></div>
				 			<div class="col-md-6">
				 				<select id="screenShotTimer" class="form-control" ng-init="screenShotTimer='1'" ng-model="screenShotTimer">
				 					<?php
									$arrLoop = array(1, 2, 3, 5, 10, 15, 20, 30);
									foreach ($arrLoop as $val) { ?>
				 						<option value="<?php echo $val; ?>">every <?php echo $val; ?>min</option>
				 					<?php } ?>
				 				</select>
				 			</div>
				 		</div>
				 		<div class="col-md-12">
				 			<div class="col-md-4" style="line-height:33px;"><b>System Tray Icon</b> <i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="top" title="Hooray!"></i></div>
				 			<div class="col-md-6"><input type="checkbox" id="trayIconToggle" data-height="22" data-width="68"></div>
				 		</div>
						<div class="col-md-12">
				 			<div class="col-md-4" style="line-height:33px;"><b>Key Stroke</b> <i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="top" title="Hooray!"></i></div>
				 			<div class="col-md-6"><input type="checkbox" id="keyLogsToggle" data-height="22" data-width="68"></div>
				 		</div>
				 		<div class="col-md-12">
				 			<div class="col-md-4" style="line-height:33px;"><b>Web History</b> <i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="top" title="Hooray!"></i></div>
				 			<div class="col-md-6"><input type="checkbox" id="webHistoryToggle" data-height="22" data-width="68"></div>
				 		</div>

						<?php if(!isset($_GET['eid'])) { ?>
				 		<div class="col-md-12">
				 			<div class="col-md-4" style="line-height:33px;"><b>Stealth Install</b> <i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="top" title="Hooray!"></i></div>
				 			<div class="col-md-6"><input type="checkbox" id="installToggle" data-height="22" data-width="68"></div>
				 		</div>
						<div class="col-md-12">
				 			<div class="col-md-4" style="line-height:33px;"><b>Pause All Users</b> <i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="top" title="Hooray!"></i></div>
				 			<div class="col-md-6"><input type="checkbox" id="pauseToggle" data-height="22" data-width="68"></div>
				 		</div>
						<?php } ?>

				 		<div class="col-md-12">
				 			<div class="col-md-4" style="line-height:33px;"><b>Idle Time</b></div>
				 			<div class="col-md-6">
				 				<select id="lazyMinutesTimer"  class="form-control" ng-init="lazyMinutesTimer='1'" ng-model="lazyMinutesTimer">
				 					<?php
									$arrLoop = array(1, 2, 3, 5, 10, 15, 20, 30, 45, 60);
									foreach ($arrLoop as $val) { ?>
				 						<option value="<?php echo $val; ?>">idle for <?php echo $val; ?>min</option>
				 					<?php } ?>
				 				</select>
				 			</div>
				 		</div>

				 		<div class="col-md-12">
				 			<div class="col-md-4" style="line-height:33px;"><b>USB Drive Access</b> <i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="top" title="Hooray!"></i></div>
				 			<div class="col-md-6"><input type="checkbox" id="usbBlockToggle" data-height="22" data-width="68"></div>
				 		</div>
				 		<div class="col-md-12">
				 			<div class="col-md-4" style="line-height:33px;"><b>Task Manager Access</b> <i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="top" title="Hooray!"></i></div>
				 			<div class="col-md-6"><input type="checkbox" id="taskMToggle" data-height="22" data-width="68"></div>
				 		</div>

				 		<?php if($_GET["eid"]=="") { ?>
				 		<div class="col-md-12">
				 			<div class="col-md-4" style="line-height:33px;"><b>Delete PIN</b> <i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="top" title="You need this Pin to Delete data"></i></div>
				 			<div class="col-md-6">
				 				<div class="col-md-6" style="padding-left:0;">
					 				<span id="currentPin" style="line-height:35px;"><b>{{pin}} <a href="javascript:void()" ng-click="editPin()"><i class="glyphicon glyphicon-pencil"></i></b></a></span>
					 				<span id="editPin" class="hide">
					 					<div class="entry input-group col-xs-3">
				                        	<input class="form-control" name="pin" type="text" ng-model="pin" maxlength="4" id="pin" placeholder="Pin" numbers-only />
					                    	<span class="input-group-btn">
					                            <button class="btn btn-default btn-add" type="button" ng-click="savepin(pin)">
					                                <span class="glyphicon glyphicon-ok"></span>
					                            </button>
					                        </span>
	                    				</div>
	                    			</span>
                    			</div>
                    			<div id="pinError" class="col-md-4 alert alert-warning hide" style="padding:5px;">
                    				Must be 4 numbers.
                    			</div>
                    		</div>
				 		</div>
				 		<?php } ?>

						<div class="col-md-12" style="padding-top:10px;">
						<?php if(isset($_GET["eid"]) && $_GET["eid"] != "") { ?>
							<div class="col-md-4">&nbsp;</div>
				 			<div class="col-md-6">
								<button type="button" class="btn ssbtn" id="updateOneUser">Apply Changes</button>
								<input type="hidden" id="eid" value="<?php echo base64_decode($_GET["eid"]); ?>">
							</div>
						<?php } else { ?>
							<div class="col-md-2">&nbsp;</div>
				 			<div class="col-md-8">
								<button type="button" class="btn ssbtn" id="updateBoth">Apply to all users</button>&nbsp;&nbsp;
								<button type="button" class="btn ssbtn" id="updateOnlyGlobal">Apply to new users only</button>
							</div>
						<?php } ?>
				 		</div>
						<div class="col-md-12 hidden ssLoading">
							<img src="./images/loading.gif" alt="">
						</div>
						<div class="col-md-12 hidden ssMsg" style="padding-top:5px">
							<h3>Successfully Updated</h3>
						</div>

						<script>
						$(function() {
							$.fn.getCommonData = function() {
								var obj = new Object();
								obj.screenShot = $('#screenShotToggle').is(':checked') ? "1" : "0";
								obj.trayIcon = $('#trayIconToggle').is(':checked') ? "1" : "0";
								obj.keyLogs = $('#keyLogsToggle').is(':checked') ? "1" : "0";
								obj.webHistory = $('#webHistoryToggle').is(':checked') ? "1" : "0";
								obj.install = $('#installToggle').is(':checked') ? "1" : "0";
								obj.pause = $('#pauseToggle').is(':checked') ? "1" : "0";
								obj.usbBlock = $('#usbBlockToggle').is(':checked') ? "1" : "0";
								obj.taskM = $('#taskMToggle').is(':checked') ? "1" : "0";
								obj.screenShotTimer = $('#screenShotTimer').val();
								obj.lazyMinutesTimer = $('#lazyMinutesTimer').val();
								return obj;
							};

							$.fn.callAPIFun = function(jsonString) {
								$('.ssLoading').removeClass('hidden');
								$('.ssMsg').addClass('hidden');

								$.ajax({
									url: "<?php echo baseurl .'api/ed/updateSettingNew'; ?>",
									beforeSend: function(request) {
										request.setRequestHeader("X-API-KEY", "A1F584C3083132CE18DCDA579C753579D3276AAB");
									},
									method: 'POST',
									data: jsonString,
									dataType: 'json'
								}).fail(function(data) {
									console.log('failed', data);
								}).done(function(data) {
									console.log(data);
									$('.ssLoading').addClass('hidden');
									$('.ssMsg').removeClass('hidden');
								});
							};

							$('#updateOneUser').click(function() {
								var jsonObj = $.fn.getCommonData();
								jsonObj.flag = "3";
								jsonObj.eid = $('#eid').val();
								var jsonString = JSON.stringify(jsonObj);
								$.fn.callAPIFun(jsonString);
							});

							$('#updateOnlyGlobal').click(function() {
								var jsonObj = $.fn.getCommonData();
								jsonObj.flag = "1";
								var jsonString = JSON.stringify(jsonObj);
								//console.log(jsonString);
								$.fn.callAPIFun(jsonString);
							});

							$('#updateBoth').click(function() {
								var jsonObj = $.fn.getCommonData();
								jsonObj.flag = "2";
								var jsonString = JSON.stringify(jsonObj);
								//console.log(jsonString);
								$.fn.callAPIFun(jsonString);
							});


							$('[data-toggle="tooltip"]').tooltip();
							$('#screenShotToggle').change(function() {
								if ($(this).prop('checked') == true) {
									$("#screenshottimeinterval").removeClass("hide");
								} else {
									$("#screenshottimeinterval").addClass("hide");
								}
							});

							$("#screenShotToggle").bootstrapToggle({on:"Enabled",off:"Disabled"});
							$("#trayIconToggle").bootstrapToggle({on:"Enabled",off:"Disabled"});
							$("#keyLogsToggle").bootstrapToggle({on:"Enabled",off:"Disabled"});
							$("#webHistoryToggle").bootstrapToggle({on:"Enabled",off:"Disabled"});
							$("#installToggle").bootstrapToggle({on:"Enabled",off:"Disabled"});
							$("#pauseToggle").bootstrapToggle({on:"Enabled",off:"Disabled"});
							$("#usbBlockToggle").bootstrapToggle({on:"Enabled",off:"Disabled"});
							$("#taskMToggle").bootstrapToggle({on:"Enabled",off:"Disabled"});
						})
						</script>
					</form>
				</div>
				<div class="cleafix"></div>
			</div>
			<div id="messages">
				<div style="margin: 10px; float: right;"></div>
				<br clear="all">
				<div id="resultval"></div>
			</div>
			<center></center>
		</div>
		<br class="clearfix">
		<!-- Modal -->
		<style>
		.toggle.btn{min-width:59px;min-height:22px!important;margin:2px}
		#DeleteAllModal{font-family:"Segoe UI",Arial,sans-serif}
		#DeleteAllModal h5{font-size:16px}
		.m-t-10{font-size:13px}
		#load-content label{line-height:22px!important}
		#lazyMinutesTimer{width:100px}
		#screenShotTimer{width:100px}
		#pin{width:80px}
		#inputoverlapbutton{left:-15px}
		.input-group{width:100px}
		.ssbtn{background-color:#000;color:#fff;}
		</style>
	</div>
</div>
</div>
<?php include_once("footer.php"); ?>
