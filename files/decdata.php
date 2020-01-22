<?php
include_once("connection.php");

function milliseconds() {
    $mt = explode(' ', microtime());
    return ((int)$mt[1]) * 1000 + ((int)round($mt[0] * 1000));
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

function savePCInfo($enduserid, $endownerid, $dtype, $ddata) {
	Global $conn;

    $ddata = urldecode($ddata);
	$ddata = sanitize_for_xml($ddata);
	$xml_string = '<?xml version="1.0" encoding="UTF-8"?>'.$ddata;

	$ttt = Date("Y-m-d H:i:s", time()) ." - ". $enduserid ." - ". $endownerid ." ". $xml_string;
	$conn->query("INSERT INTO notes SET note = 'Savenetdata ".$ttt."'");

    $xml = simplexml_load_string($xml_string);
	if(!$xml) {
	    echo 'Unable to load XML string';
	} else {
        //installed software
        $updatesql = $conn->query("UPDATE enduserinstalledsoftware SET installflag = 0 WHERE enduserid = $enduserid ");
        foreach($xml->osinfo->data as $user) { //run only once
            $OS = $user->OS;
	    	$OSarch = $user->OSarch;
	    	$CPU = $user->CPU;
	    	$RAM = $user->RAM;
	    	$Workgroup = $user->Workgroup;
	    	$CName = $user->CName;
	    	$UName = $user->UName;
	    	$UserDomainName = urlencode($user->UserDomainName);
	    	$OSVersion = urlencode($user->OSVersion);
	    	$HDD = urlencode($user->HDD);
	    	$Networks = urlencode($user->Networks);

            //OS INFO
	    	$checkquery = $conn->query("SELECT id FROM U_userpcinfo WHERE enduserid = $enduserid ");
			if($checkquery->num_rows != 0) {
				$conn->query("UPDATE U_userpcinfo SET
				os = '".$OS."',
				osarch = '".$OSarch."',
				cpu = '".$CPU."',
				ram = '".$RAM."',
				workgroup = '".$Workgroup."',
				cname = '".$CName."',
				uname = '".$UName."',
				userdomainname = '".$UserDomainName."',
				osversion = '".$OSVersion."',
				hdd = '".$HDD."',
				networks = '".$Networks."' WHERE enduserid = $enduserid ");
			} else {
				$conn->query("INSERT INTO U_userpcinfo SET
				enduserid = '".$enduserid."',
                os = '".$OS."',
				osarch = '".$OSarch."',
				cpu = '".$CPU."',
				ram = '".$RAM."',
				workgroup = '".$Workgroup."',
				cname = '".$CName."',
				uname = '".$UName."',
				userdomainname = '".$UserDomainName."',
				osversion = '".$OSVersion."',
				hdd = '".$HDD."',
				networks = '".$Networks."' ");
			}
        } //foreach closed

        //Installed Software
        $i = 0;
        foreach($xml->installedsoftware->data as $user) {
            $in_year = substr($user->installdate, 0, 4);
	    	$in_month = substr($user->installdate, 4, 2);
	    	$in_date = substr($user->installdate, 6, 2);
            $install_date = $in_year ."-". $in_month ."-". $in_date;
            $displayname = $user->displayname;
	    	$publisher = $user->publisher;
	    	$estimatedsize = $user->estimatedsize;

            $sno = milliseconds() + $i;
	    	$ipaddress = $_SERVER["REMOTE_ADDR"];
	    	$newsno = $sno;

            $checkquery = $conn->query("SELECT * FROM enduserinstalledsoftware WHERE displayname = '".$displayname."' AND enduserid = $enduserid ");
			if($checkquery->num_rows != 0) {
				$conn->query("UPDATE enduserinstalledsoftware SET installflag = 1 WHERE displayname = '".$displayname."' AND enduserid = $enduserid ");
			} else {
                $conn->query("INSERT INTO enduserinstalledsoftware SET
                    sno = '".$sno."',
    				newsno = '".$newsno."',
    				enduserid = '".$enduserid."',
    				endownerid = '".$endownerid."',
    				dtype = '".$dtype."',
    				ipaddress = '".$ipaddress."',
                    hostname = '',
                    ddate = '".$sno."',
    				displayname = '".$displayname."',
    				publisher = '".$publisher."',
    				estimatedsize = '".$estimatedsize."',
    				installdate = '".$install_date."',
                    comments = '',
    				installflag = 1");
            }

            $i++;
        } //foreach closed
    }

	header('Access-Control-Allow-Origin: *');
	header('Content-Type: text/plain');
	echo "100";
}

if(isset($_REQUEST['enduserid']) && isset($_REQUEST['endownerid'])) {
    $enduserid = $_REQUEST['enduserid'];
    $endownerid = $_REQUEST['endownerid'];
    $dtype = $_REQUEST['dtype'];
    $ddata = $_REQUEST['ddata'];
    if($dtype == "44") {
    	savePCInfo($enduserid, $endownerid, $dtype, $ddata);
    }
} else {
    echo "Hack Attempt";
}
?>
