<?php
$title="Screenshot Gallery | EnDetect";
include("commonfunctions.php");
include("header.php");
$enduserid = base64_decode($_REQUEST["eid"]);
?>
<body ng-app="submitExample" id="pavdescription1" onload="galleryimages(<?php echo $enduserid; ?>);">
<?php
include("headerbar.php");
?>
	<div id="content-main" ng-controller="GalleryController">
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
			<?php 
				if($enduserid!="")
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
		<!-- main template -->
		<div id="pen2">
			<div id="pen2-left">
				
			<?php 
			include("connection.php");
			$userquery = $conn->query("select name,dept,designation from U_endusers where sno='".$enduserid."'");
			$userarr = $userquery->fetch_assoc();
			if(($userarr['dept']!='') || (strlen($userarr['dept'])>0))
			{
				$dept ="- ".$userarr['dept'];
			}
			if(($userarr['designation']!='') || (strlen($userarr['designation'])>0))
			{
				$designation ="- ".$userarr['designation'];
			}
			
			$conn->close();
			?>
				<div class="col-md-12">
					<div class="col-md-6">
							<h3>Screenshot Gallery <!-- <?php if($enduserid!="") { ?> for <?php echo $userarr['name'];?> --> <!-- <?php echo $dept;?> <?php echo $designation;  ?> --> <!-- <?php  } ?> --></h3>
					</div>
					<div class="col-md-4 text-right" style="float:right;">
							<a href="javascript:void(0)" onclick="galleryrefresh()" id="gallery-refresh" class="btn btn-primary btn-xs">Refresh <i class="glyphicon glyphicon-refresh"></i></a><!-- &nbsp;<a  id="web-delete" class="btn btn-danger btn-xs">Delete <i class="glyphicon glyphicon-trash"></i></a> -->
					</div>
				</div>
		 		<div class="clearfix"></div>
				<hr>
				<div id="fbgallery">
					<div id="fbgallery_album_photos" ng-init="GetfGallery(<?php echo $enduserid;?>)">
						<input type="hidden" name="imagecounttot" id="imagecounttot" value="0">
						<input type="hidden" name="imagecounter" id="imagecounter" value="0">
						<input type="hidden" name="imagecnt" id="imagecnt" value="0">
						<input type="hidden" name="imagelastid" id="imagelastid" value="">
						<input type="hidden" name="recordsno" id="recordsno" value="">
						<div id="galleryimages">
						</div>
					</div>
					<div style="clear:both"></div><br><br>
					<div class="panel-heading" onclick="GetofGallery(<?php echo $_REQUEST['enduserid']; ?>)" id="loadmore<?php echo $r['_source']['sno'] ?>">
						<a ng-hide="ascrec">
							<h4 class="panel-title">
								<center style="cursor: pointer;">.. load more ..</center>
							</h4>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="blanket" style="display:none;" onclick="closepopup()">
				
	</div>
	<div id="popUpDiv" style="display:none;">
		<div id="loadondemandsnap"></div>
	</div>
	<div class="loadinggif2" id="loadinggif2" style="position:fixed;z-index:999999;top:40%;left:50%;display:none;">
		<img src="images/loading.gif">
	</div>
<?php
include("footer.php");
?>


