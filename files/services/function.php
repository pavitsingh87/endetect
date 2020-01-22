<?php
include("../commonfunctions.php");
if(@$_GET["mode"]=="showuserwisetimesheet")
{
	include("connection.php");
	if($_GET["selectedmonth"]!='')
	{
		$explodemonth = explode("-",$_GET["selectedmonth"]);
		$starttime=Date("Y-m-d H:i:s", strtotime($explodemonth["1"]."-".$explodemonth["0"]."-01 00:00:00"));
		$endtime=Date("Y-m-d H:i:s", strtotime($explodemonth["1"]."-".$explodemonth["0"]."-31 00:00:00"));
		$concatstr.= " AND (starttime between '".$starttime."' AND '".$endtime."')";
	}
	else{ 
		$starttime = Date("Y-m-01 00:00:00",time());
		$endtime = Date("Y-m-31 23:59:59",time()); 
		$concatstr.= " AND (starttime between '".$starttime."' AND '".$endtime."')";
	}
	if($_GET["enduserid"]!="")
	{
		$concatstr.= " AND enduserid='".base64_decode($_GET["enduserid"])."'";
	}
	
	$runningproc1 = $conn->query("select * from U_useratt as uatt INNER JOIN U_endusers as uend ON uatt.enduserid=uend.sno where 1 ".$concatstr." order by id desc limit 10");
	$totalrecords = $runningproc1->num_rows;
	if($totalrecords>0)
	{
		$i=0;
		?>
		<table class="table">
			  <thead>
			    <tr>
			      <th style="text-align:center;">Date</th>
			      <th style="text-align:center;">Login</th>
			      <th style="text-align:center;">Logout</th>
			      <th style="text-align:center;">Total</th>
			    </tr>
			  </thead>
			<?php
			while($row = $runningproc1->fetch_assoc())
			{
				?>
				<tr>
					<td style="text-align:center;line-height: 30px;"><?php echo Date("d-M-Y ( D )", strtotime($row["starttime"])); ?></td>
					<td style="text-align:center;line-height: 30px;"><?php echo Date("H:i", strtotime($row["starttime"])); ?></td>
					<td style="text-align:center;line-height: 30px;"><?php echo Date("H:i", strtotime($row["endtime"])); ?></td>
					<td style="text-align:center;line-height: 30px;">
						<?php
						$diff=strtotime($row["endtime"])-strtotime($row["starttime"]);
						$udiff[] = $diff;
						echo converttotime($diff);
						?>
					</td>
				</tr>	
				<?php
				$i++;
			}
			?>
			<tr>
				<td colspan="3" class="text-center"><b>Average Working Hours</b></td>
				<td class="text-center">
					<?php  
						$avgcnt = count($udiff);
						$sumarr = array_sum($udiff);
						$avgarr = ceil($sumarr/$avgcnt);
						echo converttotime($avgarr);
					?>	
				</td>
			</tr>
		</table>
		<?php
	}
	$conn->close();
}
if((isset($_REQUEST['enduserid'])) && (isset($_REQUEST['action'])))
{
	include("connection.php");
	$snapshotquery 	= 	$conn->query("insert into U_getaction set ownerid='".$_SESSION['ownerid']."', enduserid='".$_REQUEST['enduserid']."', action='".$_REQUEST['action']."',actiondate='".date("Y-m-d H:i:s",time())."',ipaddress='".$_SERVER['REMOTE_ADDR']."',hostname='".gethostbyaddr($_SERVER['REMOTE_ADDR'])."',feedid='".time()."'");
	echo $conn->insert_id;
	$conn->close();
}
else if(isset($_REQUEST['notifyowner']))
{
	include("connection.php");
	if(strlen($_REQUEST['notifyowner'])>0)
	{
		
		$userdetquery 	= 	"select * from U_getaction where ownerid='".$_REQUEST['notifyowner']."' AND status='1' AND notify='1' AND actionpath!='' order by sno desc limit 20";
		$executequery 	= 	$conn->query($userdetquery);
		$exenum = $executequery->num_rows;
		if($exenum>0)
		{
			while($userarray 	= $executequery->fetch_assoc())
			{
				$enduserquery = "select name,profilepic from U_endusers where sno='".$userarray['enduserid']."'";
				$exeenduserquery = $conn->query($enduserquery);
				$enduserqueryarr = $exeenduserquery->fetch_assoc();
				if($userarray['action']=='21')
				{
					
					if($userarray['notread']=='1')
					{
						?>
						<li class="list-group-item1 changeback jewelItemnew" style="cursor:pointer;padding:10px;">
						<?php
					}
					else{
						?>
						<li class="list-group-item1 changeback" style="cursor:pointer;padding:10px;">
						<?php
					}
					
				}
				else 
				{
					?>
					<li class="list-group-item1 changeback"  style="cursor:pointer;padding:10px;">
					<?php 
				}
				
				?>
				
				
				
							<div style="float:left;"  onclick="popuphome('popUpDiv','<?php echo $userarray['actionpath']; ?>','<?php echo $userarray['action']; ?>','<?php echo $userarray['apptitle']?>','<?php echo $userarray['enduserid']; ?>','<?php echo $enduserqueryarr['name']; ?>')" >
								<i><img src="thumbnail.php?src=<?php echo $enduserqueryarr['profilepic'];?>&w=28&h=28" alt="User Icon"></i>
								<div class="text-holder">
									<span class="title-text" style="font-size:9px;">
									   <?php echo $enduserqueryarr['name']?>
									</span>
									<span class="description-text" style="font-size:9px;">
										<?php if($userarray['action']=='21') echo "On-demand Snapshot";?>
								  </span>
								  <span class="description-text" style="font-size:9px;">
										<?php echo $userarray['actiondate']; ?>
								  </span>
								  
								</div>
								<i><img src="thumbnail.php?src=<?php echo $userarray['actionpath'] ?>&w=28&h=28"></i>	
							</div>
							<div  style="float:left;" onclick="unnotify(<?php echo $_REQUEST['notifyowner']; ?>,<?php echo $userarray['sno']?>)" >
								<div class="text-holder"><img class="notification_mark_as_read" src="images/markasread.png" title="Mark as read" alt="Mark as read"></div>
							</div>
							<br style="clear:both;" />
				</li>
				<?php 
			}
			$userdetquery 	= 	"select * from U_getaction where ownerid='".$_REQUEST['notifyowner']."' AND notify='0' AND status='1' order by sno desc limit 1";
			$executequery 	= 	$conn->query($userdetquery);
			$fetarr = $executequery->fetch_assoc();
			$updatequery = $conn->query("update U_getaction set notify=1 where ownerid='".$_REQUEST['notifyowner']."'");
		}
		
		else
		{
			?>
			<li class="list-group-item1">
				No records found
			</li>
			<?php 
		}
		?>
		<?php 
		
		
	}
	$conn->close();
}
else if(isset($_REQUEST['unnotifyid']))
{
	include("connection.php");
	if(strlen($_REQUEST['unnotifyid'])>0)
	{
		$userdetquery 		= "update U_getaction set notread='0' where sno='".$_REQUEST['unnotifyid']."'";
		$executequery 		= $conn->query($userdetquery);
		$userdetquery 	= 	"select * from U_getaction where ownerid='".$_REQUEST['notifyowner1']."' AND status='1' AND notify='1' order by sno desc";
		$executequery 	= 	$conn->query($userdetquery);
		$exenum = $executequery->num_rows;
		if($exenum>0)
		{
			while($userarray 	= $executequery->fetch_assoc())
			{
				$enduserquery = "select name,profilepic from U_endusers where sno='".$userarray['enduserid']."'";
				$exeenduserquery = $conn->query($enduserquery);
				$enduserqueryarr = $exeenduserquery->fetch_assoc();
				if($userarray['action']=='21')
				{
					if($userarray['notread']=='1')
					{
						?>
						<li class="list-group-item1 changeback jewelItemnew" style="cursor:pointer;padding:10px;">
						<?php
					}
					else{
						?>
						<li class="list-group-item1 changeback" style="cursor:pointer;padding:10px;">
						<?php
					}
					
				}
				else 
				{
					?>
					<li class="list-group-item1 changeback"  style="cursor:pointer;padding:10px;">
					<?php 
				}
				
				?>
				
				
				
							<div style="float:left;"  onclick="popuphome('popUpDiv','<?php echo $userarray['actionpath']; ?>','<?php echo $userarray['action']; ?>','<?php echo $userarray['apptitle']?>','<?php echo $userarray['enduserid']; ?>','<?php echo $enduserqueryarr['name']; ?>')" >
								<i><img src="thumbnail.php?src=<?php echo $enduserqueryarr['profilepic'];?>&w=28&h=28" alt="User Icon"></i>
								<div class="text-holder">
									<span class="title-text" style="font-size:9px;">
									   <?php echo $enduserqueryarr['name']?>
									</span>
									<span class="description-text" style="font-size:9px;">
										<?php if($userarray['action']=='21') echo "On-demand Snapshot";?>
								  </span>
								  <span class="description-text" style="font-size:9px;">
										<?php echo $userarray['actiondate']; ?>
								  </span>
								  
								</div>
								<i><img src="thumbnail.php?src=<?php echo $userarray['actionpath'] ?>&w=28&h=28"></i>	
							</div>
							<div  style="float:left;" onclick="unnotify(<?php echo $_REQUEST['notifyowner1']; ?>,<?php echo $userarray['sno']?>)" >
								<div class="text-holder"><img class="notification_mark_as_read" src="images/markasread.png" title="Mark as read" alt="Mark as read"></div>
							</div>
							<br style="clear:both;" />
				</li>
				<?php 
			}
		}
		
		else
		{
			?>
			<li class="list-group-item1">
				No records found
			</li>
			<?php 
		}
		
	}
	$conn->close();
}
else if(isset($_REQUEST['selectuser']))
{
	include("connection.php");
	$userdetquery 	= 	"select * from U_endusers where sno='".$_REQUEST['userid']."'";
	$executequery 	= 	$conn->query($userdetquery);
	$userarray 		=  	$executequery->fetch_assoc();
	$conn->close();
}
function ProfileOwnerImage($ownerid)
{	
	if(isset($ownerid))
	{
		include("connection.php");
		$findimage = $conn->query("select profilepic from U_endowners where sno='".$ownerid."'");
		$findarray = $findimage->fetch_assoc();
		return $findarray['profilepic'];
		$conn->close();
	}	
}
function Editprofile($ownerid)
{	
	$concat="";
	if(isset($ownerid))
	{
		include("connection.php");
		$findimage 		= $conn->query("select * from U_endowners where sno='".$ownerid."'");
		$findarray 		= $findimage->fetch_assoc();
		$editprofile[] 	= $findarray['name'];
		$editprofile[]	= $findarray['email'];
		$editprofile[]	= $findarray['phone'];
		$editprofile[]	= $findarray['company'];
		$editprofile[]	= $findarray['country'];
		$editprofile[]	= $findarray['state'];
		$editprofile[]	= $findarray['city'];
		$editprofile[]	= $findarray['zipcode'];
		$editprofile[]	= $findarray['address1'];
		$editprofile[]	= $findarray['address2'];
		$concat 	= $editprofile;
		return $concat;
		$conn->close();
	}
}
function GetCountry($selectedcountry)
{
	include("connection.php");
	$concat="";
	$countryquery = $conn->query("select * from country1");
	ob_start();
	echo "<select name='country'>";
	while($countryarray = $countryquery->fetch_assoc())
	{
		
		?>
		<option value="<?php echo $countryarray['id'];?>" <?php if($countryarray['id']==$selectedcountry) { echo "selected"; }?>><?php echo $countryarray['country'];?></option>
		<?php
	}
	echo "</select>";
	$concat = ob_get_contents();
	ob_end_clean();
	echo $concat;

	$conn->close();
}
function country()
{
	include("connection.php");
	$concat="";
	$countryquery = $conn->query("select * from country1");
	ob_start();
	echo "<select name='country' class='form-control'>";
	while($countryarray = $countryquery->fetch_assoc())
	{

		?>
		<option value="<?php echo $countryarray['id'];?>" <?php if(isset($_REQUEST['country'])) { if($countryarray['id']==$_REQUEST['country']) { echo "selected"; } } ?>><?php echo $countryarray['country'];?></option>
		<?php
	}
	echo "</select>";
	$concat = ob_get_contents();
	ob_end_clean();
	echo $concat;
	$conn->close();
}
function selectuser($ownerid)
{
	include("connection.php");
	$concat="";
	$selectuserquery = $conn->query("select * from U_endusers where ownerid='".$ownerid."'");
	ob_start();
	echo "<select name='selectuser' id='selectuser' class='form-control' ng-model='selectuser' ng-change='userdaterange()'>";
	echo "<option value=''>--Select--</option>";
	while($selectuserarray = $selectuserquery->fetch_assoc())
	{

		?>
		<option value="<?php echo $selectuserarray['sno'];?>" name="<?php echo $selectuserarray['name'];?>" <?php if(isset($selectuserarray['sno'])) { if($selectuserarray['sno']==@$_REQUEST['selectuser']) { echo "selected"; } } ?>><?php echo $selectuserarray['name'];?></option>
		<?php
	}
	echo "</select>";
	$concat = ob_get_contents();
	ob_end_clean();
	echo $concat;

	$conn->close();
}
?>