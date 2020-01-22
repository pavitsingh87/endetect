<?php


if((isset($_REQUEST['enduserid'])) && (isset($_REQUEST['action'])))
{
		include("services/connection.php");
	$snapshotquery 	= 	mysql_query("insert into U_getaction set enduserid='".$_REQUEST['enduserid']."', action='".$_REQUEST['action']."',actiondate='".date("Y-m-d H:i:s",time())."',ipaddress='".$_SERVER['REMOTE_ADDR']."',hostname='".gethostbyaddr($_SERVER['REMOTE_ADDR'])."'");
	mysql_close($db_connect);
}
if(isset($_REQUEST['selectuser']))
{
	include("connection.php");
	$userdetquery 	= 	"select * from U_endusers where sno='".$_REQUEST['userid']."'";
	$executequery 	= 	mysql_query($userdetquery);
	$userarray 		=  	mysql_fetch_array($executequery);
	mysql_close($db_connect);
}
function ProfileOwnerImage($ownerid)
{	
	if(isset($ownerid))
	{
			include("services/connection.php");
		$findimage = mysql_query("select profilepic from U_endowners where sno='".$ownerid."'");
		$findarray = mysql_fetch_array($findimage);
		return $findarray['profilepic'];
		mysql_close($db_connect);
	}	
}
function Editprofile($ownerid)
{	
	$concat="";
	if(isset($ownerid))
	{
			include("services/connection.php");
		$findimage 		= mysql_query("select * from U_endowners where sno='".$ownerid."'");
		$findarray 		= mysql_fetch_array($findimage);
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
		mysql_close($db_connect);
	}
}
function GetCountry($selectedcountry)
{
		include("services/connection.php");
	$concat="";
	$countryquery = mysql_query("select * from country1");
	ob_start();
	echo "<select name='country'>";
	while($countryarray = mysql_fetch_array($countryquery))
	{
		
		?>
		<option value="<?php echo $countryarray['id'];?>" <?php if($countryarray['id']==$selectedcountry) { echo "selected"; }?>><?php echo $countryarray['country'];?></option>
		<?php
	}
	echo "</select>";
	$concat = ob_get_contents();
	ob_end_clean();
	echo $concat;

	mysql_close($db_connect);
}
function country()
{
		include("services/connection.php");
	$concat="";
	$countryquery = mysql_query("select * from country1");
	ob_start();
	echo "<select name='country' class='form-control'>";
	while($countryarray = mysql_fetch_array($countryquery))
	{

		?>
		<option value="<?php echo $countryarray['id'];?>" <?php if(isset($_REQUEST['country'])) { if($countryarray['id']==$_REQUEST['country']) { echo "selected"; } } ?>><?php echo $countryarray['country'];?></option>
		<?php
	}
	echo "</select>";
	$concat = ob_get_contents();
	ob_end_clean();
	echo $concat;

	mysql_close($db_connect);
}
function selectuser($ownerid)
{
		include("services/connection.php");
	$concat="";
	$selectuserquery = mysql_query("select * from U_endusers where ownerid='".$ownerid."'");
	ob_start();
	echo "<select name='selectuser' id='selectuser' class='form-control' ng-model='selectuser' ng-change='userdaterange()'>";
	echo "<option value=''>--Select--</option>";
	while($selectuserarray = mysql_fetch_array($selectuserquery))
	{

		?>
		<option value="<?php echo $selectuserarray['sno'];?>" name="<?php echo $selectuserarray['name'];?>" <?php if(isset($selectuserarray['sno'])) { if($selectuserarray['sno']==@$_REQUEST['selectuser']) { echo "selected"; } } ?>><?php echo $selectuserarray['name'];?></option>
		<?php
	}
	echo "</select>";
	$concat = ob_get_contents();
	ob_end_clean();
	echo $concat;

	mysql_close($db_connect);
}
function createxml($ddata)
{
	include("services/connection.php");
	$xml_string = '<?xml version="1.0" encoding="ISO-8859-1"?><users>'.$ddata.'</users>';
	
	if( ! $xml = simplexml_load_string( $xml_string ) )
	{
		echo 'Unable to load XML string';
	}
	else
	{
		$db_host			= "localhost";
		$db_name			= "app";
		$db_username			= "root";
		$db_password			= 'jhh78W5$gb';
		$db_connect			= mysql_connect($db_host, $db_username, $db_password);
		$select_db			= mysql_select_db($db_name, $db_connect);
	
		foreach( $xml as $user )
		{
			 
			$sno			=  getNextSequence("sno");
			$enduserid		= (string) $user->enduserid;
			$endownerid		= (string)$user->endownerid;
			$dtype			= (string) $user->dtype;
			$charcount		= (string) $user->charcount;
			$ddata			= (string) $user->ddata;
			$app_title		= (string) $user->app_title;
			$other			= (string) $user->other;
			$mouse_clicks	= (string) $user->mouseclicks;
			$wordcount		= (string)$user->wordcount;
			$linescount		= (string)$user->linecount;
			$decdata		= (string)$user->decdata;
			$endversion 	= (string) $user->endversion;
			$ddate 			= (string) $user->ddate;
			$ddate 			= Date("Y-m-d H:i:s",strtotime($ddate));
			$ttype='0';
				
			$ipaddress = $_SERVER['REMOTE_ADDR'];
			$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	
			if($dtype=='1')
			{
				$decdata="Startup";
			}
			else if($dtype=='9')
			{
				$decdata="Shutdown";
			}
	
	
			/*Create array of data to store*/
			$user_data=array(
					'sno'=>$sno,
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
					'endversion'=>$endversion,
					'ddate'=>strtotime($ddate)
			);
				
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
				
	
			$checkuser = @mysql_num_rows(mysql_query("select * from U_endusers where sno='".$enduserid."' AND active='1' AND licenseflag='1'"));
			if($checkuser>0)
			{
				$update_enduser = mysql_query("update U_endusers set $timeset"."".$updateconcat."".$charcount." where sno='".$enduserid."'");
				$collection->save($user_data);
					
	
			}
		}
		$mysql_close($db_connect);
		if(isset($update_enduser))
		{
			return "100";
		}
	
	}
}


function imagecreate($enduserid,$ownerid,$ddata)
{
	$ky = "fPixAxOcDiGZFXeCCOfUF3SyGWCQQtuni/cx/yVvgCU="; //INSERT THE KEY GENERATED BY THE C# CLASS HERE
	$iv = "n8OspIyuqBHdTn0q/JRJfi9A9WZV61B8BqJQKxEw64E="; //INSERT THE IV GENERATED BY THE C# CLASS HERE

	$imageDataEncoded = decryptRJ256($ky, $iv, $ddata);

	$imageData = base64_decode($imageDataEncoded);
	$source = imagecreatefromstring($imageData);
	$angle = 0;
	$rotate = imagerotate($source, $angle, 0); // if want to rotate the image
	$snapshot="snapshot";
	$year = date("Y",time());
	$month = date("m",time());
	$day = date("d",time());

	$enduserid = $enduserid;
	$ownerid = $endownerid;
	//file_exists
	$directoryname = $snapshot."/".$year."/".$month."/".$day."/".$ownerid."/".$enduserid."/";
	if(!(file_exists($directoryname)))
	{
		@mkdir($directoryname, 0777,true);
	}
	$currenttime 	= time();
	$imageName 		= $directoryname.$enduserid."-".$currenttime.".png";
	$imageSave 		= imagejpeg($rotate,$imageName,100);
	imagedestroy($source);

	return $imageName;
}

function decryptRJ256($encrypted)
{
	$key = "fPixAxOcDiGZFXeCCOfUF3SyGWCQQtuni/cx/yVvgCU="; //INSERT THE KEY GENERATED BY THE C# CLASS HERE
	$iv = "n8OspIyuqBHdTn0q/JRJfi9A9WZV61B8BqJQKxEw64E="; //INSERT THE IV GENERATED BY THE C# CLASS HERE
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

//removes PKCS7 padding
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
function createImage($enduserid,$ownerid,$dtype,$ddata)
{
	$year = date("Y",time());
	$month = date("m",time());
	$day = date("d",time());
	$snapshot = "snapshot";
	$directoryname = $snapshot."/".$year."/".$month."/".$day."/".$ownerid."/".$enduserid."/";
	
	
	if(!(file_exists($directoryname)))
	{
		@mkdir($directoryname, 0777,true);
	}
	
	$imageDataEncoded = decryptRJ256($ddata);
	
	$imageData = base64_decode($imageDataEncoded);
	$source = imagecreatefromstring($imageData);
	$angle = 0;
	$rotate = imagerotate($source, $angle, 0); // if want to rotate the image
	
	$currenttime 	= time();
	$imageName 		= $directoryname.$enduserid."-".$currenttime.".png";
	$imageSave 		= imagejpeg($rotate,$imageName,100);
	imagedestroy($source);
	return $imageSave;
}

?>