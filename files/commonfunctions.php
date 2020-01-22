<?php
include_once("connection.php");
function converttotime($time)
{
	if($time>86400)
	{
		$days = floor($time / (60 * 60 * 24));
		$time -= $days * (60 * 60 * 24);

		$hours = floor($time / (60 * 60));
		$time -= $hours * (60 * 60);

		$minutes = floor($time / 60);
		$time -= $minutes * 60;

		$seconds = floor($time);
		$time -= $seconds;

		echo "{$days}d {$hours}h {$minutes}m {$seconds}s";
	}
	else if($time>3600)
	{
		$hours = floor($time / (60 * 60));
		$time -= $hours * (60 * 60);

		$minutes = floor($time / 60);
		$time -= $minutes * 60;

		$seconds = floor($time);
		$time -= $seconds;
		echo "{$hours}h {$minutes}m {$seconds}s";
	}
	else if($time>60)
	{
		$minutes = floor($time / 60);
		$time -= $minutes * 60;

		$seconds = floor($time);
		$time -= $seconds;
		echo "{$minutes}m {$seconds}s";
	}


	$minutes = floor($time / 60);
	$time -= $minutes * 60;

	$seconds = floor($time);
	$time -= $seconds;
}
function paginate($item_per_page, $current_page, $total_records, $total_pages, $page_url)
{
    $pagination = '';
    if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number
        $pagination .= '<ul class="pagination">';

        $right_links    = $current_page + 3;
        $previous       = $current_page - 3; //previous link
        $next           = $current_page + 1; //next link
        $first_link     = true; //boolean var to decide our first link

        if($current_page > 1){
			$previous_link = $_REQUEST["pageno"]-1;
            $pagination .= '<li class="first"><a href="'.$page_url.'&pageno=1" title="First">«</a></li>'; //first link
            $pagination .= '<li><a href="'.$page_url.'&pageno='.$previous_link.'" title="Previous"><</a></li>'; //previous link
                for($i = ($current_page-2); $i < $current_page; $i++){ //Create left-hand side links
                    if($i > 0){
                        $pagination .= '<li><a href="'.$page_url.'&pageno='.$i.'">'.$i.'</a></li>';
                    }
                }
            $first_link = false; //set first link to false
        }

        if($first_link){ //if current active page is first link
            $pagination .= '<li class="first active" style="padding: 9px;background: #bcbcbc;line-height: 15px;">'.$current_page.'</li>';
        }elseif($current_page == $total_pages){ //if it's the last active link
            $pagination .= '<li class="last active" style="padding: 9px;background: #bcbcbc;line-height: 15px;">'.$current_page.'</li>';
        }else{ //regular current link
            $pagination .= '<li class="active" style="padding: 9px;background: #bcbcbc;line-height: 15px;">'.$current_page.'</li>';
        }

        for($i = $current_page+1; $i < $right_links ; $i++){ //create right-hand side links
            if($i<=$total_pages){
                $pagination .= '<li><a href="'.$page_url.'&pageno='.$i.'">'.$i.'</a></li>';
            }
        }
        if($current_page < $total_pages){
				$next_link = $_REQUEST["pageno"]+1;
                $pagination .= '<li><a href="'.$page_url.'&pageno='.$next_link.'" >></a></li>'; //next link
                $pagination .= '<li class="last"><a href="'.$page_url.'&pageno='.$total_pages.'" title="Last">»</a></li>'; //last link
        }

        $pagination .= '</ul>';
    }
    return $pagination; //return pagination links
}
function categories()
{
	ob_start();
	?>
		<div class="panel-group" id="accordionMenu" role="tablist" aria-multiselectable="true">
		    <div class="panel-default">
		      <div class="panel-heading collapsed" role="tab" id="headingOne">
		        <h4 class="panel-title" style="font-size:14px;">
		        <a role="button" data-toggle="collapse" data-parent="#accordionMenu" href="#cat" aria-expanded="true" aria-controls="cat">
		          Filter
		        </a>
		      </h4>
		      </div>
		      <div id="cat" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
		        <div class="panel-body">
		          <ul class="nav">
		            <li><a class="not_selected" href="javascript: void(0)" onclick="changedtype('11')"><img src="images/alldata.png" /> All Data<i class="aro-right"></i></a></li>
		            <li><a class="not_selected" href="javascript: void(0)" onclick="changedtype('2')"><img src="images/texticon.png" /> Typed Text<i class="aro-right"></i></a></li>
		            <li><a class="not_selected"  href="javascript: void(0)" onclick="changedtype('3')"><img src="images/copypasteicon.png" /> Copied Text<i class="aro-right"></i></a></li>
		            <li><a class="not_selected" href="javascript: void(0)" onclick="changedtype('4')"><img src="images/copy_files.png" /> Copied Files<i class="aro-right"></i></a></li>
		            <li><a class="not_selected" href="javascript: void(0)" onclick="changedtype('5')"><img src="images/pendrive.png" /> USB drive<i class="aro-right"></i></a></li>
		            <li><a class="not_selected"  href="javascript: void(0)" onclick="changedtype('7')"><img src="images/mobileicon.png" /> Mobile Ins<i class="aro-right"></i></a></li>
		            <li><a class="not_selected" href="javascript: void(0)" onclick="changedtype('13')"><img src="images/watchlistsm.png" /> Watchlist Files<i class="aro-right"></i></a></li>
		          </ul>
		        </div>
		      </div>
		    </div>
		</div>
	<?php
	$output = ob_get_contents();
	ob_end_clean();
	echo $output;
}
function dropdownsettingoptions()
{
	?>
	<ul class="nav">
		<li><a class="not_selected" href="settings.php">Global Settings</a></li>
        <li><a class="not_selected" href="manageusers.php">Manage Users</a></li>
		<li><a class="not_selected" href="licensehistory.php">License Manager</a></li>
		<li><a class="not_selected" href="changepassword.php">Change Password</a></li>
        <li><a class="not_selected" href="alert-type.php">Alert Type</a></li>
		<li><a class="not_selected" href="delete.php">Delete All Data</a></li>
  	</ul>
	<?php
}
function useroptions()
{
	ob_start();
	?>
	<div class="panel-group" id="accordionMenu" role="tablist" aria-multiselectable="true">
	    <div class="panel-default">
	      <div class="panel-heading collapsed" role="tab" id="headingOne">
	        <h4 class="panel-title" style="font-size:14px;">
	        <a role="button" data-toggle="collapse" data-parent="#accordionMenu" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
	          Settings
	        </a>
	      </h5>
	      </div>
	      <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
	        <div class="panel-body">
	          <ul class="nav">
	            <li><a class="not_selected" href="licenceactivation.php"><img src="images/edit_profile.png" /> License Activation</a></li>
	            <li><a class="not_selected" href="manageusers.php"><img src="images/friends.gif" /> Manage Users</a></li>
	            <li><a class="not_selected" href="changepassword.php"><img src="images/stock_lock_open.png" /> Change Password<i class="aro-right"></i></a></li>
	          </ul>
	        </div>
	      </div>
	    </div>
	</div>
	<?php
	$output = ob_get_contents();
	ob_end_clean();
	echo $output;
}
function homemenuoptions()
{
	ob_start();
	?>
	<div class="panel-group" id="accordionMenu" role="tablist" aria-multiselectable="true">
	    <div class="panel-default">
	      <div id="homemenuoptions" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
	        <div class="panel-body">
	          <ul class="nav">
	            <li><a class="not_selected" href="/"><img src="images/news_feed.gif" /> Activity <i class="aro-right"></i></a></li>
	            <li><a href="webhistory.php"> <img src="images/webhistory.png" /> Web History</a></li>
				<li><a class="not_selected" href="gallery.php">
				<img src="images/notification1.png" /> Screenshot Gallery <?php if($file!="") { ?>( <?php echo $file; ?> )<?php } ?><i class="aro-right"></i></a></li>
				<!-- <li><a href="runningapps.php"> <img src="images/sandbox.png" /> Running Apps</a></li> -->
				<li><a href="internetusage.php"> <img src="images/gauge.png" /> Internet Usage</a></li>
				<li><a href="timesheet.php"> <img src="images/attendance.png" /> Time Sheet</a></li>
				<!-- <li><a href="osinfo.php"> <img src="images/os.png" /> OS Info</a></li> -->
	          </ul>
	        </div>
	      </div>
	    </div>
	</div>
	<?php
	$output = ob_get_contents();
	ob_end_clean();
	echo $output;
}
function deleteUserModal()
{
	ob_start();
	?>
	<div id="DeleteAllModal" class="modal fade" role="dialog">
		  <div class="modal-dialog">
		    <!-- Modal content-->
		    	<div class="modal-content">
			    	<div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal">&times;</button>
			        <h4 class="modal-title">Confirmation</h4>
			    </div>
		      	<div class="modal-body">
		      		<p>
		      			Do you want to delete all logs, screenshots, and other associated data with this user?
		      		</p>
		      		<br>
		      		<p>
		      			Are you sure you want to delete your account? This action cannot be undone and you will be unable to recover any data.
		      		</p>
		      		<br>
		      		<div class="form-group m-t-10">
			        	<p>Type 'PIN' below to proceed.<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="top" title="You can find your Current PIN in the Global Settings"></i></p>
						<br>
		            	<input type="hidden" ng-model="eid" ng-init="eid='<?php echo $_GET["eid"]; ?>'">
			        	<input type="text" id="delete-field" ng-model="deleteInputText" numbers-only autofocus="" class="form-control" style="" maxlength="4">
			        	<div id="pinError" class="col-md-4 alert alert-warning hide">
			        		Must be 4 numbers.
			        	</div>
			    	</div>
			    </div>
		      	<div class="modal-footer">
		      		<button class="btn btn-danger ng-isolate-scope" type="button" track-event="" ng-click="deleteUserData()">Delete</button>
		        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		      	</div>
		    </div>
		  </div>
		</div>
		<script>
		  $(function() {
		  	$('[data-toggle="tooltip"]').tooltip();
		  	});
		</script>
	<?php
	$output = ob_get_contents();
	ob_end_clean();
	echo $output;
}
function deleteSinglePost()
{
	ob_start();
	?>
	<div id="DeletePostModal" class="modal fade" role="dialog" style="z-index:999999999;">
		  <div class="modal-dialog">
		    <!-- Modal content-->
		    	<form name="deletePost">
			    	<div class="modal-content">
				    	<div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal">&times;</button>
				        <h4 class="modal-title">Confirmation</h4>
				    </div>
			      	<div class="modal-body">
			      		<p>
			      			Are you sure you want to delete the data?
			      		</p>
			      		<br>
			      		<p>
			      			 This action cannot be undone and you will be unable to recover any data.
			      		</p>
			      		<br>
			      		<div class="form-group m-t-10">
				        	<div style="padding-bottom:10px">Type 'PIN' below to proceed.<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="top" title="You can find your Current PIN in the Global Settings"></i></div>
			            	<input type="hidden" id="pid" name="pid" value="">
			            	<input type="hidden" id="ptype" name="ptype" value="">
				        	<input type="text" id="delete-field" name="pinconfirm" ng-model="pinconfirm" numbers-only autofocus="" required class="form-control" maxlength="4">
				        	<div id="pinpostError" class="col-md-4 alert alert-warning hide">
				        		Must be 4 numbers.
				        	</div>
				    	</div>
				    </div>
			      	<div class="modal-footer">
			      		<button class="btn btn-danger ng-isolate-scope" type="submit" track-event="" onclick="deletePostData()">Delete</button>
			        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			      	</div>
		        </form>
		    </div>
		  </div>
		</div>
		<script>
		  $(function() {
		  	$('[data-toggle="tooltip"]').tooltip();
		  	});
		</script>
	<?php
	$output = ob_get_contents();
	ob_end_clean();
	echo $output;
}

function userprofile_account($enduserid)
{
	ob_start();
	if(basename($_SERVER["PHP_SELF"])=="search.php" || basename($_SERVER["PHP_SELF"])=="webhistory.php" ||  basename($_SERVER["PHP_SELF"])=="settings.php" || basename($_SERVER["PHP_SELF"])=="postselblock.php")
	{
		$controller = "ExampleController";
	}
	else if(basename($_SERVER["PHP_SELF"])=="gallery.php" || basename($_SERVER["PHP_SELF"])=="gallery.php" )
	{
		$controller = "GalleryController";
	}
	else if(basename($_SERVER["PHP_SELF"])=="lazyminutes.php" || basename($_SERVER["PHP_SELF"])=="internetusage.php" || basename($_SERVER["PHP_SELF"])=="osinfo.php" || basename($_SERVER["PHP_SELF"])=="timesheet.php" || basename($_SERVER["PHP_SELF"])=="userprofile.php" ||  basename($_SERVER["PHP_SELF"])=="runningapps.php")
	{
		$controller = "UserProfileController";
	}

	if($_GET["eid"]=="")
	{
		//echo $enduserid;
	}
	?>
	<div ng-init="userdetails('<?php echo $enduserid; ?>')">
		<div class="col-md-12 text-center" style="padding-left:0px;">
			<img src="thumbnail.php?src={{userdetp.profilepic}}&w=50&h=50" style="border-radius:50%;"/>
		</div>
		<div class="clearfix"></div>
		<br>
		<div class="col-md-12 text-center"  style="padding-left:0px;">
			<div class="col-md-12" style="padding:0;">
					<button class="btn btn-link btn-xs" onclick="edituser('<?php echo $enduserid; ?>',angular.element(document.querySelector('[ng-controller=<?php echo $controller; ?>]')).scope().userdetp.name,angular.element(document.querySelector('[ng-controller=<?php echo $controller; ?>]')).scope().userdetp.dept,angular.element(document.querySelector('[ng-controller=<?php echo $controller; ?>]')).scope().userdetp.designation,angular.element(document.querySelector('[ng-controller=<?php echo $controller; ?>]')).scope().userdetp.groupid)"><i class="glyphicon glyphicon-pencil"></i></button>
					<a href="settings.php?eid=<?php echo $_GET["eid"]; ?>" class="btn btn-link btn-xs"><i class="glyphicon glyphicon-cog"></i></a>
					<button class="btn btn-link btn-xs" data-toggle="modal" data-target="#DeleteAllModal"><i class="glyphicon glyphicon-trash"></i></button>
			</div>
		</div>
		<div class="clearfix"></div>
		<br>
		<div class="col-md-12">
	  		<div class="clearfix"></div>
	  		<div style="word-break:break-word;font-size:14px;font-weight:bold;">
		  		{{userdetp.name}}
		  	</div>
		  	<div class="clearfix"></div>
		  	<div class="col-md-12" style="padding-left:0px;">
		  		<p ng-if="userdetp.dept!=''">
		  			<span><img src="images/department.png"></span> {{userdetp.dept}}
		  		</p>
		  		<p ng-if="userdetp.designation!=''">
		  			<span><img src="images/workspace.png"></span> {{userdetp.designation}}
		  		</p>
		  	</div>
	  	  	<br>
		</div>
		<div class="clearfix"></div><br>
		<div class="progress" style="margin-left:5px;margin-bottom:3px;margin-top:3px;">
			  <div class="progress-bar progress-bar-success" role="progressbar" style="width:25%;cursor:pointer;" alt="Typed Words" title="Typed Words">
			    {{userdetp.typed_words}}
			  </div>
			  <div class="progress-bar progress-bar-warning" role="progressbar" style="width:25%;cursor:pointer;" alt="Copied Words" title="Copied Words">
			    {{userdetp.copiedwords}}
			  </div>
			  <div class="progress-bar progress-bar-danger" role="progressbar" style="width:25%;cursor:pointer;" alt="Copied Files"  title="Copied files">
			    {{userdetp.files_copies}}
			  </div>
			  <div class="progress-bar progress-bar-info" role="progressbar" style="width:25%;cursor:pointer;" alt="Pendrive In"  title="Pendrive In">
			    {{userdetp.pendrivein}}
			  </div>
			  <div class="progress-bar progress-bar-info" role="progressbar" style="width:25%;cursor:pointer;" alt="Mobile In"  title="Mobile In">
			    {{userdetp.mobilein}}
			  </div>
		</div>

		<div class="clearfix"></div>
		<div class="clearfix"></div>
	</div>
	<?php
	$output = ob_get_contents();
	ob_end_clean();
	echo $output;
}

function activityhideshow($enduserid)
{
	ob_start();
	?>
	<div class="panel-heading collapsed" style="font-size:14px;">

	</div>
	<?php
	$output = ob_get_contents();
	ob_end_clean();
	echo $output;
}
function leftbar_account($enduserid)
{
	Global $conn;
	$encuis = base64_encode($enduserid);
	ob_start();
	?>
	<div class="panel-group" id="accordionMenu" role="tablist" aria-multiselectable="true">
	    <div class="panel-default">
	      <div id="userprofile_leftbar" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
	        <div class="panel-body">
	          <ul class="nav">
	            	<li>
	            		<a href="<?php echo baseurl; ?>userprofile.php?eid=<?php echo $encuis; ?>"><img src="images/news_feed.gif"> Activity</a>
	            	</li>
	            	<!-- <li>
	            		<a class="not_selected" id="livehref" href="javascript: void(0)" onclick="liverec('<?php echo $enduserid;?>','1')"><img src="images/conference.png">
						Live Activity<i class="aro-right"></i></a>
						<a class="not_selected" id="exitlivehref" style="display:none;"  href="javascript: void(0)" onclick="liverec('<?php echo $enduserid;?>','0')"><img src="images/conference.png"> Exit Activity<i class="aro-right"></i></a>
	            	</li> -->
	            <?php

					$euis = $conn->query("select * from enduserinstalledsoftware where enduserid='".$enduserid."'");
					if(($euis->num_rows)>0) {
					?>
					<li><a href="installedsoftware.php?eid=<?php echo $encuis; ?>" > <img src="images/installedsoftware.png" /> Installed Software</a></li>
					<?php } ?>
					<li><a href="webhistory.php?eid=<?php echo $encuis; ?>"> <img src="images/webhistory.png" /> Web History</a></li>
					<li><a class="not_selected" href="gallery.php?eid=<?php echo $encuis; ?>">
					<img src="images/notification1.png" /> Screenshot Gallery <?php if($file!="") { ?>( <?php echo $file; ?> )<?php } ?><i class="aro-right"></i></a></li>
					<li><a href="runningapps.php?eid=<?php echo $encuis; ?>"> <img src="images/sandbox.png" /> Running Apps</a></li>
					<li><a href="lazyminutes.php?eid=<?php echo $encuis; ?>"> <img src="images/zerobusiness.png" /> Lazy Minutes</a></li>
					<li><a href="internetusage.php?eid=<?php echo $encuis; ?>"> <img src="images/gauge.png" /> Internet Usage</a></li>
					<li><a href="timesheet.php?eid=<?php echo $encuis; ?>"> <img src="images/attendance.png" /> Time Sheet</a></li>
					<li><a href="osinfo.php?eid=<?php echo $encuis; ?>"> <img src="images/os.png" /> OS Info</a></li>
	          </ul>
	        </div>
	      </div>
	    </div>
	</div>
	<?php
	$output = ob_get_contents();
	ob_end_clean();
	echo $output;
}
function leftbar_withoutlive_account($enduserid)
{
	Global $conn;
	$encuis = base64_encode($enduserid);
	ob_start();
	?>
	<div class="panel-group" id="accordionMenu" role="tablist" aria-multiselectable="true">
	    <div class="panel-default">
	      <div id="userprofile_leftbar" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
	        <div class="panel-body">
	          <ul class="nav">
	            	<li>
	            		<a href="<?php echo baseurl; ?>userprofile.php?eid=<?php echo $encuis; ?>"><img src="images/news_feed.gif"> Activity</a>
	            	</li>
	            	<?php
	            	$euis = $conn->query("select * from enduserinstalledsoftware where enduserid='".$enduserid."'");
					if(($euis->num_rows)>0) {
					?>
					<li><a href="installedsoftware.php?eid=<?php echo $encuis; ?>" > <img src="images/installedsoftware.png" /> Installed Software</a></li>
					<?php } ?>
					<li><a href="webhistory.php?eid=<?php echo $encuis; ?>"> <img src="images/webhistory.png" /> Web History</a></li>
					<li><a class="not_selected" href="gallery.php?eid=<?php echo $encuis; ?>">
					<img src="images/notification1.png" /> Screenshot Gallery <?php if($file!="") { ?>( <?php echo $file; ?> )<?php } ?><i class="aro-right"></i></a></li>
					<?php if(basename($_SERVER["PHP_SELF"])=="userprofile.php") { ?>
					<li><a class="not_selected" href="javascript: void(0)" onclick="snapshotgallery('<?php echo $_REQUEST['enduserid'];?>')">
					<img src="images/notification1.png" /> Screenshot Gallery <?php if($file!="") { ?>( <?php echo $file; ?> )<?php } ?><i class="aro-right"></i></a></li>
					<?php } ?>
					<li><a href="runningapps.php?eid=<?php echo $encuis; ?>"> <img src="images/sandbox.png" /> Running Apps</a></li>
					<li><a href="lazyminutes.php?eid=<?php echo $encuis; ?>"> <img src="images/zerobusiness.png" /> Lazy Minutes</a></li>
					<li><a href="internetusage.php?eid=<?php echo $encuis; ?>"> <img src="images/gauge.png" /> Internet Usage</a></li>
					<li><a href="timesheet.php?eid=<?php echo $encuis; ?>"> <img src="images/attendance.png" />Time Sheet</a></li>
					<li><a href="osinfo.php?eid=<?php echo $encuis; ?>"> <img src="images/os.png" /> OS Info</a></li>
	          </ul>
	        </div>
	      </div>
	    </div>
	</div>
	<?php
	$output = ob_get_contents();
	ob_end_clean();
	echo $output;
}
function settingMenu()
{
	ob_start();
	?>
	<div class="panel-group" id="accordionMenu" role="tablist" aria-multiselectable="true">
	    <div class="panel-default">
	      	<div id="userprofile_leftbar" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
	        	<div class="panel-body">
		          	<ul class="nav">
		            	<li>
		            		<a class="not_selected" href="<?php echo baseurl; ?>"><img src="images/news_feed.gif"> Activity <i class="aro-right"></i></a>
		            	</li>
		            	<li>
		            		<a class="not_selected" href="userlicenseactivation.php"><img src="images/edit_profile.png"> License Activation <i class="aro-right"></i></a>
		            	</li>
		            	<li>
		            		<a class="not_selected" href="manageusers.php"><img src="images/friends.gif"> Manage Users<i class="aro-right"></i></a>
		            	</li>
		            	<li>
		            		<a class="not_selected" href="changepassword.php"><img src="images/stock_lock_open.png"> Change Password<i class="aro-right"></i></a>
		            	</li>
		            </ul>
		        </div>
		    </div>
		</div>
	</div>
	<?php
	$output = ob_get_contents();
	ob_end_clean();
	echo $output;
}
function ago($time)
{
	$chlen = strlen($time);
	if($chlen>10)
	{
		$mil = $time;
		$seconds = $mil / 1000;
		$ncrdate = date("d-M-Y H:i:s",$seconds);

		$time = strtotime($ncrdate);
	}
$periods = array("sec", "min", "hour", "day", "week", "month", "year", "decade");
	$lengths = array("59","59","24","7","4.35","12","10");

	$now = time();

	$difference     = $now - $time;
	$tense         = "ago";

	for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++)
	{
		$difference /= $lengths[$j];
	}

	$difference = round($difference);

	if($difference != 1) {
	$periods[$j].= "s";
	}
	if($periods[$j]=='hour' || $periods[$j]=='hours'  || $periods[$j]=='day' || $periods[$j]=='days' || $periods[$j]=='week'  || $periods[$j]=='weeks'  || $periods[$j]=='month'  || $periods[$j]=='months' || $periods[$j]=='year' || $periods[$j]=='years' || $periods[$j]=='decade')
	{
	$ncrdate = date("d-M-Y H:i:s",$time);
			return "$ncrdate";
	}
	else
	{
	return "$difference $periods[$j] ago";
	}
}

function ago1($time)
{
	$chlen = strlen($time);
	if($chlen>10)
	{
		$mil = $time;
		$seconds = $mil / 1000;
		$ncrdate = date("d-M-Y H:i:s",$seconds);

		$time = strtotime($ncrdate);
	}
	$periods = array("sec", "min", "hour", "day", "week", "month", "year", "decade");
	$lengths = array("59","59","24","7","4.35","12","10");

	$now = time();

	$current_time = ($now-3600);


	if($time>$current_time)
	{

		$difference     = $now - $time;
		$tense         = "ago";

		for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++)
		{
			$difference /= $lengths[$j];
		}

		$difference = round($difference);

		if($difference != 1) {
			$periods[$j].= "s";
		}
		if($periods[$j]=='hour' || $periods[$j]=='hours'  || $periods[$j]=='day' || $periods[$j]=='days' || $periods[$j]=='week'  || $periods[$j]=='weeks'  || $periods[$j]=='month'  || $periods[$j]=='months' || $periods[$j]=='year' || $periods[$j]=='years' || $periods[$j]=='decade')
		{
			$ncrdate = date("d-M-Y H:i:s",$time);
			return "$ncrdate";
		}
		else
		{
			return "$difference $periods[$j] ago";
		}

	}
	else
	{

		$ncrdate = date("d-M-Y H:i:s",$time);
		return "$ncrdate";
	}
}
function substring($string,$length)
{
		if (strlen($string) > $length) {

			$string = substr($string, 0, $length);
			$string = $string."...";
			//$string = substr($stringCut, 0, strrpos($stringCut, ' ')).'...';
		}
		else
		{
			$string = $string;
		}
		return $string;
}
function getUserDet($sno)
{
	Global $conn;
	$userQuery = $conn->query("select profilepic,name,dept from U_endusers where sno='".$sno."'");
	$userQuery_num = $userQuery->num_rows;
	if($userQuery_num>0)
	{
		$userdet = $userQuery->fetch_assoc();
		?>

		<img src='<?php echo $userdet["profilepic"];?>' class="text-center" style='width:20px;height:20px;'>
		&nbsp; <?php echo substring($userdet["name"], 10); ?>

		<?php
	}
}
function ago2($time)
{
	$chlen = strlen($time);
	if($chlen>10)
	{
		$mil = $time;
		$seconds = $mil / 1000;
		$ncrdate = date("d-M-Y H:i:s",$seconds);

		$time = strtotime($ncrdate);
	}
	$periods = array("s", "m", "h", "d", "w", "", "", "");
	$lengths = array("60","60","24","7","4.35","12","10");

	$now = time();

	$difference     = $now - $time;
	$tense         = "ago";

	for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
		$difference /= $lengths[$j];
	}

	$difference = round($difference);

	if($difference != 1) {
		$periods[$j].= "";
	}

	if(($periods[$j]=='h') && (($difference>=1) && ($difference<8)))
	{
		return "<div title='Idle' class='btn-default ribbon' style='height: 5px;width: 59px;margin-left: -5px;'>&nbsp;</div>";
	}
	else if(($periods[$j]=='s') || ($periods[$j]=='m'))
	{
		return "<div title='Online' class='btn-success ribbon' style='height: 5px;width: 59px;margin-left: -5px;'>&nbsp;</div>";
	}
	else
	{
		return "<div title='Offline' class='btn-danger ribbon' style='height: 5px;width: 59px;margin-left: -5px;'>&nbsp;</div>";
	}
}
function checkOwnerSession()
{
	Global $conn;
	// in this we check owner and sessionid exist with status 1
	// if 1 then remain login else logout immediately
	if( isset($_COOKIE["email"]) && $_COOKIE["email"] != "")
	{

	    $_SESSION['email']      = $_COOKIE['email'];
	    $_SESSION['name']       = $_COOKIE['name'];
	    $_SESSION['ownerid']    = $_COOKIE['ownerid'];
	    $_SESSION['company']    = $_COOKIE['company'];
	    $_SESSION['ownersession'] = $_COOKIE['ownersession'];
	    $conn->query("update endowner_session set status='1', sessionid='".$_SESSION['ownersession']."' where endownerid='".$_SESSION['ownerid']."'");

	}
	$sessionid = $_SESSION["ownersession"];
	$ownerid = $_SESSION["ownerid"];
	$login_query	= $conn->query("select * from endowner_session where status='1' AND sessionid='".$sessionid."' AND endownerid='".$ownerid."'");
	$login_num 		= $login_query->num_rows;


	if($login_num>0)
	{

	}
	else
	{
		?>
			<script type="text/javascript">window.location.href="logout.php";</script>
		<?php
	}
}
function purge($page,$type)
{

}
?>
