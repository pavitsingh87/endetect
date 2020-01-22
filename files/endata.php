<?php
date_default_timezone_set("Asia/Kolkata");
include("connection.php");
function getNextSequence($name){
	return time();
}
function getUniqueSequence($name){
	return (md5(time() . rand()));
}
function milliseconds() {
    $mt = explode(' ', microtime());
    return ((int)$mt[1]) * 1000 + ((int)round($mt[0] * 1000));
}
function customMailFunction($sno,$enduserid,$endownerid,$dtype,$charcount,$app_title,$other,$mouse_clicks,$wordcount,$linescount,$ttype,$hostname,$delete,$endversion,$decdata,$ddata) {
    $url_send = baseurl.'services/endetect_api.php';
    $user_data=array(
				'sno'=>$sno,
				'newsno'=>$newsno,
				'enduserid'=>$enduserid,
				'endownerid'=>$endownerid,
				'dtype'=>$dtype,
				'charcount'=>$charcount,
				'app_title'=>$app_title,
				'other'=>$other,
				'mouse_clicks'=>$mouse_clicks,
				'wordcount'=>$wordcount,
				'linecount'=>$linescount,
				'ttype'=>$ttype,
				'ipaddress'=>$ipaddress,
				'hostname'=>$hostname,
				'delete'=>$delete,
				'endversion'=>$endversion,
				'ddate'=>milliseconds(),
				'decdata'=>$decdata,
				'ddata'=>$ddata
				);
    $str_data = json_encode($user_data);
    sendPostData($url_send, $str_data);
}
function sendPostData($url, $post){
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST,           1 );
	curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
	curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain',
	        "X-API-KEY: A1F584C3083132CE18DCDA579C753579D3276AAB"));
	$result = curl_exec($ch);
	curl_close($ch);  // Seems like good practice
}
function decryptRJ256($key,$iv,$encrypted)
{
	//PHP strips "+" and replaces with " ", but we need "+" so add it back in...
	$encrypted = str_replace(' ', '+', $encrypted);

	//get all the bits
	$key = base64_decode($key);
	$iv = base64_decode($iv);
	$encrypted = base64_decode($encrypted);

	$rtn = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $encrypted, MCRYPT_MODE_CBC, $iv);
	$rtn = unpad($rtn);
	return($rtn);
}
function unpad($value)
{
	$blockSize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
	$packing = ord($value[strlen($value) - 1]);
	if($packing && $packing < $blockSize)
	{
		for($P = strlen($value) - 1; $P >= strlen($value) - $packing; $P--)
		{
			if(ord($value{$P}) != $packing)
			{
			$packing = 0;
			}
		}
	}
	return substr($value, 0, strlen($value) - $packing);
}
$currentdatetime = Date("Y-m-d H:i:s",time());
$enduserid = $_REQUEST['enduserid'];
$endownerid = $_REQUEST['endownerid'];
$charcount = $_REQUEST['charcount'];
$dtype = $_REQUEST['dtype'];
$ddata = $_REQUEST['ddata'];
$delete = "1";
$mouse_clicks = $_REQUEST['mouse_clicks'];
$app_title = $_REQUEST['app_title'];
$other= $_REQUEST['other'];
$endversion= $_REQUEST['endversion'];
$wordcount= $_REQUEST['wordcount'];
$linescount= $_REQUEST['linecount'];
$actionresult= $_REQUEST['actionresult'];

if($actionresult!="")
{
}
if($linescount=='null')
{
	$linescount='0';
}
$decdata = $_REQUEST["decdata"];

$ddate = date("Y-m-d H:i:s",time());
$ipaddress = $_SERVER['REMOTE_ADDR'];
$ttype = '1';
if(strtolower($dtype)=='pending')
{
	$xml_string = '<?xml version="1.0" encoding="ISO-8859-1"?><users>'.$ddata.'</users>';
    if( ! $xml = simplexml_load_string( $xml_string ) )
    {
        echo 'Unable to load XML string';
    }
    else
    {
		foreach( $xml as $user )
	    {
			$sno			=  milliseconds();
			$newsno			=  $sno;
		    $enduserid		= (string) $user->enduserid;
		    $endownerid		= (string) $user->endownerid;
		    $dtype			= (string) $user->dtype;
		    $charcount		= (string) $user->charcount;
		    $ddata			= (string) $user->ddata;
		    $app_title		= (string) $user->app_title;
		    $other			= (string) $user->other;
		    $mouse_clicks	= (string) $user->mouseclicks;
		    $wordcount		= (string) $user->wordcount;
		    $linescount		= (string) $user->linecount;
		    $decdata		= (string) $user->decdata;
		    $endversion 	= (string) $user->endversion;
		    $ddate 			= (string) $user->ddate;
			$usermanuallyoff=$conn->query("select * from U_endusers where sno='".$enduserid."' AND active='1' AND licenseflag='1'");
			$usermanuallyoffnum = @$usermanuallyoff->num_rows;
			if($usermanuallyoffnum>0)
			{
				$ttype='0';
				if($dtype=='1')
			    {
			    	$decdata="Startup";
			    }
			    else if($dtype=='9')
			    {
			    	$decdata="Shutdown";
			    }
			    if(($dtype!='20') || ($dtype!='21'))
				{

				/*Create array of data to store*/
				$user_data=array(
				'sno'=>$sno,
				'newsno'=>$sno,
				'enduserid'=>$enduserid,
				'endownerid'=>$endownerid,
				'dtype'=>$dtype,
				'charcount'=>$charcount,
				'ddata'=>$ddata,
				'app_title'=>$app_title,
				'other'=>$other,
				'mouse_clicks'=>$mouse_clicks,
				'wordcount'=>$wordcount,
				'linecount'=>$linescount,
				'decdata'=>$decdata,
				'ttype'=>$ttype,
				'ipaddress'=>$ipaddress,
				'hostname'=>$hostname,
				'delete'=>$delete,
				'endversion'=>$endversion,
				'ddate'=>$ddate
				);

				//$collection->save($user_data);
				customMailFunction($sno,$enduserid,$endownerid,$dtype,$charcount,$app_title,$other,$mouse_clicks,$wordcount,$linescount,$ttype,$hostname,$delete,$endversion,urlencode($decdata),$ddata);
				}
				if($dtype=='1')
				{
					$timeset ="startuptime='".$ddate."'";
				}
				elseif ($dtype=='9')
				{
					$timeset ="shutdowntime='".$ddate."'";
				}
				else
				{
					$timeset ="lastaccesstime='".$ddate."'";
				}

				if($dtype=='2')
				{
					$updateconcat = ",type_words=type_words+".intval($wordcount);
				}
				if($dtype=='3')
				{
					$updateconcat = ",copied_words=copied_words+".intval($wordcount);
				}
				if($dtype=='4')
				{
					$updateconcat = ",files_copies=files_copies+".intval($linescount);
				}
				if($dtype=='5')
				{
					$updateconcat = ",pendriveinsert=pendriveinsert+1";
				}
				if($dtype=='7')
				{
					$updateconcat = ",mobileinsert=mobileinsert+1";
				}
				$charcount = ",datasize=datasize+".intval($charcount);

				$checkuserquery = $conn->query("select * from U_endusers where sno='".$enduserid."' AND active='1' AND licenseflag='1'");
				$checkuser = $checkuserquery->num_rows;

				if($checkuser>0)
				{
					$update_enduser = $conn->query("update U_endusers set ".$timeset."".$updateconcat."".$charcount." where sno='".$enduserid."'");

				}
				else
				{
					$InsertEnduser = $conn->query("insert into U_endusers set name='".$other."',ownerid='".$endownerid."',active=1,licenseflag=1,jdt='".$currentdatetime."',macaddress='".$macaddress."',trackkey='".$track."',pcname='".$other."',ipaddress='".$ipaddress."',hostname='".$hostname."'");
				}
			}
			else
			{

			}
	    }
		$errormessage = "100";
    }
}
else
{

	$usermanuallyoff=$conn->query("select * from U_endusers where sno='".$enduserid."' AND active='1' AND licenseflag='1'");
	$usermanuallyoffnum = @$usermanuallyoff->num_rows;
	if($usermanuallyoffnum>0)
	{
		if(($dtype=='20') || ($dtype=='21'))
		{
			$imageDataEncoded = $_POST["decdata"];

			if($endversion<='2000' || $endversion==null)
			{
				$imageDataEncoded = str_replace(" ","+",$imageDataEncoded);
			}
			$imageData = base64_decode($imageDataEncoded);
			$source = imagecreatefromstring($imageData);
			$angle = 0;
			$rotate = imagerotate($source, $angle, 0); // if want to rotate the image
			$mainsnapshoturl = $_SERVER['DOCUMENT_ROOT']."/";
			$snapshot = "snapshot";
			$year = date("Y",time());
			$month = date("m",time());
			$day = date("d",time());

			$enduserid = $enduserid;
			$ownerid = $endownerid;
			//file_exists
			$directoryname = $mainsnapshoturl.$snapshot."/".$year."/".$month."/".$day."/".$ownerid."/".$enduserid."/";
			$subdirec = $snapshot."/".$year."/".$month."/".$day."/".$ownerid."/".$enduserid."/";
			if(!(file_exists($directoryname)))
			{
				@mkdir($directoryname, 0755,true);
			}
			$currenttime 	= time();

			$imageName 		= $subdirec.$enduserid."-".$currenttime.".jpg";
			$imageSave 		= imagejpeg($rotate,$imageName,80);
			imagedestroy($source);
			$subimagename = $subdirec.$enduserid."-".$currenttime.".jpg";

			//$subimagename = $decdata;
			$decdata = $subimagename;
			$ddata = $subimagename;
		}
		$sno	=  milliseconds();
		$newsno = $sno;
		if($dtype=='1')
		{
			$decdata="Startup";
		}
		else if($dtype=='9')
		{
			$decdata="Shutdown";
		}
		$checkuserquery=$conn->query("select * from U_endusers where sno='".$enduserid."' AND active='1' AND licenseflag='1'");
		$checkuser = @$checkuserquery->num_rows;
		if($checkuser>0)
		{
			$fetchuserarr = $checkuserquery->fetch_assoc();
			$fetchdetails = date("Y-m-d",strtotime($fetchuserarr['startuptime']));
			$currentdatestamp = date("Y-m-d",time());
			if($dtype=='1')
			{
				$timeset ="startuptime='".$ddate."'";
			}
			elseif ($dtype=='9')
			{
				$timeset ="shutdowntime='".$ddate."'";
			}
			else
			{
				$timeset ="lastaccesstime='".$ddate."'";
			}
			if(strcmp($fetchdetails, $currentdatestamp)!='0')
			{
				$timeset ="startuptime='".$ddate."',lastaccesstime='".$ddate."' ";
			}
			if(($dtype!='20') || ($dtype!='21'))
			{
				$user_data=array(
						'sno'=>$sno,
						'newsno'=>$newsno,
						'enduserid'=>$enduserid,
						'endownerid'=>$endownerid,
						'dtype'=>$dtype,
						'charcount'=>$charcount,
						'ddata'=>$ddata,
						'app_title'=>$app_title,
						'other'=>$other,
						'mouse_clicks'=>$mouse_clicks,
						'wordcount'=>$wordcount,
						'linecount'=>$linescount,
						'decdata'=>$decdata,
						'ttype'=>$ttype,
						'delete'=>$delete,
						'ipaddress'=>$ipaddress,
						'hostname'=>$hostname,
						'endversion'=>$endversion,
						'ddate'=>time()
				);

				customMailFunction($sno,$enduserid,$endownerid,$dtype,$charcount,$app_title,$other,$mouse_clicks,$wordcount,$linescount,$ttype,$hostname,$delete,$endversion,urlencode($decdata),$ddata);
			}
			if($dtype=='2')
			{
				$updateconcat = ",type_words=type_words+".intval($wordcount);
			}
			if($dtype=='3')
			{
				$updateconcat = ",copied_words=copied_words+".intval($wordcount);
			}
			if($dtype=='4')
			{
				$updateconcat = ",files_copies=files_copies+".intval($linescount);
			}
			if($dtype=='5')
			{
				$updateconcat = ",pendriveinsert=pendriveinsert+1";
			}
			if($dtype=='7')
			{
				$updateconcat = ",mobileinsert=mobileinsert+1";
			}
			$charcount = ",datasize=datasize+".intval($charcount);
			$versionsta= ",version=".$endversion;
			if($checkuser>0)
			{
				$update_enduser = $conn->query("update U_endusers set ".$timeset."".$updateconcat."".$charcount."".$versionsta."  where sno='".$enduserid."'");
				if($dtype=='21')
				{
					$update_uaction = $conn->query("update U_getaction set status='1',actionpath='".$decdata."',apptitle='".$app_title."',feedid='".$sno."' where sno='".$actionresult."'");
				}
			}
			else
			{
				$InsertEnduser = $conn->query("insert into U_endusers set
				sno='".$enduserid."',
				name='".$other."',ownerid='".$endownerid."',active=1,licenseflag=1,jdt='".$currentdatetime."',macaddress='".$macaddress."',trackkey='".$track."',pcname='".$other."',ipaddress='".$ipaddress."',hostname='".$hostname."'");
			}
		}
	}
	else
	{

	}
	header('Access-Control-Allow-Origin: *');
	header('Content-Type: text/plain');
	$errormessage="100";
}
$conn->close();
echo $errormessage;
?>
