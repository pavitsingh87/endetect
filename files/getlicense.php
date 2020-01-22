<?php
require 'vendor/autoload.php';
use Elasticsearch\ClientBuilder;
$client = ClientBuilder::create()->build();
include("connection.php");
function milliseconds() {
    $mt = explode(' ', microtime());
    return ((int)$mt[1]) * 1000 + ((int)round($mt[0] * 1000));
}
function returntf($str)
{
	if($str=="1" || $str=="")
	{
		return "true";
	}
	else
	{
		return "false";
	}
}
$enduserid = $_REQUEST["enduserid"];
$ownerid = $_REQUEST["ownerid"];
$lickey = $_REQUEST["lickey"];
$ddata = $_REQUEST["ddata"];
$dtype = $_REQUEST["dtype"];

$getact_query = "select sno from U_getaction where enduserid='".$_REQUEST['enduserid']."' AND status='0' order by sno desc limit 1 ";

$getact_exe = $conn->query($getact_query);

$getact_num = $getact_exe->num_rows;

$action_var = "none";
if($getact_num>0)
{
	$getact_arr = $getact_exe->fetch_assoc();
	
	$action_var =  "21#".$getact_arr['sno'];
}

$checkownerlicense = "select * from U_endusers where sno='".$enduserid."' AND ownerid='".$ownerid."' AND lickey='".$lickey."' limit 1";
$col_query = $conn->query($checkownerlicense);
if($col_query->num_rows>0)
{
	$fetarrayrow = $col_query->fetch_object();
	$activationdate = $fetarrayrow->act_date;
	$expirationdate = $fetarrayrow->expiry_date;
	$keystore = $fetarrayrow->keystore;
	$trayicon = $fetarrayrow->trayicon;
	$screenshot = $fetarrayrow->screenshot;
	$screenshotinterval = $fetarrayrow->screenshot_interval;
	$suspendedlic = $fetarrayrow->suspendeduser;
	//$expire=true;
	if(time() > strtotime($expirationdate))
	{
		$arr = "status=600&expire=true";
		echo $arr;
	}
	else if($suspendedlic=="1")
	{
		$arr = "status=700&suspend=true";
		echo $arr;
	}
	else
	{
		$keylog = returntf($fetarrayrow->keylog);
		$trayicon = returntf($fetarrayrow->trayicon);
		$screenshot = returntf($fetarrayrow->screenshot);
		$auth = returntf($fetarrayrow->active);
		$pause = returntf($fetarrayrow->pause);
		$eddelete = returntf($fetarrayrow->eddelete);
		$webhistory = returntf($fetarrayrow->webhistory);
		$stealthinstall = returntf($fetarrayrow->stealthinstall);
		$lazyminutes = $fetarrayrow->lazyminutes;
		$fullname = $fetarrayrow->name;
		//$expirydate = Date('d/m/Y', strtotime($expirationdate.' -1 day'));
		$expirydate = Date('Y-m-d', strtotime($expirationdate.' -1 day'));
		$expirydate2 = Date('d/m/Y', strtotime($expirationdate.' -1 day'));
		
		$currentTimeStamp = time();
		$expireTimeStamp = strtotime($expirydate);
		$usbblock = returntf($fetarrayrow->usbblock);
		$taskmanagerblock = returntf($fetarrayrow->taskmanagerblock);

		if($currentTimeStamp>$expireTimeStamp)
		{
			$expireUser = "true";
		}
		else
		{
			$expireUser="false";
		}
		if($suspendedlic==0)
		{
			$suspend="false";
		}
		else
		{
			$suspend="true";
		}
		$arr = "status=500&startdate=".Date("d/m/Y",strtotime($activationdate))."&enddate=".$expirydate2."&trayicon=".$trayicon."&keylog=".$keylog."&screenshot=".$screenshot."&screenshotinterval=".$fetarrayrow->screenshot_interval."&auth=".$auth."&pause=".$pause."&remove=".$eddelete."&lazyminutes=".$lazyminutes."&webhistory=".$webhistory."&stealthinstall=".$stealthinstall."&fullname=".$fullname."&expire=".$expireUser."&suspend=".$suspend."&usbblock=".$usbblock."&taskmanagerblock=".$taskmanagerblock."&actionresult=".$action_var;
		echo $arr;	
		if($dtype=="52" && $ddata!="")
		{
			// Internet Usage & Web History
			$startdatetime = Date("Y-m-d", time());
			$enddatetime = Date("Y-m-d", time());
			$rt = "select * from U_useratt where enduserid='".$enduserid."'";
			$checkuseratt = $conn->query("select * from U_useratt where enduserid='".$enduserid."' AND (starttime between '".$startdatetime."' AND '".$enddatetime."') order by id desc limit 1");
			if($checkuseratt->num_rows>0)
			{
				$userarr = $checkuseratt->fetch_assoc();
				$idupdate = $userarr["id"];
				$conn->query("update U_useratt set endtime='".Date("Y-m-d H:i:s",time())."' where id='".$idupdate."'");
			}
			else
			{
				$conn->query("insert into U_useratt set enduserid='".$enduserid."', starttime='".Date("Y-m-d H:i:s",time())."',endtime='".Date("Y-m-d H:i:s",time())."'");
			}
			
		}
	}
}
else
{
	$arr = "status=900";
		echo $arr;	
}
?>
