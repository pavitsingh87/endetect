<?php
$domainname = "https"."://".$_SERVER['HTTP_HOST']."/";
define("baseurl", $domainname);
if($_REQUEST["getusersearchid"]!="" || $_REQUEST["enduserid"]!="")
{
	$enduserid="";
	$enduserid=$_REQUEST["getusersearchid"];
	$enduserid=$_REQUEST["enduserid"];
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

if((basename($_SERVER['PHP_SELF'])=='userprofile.php')) { ?>
				
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
	</div>
   
 	<HR>
	<br style="clear:both" />
	
	<a class="not_selected" id="livehref" href="javascript: void(0)" onclick="liverec('<?php echo $_REQUEST['enduserid'];?>','1')">
	<img src="images/live.png" /> Live Activity<i class="aro-right"></i></a>
	<a class="not_selected" id="exitlivehref" style="display:none;"  href="javascript: void(0)" onclick="liverec('<?php echo $_REQUEST['enduserid'];?>','0')">
	<img src="images/live.png" /> Exit Activity<i class="aro-right"></i></a>
	<a class="not_selected" href="javascript: void(0)" onclick="snapshotgallery('<?php echo $_REQUEST['enduserid'];?>')">
	<img src="images/notification1.png" /> Snapshot Gallery <?php if($file!="") { ?>( <?php echo $file; ?> )<?php } ?><i class="aro-right"></i></a>
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
	<a class="not_selected" style="padding-left: 28px;"  href="javascript: void(0)" onclick="changedtype('11')"><img src="images/alldata.png" /> All data<i class="aro-right"></i></a>
	<a class="not_selected" style="padding-left: 28px;"  href="javascript: void(0)" onclick="changedtype('2')"><img src="images/texticon.png" /> All typed text<i class="aro-right"></i></a>
	<a class="not_selected" style="padding-left: 28px;"  href="javascript: void(0)" onclick="changedtype('3')"><img src="images/copypasteicon.png" /> All copied text<i class="aro-right"></i></a>
	<a class="not_selected" style="padding-left: 28px;"  href="javascript: void(0)" onclick="changedtype('4')"><img src="images/copy_files.png" /> All copied files<i class="aro-right"></i></a>
	<a class="not_selected" style="padding-left: 28px;"  href="javascript: void(0)" onclick="changedtype('5')"><img src="images/pendrive.png" /> All pendrive files<i class="aro-right"></i></a>
	<a class="not_selected" style="padding-left: 28px;"  href="javascript: void(0)" onclick="changedtype('13')"><img src="images/watchlistsm.png" /> All Watchlist files<i class="aro-right"></i></a>
	<a class="not_selected" style="padding-left: 28px;"  href="javascript: void(0)" onclick="changedtype('13')"><img src="images/watchlistsm.png" /> All mobile ins<i class="aro-right"></i></a>
		
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
	 	include("services/connection.php");
		if($_REQUEST["enduserid"]!="") { 
			$userquery = mysql_query("select * from U_endusers where sno='".$_REQUEST["enduserid"]."'");
			if(mysql_num_rows($userquery)) {
				$userarray = mysql_fetch_array($userquery);
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
	 	include("services/connection.php");
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
				  		<?php if($userarray["dept"]!="") { ?>
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
	<a class="not_selected" style="padding-left: 28px;"  href="javascript: void(0)" onclick="changedtype('11')"><img src="images/alldata.png" /> All data<i class="aro-right"></i></a>
	<a class="not_selected" style="padding-left: 28px;"  href="javascript: void(0)" onclick="changedtype('2')"><img src="images/texticon.png" /> All typed text<i class="aro-right"></i></a>
	<a class="not_selected" style="padding-left: 28px;"  href="javascript: void(0)" onclick="changedtype('3')"><img src="images/copypasteicon.png" /> All copied text<i class="aro-right"></i></a>
	<a class="not_selected" style="padding-left: 28px;"  href="javascript: void(0)" onclick="changedtype('4')"><img src="images/copy_files.png" /> All copied files<i class="aro-right"></i></a>
	<a class="not_selected" style="padding-left: 28px;"  href="javascript: void(0)" onclick="changedtype('5')"><img src="images/pendrive.png" /> All pendrive files<i class="aro-right"></i></a>
	<a class="not_selected" style="padding-left: 28px;"  href="javascript: void(0)" onclick="changedtype('13')"><img src="images/watchlistsm.png" /> All Watchlist files<i class="aro-right"></i></a>
	<a class="not_selected" style="padding-left: 28px;"  href="javascript: void(0)" onclick="changedtype('13')"><img src="images/watchlistsm.png" /> All mobile ins<i class="aro-right"></i></a>
	<?php } ?>
	</div>

<?php } ?>