<?php 
@session_start();
$title="Activity Log | EnDetect";
include("commonfunctions.php");
checkOwnerSession();
include("header.php");
//echo $_SESSION['ownerid'];
?>
<body ng-app="submitExample" id="pavdescription1">
<?php include("headerbar.php");?>
<div style="clear:both"></div>
<div id="content-main" ng-controller="PaymentHistoryController">
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
		<?php
			homemenuoptions();
			//useroptions();
			categories();
		?>
		<br> <br>
	</div>
	<div id="pen2">
		<div id="pen2-left">	
			<div id="latestimages">
			</div>
			<br />
			<div class="col-md-12">
				<table class="table">
				  <thead class="thead-dark">
				    <tr>
				      <th scope="col">Package</th>
				      <th scope="col">Lic Key</th>
				      <th scope="col">Lic Expired</th>
				      <th scope="col">Payment Type</th>
				      <th scope="col">Status</th>
				    </tr>
				  </thead>
				  <tbody>
				    <tr ng-show="listPaymentHistory" ng-repeat="(key, value) in ng_payment_success">
				    	<td><span ng-bind="value.package_type"></span><!-- <br>Price <span ng-bind="value.base_price"></span> - Users <span ng-bind="value.total_licenses"> --></td>
				    	<td><span ng-bind="value.lickey"></span></td>
				    	<td><span ng-bind="value.licexp_date"></span></td>
				    	<td><span ng-bind="value.paymenttype"></span></td>
				    	<td>
				    		<span ng-if="value.packageRenew==0" ng-bind="value.order_status==1 ? 'Success' : 'Failed'"></span>
				    		<span class="text-primary" ng-if="value.packageRenew==1"><a href="http://localhost/endy/renewpackages?lk={{value.lk}}" target="_blank"><span class="text-info">Package Renew</span></a></span>
				    	</td>
				    </tr>
				    <tr ng-hide="listPaymentHistory">
				    	<td colspan="5">No records found</td>
				    </tr>
				  </tbody>
				</table>
			</div>
			<div class="clearfix"></div>
			<div class="message-container hide">
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
		<br class="clearfix">
		<!-- Modal -->
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
	</div>
</div>
</div>
<?php include("footer.php")?>
