<?php 
@session_start();
$title="Activity Log | EnDetect";
include("commonfunctions.php");
checkOwnerSession();
include("header.php");
//echo $_SESSION['ownerid'];
?>
<script src="https://checkout.razorpay.com/v1/checkout-new.js"></script>
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
			<div class="col-md-12" style="padding-left:0px;">
				<table class="table">
				  <thead class="thead-dark">
				    <tr>
				      <th scope="col">Package</th>
				      <th scope="col">Lic Key</th>
				      <th scope="col">Lic Exp</th>
				      <th scope="col">Total Lic</th>
				      <th scope="col">Used Lic</th>
				      <th scope="col">Payment</th>
				      <th scope="col">Status</th>
				    </tr>
				  </thead>
				  <tbody>
				    <tr ng-show="listPaymentHistory" ng-repeat="(key, value) in ng_payment_success">
				    	<td><span ng-bind="value.package_type"></span><!-- <br>Price <span ng-bind="value.base_price"></span> - Users <span ng-bind="value.total_licenses"> --></td>
				    	<td><span ng-bind="value.lickey"></span></td>
				    	<td><span ng-bind="value.licexp_date"></span></td>
				    	<td><span ng-bind="value.total_lic"></span></td>
				    	<td><span ng-bind="value.license_used"></span></td>
				    	<td><span ng-bind="value.paymenttype"></span></td>
				    	<td>
				    		<span ng-if="value.packageRenew==0" ng-bind="value.order_status==1 ? 'Success' : 'Failed'"></span>
				    		<span class="text-primary" ng-if="value.packageRenew==1"><a href="javascript:void()" ng-click="licenseRenew(value)" data-toggle="modal" data-target="#myModal" ><span class="text-info">Package Renew</span></a></span>
				    		<span class="text-primary" ng-if="value.packageRenew==2"><a href="javascript:void()"><span class="text-info">License Suspended <br><b style="font-size:9px;">(
  Contact Administrator)
</b></span></a></span>
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
		.loader {
		  border: 16px solid #f3f3f3;
		  border-radius: 50%;
		  border-top: 16px solid #3498db;
		  width: 50px;
		  height: 50px;
		  -webkit-animation: spin 2s linear infinite; /* Safari */
		  animation: spin 2s linear infinite;
		}

		/* Safari */
		@-webkit-keyframes spin {
		  0% { -webkit-transform: rotate(0deg); }
		  100% { -webkit-transform: rotate(360deg); }
		}

		@keyframes spin {
		  0% { transform: rotate(0deg); }
		  100% { transform: rotate(360deg); }
		}
		</style>
	</div>
	<div id="myModal" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal">&times;</button>
			    <h4 class="modal-title">Renew Package</h4>
			  </div>
			  <div class="modal-body">
			  		<div class="text-center" id="licensePackage">
			  			<center>
			  	  			<div class="loader"></div>
			  	  		</center>
			  	  	</div>
			  	  	<div class="hide" id="packageFG">
			  	  		<table class="table table-bordered">
							<tbody>
								<tr>
									<td>Package Name</td>
									<td>{{transDet.package_type}}</td>
								</tr>
								<tr>
									<td>No. Of Licenses</td>
									<td>{{transDet.total_lic}}</td>
								</tr>
								<tr>
									<td>Price</td>
									<td>{{licenseDet.amount}} - /user/month <span ng-if="transDet.packageid=='1'">(billed annually) </span></td>
								</tr>
								<tr>
									<td>Sub total</td>
									<td>{{subtotal}}</td>
								</tr>
								<tr ng-if="discount!=''">
									<td>Discount</td>
									<td>{{discount}}</td>
								</tr>
								<tr ng-if="amountafterdiscount!=''">
									<td>Amount after discount</td>
									<td>{{amountafterdiscount}}</td>
								</tr>
								<tr>
									<td>GST {{licenseDet.igst+"%"}}</td>
									<td>{{gstPer}}</td>
								</tr>
								<tr>
									<td>Total Payable Amount</td>
									<td>{{totalamount}}</td>
								</tr>
								<tr class="hide">
									<td>Promocode</td>
									<td>

										<div class="col-sm-6" style="padding-left:0px;">
											<input type="text" class="form-control" aria-label="Amount" name="promocode" id="promocode" ng-model="promocode">
										</div>
										<div class="col-xs-2">
											<input type="button" value="Apply" class="btn btn-primary" ng-click="redeempromo(transDet)" >
										</div>
										<div class="clearfix"></div>
									</td>
								</tr>
								<tr>
									<td>Payment Type</td>
									<td>Razorpay</td>
								</tr>
								<tr>
									<td></td>
									<td><input type="button" value="Renew Package" ng-click="renewPackage(transDet)" class="btn btn-primary"></td>
								</tr>
							</tbody>
						</table>
			  	  	</div>
			  </div>
			  <div class="modal-footer">
			    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			  </div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        
	      </div>
	      <div class="modal-body">
	        <div class="errorMsg" id="errorMsg"></div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary">Save changes</button>
	      </div>
	    </div>
	  </div>
	</div>
	<div class="modal fade" id="abortModal" tabindex="-1" role="dialog" aria-labelledby="abortModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        	
	      </div>
	      <div class="modal-body">
	      	Transaction Aborted
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	      </div>
	    </div>
	  </div>
	</div>
</div>
</div>
<?php include("footer.php")?>
