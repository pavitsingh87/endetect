<?php 
function getNextSequence($name){
	global $collection;
	$retval = $collection->findAndModify(
			array('_id' => $name),
			array('$inc' => array("seq" => 1)),
			null,
			array(
					"new" => true,
			)
	);
	return $retval['seq'];
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
function createxml($dtype,$ddata)
{
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
		echo "100";
		$mysql_close($db_connect);
	
	
	}
}
?>