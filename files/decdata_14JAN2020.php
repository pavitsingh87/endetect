<?php
include_once("connection.php");

require 'vendor/autoload.php';
use Elasticsearch\ClientBuilder;
$client = ClientBuilder::create()->build();

// function getNextSequence($name){
// 	return time();
// }
// function getUniqueSequence($name){
// 	return (md5(time() . rand()));
// }

function milliseconds() {
    $mt = explode(' ', microtime());
    return ((int)$mt[1]) * 1000 + ((int)round($mt[0] * 1000));
}

function customMailFunction($sno,$enduserid,$endownerid,$dtype,$charcount,$app_title,$other,$mouse_clicks,$wordcount,$linescount,$ttype,$hostname,$delete,$endversion,$decdata,$ddata) {
    $url_send = baseurl ."services/endetect_api.php";
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

function savePCInfo($enduserid, $endownerid, $dtype, $ddata)
{
	Global $conn;

	$ddata = urldecode($ddata);
	$explodestr = explode("</osinfo>",$ddata);
	$str1 = $explodestr["0"]."</osinfo></root>";
	$str2 = "<root>".$explodestr["1"];

    $ttt = Date("Y-m-d H:i:s", time()) ." - ". $enduserid ." - ". $endownerid ." ". $ddata;
	$conn->query("INSERT INTO notes SET note = 'DecData ".$ttt."'");

	$xml_string = '<?xml version="1.0" encoding="UTF-8"?>'.$str1;
	$xml = simplexml_load_string( $xml_string );
	$xml_string1 = '<?xml version="1.0" encoding="UTF-8"?>'.$str2;
	$xml1 = simplexml_load_string( $xml_string1 );
	if( ! $xml)
	{
	    echo 'Unable to load XML string';
	}
	else
	{
		$updatesql = $conn->query("update enduserinstalledsoftware set installflag='0' where enduserid='".$enduserid."'");
		$j=0;
		foreach($xml->osinfo->children() as $user)
		{
			$OS = $user[$j]->OS;
	    	$OSarch = $user[$j]->OSarch;
	    	$CPU = $user[$j]->CPU;
	    	$RAM = $user[$j]->RAM;
	    	$Workgroup = $user[$j]->Workgroup;
	    	$CName = $user[$j]->CName;
	    	$UName = $user[$j]->UName;
	    	$UserDomainName = urlencode($user[$j]->UserDomainName);
	    	$OSVersion = urlencode($user[$j]->OSVersion);
	    	$HDD = urlencode($user[$j]->HDD);
	    	$Networks = urlencode($user[$j]->Networks);
	    	// osinfo
	    	$checkquery = $conn->query("select * from U_userpcinfo where enduserid='".$enduserid."'");
			if($checkquery->num_rows>0)
			{
				$updatemysql = $conn->query("update U_userpcinfo set
				os='".$OS."',
				osarch='".$OSarch."',
				cpu='".$CPU."',
				ram='".$RAM."',
				workgroup='".$Workgroup."',
				cname='".$CName."',
				uname='".$UName."',
				userdomainname='".$UserDomainName."',
				osversion='".$OSVersion."',
				hdd='".$HDD."',
				networks='".$Networks."' where enduserid='".$enduserid."'");
			}
			else
			{
				$insertmysql = $conn->query("insert into U_userpcinfo set
				enduserid='".$enduserid."',
				os='".$OS."',
				osarch='".$OSarch."',
				cpu='".$CPU."',
				ram='".$RAM."',
				workgroup='".$Workgroup."',
				cname='".$CName."',
				uname='".$UName."',
				userdomainname='".$UserDomainName."',
				osversion='".$OSVersion."',
				hdd='".$HDD."',
				networks='".$Networks."'");
			}
			$j++;
		}
	}
	if( ! $xml1)
	{
	    echo 'Unable to load XML string';
	}
	else
	{
		$i=0;
		foreach($xml1->children() as $user)
		{
			$installyear = substr($user[$i]->data->installdate,0,4);
	    	$installmonth = substr($user[$i]->data->installdate,4,2);
	    	$installdate = substr($user[$i]->data->installdate,6,2);
	    	$displayname = $user[$i]->data->displayname;
	    	$publisher = $user[$i]->data->publisher;
	    	$estimatedsize = $user[$i]->data->estimatedsize;
	    	$installdate1 = $installyear."-".$installmonth."-".$installdate;
	    	$comments = $user[$i]->data->comments;
	    	$sno = milliseconds()+$i;
	    	$ipaddress = $_SERVER["REMOTE_ADDR"];
	    	$hostname = gethostname();
	    	$newsno = $sno;

    		// installed software in mysql

			$checkquery = $conn->query("select * from enduserinstalledsoftware where displayname='".$displayname."' AND enduserid='".$enduserid."'");
			if($checkquery->num_rows>0)
			{
				$updatemysql = $conn->query("update enduserinstalledsoftware set installflag='1' where enduserid='".$enduserid."' AND displayname='".$displayname."'");
			}
			else
			{
				$insertmysql = $conn->query("insert into enduserinstalledsoftware set sno='".$sno."',
				newsno='".$newsno."',
				enduserid='".$enduserid."',
				endownerid='".$endownerid."',
				dtype='".$dtype."',
				ipaddress='".$ipaddress."',
				hostname='".$hostname."',
				ddate='".$sno."',
				displayname='".$displayname."',
				publisher='".$publisher."',
				estimatedsize='".$estimatedsize."',
				installdate='".$installdate1."',
				comments='".$comments."',
				installflag='1'");
			}
			$i++;
		}
	}
	header('Access-Control-Allow-Origin: *');
	header('Content-Type: text/plain');
	$errormessage="100";
	$conn->close();
	echo $errormessage;
}
$enduserid = $_REQUEST['enduserid'];
$endownerid = $_REQUEST['endownerid'];
$dtype = $_REQUEST['dtype'];

$ddata = $_REQUEST['ddata'];
if($dtype=="44")
{
	savePCInfo($enduserid, $endownerid, $dtype, $ddata);
}
?>
