<div id="pocp1" class="pocp_left" ng-controller="ActivityCtrl" style="right: 0px;">
	<input type="hidden" name="countahead" id="countahead" value="1">
	<div class="commentsToggle">
		<span class="latestusers" id="commentsToggle"> Latest Users </span>
		<span class="settingsupdate" ng-click="showactiveusersreload()">
			<img src="images/refreshuserslist.png" style="position:relative;top:-2px;cursor:pointer;">
		</span>
		<input type="hidden" id="latestuserliststatus" value="" name="latestuserliststatus">
	</div>

	<div class="pocp" id="pocp2">
		<div style="background-color: #fff; width: 200px;">
			<input type="text" id="userfilterbar" name="userfilterbar" ng-model="userfilterbar" style="background: url('<?php echo baseurl; ?>images/search-white.png') no-repeat scroll 10px 6px #FCFCFC; border: 0px solid #D1D1D1; font: bold 12px Arial, Helvetica, sans-serif; color: #747474; width: 200px; height: 30px; padding-left: 30px; border-top: 1px solid #747474; transition: all 0.7s ease 0s;">
		</div>
		<hr style="color:#cecece;">

		<div class="col-md-12" style="height: 30px;margin-top:10px;margin-bottom:5px;" ng-init="showactiveusers('1')">
			<div class="col-md-2 gactive" id="activeuserslist" ng-click="showactiveusers('1')" style="cursor:pointer;border-right:1px solid #ddd;text-align:center;line-height: 25px;" title="Active Users">
				Active
			</div>
			<div class="col-md-2" ng-click="showactiveusers('2')" id="idleuserslist" style="cursor:pointer;border-right:1px solid #ddd;text-align:center;line-height: 25px;" title="Idle Users">
				Idle
			</div>
			<div class="col-md-2" ng-click="showactiveusers('3')" id="offlineuserslist" style="cursor:pointer;line-height: 25px;text-align:center;" title="Offline Users">
				Offline
			</div>
		</div>
		<hr style="color:#cecece;">
		<div style="clear:both"></div>

		<div class="pocp_content" id="pavdescription" style="border-top:1px solid #dedede;">
			<div class="Loader hide" id="userlistLoad" align="center">
				<img src="./images/loading.gif">
			</div>

			<div class="div_ads" id="userlistOnline">
				<div ng-show="GetLatestUsers.length>0">
					<div style="text-align:center;">
						<div style="font-size:12px;padding:5px;font-style:italic;" ng-bind="jhi"></div>
					</div>
					<ul class="list-group pending proton-widget parentDiv">
						<li ng-repeat="item in GetLatestUsers | filter:userfilterbar track by $index" class="list-group-item">
							<div class="panel1">
								<div class="front card" style="background-image: url('thumbnail.php?src={{item.profilepic}}&w=28&h=28')"></div>
								<div class="back card" value="{{item.id}}" id="vb{{item.id}}" name="vba{{item.id}}" onclick="imageprocess(this.id)"></div>
							</div>
							<div style="float:left;width:10%;" class="" id="parentdiv-{{item.id}}" title="Version - {{item.version}}">
								<a href="userprofile.php?eid={{item.eid}}">
									<div class="text-holder" style="height: 32px;" id="textholder-{{item.id}}">
										<div>
											<span class="title-text" style="font-size: 12px;" id="username-{{item.id}}" ng-bind="item.username"></span>
											<span class="description-text" id="desctext-{{item.id}}" style="font-size: 10px;" ng-bind="item.startup" ng-if=(item.startup!='Offline')></span>
										</div>
										<div>
											<span class="onlineclass" style="font-size: 11px;" id="onlineclass-{{item.id}}" ng-bind="item.lastaccess"></span>
										</div>
									</div>
								</a>
							</div><br />
							<div style="clear: both"></div>
						</li>
					</ul>
				</div>
				<div ng-show="GetLatestUsers.length==0">
					<div ng-bind="jho" style="text-align:center;font-size:12px;padding:5px;font-style: italic;"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<style type="text/css">
.gactive{background:#5ec25e;color:#fff}
.yactive{background:#dedede;color:#000}
.ractive{background:#e45353;color:#fff}
.panel1{margin:auto;position:relative;height:28px;width:40px;float:left}
.card{height:28px;width:28px;margin-left:2px;border-radius:50%;-o-transition:all .5s;-ms-transition:all .5s;-moz-transition:all .5s;-webkit-transition:all .5s;transition:all .5s;-webkit-backface-visibility:hidden;-ms-backface-visibility:hidden;-moz-backface-visibility:hidden;backface-visibility:hidden;position:absolute;top:0;left:0}
.front{z-index:2;background-image:url(<?php echo $domainname;?>/images/fg.jpg)}
.back{z-index:99999;-webkit-transform:rotateX(-180deg);-ms-transform:rotateX(-180deg);-moz-transform:rotateX(-180deg);transform:rotateX(-180deg);background-image:url(<?php echo $domainname;?>/images/opacity.jpg)}
.panel1:hover .front{z-index:1;-webkit-transform:rotateX(180deg);-ms-transform:rotateX(180deg);-moz-transform:rotateX(180deg);transform:rotateX(180deg)}
.panel1:hover .back{z-index:2;-webkit-transform:rotateX(0deg);-ms-transform:rotateX(0deg);-moz-transform:rotateX(0deg);transform:rotateX(0deg)}
</style>

<script type="text/javascript">
			$(document).ready(function()
			{
				$('#commentsToggle').click(function(){
				    $('#pocp2').toggleClass('hidden');
				    $('.commentsToggle').toggleClass('showin');
				});
			});
			function imageprocess(click)
			{
				 var gh = click.split("vb");
				 var idtocheck = gh['1'];
				 document.getElementById("blanket").style.display="block";
				 document.getElementById("popUpDiv").innerHTML="";
				 document.getElementById("popUpDiv").style.display="block";
				 document.getElementById('loadinggif2').style.display="block";
				 document.getElementById('loadinggif2').innerHTML = "<div style='color:#fff;padding:5px;'><b> Fetching image from PC</b> </div><div><img src='images/loading.gif'></div>";
				 snapshot(gh['1'],"21");
				 notifycntupdate(document.getElementById('notifycnt').value);
			}
			function changewindow(clicked)
			{
				var gh = clicked.split("parentdiv-");
				var idtocheck = gh['1'];
				document.getElementById("enduserid").value=idtocheck;
				document.getElementById("userprofileredirection").submit();
			}
			function homechangewindow(clicked)
			{
				console.log(clicked);
				var idtocheck = clicked;
				document.getElementById("enduserid").value=idtocheck;
				document.getElementById("userprofileredirection").submit();
			}
	</script>

<script type='text/javascript'>
		/* $("#pavdescription").scroll(function() {
		   angular.element(document.getElementById('pocp1')).scope().onTimeoutpavit();
		});
		$(window).load(function(){

		});*/
	</script>
<input type="hidden" name="notifycnt" id="notifycnt" value="0">
<script type="text/javascript">

	</script>
<script type="text/javascript">
$( "#hide-cont" ).click(function() {

$( "#sidebar-container" ).slideToggle( "slow" );
$( "#show-cont" ).css("display", "block");
$( "#hide-cont" ).css("display", "none");
	$(window).scroll(function () {
	    //if you hard code, then use console
	    //.log to determine when you want the
	    //nav bar to stick.
	    console.log($(window).scrollTop())
	  if ($(window).scrollTop() > 30) {
	    $('#nav_bar').addClass('navbar-fixed');
	  }
	  if ($(window).scrollTop() < 31) {
	    $('#nav_bar').removeClass('navbar-fixed');
	  }
	});
});
$( "#show-cont" ).click(function() {
	$( "#show-cont" ).css("display", "none");
	$( "#hide-cont" ).css("display", "block");
	document.getElementById('streamuserres').value='0';
$( "#sidebar-container" ).slideToggle( "slow" );
});

$( "#searchbar" ).click(function() {
	document.getElementById("search-container").style.display="block";
	document.getElementById("searchbar").style.display="none";

});
function closesearch() {
	document.getElementById("search-container").style.display="none";
	document.getElementById("searchbar").style.display="block";
	return false;
};
</script>
<script type="text/javascript">
	window.onkeyup = function (event) {
		if (event.keyCode == 27) {
			document.getElementById("blanketprevnext").style.display="none";
			document.getElementById("popUpDivprevnext").style.display="none";
			document.getElementById("popUpDivgallery").style.display="none";
			document.getElementById("blanketgallery").style.display="none";
		}
	}
</script>
<!-- <script type='text/javascript' src='js/jquery/1.7.2/jquery.min.js'></script>
<script type="text/javascript" src="js/ui/1.8.18/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css"
	href="css/ajax/libs/jqueryui/1.8.6/themes/smoothness/jquery-ui.css">-->
<script src="js/jquery-1.9.1.js"></script>
<script type="text/javascript">
$(document).ready(function (e) {
	$("#uploadForm").on('submit',(function(e) {
		e.preventDefault();
		$.ajax({
        	url: "editprofileimage.php",
			type: "POST",
			data:  new FormData(this),
			contentType: false,
    	    cache: false,
			processData:false,
			success: function(data)
		    {
			$("#targetLayer").html(data);
		    },
		  	error: function()
	    	{
	    	}
	   });
	}));
});
</script>

<script>
		$.noConflict();
		// Code that uses other library's $ can follow here.
	</script>
<?php
if(basename($_SERVER['PHP_SELF'])!='gallery.php') {
?>
<div id="blanket" style="display: none; height: 4680px;"
	onclick="closepopup()">
	<div class="loadinggif2" id="loadinggif2"
		style="position: fixed; top: 40%; left: 40%; display: none;">
		<img src="images/loading.gif">
	</div>
</div>
<div id="popUpDiv" style="display: none;"></div>
<?php } ?>
<div id="dialog-confirm"></div>
<style>
.bgColor{width:440px;height:100px;background-color:#F9D735}
.bgColor label{font-weight:700;color:#A0A0A0}
#targetLayer{float:left;width:100px;height:100px;text-align:center;line-height:100px;font-weight:700;color:silver;background-color:#F0E8E0;overflow:auto}
#uploadFormLayer{float:right;padding:10px}
.btnSubmit{background-color:#3FA849;padding:4px;border:#3FA849 1px solid;color:#FFF}
.inputFile{padding:3px;background-color:#FFF}
</style>
<!-- User Edit Profile -->
<!-- Modal -->
<div id="onDemandScreenshotModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">On-Demand Screenshot</h4>
      </div>
      <div class="modal-body">
        <p><div id="errorOnDemandScreenshot"></div></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<div id="editUserModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit User</h4>
      </div>
      <div class="modal-body">
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
				<label class="control-label col-md-3">Name</label>
				<div class="col-md-7">
				<input type="hidden" name="edituser_enduserid"
							id="edituser_enduserid"
							value="<?php echo $_REQUEST['enduserid']?>"> <input
							id="edituser_first-name" class="form-control"
							style="width: 230px" name="edituser_first-name"
							value="<?php echo $getuserarr['name'];?>">
				</div><div class="clearfix"></div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3">Group</label>
				<div class="col-md-7">
					<select name="edituser_group" id="edituser_group"
							class="form-control" style="width: 255px;">
							<option value="">Default</option>
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
				</div><div class="clearfix"></div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3">Department</label>
				<div class="col-md-7">
					<input id="edituser_dept" class="form-control"
							name="edituser_dept" style="width: 230px"
							value="<?php echo $getuserarr['dept'];?>">
				</div><div class="clearfix"></div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3">Designation</label>
				<div class="col-md-7">
					<input id="edituser_designation" class="form-control"
							style="width: 230px" name="edituser_designation"
							value="<?php echo $getuserarr['designation'];?>">
				</div><div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
      </div>
      <div class="modal-footer">
      	<button id='btnupdate' class="btn btn-success"
							onclick="usersubmitpopup()">Update</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="addNote" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Quick Note</h4>
      </div>
      <div class="modal-body">
      		<div id='note_success'
				class="alert alert-dismissable alert-success fade in"
				style="display: none">
				<span class="title"><i class="icon-check-sign"></i> Successfully
					Edited</span>
			</div>
			<div id='note_error'
				class="alert alert-dismissable alert-danger fade in"
				style="display: none">
				<span class="title"><i class="icon-check-sign"></i> Error</span>
			</div>
			<div class="clearfix"></div>
			<div class="form-group">
				<br style="clear: both">
					<input type="hidden" name="note_postid" id="note_postid"
						value="<?php echo $sno; ?>">
					<textarea name="editnote" id="editnote" style="resize: none;max-width:none"
						maxlength="99"><?php echo $note;?></textarea>

			</div>
			<div class="clearfix"></div>
      </div>
      <div class="modal-footer">
      	<input type="button" class="btn btn-success" name="save" id="save"
							value="Save" onclick="savenote()">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div id="blanketgallery" style="display: none; height: 4680px;"
	onclick="closepopupgallery()"></div>

<div id="popUpDivgallery" style="display: none;">
	<div id="loadondemandsnap">
		<input id="usergallerycurrentsno" name="usergallerycurrentsno"
			value="">
	</div>
</div>

<div id="blanketprevnext" style="display: none; height: 4680px;"
	onclick="closeprevnext()"></div>
<div id="popUpDivprevnext" style="display: none;">
	<iframe id="prevnextcont" src="" style="width: 800px; height: 400px;">

	</iframe>

</div>

<div class="loadinggifgal" id="loadinggifgal"
	style="position: fixed; top: 50%; left: 45%; display: none; z-index: 99999999;">
	<img src="images/loading.gif">
</div>
<!-- Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Confirm</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<input type="hidden" id="blockid" name="blockid" value="">
        Are you sure you want to delete this post.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
        <button type="button" class="btn btn-primary" onclick="callback()">Yes</button>
      </div>
    </div>
  </div>
</div>
<button onclick="startTime"><div id="test"></div>
<script>
$(document).ready(function() {
    var text_max = 99;
    $('#textarea_feedback').html(text_max + ' characters remaining');

    $('#editnote').keyup(function() {
        var text_length = $('#editnote').val().length;
        var text_remaining = text_max - text_length;

        $('#textarea_feedback').html(text_remaining + ' characters remaining');
    });
});

</script>
<script>
	$(document).ready(function () {
		$("#example1").dateDropdowns();
		$("#example2").dateDropdowns();
		$("#wFromDate").dateDropdowns();
		$("#wToDate").dateDropdowns();
        /*$('#example1').datepicker({
            format: "dd-M-yy",
            maxDate:'0',
            autoclose: true
        });
        $('#example2').datepicker({
            format: "dd-M-yy",
            maxDate:'0',
            autoclose: true
        });
        $('#wFromDate').datepicker({
        	format: "dd-M-yy",
            maxDate:'0',
            autoclose: true
    	});
    	$('#wToDate').datepicker({
    		format: "dd-M-yy",
            maxDate:'0',
            autoclose: true
    	});   */
    });
/*var i=0;
$( window ).load(function() {
  	var today = new Date();
    var s = today.getSeconds();
    var t = setTimeout(startTime, 1000);
});
function startTime() {

	i=i+1;
    if(i>60)
    {
    	angular.element(document.getElementById('pocp1')).scope().showactiveusers('1');
    	i=0;
    }
    var t = setTimeout(startTime, 1000);
}*/

</script>
<!-- User Edit Profile -->

</body>
</html>
