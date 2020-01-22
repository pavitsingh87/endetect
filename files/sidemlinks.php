<style>
		ul {
		  margin: 0;
		  padding: 0;
		  list-style: none;
		}

		.nav li {
		  border-bottom: 1px solid #eee;
		}

		.nav li a {
		  font-size: 14px;
		}

		#accordionMenu {
		 
		}

		.panel-body {
		  padding: 0;
		}

		.panel-group .panel+.panel {
		  margin-top: 0;
		  border-top: 0;
		}

		.panel-group .panel {
		  border-radius: 0;
		}

		.panel-default>.panel-heading {
		  color: #333;
		  background-color: #fff;
		  border-color: #e4e5e7;
		  padding: 0;
		  -webkit-user-select: none;
		  -moz-user-select: none;
		  -ms-user-select: none;
		  user-select: none;
		}

		.panel-default>.panel-heading a {
		  display: block;
		  padding: 10px 15px;
		  text-decoration: none;
		}

		.panel-default>.panel-heading a:after {
		  
		  position: relative;
		  top: 5px;
		  display: inline-block;
		  font-family: 'Glyphicons Halflings';
		  font-style: normal;
		  font-weight: 400;
		  line-height: 1;
		  -webkit-font-smoothing: antialiased;
		  -moz-osx-font-smoothing: grayscale;
		  float: right;
		  transition: transform .25s linear;
		  -webkit-transition: -webkit-transform .25s linear;
		}

		.panel-heading a[aria-expanded="true"]:after {
		  content: "\e113";
		}

		.panel-heading a[aria-expanded="false"]:after {
		  content: "\e114";
		}
	</style>
<?php
if($_REQUEST["getusersearchid"]!="" || $_REQUEST["enduserid"]!="")
{
	$enduserid="";
	$enduserid=$_REQUEST["getusersearchid"];
	$enduserid=$_REQUEST["enduserid"];
	$encuis = base64_encode($_REQUEST["enduserid"]);
	$url = baseurl."api/ed/countGallery/?enduserid=".$enduserid;

	$options = array(
	  'http'=>array(
	    'method'=>"GET",
	    'header'=>"Accept-language: en\r\n" .
	              "Cookie: foo=bar\r\n" .  // check function.stream-context-create on php.net
	              "X-API-KEY: A1F584C3083132CE18DCDA579C753579D3276AAB\r\n" // i.e. An iPad 
	  )
	);

	$context = stream_context_create($options);
	$file = file_get_contents($url, false, $context);
	//echo $rg = file_get_contents();
}
if($_REQUEST["eid"]!="")
{
	$_REQUEST["enduserid"] = base64_decode($_REQUEST["eid"]);
	$encuis = base64_encode($_REQUEST["enduserid"]);
}
if((basename($_SERVER['PHP_SELF'])=='userprofile.php') || (basename($_SERVER['PHP_SELF'])=='webhistory.php') || (basename($_SERVER['PHP_SELF'])=='installedsoftware.php') || (basename($_SERVER['PHP_SELF'])=='osinfo.php') || (basename($_SERVER['PHP_SELF'])=='runningapps.php') || (basename($_SERVER['PHP_SELF'])=='lazyminutes.php') || (basename($_SERVER['PHP_SELF'])=='internetusage.php') || (basename($_SERVER['PHP_SELF'])=='lazyactivity.php') || (basename($_SERVER['PHP_SELF'])=='attendance.php')) { ?>
				
<div class="sideMLinks" ng-init="userdetails('<?php echo $_REQUEST['enduserid']; ?>')">
	<div style="padding-left:10px;text-align:center;float:left;">
		<img src="thumbnail.php?src={{userdetp.profilepic}}&w=50&h=50" style="border-radius:50%;"/>
	</div>
	<div style="padding-top:5px;margin-left:10px;color: #666;float:left;">
  		<div style="word-break:break-word;font-size:14px;font-weight:bold;">
  		{{userdetp.name}}
  		</div>
  		<br>
  		<div style="word-break:break-word;font-size:12px;" ng-if="userdetp.dept">
  		<span><img src="images/department.png"></span> {{userdetp.dept}}
  		</div>
  		<div style="word-break:break-word;font-size:12px;" ng-if="userdetp.designation">
  		<span><img src="images/workspace.png"></span> {{userdetp.designation}}
  		</div>
  		<div style="word-break:break-word;font-size:12px;" ng-if="userdetp.designation">
  		<span><img src="images/workspace.png"></span> {{userdetp.designation}}
  		</div>
	  		
	</div>
	<br style="clear:both" />
	<br>
     <div class="progress" style="margin-left:5px;">
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
   
 	<HR>
	<br style="clear:both" />
	<?php if(basename($_SERVER["PHP_SELF"])=="userprofile.php") { ?>
	<a class="not_selected" id="livehref" href="javascript: void(0)" onclick="liverec('<?php echo $_REQUEST['enduserid'];?>','1')">
	<img src="images/live.png" /> Live Activity<i class="aro-right"></i></a>
	<?php } else { ?> <a class="not_selected" id="livehref" href="userprofile.php?eid=<?php echo $encuis; ?>" onclick="liverec('<?php echo $_REQUEST['enduserid'];?>','1')">
	<img src="images/live.png" /> User Profile<i class="aro-right"></i></a><?php } ?>
	<a class="not_selected" id="exitlivehref" style="display:none;"  href="javascript: void(0)" onclick="liverec('<?php echo $_REQUEST['enduserid'];?>','0')">
	<img src="images/live.png" /> Exit Activity<i class="aro-right"></i></a>
	<?php 
	include("connection.php");
	$euis = $conn->query("select * from enduserinstalledsoftware where enduserid='".$_REQUEST["enduserid"]."'");
	if(($euis->num_rows)>0) { 
	?>
	<a href="installedsoftware.php?eid=<?php echo base64_encode($_REQUEST["enduserid"]); ?>" > <img src="images/installedsoftware.png" /> Installed Software</a>
	<?php } ?>
	<a href="webhistory.php?eid=<?php echo $encuis; ?>"> <img src="images/webhistory.png" /> Web History</a>
	<?php if(basename($_SERVER["PHP_SELF"])=="userprofile.php") { ?>
	<a class="not_selected" href="javascript: void(0)" onclick="snapshotgallery('<?php echo $_REQUEST['enduserid'];?>')">
	<img src="images/notification1.png" /> Screenshot Gallery <?php if($file!="") { ?>( <?php echo $file; ?> )<?php } ?><i class="aro-right"></i></a>
	<?php } ?>
	<a href="runningapps.php?eid=<?php echo $encuis; ?>"> <img src="images/sandbox.png" /> Running Apps</a>
	<a href="lazyminutes.php?eid=<?php echo $encuis; ?>"> <img src="images/zerobusiness.png" /> Lazy Minutes</a>
	<a href="internetusage.php?eid=<?php echo $encuis; ?>"> <img src="images/gauge.png" /> Internet Usage</a>
	<a href="attendance.php?eid=<?php echo $encuis; ?>"> <img src="images/attendance.png" /> Attendance</a>
	<a href="osinfo.php?eid=<?php echo $encuis; ?>"> <img src="images/os.png" /> OS Info</a>
	
	<!-- -->
	<?php if($_REQUEST["enduserid"]=="") { ?> 
	<a class="not_selected" href="licenceactivation.php">
	<img src="images/edit_profile.png" /> License Activation <i class="aro-right"></i></a> 
	<a class="not_selected" href="manageusers.php">
	<img src="images/friends.gif" /> Manage Users<i class="aro-right"></i></a>
	<a class="not_selected" href="changepassword.php">
	<img src="images/stock_lock_open.png" /> Change Password<i class="aro-right"></i></a>
	<?php } ?>
	

	<br style="clear:both" />
	<HR>
	<br style="clear:both" />
	<?php if(basename($_SERVER["PHP_SELF"])=="userprofile.php") { ?>
	<a class="not_selected" style="padding-left: 28px;"  href="javascript: void(0)" onclick="changedtype('11')"><img src="images/alldata.png" /> All data<i class="aro-right"></i></a>
	<a class="not_selected" style="padding-left: 28px;"  href="javascript: void(0)" onclick="changedtype('2')"><img src="images/texticon.png" /> All typed text<i class="aro-right"></i></a>
	<a class="not_selected" style="padding-left: 28px;"  href="javascript: void(0)" onclick="changedtype('3')"><img src="images/copypasteicon.png" /> All copied text<i class="aro-right"></i></a>
	<a class="not_selected" style="padding-left: 28px;"  href="javascript: void(0)" onclick="changedtype('4')"><img src="images/copy_files.png" /> All copied files<i class="aro-right"></i></a>
	<a class="not_selected" style="padding-left: 28px;"  href="javascript: void(0)" onclick="changedtype('5')"><img src="images/pendrive.png" /> All USB drive<i class="aro-right"></i></a>
	<a class="not_selected" style="padding-left: 28px;"  href="javascript: void(0)" onclick="changedtype('7')"><img src="images/mobileicon.png" /> All mobile ins<i class="aro-right"></i></a>
	<a class="not_selected" style="padding-left: 28px;"  href="javascript: void(0)" onclick="changedtype('13')"><img src="images/watchlistsm.png" /> All watchlist files<i class="aro-right"></i></a>
	<?php } ?>
</div>

<?php }
else if((basename($_SERVER['PHP_SELF'])=='postselblock.php')) { ?>
<div class="sideMLinks">
	<div style="padding-left:10px;text-align:center;float:left;">
	<img src="thumbnail.php?src={{userdetp.profilepic}}&w=50&h=50" style="border-radius:50%;"/>
	</div>
		<div style="padding-top:5px;margin-left:10px;color: #666;float:left;">
	  		<div style="word-break:break-word;font-size:14px;font-weight:bold;">
	  		{{userdetp.name}}
	  		</div>
	  		<br>
	  		<div style="word-break:break-word;font-size:12px;" ng-if="userdetp.dept">
	  		<span><img src="images/department.png"></span> {{userdetp.dept}}
	  		</div>
	  		<div style="word-break:break-word;font-size:12px;" ng-if="userdetp.designation">
	  		<span><img src="images/workspace.png"></span> {{userdetp.designation}}
	  		</div>
	  		
	</div>
	<br style="clear:both" /><br>
	<div class="progress" style="margin-left:5px;">
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
	</div>
</div>
<?php } 

else if((basename($_SERVER['PHP_SELF'])=='gallery.php'))  {
?>
<div class="sideMLinks">
	<?php
	 	include("connection.php");
		if($_REQUEST["enduserid"]!="") { 
			$userquery = $conn->query("select * from U_endusers where sno='".$_REQUEST["enduserid"]."'");
			if($userquery->num_rows) {
				$userarray = $userquery->fetch_assoc();
			}
			?>
			<div style="padding-left:10px;text-align:center;float:left;">
				<img src="thumbnail.php?src=<?php echo $userarray["profilepic"] ?>&w=50&h=50" style="border-radius:50%;"/>
				</div>
					<div style="padding-top:5px;margin-left:10px;color: #666;float:left;">
				  		<div style="word-break:break-word;font-size:14px;">
				  		<?php echo $userarray["name"]; ?>
				  		</div>
				  		<br>
				  		<?php if($userarray["dept"]!="") { ?>
				  		<div style="word-break:break-word;font-size:12px;">
				  		<span><img src="images/department.png"></span> <?php echo $userarray["dept"]; ?>
				  		</div>
				  		<?php } ?>
				  		<?php if($userarray["designation"]!="") { ?>
				  		<div style="word-break:break-word;font-size:12px;">
				  		<span><img src="images/workspace.png"></span> <?php echo $userarray["designation"]; ?>
				  		</div>
				  		<?php } ?>
				</div>
				<br style="clear:both" />
				<br>
				<div class="progress" style="margin-left:5px;">
				  <div class="progress-bar progress-bar-success" role="progressbar" style="width:25%;cursor:pointer;" alt="Typed Words" title="Typed Words">
				    <?php echo $userarray["type_words"]; ?>
				  </div>
				  <div class="progress-bar progress-bar-warning" role="progressbar" style="width:25%;cursor:pointer;" alt="Copied Words" title="Copied Words">
				    <?php echo $userarray["copied_words"]; ?>
				  </div>
				  <div class="progress-bar progress-bar-danger" role="progressbar" style="width:25%;cursor:pointer;" alt="Copied Files"  title="Copied files">
				    <?php echo $userarray["files_copies"]; ?>
				  </div>
				  <div class="progress-bar progress-bar-info" role="progressbar" style="width:25%;cursor:pointer;" alt="Pendrive In"  title="Pendrive In">
				    <?php echo $userarray["pendriveinsert"]; ?>
				  </div>
				</div>
		<?php }
	?>
</div>
<?php
}
else  {
?>
<div class="sideMLinks">
	<?php
	 	include("connection.php");
		if($_REQUEST["getusersearchid"]!="") { 
			$userquery = $conn->query("select * from U_endusers where sno='".$_REQUEST["getusersearchid"]."'");
			if($userquery->num_rows) {
				$userarray = $userquery->fetch_assoc();
			}
			?>
			<div style="padding-left:10px;text-align:center;float:left;">
				<img src="thumbnail.php?src=<?php echo $userarray["profilepic"] ?>&w=50&h=50" style="border-radius:50%;"/>
				</div>
					<div style="padding-top:5px;margin-left:10px;color: #666;float:left;">
				  		<div style="word-break:break-word;font-size:14px;">
				  		<?php echo $userarray["name"]; ?>
				  		</div>
				  		<?php if($userarray["dept"]!="") { ?>
				  		<div style="word-break:break-word;font-size:12px;">
				  		<span><img src="images/department.png"></span> <?php echo $userarray["dept"]; ?>
				  		</div>
				  		<?php } ?>
				  		<?php if($userarray["designation"]!="") { ?>
				  		<div style="word-break:break-word;font-size:12px;">
				  		<span><img src="images/workspace.png"></span> <?php echo $userarray["designation"]; ?>
				  		</div>
				  		<?php } ?>
				</div>
				<br style="clear:both" />
				<br>
				<div class="progress" style="margin-left:5px;">
				  <div class="progress-bar progress-bar-success" role="progressbar" style="width:25%;cursor:pointer;" alt="Typed Words" title="Typed Words">
				    <?php echo $userarray["type_words"]; ?>
				  </div>
				  <div class="progress-bar progress-bar-warning" role="progressbar" style="width:25%;cursor:pointer;" alt="Copied Words" title="Copied Words">
				    <?php echo $userarray["copied_words"]; ?>
				  </div>
				  <div class="progress-bar progress-bar-danger" role="progressbar" style="width:25%;cursor:pointer;" alt="Copied Files"  title="Copied files">
				    <?php echo $userarray["files_copies"]; ?>
				  </div>
				  <div class="progress-bar progress-bar-info" role="progressbar" style="width:25%;cursor:pointer;" alt="Pendrive In"  title="Pendrive In">
				    <?php echo $userarray["pendriveinsert"]; ?>
				  </div>
				</div>
		<?php }
	?>
	<a class="not_selected" href="<?php echo $domainname; ?>"><img src="images/news_feed.gif" /> Activity <i class="aro-right"></i></a>
	<a class="not_selected" href="<?php echo $domainname; ?>live.php"><img src="images/conference.png" /> Live <i class="aro-right"></i></a>
	<?php if($_REQUEST["getusersearchid"]=="") { ?>
	<a class="not_selected" href="licenceactivation.php"><img src="images/edit_profile.png" /> License Activation <i class="aro-right"></i></a> 
	<a class="not_selected" href="manageusers.php"><img src="images/friends.gif" /> Manage Users<i class="aro-right"></i></a>
	<a class="not_selected" href="changepassword.php"><img src="images/stock_lock_open.png" /> Change Password<i class="aro-right"></i></a>
	<?php } ?>
	<HR>
	<BR>
	<?php 
	
	if((basename($_SERVER['PHP_SELF'])=='gallery.php') || (basename($_SERVER['PHP_SELF'])=='manageusers.php') || (basename($_SERVER['PHP_SELF'])=='postselblock.php') || (basename($_SERVER['PHP_SELF'])=='licenceactivation.php') || (basename($_SERVER['PHP_SELF'])=='changepassword.php')) { ?>
	
	<?php } else { ?>
		  <div class="panel-group" id="accordionMenu" role="tablist" aria-multiselectable="true">
		    <div class="panel-default">
		      <div class="panel-heading" role="tab" id="headingOne">
		        <h4 class="panel-title">
		        <a role="button" data-toggle="collapse" data-parent="#accordionMenu" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
		          Categories
		        </a>
		      </h4>
		      </div>
		      <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
		        <div class="panel-body">
		          <ul class="nav">
		            <li><a class="not_selected" href="javascript: void(0)" onclick="changedtype('11')"><img src="images/alldata.png" /> All Data<i class="aro-right"></i></a></li>
		            <li><a class="not_selected" href="javascript: void(0)" onclick="changedtype('2')"><img src="images/texticon.png" /> Typed Text<i class="aro-right"></i></a></li>
		            <li><a class="not_selected"  href="javascript: void(0)" onclick="changedtype('3')"><img src="images/copypasteicon.png" /> Copied Text<i class="aro-right"></i></a></li>
		            <li><a class="not_selected" href="javascript: void(0)" onclick="changedtype('4')"><img src="images/copy_files.png" /> All copied files<i class="aro-right"></i></a></li>
		            <li><a class="not_selected" href="javascript: void(0)" onclick="changedtype('5')"><img src="images/pendrive.png" /> All USB drive<i class="aro-right"></i></a></li>
		            <li><a class="not_selected"  href="javascript: void(0)" onclick="changedtype('7')"><img src="images/mobileicon.png" /> All mobile ins<i class="aro-right"></i></a></li>
		            <li><a class="not_selected" href="javascript: void(0)" onclick="changedtype('13')"><img src="images/watchlistsm.png" /> All watchlist files<i class="aro-right"></i></a></li>
		          </ul>
		        </div>
		      </div>
		    </div>
		  </div>
	<?php } ?>
	</div>

<?php } ?>