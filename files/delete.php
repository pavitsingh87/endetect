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
<div id="content-main" ng-controller="ExampleController">
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
		.starter-template
		{
			padding: 3rem 1.5rem;
			text-align: center;
		}
		.lead {
    font-size: 1.25rem;
    font-weight: 300;
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
				<div id="load-content">
				  	<div class="starter-template">
			        	<h1>Delete All Content</h1>
			        	<p class="lead">Do you want to delete all logs, screenshots, and other associated data with your account?</p>
			      	</div>
			      	<div class="col-md-12 text-center">
			      		<div class="col-md-3"><h2 style='line-height:22px;'>Admin PIN<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="top" title="You can find your Current PIN in the Global Settings"></i></h2></div>
		      			<div class="col-md-4"><form ng-submit="deleteConfirm(pin)">
		      				<div class="entry input-group col-xs-3">

		                        	<input class="form-control" name="pin" type="text" ng-model="pin" maxlength="4" id="pin" placeholder="" numbers-only style="width:100px;"/>
			                    	<span class="input-group-btn">
			                            <button class="btn btn-default btn-add" type="submit" >
			                                <span class="glyphicon glyphicon-trash"></span>
			                            </button>
			                        </span>

	        				</div></form>
		      			</div>
		      			<div id="pinError" class="col-md-4 alert alert-warning hide" style="padding:5px;">
	        				Must be 4 numbers.
	        			</div>
		      		</div>

			    </div>
			    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				    <div class="modal-dialog">
				        <div class="modal-content">
				            <div class="modal-header">
				                Confirmation
				            </div>
				            <div class="modal-body">
				                Are you sure you want to delete your account? This action cannot be undone and you will be unable to recover any data.
				            </div>
				            <div class="modal-footer">
				                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				                <a href="javascript:void(0)" ng-click="deleteAll(pin)" class="btn btn-danger btn-ok" >Yes, delete it</a>
				            </div>
				        </div>
				    </div>
				</div>
		    </div>
		</div>
		<script>
		  $(function() {
		  	$('[data-toggle="tooltip"]').tooltip();
		  	});
		</script>
	</div>
</div>
</div>
<?php include("footer.php")?>
