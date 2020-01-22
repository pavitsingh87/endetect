<form class="hide" target="_blank" name="postselblock" id="postselblock" method="POST" action="postselblock.php">
	<input type="hidden" name="sno" id="postblockendsno" value="">
	<input type="hidden" name="enduserid" id="postblockenduserid" value="">
</form>
<!-- end for autocomplete -->
<div id="FB-Bar" style="position: fixed; top: 0px;height:48px;background-color:#fff">
		<div id="FB-Frame">
			<div id="logo">
				<a href="<?php echo baseurl; ?>" style="top:0px;"><img src="images/en-detect.png" style="width:120px;"></a>
			</div>
			<div id="searchNav">
					<form name="frm1" id="frm1" action="search.php" method="POST">
						<div id="search" ng-controller="TypeaheadCtrl">
							<?php
							include("services/connection.php");
							if(@$_REQUEST['enduserid']!='')
							{
								$resulta = $conn->query("select * from U_endusers where sno='".$_REQUEST['enduserid']."'");
								$resultarr = @$resulta->fetch_assoc();
							}
							?>
							<input name="q" autocomplete="Off"
							<?php if(@($_REQUEST['getusersearchid']!='') || @($_REQUEST['enduserid']!='')) { ?>
							ng-init="typeproblem('<?php if($_REQUEST['getusersearchid']!='') { echo $_REQUEST['getusersearchid']; } if($_REQUEST['enduserid']!='') { echo $_REQUEST['enduserid']; }  ?>')"
							<?php } ?> ng-click="openoptions(customSelected.id)" type="text"
							id="userInput" placeholder="All" ng-model="customSelected"
							typeahead="state as state.name for state in statesWithFlags | filter:$viewValue | limitTo:5" style="border-right: 1px solid #626060;">
							<input name="q1" type="text" class="search" id="searchInput" placeholder="Search" value="<?php echo $_REQUEST['q1'];?>"  onclick="optionsearchbln()" onfocus="checkdetr()" ng-model="searchtext" ng-init="searchtext='<?php echo $_REQUEST['q1'];?>'">
							<span class="headerdropbox glyphicon glyphicon-chevron-down" id="searchbar" style="position:relative;top:-17px;left:300px;width:30px;cursor:pointer;" onclick="searchbar()"></span>
							<input name="Search" type="submit" id="searchButton" >
							<div class="search-container" id="search-container">
								<div id="divfirstuser" style="display: block;background-color:#fff;margin-top:1px; border-bottom: 1px solid rgba(0, 0, 0, 0.52);box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.52);">
									<div class="ui-dialog-titlebar ui-helper-clearfix" style="padding: 10px;">
										<span id="ui-dialog-title-dialog-confirm" class="ui-dialog-title"><b>Advance Search</b></span> <span class="glyphicon glyphicon-remove" onclick="closesearch()" style="cursor:pointer;float:right;color:rgba(0, 0, 0, 0.52);"></span>
									</div>
									<hr>
									<div class="form-horizontal" style="padding: 10px;">
										<div class="clearfix"></div>
										<div class="form-group">
											<div class="form-group">
												<label>Search</label>
												<div>
													<input ng-model="searchtext" class="form-control">
												</div>
											</div>
										</div>
										<div class="form-group">
											<label for="first-name"></label>
													<?php
													if(@$_REQUEST["searchtypes"]=="3" || @$_REQUEST["searchtypes"]=="") {
														?>
														<div ng-init="usertypes=3">
														<?php
													}
													?>
													<?php
													if(@$_REQUEST["searchtypes"]=="1") { ?>
													<div ng-init="usertypes=1">
													<?php } ?>
													<?php if($_REQUEST["searchtypes"]=="2") { ?>
													<div ng-init="usertypes=2">
													<?php } ?>
													<input type="radio" id="prefixsearch" value="3" selected='selected' ng-model="usertypes" ng-value="3"> Start with
													<input type="radio" id="broadsearch" value="1" selected='selected' ng-model="usertypes" ng-value="1"> Containing Search &nbsp;&nbsp; <input type="radio" id="exactsearch" value="2" onclick="changesearchtypes('2')"  ng-model="usertypes" ng-value="2"> Exact Match
													<input type="hidden" name="searchtypes" id="searchtypes" value="{{usertypes}}">
												</div>
											</div>
										</div>
										<div class="clearfix"></div>
										<div class="col-md-11">
											<div class="form-group">
												<label for="department">
												<span style="position: relative; top: 2px;">
												<input type="checkbox" id="cutomisedate" name="cutomisedate" value="0" <?php if($_REQUEST['cutomisedate']=='1') { echo "checked"; } ?> onchange="customddate()"></span> Custom Date</label> <br style="clear:both">
												<?php if($_REQUEST['cutomisedate']=='1') { $cutomiseserstyle="display:none";$cutomiseserstyle1="display:block"; } else { $cutomiseserstyle="display:block";$cutomiseserstyle1="display:none"; }?>
												<div id="daterange" style="<?php echo $cutomiseserstyle1;?>">
													<span style="float:left;padding-right:10px;padding-top: 5px;">From </span> <input type="text" id="example1"  class="form-control" style="width:80px;float:left;font-size:10px;" name="from" value="<?php echo $_REQUEST['from']; ?>"><span style="float:left;padding-right:10px;padding-left:10px;padding-top: 5px;"> To </span> <input  type="text" id="example2"  class="form-control" style="width:80px;float:left;font-size:10px;" name="to" value="<?php echo $_REQUEST['to']; ?>">
												</div>
												<div id="dropdowndaterange" style="<?php echo $cutomiseserstyle;?>">
												<?php include("daterange.php"); ?>
												</div>
											</div>
											<div class="clearfix"></div>
											<div class="form-group">
													<label for="first-name">Category</label>
													<div>
														<?php include("searchin.php");?>
													</div>
											</div>
											<div class="clearfix"></div>
											<div class="form-group">
												<label>Include</label>
												<div>
													<input id="musthave" class="form-control" name="musthave" value="<?php echo $_REQUEST['musthave']?>">
												</div>
											</div>
											<div class="clearfix"></div>
											<div class="form-group">
												<label>Exclude</label>
												<div>
													<input id="mustnothave" class="form-control" name="mustnothave" value="<?php echo $_REQUEST['mustnothave']?>">
												</div>
											</div>
											<div class="clearfix"></div>
											<div class="form-group">
												<label for="department">
													<span style="position: relative; top: 2px;">
													<input type="checkbox" id="notincludeimg" name="notincludeimg" value="0" <?php if($_REQUEST['notincludeimg']=='0') { echo "checked"; } ?> onchange="changeinludeimg()">
													</span>
													Exclude snapshot
												</label>
												<br style="clear:both">
												<div class="col-md-10">
													<input type="hidden" id="ntincludeimg" name="ntincludeimg" value="<?php echo $_REQUEST['ntincludeimg']?>">
												</div>
											</div>
											<div class="clearfix"></div>
											<div class="form-group">
												<div>
													<input type="hidden" name="stringsearch" id="stringsearch"
														value="<?php echo $_REQUEST['stringsearch']?>">
													<input
														type="hidden" name="optionsearch" id="optionsearch"
														value="<?php echo $_REQUEST['optionsearch']?>">
													<input
														type="hidden" name="getusersearchid" id="getusersearchid"
														ng-value="customSelected.id">
													<button id="btnupdate" class="btn btn-primary" onclick="return advancesearchoption()">Search</button>
													<input type="reset" class="btn btn-warning" value="Reset" />
													<button  class="btn btn-danger" onclick="return closesearch()">Close</button>
												</div>
											</div>
											<div class="clearfix"></div>
										</div>
										<div class="clearfix"></div>
									</div>
								</div>
							</div>
						</div>
					</form>

				<style>
				#TopMenu .nav li a {
				 padding: 15px 10px;
				}
				</style>
				<div id="TopMenu">
					<ul class="nav navbar-nav">
						<li><a href="<?php echo baseurl; ?>" title="Home"><i class="glyphicon glyphicon-home"></i></a></li>
						<li><a href="javascript:void(0)" title="Notification">
								<span id="notificationLink" class="home" href="#" onclick="notificationbar('<?php echo $_SESSION['ownerid']?>')" style="text-decoration:none;padding-top:5px;cursor:pointer;position:relative;top:0px;">
	  								<span id="notificationcount" style="display:none;top:-12px;"></span> <i class="glyphicon glyphicon-bell"></i>
								</span>
	  						</a>
  				      		<div id="notificationContainer" style="display:none;">
  								<div class="beepernubcenter"></div>
  								<div id="notificationTitle">
  									Notifications
  									<div style="float: right" id="notificationClose">X</div>
  								</div>
  								<div id="notificationsBody" class="notifications">
  									<div class="notification1">
  										<div class="loadinggif1" id="loadinggif1">
  											<img src="images/loading.gif">
  										</div>
  										<ul class="list-group" id="notifyme">
  										</ul>
  									</div>
  								</div>
  								<div id="notificationFooter" style="cursor:pointer" onclick="window.location.href='allnotifications.php'">
  									<a style="padding-left: 50px;padding-right: 50px;padding-top: 10px;padding-bottom: 10px;">See All</a>
  								</div>
  							</div>
  						</li>
						<li><a href="<?php echo baseurl; ?>userlicenseactivation.php" title="Pending License">
							<img src="images/s-pending.png" alt="" width="18">
						</a></li>
				      	<li><a class="dropdown-toggle pointer" title="Settings" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="javascript:void(0)">
								<img src="images/s-settings.png" alt="" width="18">
							</a>
							<div class="dropdown-menu" style="width:250px;" aria-labelledby="dropdownMenuButton">
								<?php dropdownsettingoptions(); ?>
							</div>
						</li>
						<li><a href="<?php echo baseurl .'Endetect.zip'; ?>" title="Download" target="_blank"><i class="glyphicon glyphicon-download-alt"></i></a></li>
						<li><a href="<?php echo baseurl .'logout.php'; ?>" style="color:red;" title="Logout"><i class="glyphicon glyphicon-off"></i></a></li>
				    </ul>
				</div>
			</div>
	</div>
<div style="clear:both"></div>
<?php deleteSinglePost(); ?>
<div id="toastmessage"></div>
