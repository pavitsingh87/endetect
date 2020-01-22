<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/html; charset=utf-8');
require 'vendor/autoload.php';
use Elasticsearch\ClientBuilder;
$client = ClientBuilder::create()->build();
include("connection.php");

function milliseconds() {
    $mt = explode(' ', microtime());
    return ((int)$mt[1]) * 1000 + ((int)round($mt[0] * 1000));
}

function returntf($str) {
	if($str=="1" || $str=="") {
		return "true";
	} else {
		return "false";
	}
}

function sanitize_for_xml($input) {
    // Convert input to UTF-8.
    $old_setting = ini_set('mbstring.substitute_character', '"none"');
    $input = mb_convert_encoding($input, 'UTF-8', 'auto');
    ini_set('mbstring.substitute_character', $old_setting);

    // Use fast preg_replace. If failure, use slower chr => int => chr conversion.
    $output = preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', '', $input);
    if (is_null($output)) {
        // Convert to ints.
        // Convert ints back into a string.
        $output = ords_to_utfstring(utfstring_to_ords($input), TRUE);
    }
    return $output;
}

function saveIUWH($enduserid, $endownerid, $dtype, $ddata) {
	Global $conn, $client;

	$ddata = urldecode($ddata);
	$ddata = sanitize_for_xml($ddata);
	$xml_string = '<?xml version="1.0" encoding="UTF-8"?>'.$ddata;

	$ttt = Date("Y-m-d H:i:s", time()) ." - ". $enduserid ." - ". $endownerid ." ". $xml_string;
	$conn->query("INSERT INTO notes SET note = 'Savenetdata ".$ttt."'");

    $xml = simplexml_load_string($xml_string);
	if(!$xml) {
	    echo 'Unable to load XML string';
	} else { // web history
		foreach($xml->webhistory->data as $user) {
			$url = urlencode($user->url);
	    	$title = (string)$user->title;
	    	$visitedtime = (string)$user->visitedtime;
	    	$browser = (string)$user->browser;

	    	$sno = milliseconds() + $i;
	    	$ipaddress = $_SERVER["REMOTE_ADDR"];
	    	$hostname = "";
	    	$newsno = $sno;
			//$ss_date = Date("Y-m-d H:i:s", $visitedtime);

            $params = [
                'index' => 'mongoindex1',
                'type'  => 'u_endata',
                'id'    => $sno,
                'body'  => array(
                    'sno' => $sno,
    				'newsno' => $newsno,
    				'enduserid' => $enduserid,
    				'endownerid' => $endownerid,
    				'dtype' => '42',
    				'ipaddress' => $ipaddress,
    				'ddate' => $sno,
    				'url' => $url,
    				'title' => $title,
    				'visitedtime' => $visitedtime,
    				'browser' => $browser
            	)
            ];

            //echo "<pre>"; print_r($params); echo "</pre>";
            try {
                $responses = $client->index($params);
            	//echo "<pre>"; print_r($response); echo "</pre>";
            } catch (Exception $e) {
                echo 'Caught exception: ',  $e->getMessage();
            }
		}

		//Internet Usage
		foreach($xml->internetusage->data as $user) {
			$sent = $user->sent;
	    	$recd = $user->recd;
	    	$name = $user->name;
	    	$date = Date("Y-m-d", time());

            // check data for current day or not with name
	    	$sqlf = $conn->query("select * from U_internetusage where enduserid='".$enduserid."' AND name='".$name."' AND datesave='".$date."'");
	    	if(($sqlf->num_rows) > 0) { //update
	    		$conn->query("update U_internetusage set endownerid='".$endownerid."', sent=".$sent.", received=".$recd." where enduserid='".$enduserid."' AND name='".$name."' AND datesave='".$date."'");
	    	} else { //insert
	    		$conn->query("insert into U_internetusage set endownerid='".$endownerid."', sent=".$sent.", received=".$recd." , enduserid='".$enduserid."' , name='".$name."', datesave='".$date."'");
	    	}
		}

		//Running Apps
		foreach($xml->runningapps->data as $user) {
			$procname = $user->ProcName;
			$title = $user->Title;
			$totaltime = $user->TotalTime;
			$activetime = $user->ActiveTime;
			$date = Date("Y-m-d", time());

            $sqlf = $conn->query("select * from U_runningapps where enduserid='".$enduserid."' AND procname='".$procname."' AND title='".$title."'");
			if($sqlf->num_rows > 0) { //update
	    		$conn->query("update U_runningapps endownerid='".$endownerid."', set datesave='".$date."', activetime='".$activetime."', totaltime='".$totaltime."' where procname='".$procname."' AND enduserid='".$enduserid."' AND title='".$title."'");
	    	} else { //insert
	    		$conn->query("insert into U_runningapps set endownerid='".$endownerid."', enduserid='".$enduserid."',
	    			datesave='".$date."', procname='".$procname."',
	    			title='".$title."', activetime='".$activetime."', totaltime='".$totaltime."'");
	    	}
		}

		//Lazy Minutes
		foreach($xml->lazyminutes->data as $user) {
			$lazy = $user->lazy;
			$startdatetime = Date("Y-m-d H:i:s", strtotime($user->startdatetime));
			$enddatetime = Date("Y-m-d H:i:s", strtotime($user->enddatetime));
			$date = Date("Y-m-d", time());

			$sqlf = $conn->query("select * from U_lazyminutes where enduserid='".$enduserid."' AND startdatetime='".$startdatetime."' AND datesave='".$date."'");
			if($sqlf->num_rows > 0) {
	    		$conn->query("update U_lazyminutes set endownerid='".$endownerid."',startdatetime='".$startdatetime."', lazymin=lazymin+".$lazy.",inserttime='".$startdatetime."', enddatetime='".$enddatetime."' where enduserid='".$enduserid."' AND startdatetime='".$startdatetime."' AND datesave='".$date."'");
	    	} else {
	    		$conn->query("insert into U_lazyminutes set endownerid='".$endownerid."',enduserid='".$enduserid."',
	    			lazymin='".$lazy."', startdatetime='".$startdatetime."',
	    			enddatetime='".$enddatetime."', datesave='".$date."', inserttime='".Date("Y-m-d H:i:s")."'");
	    	}
		}
	} //else closed
} //saveIUWH closed

$enduserid = $_REQUEST["enduserid"];
$ownerid = $_REQUEST["ownerid"];
$lickey = $_REQUEST["lickey"];
$ddata = $_REQUEST["ddata"];
$dtype = $_REQUEST["dtype"];

$col_query = $conn->query("SELECT * FROM U_endusers WHERE sno='".$enduserid."' AND ownerid='".$ownerid."' AND lickey='".$lickey."' LIMIT 1");
if($col_query->num_rows > 0) {
	$fetarrayrow = $col_query->fetch_object();
	$activationdate = $fetarrayrow->act_date;
	$expirationdate = $fetarrayrow->expiry_date;
	$keystore = $fetarrayrow->keystore;
	$trayicon = $fetarrayrow->trayicon;
	$screenshot = $fetarrayrow->screenshot;
	$screenshotinterval = $fetarrayrow->screenshot_interval;
	$suspendedlic = $fetarrayrow->suspendeduser;

	if(time() > strtotime($expirationdate)) {
		echo "status=600";
	} else if($suspendedlic == "1") {
		echo "status=700";
	} else {
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
		//$arr = "status=500&startdate=".Date("d/m/Y H:i:s",strtotime($activationdate))."&enddate=".Date("d/m/Y H:i:s",strtotime($expirationdate))."&trayicon=".$trayicon."&keylog=".$keylog."&screenshot=".$screenshot."&screenshotinterval=".$fetarrayrow->screenshot_interval."&auth=".$auth."&pause=".$pause."&remove=".$eddelete."&lazyminutes=".$lazyminutes."&webhistory=".$webhistory."&stealthinstall=".$stealthinstall."&fullname=".$fullname;

        echo "status=500";
		if($dtype == "53" && $ddata != "") {
			// Internet Usage & Web History
			$startdatetime = Date("Y-m-d 00:00:00", time());
			$enddatetime = Date("Y-m-d 23:59:59", time());
			$rt = "select * from U_useratt where enduserid='".$enduserid."'";
			$checkuseratt = $conn->query("select * from U_useratt where enduserid='".$enduserid."' AND (starttime between '".$startdatetime."' AND '".$enddatetime."') order by id desc limit 1");
			if($checkuseratt->num_rows > 0) {
				$userarr = $checkuseratt->fetch_assoc();
				$idupdate = $userarr["id"];
				$conn->query("update U_useratt set endtime='".Date("Y-m-d H:i:s",time())."' where id='".$idupdate."'");
			} else {
				$conn->query("insert into U_useratt set enduserid='".$enduserid."', starttime='".Date("Y-m-d H:i:s",time())."',endtime='".Date("Y-m-d H:i:s",time())."'");
			}
			saveIUWH($enduserid, $ownerid, $dtype, $ddata);
		}
	}
} else {
	echo "status=900";
}
?>
