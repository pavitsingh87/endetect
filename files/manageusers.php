<?php
$title = "Manage Users";
include_once("commonfunctions.php");
include_once("header.php"); ?>
<body ng-app="submitExample">
<?php include_once("headerbar.php");?>

<link rel="stylesheet" href="css/hint.css">
<div id="content-main" >
	<div id="pen1">
		<div id="profile-picture"></div>
		<br class="clearfix">
		<?php settingMenu();
		?>
		<br> <br>
	</div>
	<div id="pen2">
		<div id="pen2-left">
			<div ng-controller="customersCrtl" >
				<div class="message-container" style="width:680px;">
					<h2 style="width:450px;float:left;line-height:5px;">
						Manage Users
					</h2>

					<br class="clearfix">
					<hr style="margin:0px 0px 10px 0px;">

			    	<div class="col-md-3" style="margin-left:5px;">Filter:
			            <input type="text" ng-model="search" ng-change="filter()" placeholder="Filter" class="form-control" />
			        </div>
			        <br><br>
			        <div class="col-md-11" ng-show="filteredItems == 0">
			            <div class="col-md-12">
			                <h4>No users found</h4>
			            </div>
			        </div>
			        <div class="col-md-11" ng-show="filteredItems > 0">
			            <table class="table table-striped table-bordered">
			            <thead>
			            <th><input type='checkbox' name='licusersall' ng-model="selectedAll" ng-click="selectall()"></input></th>
			            <th>&nbsp;</th>
			            <th>Name&nbsp;</th>
			            <th>Dept&nbsp;</th>
			            <th>Desig&nbsp;</th>
			            <th>Date&nbsp;</th>
			            <th>Action&nbsp;</th>
			            </thead>
			            <tbody>
			                <tr ng-repeat="data in filtered = (list | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
			                   <td style="font-size:11px;">
			                  <input id="{{data.sno}}" class="checkboxes" type='checkbox' ng-model="data.Selected" ng-click="checkcount(data.sno,$event)" /></input>
			                   </td>
			                   <td style="font-size:11px;">
								   <a href="<?php echo baseurl .'profile_img.php?eid='; ?>{{data.sno}}">
									   <img style="border-radius:50%;float:left;" src="thumbnail.php?src={{data.profilepic}}&w=28&h=28" alt="">
								   </a>
			                   	<!-- <i style="cursor:pointer;" title="Update Profile Pic" Alt="Update profile pic">
									<img style="border-radius: 50%;float:left;" src="thumbnail.php?src={{data.profilepic}}&w=28&h=28" alt=" UserIcon" ng-click="openimageuploader(data)">
								</i> -->
			                   </td>
			                    <td style="font-size:11px;"><span style='float:left'>{{data.name}}</span></td>
			                    <td style="font-size:11px;">{{data.dept}}</td>
			                    <td style="font-size:11px;">{{data.designation}}</td>
			                    <td style="font-size:11px;">{{data.jdt}} </td>
			                    <td style="width:170px"  style="font-size:11px;">
						            <div class="btn-group">
						              <button type="button"  title="Edit User" class="btn btn-default glyphicon glyphicon-edit" ng-click="open(data);"></button>
						              <button type="button"  title="Delete User" class="btn btn-danger glyphicon glyphicon-remove-sign" ng-click="deleteProduct(data);"></button>
						              <button type="button" class="btn btn-warning glyphicon glyphicon-eye-open" ng-mouseenter="showtrack(data)" ng-mouseleave="hidetrack(data)"></button>
						              <button type="button" title="Release User" class="btn btn-primary glyphicon glyphicon-retweet" ng-click="releaseUser(data, $index)"></button>
						              <div id="track{{data.sno}}" style='display:none;position:absolute;z-index:9999999;background-color:#000;margin-top:-40px;padding:5px;color:#fff;width:auto;font-size:10px;'>
						              	<table cellspacing='5' style="background-color: #000;"><tr><td valign="top" style=padding-right:5px;">PC Name</td><td style="white-space:pre-line;word-wrap:break-word;" valign="top">{{data.pcname}}</td></tr>
						          		<tr><td valign="top"style=padding-right:5px;">Macaddress</td><td valign="top">  {{data.macaddress}}</td></tr>
						          		</table>
						              </div>
						            </div>
						        </td>
			                </tr>
			            </tbody>
			            </table>
			        </div>
        			<br>
					<div class="col-md-4" style="margin-left:10px;">
			            <h5>Filtered {{ filtered.length }} of {{ totalItems}} total users</h5>
			        </div>
					<br style="clear:both"><br>
					<div style="margin:10px 0 10px 10px">
				        <button class="btn btn-success"  ng-disabled="buttondisabled" ng-click="ActiveUsers()">Activate</button>
						<button class="btn btn-warning"  ng-disabled="buttondisabled" ng-click="InactiveUsers()">Deactivate</button>
						<button class="btn btn-danger"   ng-disabled="buttondisabled" ng-click="DeleteUsers()">Delete</button>
			        </div>
			        <div class="col-md-2">PageSize:
			            <select ng-model="entryLimit" class="form-control">
			                <option value="10">10</option>
			                <option value="20">20</option>
			                <option value="50">50</option>
			                <option value="100">100</option>
			            </select>
			        </div>
			        <br style="clear:both">
			        <div class="col-md-12" ng-show="filteredItems > 0">
			            <div pagination="" page="currentPage" on-select-page="setPage(page)" max-size="5"  boundary-links="true" total-items="filteredItems" items-per-page="entryLimit" class="pagination-small" previous-text="&laquo;" next-text="&raquo;"></div>
			        </div>
			</div>
			<div class="modal fade" id="releaseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Release User Account</h5>
      </div>
      <div class="modal-body">
        Are you sure you want release user?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" ng-click="confirmRelease()">Confirm</button>
      </div>
    </div>
  </div>
</div>
<div id="edituser_blanketr" style="display: none; height: 4680px;"
	ng-click="closepopupedituserr()"></div>
<div id="edituser_popUpDivr" style="display: none;">
<form name="edituserform" ng-submit="editusersubmit()">
	<div id='divfirstuserr' style="display: none">
		<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix"
			style="padding: 10px;">
			<span id="ui-dialog-title-dialog-confirm" class="ui-dialog-title">Edit
				User</span>
		</div>
		<div class="form-horizontal">
			<div class="clearfix"></div>
			<div style="float: left; width: 250px; padding: 20px;">
				<div id='edituser_success'
					class="alert alert-dismissable alert-success fade in"
					style="display: none">
					<span class="title"><i class="icon-check-sign"></i> Successfully
						Edited</span>
				</div>
				<div id='edituser_error'
					class="alert alert-dismissable alert-danger fade in"
					style="display: none">
					<span class="title"><i class="icon-check-sign"></i> Error</span>
				</div>
				<div class="form-group">
					<label for="first-name" class="col-md-2">Name</label> <br
						style="clear: both">
					<div class="col-md-10">
						<input type="hidden" name="edituser_enduserid" ng-model="userid" id="edituser_enduserid" value="<?php echo $_REQUEST['enduserid']?>">
						<input id="edituser_first-name" ng-model="username" class="form-control" style="width: 230px" name="edituser_first-name" value="">
					</div>
				</div>
				<div class="form-group">
					<label for="first-name" class="col-md-2">Group</label>
					<br	style="clear: both">
					<div class="col-md-10" id="usergroupid">
						<select name="edituser_group" ng-model="usergroup" id="edituser_group" class="form-control" style="width: 230px;">
							<option value="0">Default</option>
									<?php
									include("connection.php");
									$getgrpdet = $conn->query("select * from U_group where ownerid='".$_SESSION['ownerid']."' AND status=1");
									while($grprow = $getgrpdet->fetch_assoc())
									{
									?>
										<option value="<?php echo $grprow['id']?>"
								<?php if($grprow['id']==$getuserarr['groupid'])?>><?php echo $grprow['groupname']?></option>
									<?php
									}
									$conn->close();
									?>
									</select>
					</div>
				</div>
				<div class="form-group">
					<label for="department" class="col-md-2">Department</label> <br
						style="clear: both">
					<div class="col-md-10">
						<input id="edituser_dept" class="form-control" name="edituser_dept" style="width: 230px" value="" ng-model="userdepartment">
					</div>
				</div>
				<div class="form-group">
					<label for="designation" class="col-md-2">Designation</label> <br
						style="clear: both">
					<div class="col-md-10">
						<input id="edituser_designation" class="form-control" style="width: 230px" name="edituser_designation" value="" ng-model="userdesignation">
					</div>
				</div>
				<div class="form-group">
					<label for="designation" class="col-md-2"></label> <br
						style="clear: both">
					<div class="col-md-10">
						<input type="submit" id="submit" value="UPDATE" name="usersubmit" />
					</div>
				</div>

			</div>
			<div class="clearfix"></div>
		</div>
	</div>
	</form>
</div>
</div>
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
<!-- Modal -->

<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>


<script src="js/app.js"></script>
<?php include_once("footer.php"); ?>
