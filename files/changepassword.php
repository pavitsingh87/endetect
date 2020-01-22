<?php 
include("commonfunctions.php");
include("header.php");?>
<body ng-app="submitExample" id="pavdescription1">	
<?php include("headerbar.php");?>
<!-- header ends here -->
<!-- content starts here -->
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
		<?php settingMenu();// include("sidemlinks.php");
		?>
		<br> <br>
	</div>
	<!-- main template -->
	<div id="pen2">
		<div id="pen2-left">
			<div class="message-container">
				<div  ng-controller="ChangepassController">
					<div id="ErrorClass"></div>
					<form name="changepassform">
							
							<table>
							<tr>
									<td>
										<h2>
											<span class="subtitle">Change Password</span><br><br>
										</h2>
									</td>
									<td>
										
									</td>
								</tr>
								<tr>
									<td>
										<label>Current Password</label>
									</td>
									<td>
										<input type="password" class="form-control" name="currentpass" id="currentpass" ng-model="currentpassword">
									</td>
								</tr>
								<tr>
									<td>
										<label>New Password</label>
									</td>
									<td>
										<input type="password" class="form-control" name="pass1" id="pass1" ng-model="changepass1">
									</td>
								</tr>
								<tr>
									<td>
										<label>Confirm Password</label>
									</td>
									<td>
										<input type="password" class="form-control" name="pass2" id="pass2"  ng-model="changepass2">
									</td>
								</tr>
								<tr>
									<td>
									</td>
									<td>
										<br>
										<input type="submit" class="form-control" ng-click="changepassword()" value="Change Password">
									</td>
								</tr>
							</table>
					</form>
				</div>
			</div>
		</div>
		<br class="clearfix">
	</div>

	<!-- pagination -->
</div>

<!-- footer starts here 
	<div id="footerContainer"></div>-->
<!-- footer ends here -->
</div>


<?php include("footer.php")?>