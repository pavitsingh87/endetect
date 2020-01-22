<?php 
include("commonfunctions.php");
include("header.php");
?>
<body ng-app="submitExample" >	
<?php include("headerbar.php");?>

<!-- content starts here -->
<div id="content-main" >
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
			<div ng-controller="lic_customersCrtl" >
				<div class="message-container" style="width:680px;">
					<h2 style="width:450px;float:left;line-height:5px;">License Activation</h2>
					<div style='float:left;'><span style="line-height:35px;"><!-- Available License(s)<b> <span ng-bind="availablelicense"></span> </b> --></span>
					</div>
					<br class="clearfix"><br class="clearfix">
					<hr style="margin: 10px 0px 10px 0px;">
					
    		<div class="row">
        		<!-- <div class="col-md-2">
            		<select ng-model="entryLimit" ng-change='limitchange(entryLimit)' class="form-control">
                		<option value="10">10</option>
                		<option value="20">20</option>
                		<option value="50">50</option>
                		<option value="100">100</option>
            		</select>
	        	</div> -->
		        <div class="col-md-3">
					<form name='filterform' id="filterform1" ng-click="filter()">
		            <input type="text" ng-model="search"  placeholder="Filter" class="form-control" />
					<input name="Search" type="submit" id="searchButton1">
		        </div>
		        <br style="clear:both">
		    </div>
    <br/>
    <div class="row">

        <div class="col-md-11" ng-show="filteredItems > 0">
			<div ng-bind-html="warningerrormsg | to_trusted">  
			
			</div>   
			<br class="clearfix"> 
			<hr style="margin: 10px 0px 10px 0px;">
            <table class="table table-striped table-bordered">
				<thead>
				<th><input type='checkbox' name='licusersall' ng-model="selectedAll" ng-click="checkAll()"></input></th>
				<th>S.No.&nbsp;</th>
				<th>Name&nbsp;</th>
				<th>PC Name&nbsp;</th>
				<th>Installed On&nbsp;</th> 
				</thead>
				<tbody>
					<tr ng-repeat="data in filtered = (list | orderBy : predicate :reverse) | limitTo:entryLimit">
					   <td style="font-size:11px;">
						<input id="{{data.id}}" class="checkboxes" type='checkbox' ng-model="data.Selected" ng-click="checkcount(data.id,$event)" /></input>
					   </td>
					   <td style="font-size:11px;"><span style='float:left' ng-bind="data.serialnum"></span></td>
						<td style="font-size:11px;"><span style='float:left' ng-bind="data.name"></span></td>
						<td style="font-size:11px;"><span style='float:left' ng-bind="data.pcname"></span></td>
					   <td style="font-size:11px;" ng-bind="data.jdt"></td>
					</tr>
				</tbody>
            </table>
       		
       			<br class="clearfix"> <input type='checkbox' id='chklicauth'  ng-click="authterms()"></input>
					I am authorise to monitor and log all activities of selected computers. <br> <br
						class="clearfix"> <input type="checkbox" id='chkterms' ng-click="authterms()"></input> I agree to <a
						href="#"> Terms & Conditions</a>. <br class="clearfix"> <br
						class="clearfix">
				<br>
				<div class="col-md-2">
            		<select ng-model="entryLimit" ng-change='limitchange(entryLimit)' class="form-control">
                		<option value="10">10</option>
                		<option value="20">20</option>
                		<option value="50">50</option>
                		<option value="100">100</option>
            		</select>
	        	</div>
					<button class="btn btn-success"  ng-disabled="authandterms" ng-click="ActiveUsers()">Activate(<span ng-bind='chkcount'></span>)</button>
					<button class="btn btn-danger"   ng-disabled="authandterms" ng-click="DeleteUsers()">Delete(<span ng-bind='chkcount'></span>)</button>

				</div>
				<div class="col-md-12" ng-show="filteredItems > 0">    
		            <div pagination="" page="currentPage" on-select-page="setPage(page)" boundary-links="true" total-items="filteredItems" items-per-page="entryLimit" class="pagination-small" previous-text="&laquo;" next-text="&raquo;"></div>
		        </div>
				<br style="clear:both">			
				<div class="alert-notification-message" ng-hide="alerterror">
				<div class="alert-notification-message-title">Instructions :</div>
				<div class="alert-notification-message-divclass">1. You must should agree the terms and conditions to active/delete users.</div> 
				<div class="alert-notification-message-divclass">2. You must should authorise to catch data from Selected PC's.</div>
				<div class="alert-notification-message-divclass">3. Select minimum one User to activate buttons.</div>
				</div>
        </div>
        <div class="col-md-12" ng-show="filteredItems == 0">
            <div class="col-md-12">
                <h4>No pending license found</h4>
            </div>
        </div>
        
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

<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    

<script src="js/licenseapp.js"></script>         
<?php include("footer.php")?>    