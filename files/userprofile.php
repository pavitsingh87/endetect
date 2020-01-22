 <?php 
include("commonfunctions.php");
$title="User Profile | EnDetect";
if(isset($_GET['eid']))
{	
	$enduserid= base64_decode($_GET["eid"]);
}
?>
<?php include("header.php");?>
<body onload="lastrecordid('<?php echo $enduserid;?>');latestimages('<?php echo $enduserid;?>');" ng-app="submitExample">	
<?php include("headerbar.php");?>
<script type="text/javascript">

function lastrecordid(str)
{
	document.getElementById('loadinggif').style.display="block";
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        	          	
            document.getElementById("lastrecid").value =xmlhttp.responseText;
            homestreamuserprofile(str);
        }
    }
    xmlhttp.open("POST","services/getlastrecid.php?enduserid="+str,true);
    xmlhttp.send();
}
function newdatacome(str)
{
	var streamtrue = document.getElementById('streamuserres').value;
   
    if(streamtrue=='1')
    {  
		homestreamuserprofile(str);
		setTimeout(function(){ newdatacome(str); }, 3000);
    }
    
}
function homestreamuserprofile(enduserid)
{
	
	document.getElementById('loadinggif').style.display="block";
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        	document.getElementById('loadinggif').style.display="none";
        	
            document.getElementById("streamuser").innerHTML = xmlhttp.responseText;
        }
    }
    xmlhttp.open("POST","services/userstream.php?enduserid="+enduserid,true);
    xmlhttp.send();
		
}
function loadmoreuserprofile(enduserid,id)
{
	id.style.display="none";
	document.getElementById('loadinggif').style.display="block";
	var incrementer = document.getElementById('streamuserres').value;
	var addition = parseInt(incrementer)+1;
	document.getElementById('streamuserres').value=addition;
	if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          	document.getElementById('loadinggif').style.display="none";
          	$('html, body').animate({
          	    scrollTop: $(window).scrollTop() + 500
          	},{
          		duration: 1000});
            document.getElementById("streamuser").innerHTML = document.getElementById("streamuser").innerHTML + xmlhttp.responseText;
        }
    }
    xmlhttp.open("POST","services/userstream.php?streamolder=1&lastrecordid="+addition+"&enduserid="+enduserid,true);
    xmlhttp.send();
	
}
    </script>
   <input type="hidden" id="lastrecid" name="lastrecid" value="">
   <input type="hidden" id="streamuserres" name="streamuserres" value="0">

<div id="content-main" ng-controller="UserProfileController">

	<div class="row-body" >

		<!-- <div class="cover-container"  id="sidebar-container">
			<div class="cover-content">	
				<div class="three columns">
					<div class="sidebar-container" id="sidebar-container">
						<div class="sidebar-content">
							<div class="message-form-header">Statistics</div>
		
							<div class="sidebar-about">
								Activation Date: <strong ng-bind="userdetp.activationdate"></strong>
							</div>
							<div class="sidebar-about">
								Typed Words: <strong ng-bind="userdetp.typed_words"></strong>
							</div>
							<div class="sidebar-about">
								Copied Words: <strong ng-bind="userdetp.copiedwords"></strong>
							</div>
							<div class="sidebar-about">
								Files Copied: <strong ng-bind="userdetp.files_copies"></strong>
							</div>
							
						</div>
						<div id="hide-cont" style="display:block;cursor:pointer;float:right;margin-top:-35px;position:absolute;">
							<button class="btn btn-default" type="button" >
							    <span class="glyphicon glyphicon-collapse-up" style="font-size: 14px;"></span>
							     Hide Profile
							</button>
						</div>
					</div>
				</div> 
				<div class="seven columns">
					
					<img  class="cover-avatar" src="thumbnail.php?file={{userdetp.profilepic}}&width=100&height=100&cropratio=1:1" />
					<br style="clear:both;" /><br style="clear:both;" /><br style="clear:both;" /><br style="clear:both;" /><br style="clear:both;" />
					<br style="clear:both;" />
					<div>
					  Vivek Kumar SAHA
					</div>
					<br style="clear:both;" />
					<div>
					  Designation - Department
					</div>
				</div>
			</div>
		</div>
		
		<div id="show-cont" style="float:right;display:none;cursor:pointer;">
			<button class="btn btn-default" type="button">
    			<span class="glyphicon glyphicon-collapse-down" style="font-size: 14px;"></span>
			     Show Profile
			</button>
		</div>-->
		<div style="clear: both"></div>
		
		
		
		<div id="pdata" style="margin-top: -65px;">
			
			<div id="resultval">
				<!-- content starts here -->
				<div id="content-main">
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
						<?php
						userprofile_account($enduserid);
						activityhideshow($enduserid);
						leftbar_account($enduserid);
						categories();
						 ?>
						<br> <br>
						<?php deleteUserModal(); ?>
					</div>
					<div id="pen2">
						<div id="pen2-left">
							<div id="load-content" style="display:none">Live Activity </div>
							<div id="latestimages">
							</div>
							<div class="clearfix"></div>
							<br>
							<div class="col-md-12">
								<div class="col-md-4">
										<h3>User Profile</h3>
								</div>
								<div class="col-md-6 text-right" style="float:right;">
										<a class="not_selected btn btn-primary btn-xs" id="livehref" style="float:right" href="javascript: void(0)" onclick="liverec('<?php echo $enduserid;?>','1')">Auto Refresh <i class="glyphicon glyphicon-refresh"></i></a>
										<a class="not_selected btn btn-default btn-xs" id="exitlivehref" style="display:none;float:right;"  href="javascript: void(0)" onclick="liverec('<?php echo $enduserid;?>','0')">
										<img src="images/live.png" /> Exit Activity<i class="aro-right"></i></a>
										<!-- &nbsp;<a  id="web-delete" class="btn btn-danger btn-xs">Delete <i class="glyphicon glyphicon-trash"></i></a> -->
								</div>
							</div>
					 		<div class="clearfix"></div>
					 		<hr>
					 		<br>
							<div class="message-container">
								<div class="message-form-content"><input type="hidden" id="streamuserres" name="streamuserres" value="0">
									<div id="streamuser">
										
									</div>
									<center>
										<div class="loadinggif" id="loadinggif">
											<img src="images/loading.gif">
										</div>
									</center>
									
									<div class="alert alert-dismissable alert-danger fade in"
										id="ascrec" style="display: none;">No more records found</div>
								</div>
							</div>
							<div id="load-content"></div>
							<div id="messages">
								<div style="margin: 10px; float: right;"></div>
								<br clear="all">
								<div id="resultval"></div>
							</div>
							<center></center>
							<div id="load-content"></div>
							<div id="messages">
								<div style="margin: 10px; float: right;"></div>
								<br clear="all">
								<div id="resultval"></div>
							</div>
							<center></center>
						</div>
						<div class="hide" id="pen3-left" style="float: left;height: auto;margin-left: 13px;width: 98%;">

						</div>
						<br class="clearfix">
					</div>

					<!-- pagination -->
				</div>

				
			</div>

		</div>
	</div>
</div>
</div>
</div>


<?php include("footer.php")?>