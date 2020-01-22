<?php
require_once("./assets/vendor/autoload.php");
use Elasticsearch\ClientBuilder;
use Razorpay\Api\Api;
//$client = ClientBuilder::create()->build();

class Ed_model extends CI_Model {
		public $client;
    function __construct() {
        parent::__construct();
		$this->load->database();
		$this->client = ClientBuilder::create()->build();
    }
    function getPaymentHistory()
    {
    	/*$licenseQuery = $this->db->query("select * from U_license as ul where ul.owner_id='".$_SESSION["ownerid"]."'");
    	if($licenseQuery->num_rows()>0)
    	{
    		$currentday = time();
    		foreach($licenseQuery->result() as $licenseRow)
    		{
    			if(($currentday) > strtotime($licenseRow->licexp_date))
 				{
 					$packageRenew="1";
 				}
 				else
 				{
 					$packageRenew="0";
 				}
 				$getPackageData = $this->db->query("select * from package where id='".$licenseRow->packageid."' limit 1");
		    	if($getPackageData->num_rows() > 0) {
		    		$packageDet = $getPackageData->row();
		    	}
 				$getTransData = $this->db->query("select * from U_transaction where lickey='".$licenseRow->lickey."' limit 1");
		    	if($getTransData->num_rows() > 0) {
		    		$transDet = $getTransData->row();
		    	}
 				$user_data[]=array(
 					"package_type"=> $packageDet->packages,
 					"packageid"=> $licenseRow->packageid,
 					"lickey"=> $licenseRow->lickey,
 					"licexp_date"=> Date("d-M-Y",strtotime($licenseRow->licexp_date)),
 					"paymenttype"=> $transDet->paymenttype,
 					"order_status"=> $transDet->order_status,
 					"packageRenew"=> $packageRenew,
 					"total_lic"=> $licenseRow->total_lic,
 					"license_used"=> $licenseRow->license_used,
 					"lk"=> base64_encode($licenseRow->lickey),
 					"billing_zip"=> $transDet->billing_zip,
 					"billing_mobile"=> $transDet->billing_mobile
 				);
    		}
    		echo json_encode($user_data);
    	}
    	else
 		{
 			echo "0";
 		}*/
 		$transactionQuery = $this->db->query("select * from U_license as ul INNER JOIN package as p ON ul.packageid=p.id where owner_id='".$_SESSION["ownerid"]."' order by licsno desc");
    	//$transactionQuery = $this->db->query("select * from U_transaction as ut INNER JOIN U_license as ul ON ut.lickey=ul.lickey where ut.owner_id='".$_SESSION["ownerid"]."' AND ut.order_status='1' AND ut.transaction_id=ul.transaction_id order by licsno desc");
    	/*$transactionQuery = $this->db->query("select ut.package_type as package_type, ut.package_id as package_id, ul.lickey as lickey, ul.licexp_date as licexp_date, ut.paymenttype as paymenttype,ut.order_status as order_status,ul.total_lic as total_lic,ul.license_used as license_used, ut.billing_zip as billing_zip, ut.billing_mobile as billing_mobile, ut.billing_email as billing_email, ul.suspendedlic as suspendedlic from U_license as ul LEFT JOIN U_transaction as ut ON ul.lickey=ut.lickey LEFT JOIN U_subscription as us ON ul.lickey=us.lickey where ul.owner_id='".$_SESSION["ownerid"]."' group by ul.lickey order by ul.licsno desc");*/
    	if($transactionQuery->num_rows()>0)
 		{
 			$currentday = time();
 			foreach($transactionQuery->result() as $transRow)
 			{
 				//print_r($transRow);
 				if(($transRow->licexp_date)=="0000-00-00 00:00:00")
				{
					$packageRenew="0";
				}
				else if($transRow->suspendedlic=="1")
				{
					$packageRenew="2";
				}
 				else if(($currentday) > strtotime($transRow->licexp_date))
 				{
 					$packageRenew="1";
 				}
 				else
 				{
 					$packageRenew="0";
 				}
 				if(($transRow->licexp_date)=="0000-00-00 00:00:00")
				{
						$licexpdate= "No User Activated";
				}
				else
				{
					$licexpdate = Date("d-M-Y",strtotime($transRow->licexp_date));
				}

				/*$user_data[]=array(
 					"package_type"=> $transRow->packages,
 					"packageid"=> $transRow->packageid,
 					"lickey"=> $transRow->lickey,
 					"licexp_date"=> $licexpdate,
 					"paymenttype"=> $transRow->paymenttype,
 					"order_status"=> $transRow->order_status,
 					"packageRenew"=> $packageRenew,
 					"total_lic"=> $transRow->total_lic,
 					"license_used"=> $transRow->license_used,
 					"lk"=> base64_encode($transRow->lickey),
 					"billing_zip"=> $transRow->billing_zip,
 					"billing_mobile"=> $transRow->billing_mobile,
 					"billing_email"=>$transRow->billing_email
 				);*/
 				$user_data[]=array(
 					"package_type"=> $transRow->packages,
 					"packageid"=> $transRow->packageid,
 					"lickey"=> $transRow->lickey,
 					"licexp_date"=> $licexpdate,
 					"total_lic" => $transRow->total_lic,
 					"license_used"=>$transRow->license_used,
 					"paymenttype"=>"Online",
 					"order_status"=>"1",
 					"packageRenew"=>"0"
 				);
 			}
 			echo json_encode($user_data);
 		}
 		else
 		{
 			echo "0";
 		}

    }
    function getOwnerUsedLicense()
    {
    	$installEndy = $this->db->query("select * from U_license where owner_id='".$_SESSION["ownerid"]."' AND license_used>0");
    	if($installEndy->num_rows()>0)
    	{
    		echo "1";
    	}
    	else{
    		echo "0";
    	}
    }
    function updateSettings()
    {
    	/*
    	 val: val,
            enduserid: enduserid,
            type: type
        */
        $type = $_POST["type"];
        $val = $_POST["val"];
        if($val=="true") {
        	$val=1;
        } else if($val=="false") {
        	$val=0;
        } else {
        	$val=$val;
        }

        if($_POST["enduserid"] != "0")
        {
			$eid = base64_decode($_POST["enduserid"]);
        	$databasetable = "U_endusers";
	        if($type=="1")
	        {
	        	$this->db->query("update ".$databasetable." set screenshot='".$val."' where sno='".$eid."' AND ownerid='".$_SESSION["ownerid"]."'");
	        }
	        else if($type=="2")
	        {
	        	$this->db->query("update ".$databasetable." set screenshot_interval='".$val."' where sno='".$eid."' AND ownerid='".$_SESSION["ownerid"]."'");
	        }
	        else if($type=="3")
	        {
	        	$this->db->query("update ".$databasetable." set trayicon='".$val."' where sno='".$eid."' AND ownerid='".$_SESSION["ownerid"]."'");
	        }
	        else if($type=="4")
	        {
	        	$this->db->query("update ".$databasetable." set keylog='".$val."' where sno='".$eid."' AND ownerid='".$_SESSION["ownerid"]."'");
	        }
	        else if($type=="5")
	        {
	        	$this->db->query("update ".$databasetable." set webhistory='".$val."' where sno='".$eid."' AND ownerid='".$_SESSION["ownerid"]."'");
	        }
	        else if($type=="6")
	        {
	        	$this->db->query("update ".$databasetable." set stealthinstall='".$val."' where sno='".$eid."' AND ownerid='".$_SESSION["ownerid"]."'");
	        }
	        else if($type=="7")
	        {
	        	$this->db->query("update ".$databasetable." set lazyminutes='".$val."' where sno='".$eid."' AND ownerid='".$_SESSION["ownerid"]."'");
	        }
	        else if($type=="8")
	        {
	        	$this->db->query("update ".$databasetable." set pause='".$val."' where sno='".$eid."' AND ownerid='".$_SESSION["ownerid"]."'");
	        }
	        else if($type=="9")
	        {
	        	$this->db->query("update ".$databasetable." set usbblock='".$val."' where sno='".$eid."' AND ownerid='".$_SESSION["ownerid"]."'");
	        }
	        else if($type=="10")
	        {
	        	$this->db->query("update ".$databasetable." set taskmanagerblock='".$val."' where sno='".$eid."' AND ownerid='".$_SESSION["ownerid"]."'");
	        }

	    }
	    else
	    {
	    	$databasetable = "U_endowners";
	    	$databasetable1 = "U_endusers";

	    	if($type=="1")
	        {
	        	$this->db->query("update ".$databasetable." set screenshot='".$val."' where sno='".$_SESSION["ownerid"]."'");
	        }
	        else if($type=="2")
	        {
	        	$this->db->query("update ".$databasetable." set screenshotinterval='".$val."' where sno='".$_SESSION["ownerid"]."'");
	        }
	        else if($type=="3")
	        {
	        	$this->db->query("update ".$databasetable." set trayicon='".$val."' where sno='".$_SESSION["ownerid"]."'");
	        }
	        else if($type=="4")
	        {
	        	$this->db->query("update ".$databasetable." set keylog='".$val."' where sno='".$_SESSION["ownerid"]."'");
	        }
	        else if($type=="5")
	        {
	        	$this->db->query("update ".$databasetable." set webhistory='".$val."' where sno='".$_SESSION["ownerid"]."'");
	        }
	        else if($type=="6")
	        {
	        	$this->db->query("update ".$databasetable." set stealthinstall='".$val."' where sno='".$_SESSION["ownerid"]."'");
	        }
	        else if($type=="7")
	        {
	        	$this->db->query("update ".$databasetable." set lazyminutes='".$val."' where sno='".$_SESSION["ownerid"]."'");
	        }
	        else if($type=="8")
	        {
	        	$this->db->query("update ".$databasetable." set pause='".$val."' where sno='".$_SESSION["ownerid"]."'");
	        }
	        else if($type=="9")
	        {
	        	$this->db->query("update ".$databasetable." set usbblock='".$val."' where sno='".$_SESSION["ownerid"]."'");
	        }
	        else if($type=="10")
	        {
	        	$this->db->query("update ".$databasetable." set taskmanagerblock='".$val."' where sno='".$_SESSION["ownerid"]."' ");
	        }

	        if($type=="1")
	        {
	        	$this->db->query("update ".$databasetable1." set screenshot='".$val."' where ownerid='".$_SESSION["ownerid"]."'");
	        }
	        else if($type=="2")
	        {
	        	$this->db->query("update ".$databasetable1." set screenshot_interval='".$val."' where ownerid='".$_SESSION["ownerid"]."'");
	        }
	        else if($type=="3")
	        {
	        	$this->db->query("update ".$databasetable1." set trayicon='".$val."' where ownerid='".$_SESSION["ownerid"]."'");
	        }
	        else if($type=="4")
	        {
	        	$this->db->query("update ".$databasetable1." set keylog='".$val."' where ownerid='".$_SESSION["ownerid"]."'");
	        }
	        else if($type=="5")
	        {
	        	$this->db->query("update ".$databasetable1." set webhistory='".$val."' where ownerid='".$_SESSION["ownerid"]."'");
	        }
	        else if($type=="6")
	        {
	        	$this->db->query("update ".$databasetable1." set stealthinstall='".$val."' where ownerid='".$_SESSION["ownerid"]."'");
	        }
	        else if($type=="7")
	        {
	        	$this->db->query("update ".$databasetable1." set lazyminutes='".$val."' where ownerid='".$_SESSION["ownerid"]."'");
	        }
	        else if($type=="8")
	        {
	        	$this->db->query("update ".$databasetable1." set pause='".$val."' where ownerid='".$_SESSION["ownerid"]."'");
	        }
	        else if($type=="9")
	        {
	        	$this->db->query("update ".$databasetable1." set usbblock='".$val."' where ownerid='".$_SESSION["ownerid"]."'");
	        }
	        else if($type=="10")
	        {
	        	$this->db->query("update ".$databasetable1." set taskmanagerblock='".$val."' where ownerid='".$_SESSION["ownerid"]."'");
	        }

	    }
    }
    function savePin()
    {
    	$pin = $_POST["pin"];
    	$this->db->query("update U_endowners set pin='".$pin."' where sno='".$_SESSION["ownerid"]."'");
    }
    function getSettings()
    {
    	$data = array();
    	if($_POST["enduserid"] != "")
    	{
			$eid = base64_decode($_POST["enduserid"]);
    		// find settings based on employee id
    		$concatstr = " AND sno='".$eid."'";
    		$qTFOS = $this->db->query("select * from U_endusers where ownerid='".$_SESSION["ownerid"]."'".$concatstr);
     		if($qTFOS->num_rows()>0)
     		{
     			foreach($qTFOS->result() as $row)
     			{
     				$data["errormsg"] = "success";
     				$data["screenshot"] = $row->screenshot;
     				$data["screenshotinterval"] = $row->screenshot_interval;
     				$data["trayicon"] = $row->trayicon;
     				$data["keylog"] = $row->keylog;
     				$data["webhistory"] = $row->webhistory;
     				$data["stealthinstall"] = $row->stealthinstall;
     				$data["lazyminutes"] = $row->lazyminutes;
     				$data["pause"] = $row->pause;
     				$data["usbblock"] = $row->usbblock;
     				$data["taskmanagerblock"] = $row->taskmanagerblock;

     			}
     			echo json_encode($data);
     		}
     		else
     		{
     			$data["errormsg"] = "error";
     			echo json_encode($data);
     		}
    	}
    	else
    	{
    		// fetch global settings on owner basis
    		// qTFOS  query TO Fetch Owner Settings
     		$qTFOS = $this->db->query("select * from U_endowners where sno='".$_SESSION["ownerid"]."'");
     		if($qTFOS->num_rows()>0)
     		{
     			foreach($qTFOS->result() as $row)
     			{
     				$data["errormsg"] = "success";
     				$data["screenshot"] = $row->screenshot;
     				$data["screenshotinterval"] = $row->screenshotinterval;
     				$data["trayicon"] = $row->trayicon;
     				$data["keylog"] = $row->keylog;
     				$data["webhistory"] = $row->webhistory;
     				$data["stealthinstall"] = $row->stealthinstall;
     				$data["lazyminutes"] = $row->lazyminutes;
     				$data["pause"] = $row->pause;
					$data["usbblock"] = $row->usbblock;
					$data["taskmanagerblock"] = $row->taskmanagerblock;
					$data["pin"] = $row->pin;
     			}
     			echo json_encode($data);
     		}
     		else
     		{
     			$data["errormsg"] = "error";
     			echo json_encode($data);
     		}
    	}
    }
    function enOwnerDeleteContent()
    {
    	// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
		//
    	$pin = $_POST["pin"];
    	$eid = base64_decode(@$_POST["eid"]);
    	$ownerid = $_SESSION["ownerid"];
    	// check user pin is correct
    	$pin_query = $this->db->query("select * from U_endowners where pin='".$pin."' AND sno='".$_SESSION["ownerid"]."'");
    	if($pin_query->num_rows()>0)
    	{
    		if($eid=="")
    		{
	    		$ch = curl_init();

				curl_setopt($ch, CURLOPT_URL, '95.216.3.158:9200/mongoindex1/_delete_by_query');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, "\n{\n  \"query\": { \n    \"match\": {\n      \"endownerid\": \"$ownerid\"\n    }\n  }\n}\n");
				curl_setopt($ch, CURLOPT_POST, 1);

				$headers = array();
				$headers[] = 'Content-Type: application/json';
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

				$result = curl_exec($ch);
				if (curl_errno($ch)) {
				    echo 'Error:' . curl_error($ch);
				}
				curl_close ($ch);
				//$this->db->query("delete from U_endusers where ownerid='".$ownerid."'");
				$this->db->query("delete from U_internetusage where endownerid='".$ownerid."'");
				$this->db->query("delete from U_runningapps where endownerid='".$ownerid."'");
				$this->db->query("delete from U_lazyminutes where endownerid='".$ownerid."'");
				$r = array("status"=>"success");
			}
			else
			{
				$ch = curl_init();

				curl_setopt($ch, CURLOPT_URL, '95.216.3.158:9200/mongoindex1/_delete_by_query');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, "\n{\n    \"query\": {\n        \n                 \"bool\" : {\n                    \"must\" : [\n                        { \"term\" : { \"endownerid\" : \"$ownerid\" } }, \n                        { \"term\" : { \"enduserid\" : $eid } } \n                    ]\n                }\n            }\n        \n}\n");
				curl_setopt($ch, CURLOPT_POST, 1);

				$headers = array();
				$headers[] = 'Content-Type: application/json';
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

				$result = curl_exec($ch);
				if (curl_errno($ch)) {
				    echo 'Error:' . curl_error($ch);
				}
				curl_close ($ch);
				$enduserDel = $this->db->query("select * from U_endusers where sno='".$eid."' AND ownerid='".$ownerid."'");

				$this->db->query("delete from U_endusers where sno='".$eid."' AND ownerid='".$ownerid."'");
				$this->db->query("delete from U_internetusage where enduserid='".$eid."' AND  endownerid='".$ownerid."'");
				$this->db->query("delete from U_runningapps where enduserid='".$eid."' AND  endownerid='".$ownerid."'");
				$this->db->query("delete from U_lazyminutes where  enduserid='".$eid."' AND  endownerid='".$ownerid."'");
				$r = array("status"=>"success");

			}
    	}
    	else
    	{
    		$r = array("status"=>"Pin Incorrect");
    	}
    	echo json_encode($r);
    }
    function create_endata()
    {
    	$data = json_decode(file_get_contents('php://input'), true);
    	foreach ($data as $key => $value) {
		   $stdArray[] = (array) $value;
		}
		$sno = urldecode($stdArray['0']['0']);
		$newsno = urldecode($stdArray['1']['0']);
		$enduserid = urldecode($stdArray['2']['0']);
		$endownerid = urldecode($stdArray['3']['0']);
		$dtype= urldecode($stdArray['4']['0']);
		$charcount=  urldecode($stdArray['5']['0']);

		$app_title = urldecode($stdArray['6']['0']);
		$other = urldecode($stdArray['7']['0']);
		$mouse_clicks = urldecode($stdArray['8']['0']);
		$wordcount = urldecode($stdArray['9']['0']);
		@$linescount= urldecode($stdArray['10']['0']);
		$ttype=  urldecode($stdArray['11']['0']);

		@$ipaddress = urldecode($stdArray['12']['0']);
		$hostname = urldecode($stdArray['13']['0']);
		$delete = urldecode($stdArray['14']['0']);
		$endversion = urldecode($stdArray['15']['0']);
		$ddate= urldecode($stdArray['16']['0']);
		$decdata= urldecode($stdArray['17']['0']);
		$ddata= urldecode($stdArray['18']['0']);

		/*Create array of data to store*/
		$user_data=array(
		'sno'=>$sno,
		'newsno'=>$sno,
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
		'decdata'=>$decdata,
		'ddata'=>$ddata,
		'ddate'=>time()
		);
		$params['body']  = $user_data;
		$params['index'] = 'mongoindex1';
		$params['type']  = 'u_endata';
		$params['id']    = $sno;
		$retDoc2 = $this->client->index($params);

    }
    function login()
    {
    	$deviceid = $_REQUEST["deviceid"];
    	$username = $_REQUEST["username"];
    	$password = $_REQUEST["password"];
    	$checkdbquery = $this->db->query("select * from U_endowners where email='".$username."' AND password='".md5($password)."'");
    	$ownernum = $checkdbquery->num_rows();
		if($ownernum>0)
		{
			$fetcharraydb = $checkdbquery->row();
    		$arr[] =array(
    				"ownerid"	=>$fetcharraydb->sno,
    				"name"		=>$fetcharraydb->name,
    				"emailid"	=>$fetcharraydb->email,
    				"mobile" 	=>$fetcharraydb->phone,
					"status"	=>"1"
				);
    		$this->db->query("update U_endowners set deviceid='".$deviceid."' where sno='".$fetcharraydb->sno."'");
		}
		else
		{
			$arr[] =array(
    				"status"	=>"error"
				);
		}

    	echo json_encode($arr);
    }
    function countGallery()
    {
    	$enduserid=$_REQUEST["enduserid"];
    	if(isset($enduserid))
		{
			if($enduserid>'0')
			{
				$concatstr .=',{ "term" : { "enduserid" : "'.$enduserid.'" } }';
			}
		}
		$searchParams2['body']= '{
		"filter" : {
	            "range" : {
	                "dtype" : {
	                    "gte": 20,
	                    "lte": 21
	                }
	            }
	        },
			"query": { "bool" :  { "must" : [ '.$concatstr.'] } } }
		}';

		$retDoc2 = $client->search($searchParams2);


		echo $mincount2 = $retDoc2['hits']['total'];
    }
    function filterfeeds()
    {
    	$concatstr="";
    	$decdatastr="";
    	$pavolder="";
    	$rangeconcatstr="";
    	$mustnothavestr="";
    	$highlight="";
    	$results="";
    	$results1 = "";
    	if((isset($_REQUEST["datewithin"])) && @$_REQUEST["datewithin"]!=0)
    	{
	    	$datewithin = @$_REQUEST["datewithin"];

			switch ($datewithin)
			{
				case "1" :
				$filterdate="Filter by today";
				$fromdate = Date("d-M-Y",time());
				$fromdate = strtotime($fromdate);
				@$_REQUEST['from'] = Date("d-M-Y",strtotime("-1 day", $fromdate));
				@$_REQUEST['to']=Date("d-M-Y",time());
				break;
				case "2" :
				$filterdate="Filter by this week";
				$fromdate = Date("d-M-Y",time());
				$fromdate = strtotime($fromdate);
				@$_REQUEST['from'] = Date("d-M-Y",strtotime("-7 day", $fromdate));
				@$_REQUEST['to']=Date("d-M-Y",time());
				break;
				case "3" :
				$filterdate="Filter by this month";
				$fromdate = Date("d-M-Y",time());
				$fromdate = strtotime($fromdate);
				@$_REQUEST['from'] = Date("d-M-Y",strtotime("-1 month", $fromdate));
				@$_REQUEST['to']=Date("d-M-Y",time());
				break;
				case "4" :
				$filterdate="Filter by this year";
				$fromdate = Date("d-M-Y",time());
				$fromdate = strtotime($fromdate);
				@$_REQUEST['from'] = Date("d-M-Y",strtotime("-1 year", $fromdate));
				@$_REQUEST['to']=Date("d-M-Y",time());
				break;
				case "5" :
				$filterdate="";
				$fromquery = $this->db->query("select jdt from U_endowners where sno='".@$_REQUEST['ownerid']."'");
    			$fromnum = $fromquery->num_rows();
    			if($fromnum>0)
    			{
    				$fromqueryrow = $fromquery->row();
    				$fromda = strtotime($fromqueryrow->jdt);
    				@$_REQUEST['from'] =Date("d-M-Y",$fromda);
					@$_REQUEST['to'] = Date("d-M-Y",time());
    			}
    			break;
				default :
				$filterdate="";
				$fromquery = $this->db->query("select jdt from U_endowners where sno='".@$_REQUEST['ownerid']."'");
    			$fromnum = $fromquery->num_rows();
    			if($fromnum>0)
    			{
    				$fromquery = $query->row();
    				$fromda = strtotime($fromquery->jdt);
    				@$_REQUEST['from'] =Date("d-M-Y",$fromda);
					@$_REQUEST['to'] = Date("d-M-Y",time());
    			}
				break;
			}
		}
    	if(isset($_REQUEST['streamolder']))
		{
			$pavolder = @$_REQUEST['lastrecordid']*40;
		}
		else
		{
			$pavolder="0";
		}
		if(isset($_REQUEST['ownerid']))
		{
			$ownerg = @$_REQUEST['ownerid'];
			$concatstr .='{ "term" : { "endownerid" : "'.$ownerg.'" } }';
		}
		if(isset($_REQUEST['enduserid']))
		{
			if(@$_REQUEST['enduserid']>'0')
			{
				$enduserid = @$_REQUEST['enduserid'];
				$concatstr .=',{ "term" : { "enduserid" : "'.$enduserid.'" } }';
			}
		}
		if(isset($_REQUEST['dtype']))
		{
			if((@$_REQUEST['dtype']!='') || (strlen(@$_REQUEST['dtype'])>0))
			{

				if((@$_REQUEST['dtype']!='11') && (@$_REQUEST['dtype']!='12') && (@$_REQUEST['dtype']!='13'))
				{
					$dtype = @$_REQUEST['dtype'];
					$concatstr .=',{ "term" : { "dtype" : "'.$dtype.'" } }';
				}

				if(@$_REQUEST['dtype']=='13')
				{
					$dtype = @$_REQUEST['dtype'];
					$concatstr .=',{ "term" : { "watchlist" : "1" } }';
				}
			}
		}

		if(@$_REQUEST['musthave']!='')
		{
			$concatstr .=', {  "term" : { "decdata" : "'.@$_REQUEST['musthave'].'" } }';
		}

		if(@$_REQUEST["ntinclude"]=='1')
		{
			$mustnothavestr .=', "must_not" : {  "term" : { "dtype" : "20" } }';
			$mustnothavestr .=', "must_not" : {  "term" : { "dtype" : "21" } }';
		}

		if(@$_REQUEST['mustnothave']!='')
		{
			$mustnothavestr .=', "must_not" : {  "term" : { "decdata" : "'.@$_REQUEST['mustnothave'].'" } }';
		}

		if((@$_REQUEST['fromda']!='') && (@$_REQUEST['toda']!=''))
		{
			if(@$_REQUEST['fromda']=='5')
			{
				$query = $this->db->query("select jdt from U_endowners where sno='".@$_REQUEST['ownerid']."'");
    			$ownernum = $query->num_rows();
    			if($ownernum>0)
    			{
    				$fromquery = $query->row();
    				$fromda = strtotime($fromquery->jdt);
    			}
			}
			else
			{
				$fromda = strtotime(@$_REQUEST['fromda']);
			}
			$toda = strtotime(@$_REQUEST['toda'])+(23*3600);
			$rangeconcatstr .=',{ "range" : { "ddate" : { "gte" : '.$fromda.',  "lte" : '.$toda.' } } }';
		}

		if(isset($_REQUEST['ddatasearch']))
		{
			if((@$_REQUEST['dtype']!='') || (strlen(@$_REQUEST['dtype'])>0))
			{
				if(@$_REQUEST['dtype']!='11')
				{
					$checkdbquery = $this->db->query("select * from searchin where id='".$_REQUEST['dtype']."'");
	    			$checkdbnum = $checkdbquery->num_rows();
	    			if($checkdbnum>0)
	    			{
	    				$fetcharraydb = $checkdbquery->row();
	    				$ddataarray = explode($fetcharraydb->searchtxt,$_REQUEST['ddatasearch']);
						$ddatasearch = trim($ddataarray[0]);
	    			}
				}
				else {
					$ddatasearch = $_REQUEST['ddatasearch'];
				}
			}
			else {
				$ddatasearch = $_REQUEST['ddatasearch'];
			}

			if($_REQUEST['dtype']=='12')
			{
				if($ddatasearch!='')
				{
				$decdatastr ='"query" :{ "match_all" : {} }, "query" : { "match_phrase" : { "app_title" : "*'.$ddatasearch.'*" } },';
				}
				if($_REQUEST['ddatasearch']!='')
				{
					$highlight = ', "highlight": { 	"fields" : { "app_title" : {} } } ';
				}

			}
			else
			{
				if(@$ddatasearch!='')
				{
					$decdatastr ='"query" :{ "match_all" : {} }, "query" : { "match_phrase" : { "decdata" : "*'.$ddatasearch.'*" } },';
				}
				if($_REQUEST['ddatasearch']!='')
				{
					$highlight = ', "highlight": { 	"fields" : { "decdata" : {} } } ';
				}
			}


		}

		 /* $searchParams['body']= '{
				"from" : '.$pavolder.',
				"size" : 40,
				 "sort" : [
		        { "ddate" : {"order" : "desc"}},
		        "_score"
		    ],
				"query" : {
					"filtered" : { '.$decdatastr.'				"filter" : {
							"bool" : {
								"must" : [ '.$concatstr.' '.$rangeconcatstr.']'.$mustnothavestr.'
							}
						}
					}
				} '.$highlight.'

		}';
		*/
		$searchParams['body']= '{
				"from" : '.$pavolder.',
				"size" : 40,
				 "sort" : [
		        { "ddate" : {"order" : "desc"}},
		        "_score"
		    ],
				"query" : {
					"bool" : { '.$decdatastr.'				"filter" : {
							"bool" : {
								"must" : [ '.$concatstr.' '.$rangeconcatstr.']'.$mustnothavestr.'
							}
						}
					}
				} '.$highlight.'

		}';

		$searchParams1['body']= '{
				"from" : '.$pavolder.',
				"size" : 40,
				 "sort" : [
					{ "ddate" : {"order" : "desc"}},
					"_score"
				],
				"query" : {
					"bool" : {

								'.$decdatastr.'

						"filter" : {
							"bool" : {
								"must" : [ '.$concatstr.' '.$rangeconcatstr.']'.$mustnothavestr.'
							}
						}
					}
				} '.$highlight.'
		}';

		$retDoc = $this->client->search($searchParams);

		$retDoc1 = $this->client->search($searchParams1);

		if($retDoc['hits']['total']>=1)
		{
			$results=$retDoc['hits']['hits'];
		}
		if($retDoc1['hits']['total']>=1)
		{
			$results1=$retDoc1['hits']['hits'];
		}

		if($retDoc['hits']['total']>=1)
		{
			$mincount = count($results);
			$totalcount = count($results1);
			if(isset($_REQUEST['ownerid']) || (strlen($_REQUEST['ownerid'])>0))
			{
				if(isset($results))
				{
					foreach($results as $r)
					{
						if($r['_source']['dtype']=="21" || $r['_source']['dtype']=="20")
						{
							$r['_source']['decdata']="http://l192.168.1.4/".$r['_source']['decdata'];
						}
						// find username
						$finduserquery = $this->db->query("select name from U_endusers where sno='".$r['_source']['enduserid']."'");
		    			$findusernum = $finduserquery->num_rows();
		    			if($findusernum>0)
		    			{
		    				$findusernamerow = $finduserquery->row();
		    				@$endusername = $findusernamerow->name;
		    			}
		    			else
		    			{
		    				@$endusername="";
		    			}
		    				$feedsno = (string)$r['_source']['ddate'];
						$arr[] =array(
							"feedsno"		=> $feedsno,
		    				"enduserid"		=> $r['_source']['enduserid'],
		    				"endownerid"	=> $r['_source']['endownerid'],
		    				"dtype"			=> $r['_source']['dtype'],
		    				"app_title"		=> $r['_source']['app_title'],
							"other"			=> $r['_source']['other'],
							"mouse_clicks"	=> $r['_source']['mouse_clicks'],
							"wordcount"		=> $r['_source']['wordcount'],
							"linecount"		=> $r['_source']['linecount'],
							"decdata"		=> $r['_source']['decdata'],
							"delete"		=> $r['_source']['delete'],
							"ttype" 		=> $r['_source']['ttype'],
							"watchlist" 	=> @$r['_source']['watchlist'],
							"noteflag"		=> @$r['_source']['noteflag'],
							"note"			=> @$r['_source']['note'],
							"status"		=> "1",
							"endusername"		=> @$endusername
						);
					}
					echo json_encode($arr);
				}
				else
				{
					echo json_encode(array('status' => 'error'));
				}
			}
		}
		else
		{
			echo json_encode(array('status' => 'error','msg'=>'No records found'));
		}
    }
    function nexttworec()
    {
    	$concatstr="";
    	$urlsno="";
    	$urlsno = $_REQUEST['sno'];
		if($_REQUEST['ownerid']!='')
		{
			$ownerg = $_REQUEST['ownerid'];
			$concatstr .='{ "term" : { "endownerid" : "'.$ownerg.'" } }';
		}
		if(isset($_REQUEST['enduserid']))
		{
			if($_REQUEST['enduserid']>'0')
			{
				$enduserid = $_REQUEST['enduserid'];
				$concatstr .=',{ "term" : { "enduserid" : "'.$enduserid.'" } }';
			}
		}

		$concatstr .=',{ "term" : { "delete" : "1" } }';

		/*$searchParams['body']= '{
			"size" : 2,
			"query": {
				"filtered" : {
					"filter" : {
						"bool" : {
							"must" : [
								'.$concatstr.' , { "range" : { "newsno" : { "lt" : "'.$urlsno.'" } } }
							]
						}
					}
				}
			}
		}';*/
		$searchParams['body']= '{
			"size" : 2,
			"query": {
						"bool" : {
							"must" : [
								'.$concatstr.' , { "range" : { "newsno" : { "lt" : "'.$urlsno.'" } } }
							]
						}

			}
		}';

		$retDoc = $this->client->search($searchParams);



		if($retDoc['hits']['total']>=1)
		{

			$results = "";
			$results=$retDoc['hits']['hits'];
			$results = array_reverse($results);
			foreach(array_reverse($results) as $r)
			{
				if($r['_source']['dtype']=="21" || $r['_source']['dtype']=="20")
				{
					$r['_source']['decdata']="http://192.168.1.4/".$r['_source']['decdata'];
				}
				// find username
				$finduserquery = $this->db->query("select name from U_endusers where sno='".$r['_source']['enduserid']."'");
    			$findusernum = $finduserquery->num_rows();
    			if($findusernum>0)
    			{
    				$findusernamerow = $finduserquery->row();
    				@$endusername = $findusernamerow->name;
    			}
    			else
    			{
    				@$endusername="";
    			}
    			$feedsno = (string)$r['_source']['ddate'];
					$arr[] =array(
						"feedsno"		=> $feedsno,
    				"enduserid"		=> $r['_source']['enduserid'],
    				"endownerid"	=> $r['_source']['endownerid'],
    				"dtype"			=> $r['_source']['dtype'],
    				"app_title"		=> $r['_source']['app_title'],
					"other"			=> $r['_source']['other'],
					"mouse_clicks"	=> $r['_source']['mouse_clicks'],
					"wordcount"		=> $r['_source']['wordcount'],
					"linecount"		=> $r['_source']['linecount'],
					"decdata"		=> $r['_source']['decdata'],
					"delete"		=> $r['_source']['delete'],
					"ttype" 		=> $r['_source']['ttype'],
					"watchlist" 	=> @$r['_source']['watchlist'],
					"noteflag"		=> @$r['_source']['noteflag'],
					"note"			=> @$r['_source']['note'],
					"status"		=> "1",
					"endusername"		=> $endusername
				);
			}
			echo json_encode($arr);
		}

    }
    function prevtworec()
    {
    	$urlsno = $_REQUEST['sno'];
    	$concatstr="";
		if($_REQUEST['ownerid']!='')
		{
			$ownerg = $_REQUEST['ownerid'];
			$concatstr .='{ "term" : { "endownerid" : "'.$ownerg.'" } }';
		}
		if(isset($_REQUEST['enduserid']))
		{
			if($_REQUEST['enduserid']>'0')
			{
				$enduserid = $_REQUEST['enduserid'];
				$concatstr .=',{ "term" : { "enduserid" : "'.$enduserid.'" } }';
			}
		}

		$concatstr .=',{ "term" : { "delete" : "1" } }';

		/*$searchParams['body']= '{
			"size" : 2,
			"query": {
				"filtered" : {
					"filter" : {
						"bool" : {
							"must" : [
								'.$concatstr.' , { "range" : { "newsno" : { "gt" : "'.$urlsno.'" } } }
							]
						}
					}
				}
			}
		}';*/
		$searchParams['body']= '{
			"size" : 2,
			"query": {
						"bool" : {
							"must" : [
								'.$concatstr.' , { "range" : { "newsno" : { "gt" : "'.$urlsno.'" } } }
							]
				}
			}
		}';

		$retDoc = $this->client->search($searchParams);



		if($retDoc['hits']['total']>=1)
		{

			$results = "";
			$results=$retDoc['hits']['hits'];
			$results = array_reverse($results);
			foreach(array_reverse($results) as $r)
			{
				if($r['_source']['dtype']=="21" || $r['_source']['dtype']=="20")
				{
					$r['_source']['decdata']="http://192.168.1.4/".$r['_source']['decdata'];
				}
				// find username
				$finduserquery = $this->db->query("select name from U_endusers where sno='".$r['_source']['enduserid']."'");
    			$findusernum = $finduserquery->num_rows();
    			if($findusernum>0)
    			{
    				$findusernamerow = $finduserquery->row();
    				@$endusername = $findusernamerow->name;
    			}
    			else
    			{
    				@$endusername="";
    			}
    			$feedsno = (string)$r['_source']['ddate'];
					$arr[] =array(
					"feedsno"		=> $feedsno,
    				"enduserid"		=> $r['_source']['enduserid'],
    				"endownerid"	=> $r['_source']['endownerid'],
    				"dtype"			=> $r['_source']['dtype'],
    				"app_title"		=> $r['_source']['app_title'],
					"other"			=> $r['_source']['other'],
					"mouse_clicks"	=> $r['_source']['mouse_clicks'],
					"wordcount"		=> $r['_source']['wordcount'],
					"linecount"		=> $r['_source']['linecount'],
					"decdata"		=> $r['_source']['decdata'],
					"delete"		=> $r['_source']['delete'],
					"ttype" 		=> $r['_source']['ttype'],
					"watchlist" 	=> $r['_source']['watchlist'],
					"noteflag"		=> $r['_source']['noteflag'],
					"note"			=> $r['_source']['note'],
					"status"		=> "1",
					"endusername"		=> $endusername
				);
			}
			echo json_encode($arr);
		}
    }
    function NextPrevImage()
    {
    	$concatstr="";

		if(isset($_REQUEST['nextrec']))
		{
			$pavolder = $_REQUEST['nextrec'];
		}
		if(trim($_REQUEST['enduserid']) !='')
		{
			$enduserid = $_REQUEST['enduserid'];
			$concatstr.= ' , { "term" : { "enduserid" : "'.$enduserid.'" } } ';
		}
		if(isset($_REQUEST['ownerid']))
		{
			$ownerg = $_REQUEST['ownerid'];

		}

		if(isset($_REQUEST['sno']))
		{
			if($_REQUEST['sno']>'0')
			{
				$sno = $_REQUEST['sno'];

			}
		}
		if($_REQUEST['prevnext']=='0')
		{
			/*$searchParams['body'] = ' {
				"from" : 0,
				"size" : 1,
				"sort" :[
							{ "sno" : {"order" : "desc"}}
						],
				"query": {
					"filtered" : {
						"filter" : {
							"bool" : {
								"must" : [
									{ "term": { "endownerid" : "'.$ownerg.'" } },{ "term": { "dtype" : "20" } } '.$concatstr.' , { "range" : { "sno" : { "lt" : "'.$sno.'" } } }
								]
							}
						}
					}
				}
			} ' ;*/
			$searchParams['body'] = ' {
				"from" : 0,
				"size" : 1,
				"sort" :[
							{ "sno" : {"order" : "desc"}}
						],
				"query": {
							"bool" : {
								"must" : [
									{ "term": { "endownerid" : "'.$ownerg.'" } },{ "term": { "dtype" : "20" } } '.$concatstr.' , { "range" : { "sno" : { "lt" : "'.$sno.'" } } }
								]
					}
				}
			} ' ;
		}
		/* for finding the next image ie one greater record  */
		if($_REQUEST['prevnext']=='1')
		{
			/*$searchParams['body'] = ' {
				"from" : 0,
				"size" : 1,
				"sort" :[
				{ "sno" : {"order" : "asc"}}	],
				"query": {
					"filtered" : {
					"filter" : {
						"bool" : {
						"must" : [
						{ "term": { "endownerid" : "'.$ownerg.'" } },{ "term": { "dtype" : "20" } } '.$concatstr.' , { "range" : { "sno" : { "gt" : "'.$sno.'" } } }
				]
				}
				}
				}
				}
			} ';*/
			$searchParams['body'] = ' {
				"from" : 0,
				"size" : 1,
				"sort" :[
				{ "sno" : {"order" : "asc"}}	],
					"query": {
								"bool" : {
									"must" : [
										{ "term": { "endownerid" : "'.$ownerg.'" } },{ "term": { "dtype" : "20" } } '.$concatstr.' , { "range" : { "sno" : { "gt" : "'.$sno.'" } } }
									]
								}
					}
				} ';
		}

		$retDoc = $this->client->search($searchParams);

		if($retDoc['hits']['total']>=1)
		{
			$results=$retDoc['hits']['hits'];
		}
		$mincount = count($results);
		if(isset($_REQUEST['ownerid']) || (strlen($_REQUEST['ownerid'])>0))
		{
			if(isset($results))
			{
				foreach($results as $r)
				{
					$imageDataEncoded = "http://192.168.1.4/".$r['_source']['decdata'];
					$dtype = $r['_source']['dtype'];
					$nsno=$r['_source']['sno'];
					$enduserid = $r['_source']['enduserid'];
					$app_title=$r['_source']['app_title'];
					$string = html_entity_decode($app_title);
					$lastaccess = $r['_source']['ddate'];
					$arr[] =array(
						'status' => 'success',
	    				"imageDataEncoded" => $imageDataEncoded,
	    				"dtype" => $dtype,
	    				"sno" => $nsno,
	    				"enduserid" => $enduserid,
						"app_title"	=> $app_title,
						"string" => $string,
						"lastaccess" => $lastaccess
					);

				}
			}
			echo json_encode($arr);
		}
		else
		{
			echo json_encode(array('status' => 'error'));
		}
    }
    function changeOwnerPassword()
    {
    	$ownerid = $_REQUEST["ownerid"];
    	$currentpassword = $_REQUEST["currentpassword"];
    	$password = $_REQUEST["password"];
    	$confirmpassword = $_REQUEST["cpassword"];
    	$query = $this->db->query("select * from U_endowners where sno='".$ownerid."'  AND password='".md5($currentpassword)."'");
    	$ownernum = $query->num_rows();

    	if($ownernum>0)
    	{
    		if(strcmp($password, $confirmpassword)=='0')
			{
				$updatepassword = $this->db->query("update U_endowners set password='".md5($currentpassword)."'  where sno='".$ownerid."'");
				if(isset($updatepassword))
				{
					$arr[] =array(
					"status" => "success",
					"errorcode" => "1"
					);
				}
				else
				{
					$arr[] =array(
					"status" => "Network Error",
					"errorcode" => "0"
					);
				}
			}
			else
			{
				$arr[] =array(
				"status" => "No user exist",
				"errorcode" => "0"
				);
			}
    	}
    	else
    	{
    		$arr[] =array(
				"status" => "No user exist",
				"errorcode" => "0"
				);
    	}
    	echo json_encode($arr);

    }
    function getEndUsers()
    {
    	$query = $this->db->query("select * from U_endusers where ownerid='".$_REQUEST["ownerid"]."' AND active='1'");
		if($query->num_rows() > 0) {
    		foreach($query->result() as $enduser) {
    			if(($enduser->lastaccesstime)=="0000-00-00 00:00:00")
				{
					$lastaccess="";
				}
				else
				{
					$lastaccess 	= $this->ago(strtotime($enduser->lastaccesstime));
				}
				if($lastaccess=='ideal')
				{
					$lastaccess = $this->ago1(strtotime($enduser->lastaccesstime));
					$personalimg = "http://192.168.1.4/images/ideal.png";
					$lastflag = "orange";
				}
				else if($lastaccess=='offline')
				{
					$lastaccess = "offline";
					$personalimg = "http://192.168.1.4/images/offline.png";
					$lastflag = "red";
				}
				else
				{
					$lastaccess = $this->ago1(strtotime($enduser->lastaccesstime));
					$personalimg = "http://192.168.1.4/images/online.png";
					$lastflag = "green";
				}
    			$tr[] = array(
					"sno"=> $enduser->sno,
					"name"=> $enduser->name,
					"dept"=> $enduser->dept,
					"groupid" => $enduser->groupid,
					"version" => $enduser->version,
					"active" => $enduser->active,
					"pcname" => $enduser->pcname,
					"designation" => $enduser->designation,
					"startuptime"=> $enduser->startuptime,
					"shutdowntime"=> $enduser->shutdowntime,
					"lastaccesstime" => $enduser->lastaccesstime,
					"profilepic" => "http://192.168.1.4/".$enduser->profilepic,
					"ipaddress" => $enduser->ipaddress,
					"licenseflag" => $enduser->licenseflag,
					"type_words" => $enduser->type_words,
					"copied_words" => $enduser->copied_words,
					"files_copies" => $enduser->files_copies,
					"pendriveinsert"=> $enduser->pendriveinsert,
					"mobileinsert"=> $enduser->mobileinsert,
					"otherusb"=> $enduser->otherusb,
					"lastaccess"=>$lastaccess,
					"personalimg"=> $personalimg,
					"lastflag"=> $lastflag
				);
    		}
    	}
    	echo json_encode($tr);
    }
    function getFeeds()
    {
    	$pavolder=0;
    	if($_REQUEST["dtype"]!="21")
    	{
	    	$concatstr="";
	    	$pavolder="";
	    	$decdatastr="";
	    	if(isset($_REQUEST['streamolder']))
			{
				@$pavolder = $_REQUEST['page']*20;
			}
			else
			{
				@$pavolder="0";
			}
	    	if(isset($_REQUEST['ownerid']))
			{
					$ownerg = $_REQUEST['ownerid'];
					$concatstr .='{ "term" : { "endownerid" : "'.$ownerg.'" } }';
			}
			if(isset($_REQUEST['enduserid']))
			{
				if($_REQUEST['enduserid']>'0')
				{
					$enduserid = $_REQUEST['enduserid'];
					$concatstr .=',{ "term" : { "enduserid" : "'.$enduserid.'" } }';
				}
			}
			/*if(isset($_REQUEST['dtype']))
			{
				if(($_REQUEST['dtype']!='') || (strlen($_REQUEST['dtype'])>0))
				{

					if($_REQUEST['dtype']!='11')
					{
						$dtype = $_REQUEST['dtype'];
						$concatstr .=',{ "term" : { "dtype" : "'.$dtype.'" } }';
					}
				}

			}*/
			if(isset($_REQUEST['dtype']))
				{
					if((@$_REQUEST['dtype']!='') || (strlen(@$_REQUEST['dtype'])>0))
					{

						if((@$_REQUEST['dtype']!='11') && (@$_REQUEST['dtype']!='12') && (@$_REQUEST['dtype']!='13'))
						{
							$dtype = @$_REQUEST['dtype'];
							$concatstr .=',{ "term" : { "dtype" : "'.$dtype.'" } }';
						}

						if(@$_REQUEST['dtype']=='13')
						{
							$dtype = @$_REQUEST['dtype'];
							$concatstr .=',{ "term" : { "watchlist" : "1" } }';
						}
					}
				}

			$concatstr .=',{ "term" : { "delete" : "1" } }';

			if(isset($_REQUEST['ddatasearch']) && $_REQUEST['ddatasearch']!='')
			{
				if($_REQUEST['dtype']!='')
				{
					$checkdbquery = $this->db->query("select * from searchin where id='".$_REQUEST['dtype']."'");
					$fetcharraydb = $checkdbquery->row();

					$ddataarray = explode($fetcharraydb->searchtxt,$_REQUEST['ddatasearch']);
					$ddatasearch = trim($ddataarray[0]);
				}
				else
				{
					$ddatasearch = $_REQUEST["ddatasearch"];
				}
				$rgconcat="";
		   	    $rg = (explode(" ",$ddatasearch));
		   	    $rgcount = count($rg);
		   	    if($rgcount>1)
		   	    {
		   	    	$rgconcat = '{
	   								"prefix" :
	   								{
	   									"decdata" : "'.$ddatasearch.'"
	   								}
  								},
  								{
	   								"prefix" :
	   								{
	   									"app_title" : "'.$ddatasearch.'"
	   								}
  								}';
			   	    for($i=0;$i<$rgcount;$i++)
			   	    {
			   	    	if($i==0)
			   	    	{
			   	    		$rgconcat.= ',{
			   								"prefix" :
			   								{
			   									"decdata" : "'.$rg[$i].'"
			   								}
		  								},
		  								{
			   								"prefix" :
			   								{
			   									"app_title" : "'.$rg[$i].'"
			   								}
		  								},';
			   	    	}
			   	    	else if($i==($rgcount-1))
			   	    	{
			   	    		$rgconcat.= '{
			   								"prefix" :
			   								{
			   									"decdata" : "'.$rg[$i].'"
			   								}
		  								},
		  								{
			   								"prefix" :
			   								{
			   									"app_title" : "'.$rg[$i].'"
			   								}
		  								}';
			   	    	}
			   	    	else
			   	    	{
			   	    		$rgconcat.= '{
			   								"prefix" :
			   								{
			   									"decdata" : "'.$rg[$i].'"
			   								}
		  								},
		  								{
			   								"prefix" :
			   								{
			   									"app_title" : "'.$rg[$i].'"
			   								}
		  								},';
			   	    	}
			   	    }
		   		}
		   		else
		   		{
		   			$rgconcat = '{
	   								"prefix" :
	   								{
	   									"decdata" : "'.$ddatasearch.'"
	   								}
  								},
  								{
	   								"prefix" :
	   								{
	   									"app_title" : "'.$ddatasearch.'"
	   								}
  								}';
		   		}
			   	//echo $rgconcat;
			   	$decdatastr = ' "must": [ { "bool": { "should" : [ '.$rgconcat.' ]
			   			}
			   		}
			   	],';
			}
			if($_REQUEST["firstrecordid"]!='' || $_REQUEST["firstrecordid"]!='0' || $_REQUEST["firstrecordid"]!=0)
			{
				$queryadd = ', "query": { "range": { "sno": { "lte" :'.$_REQUEST["firstrecordid"].' } } }';
			}
			$searchParams['body']= '{
			"from" : '.$pavolder.',
			"size" : 20,
			"sort" :[{ "ddate" : {"order" : "desc"}}],
			"query": {
				"bool" : {
		              		'.$decdatastr.'
		            		"filter" : {
						 			"bool" : {
		                   	 			"must" : [
		                    					'.$concatstr.'
		                    				]
		                			}
		            		}
						}
					}

			}';

			$searchParams1['body']= '{
				 "from" : '.$pavolder.',
				 "size" : 21,
				 "sort" :[
			        { "ddate" : {"order" : "desc"}}	],
				"query": {
					"bool" : {

			              				'.$decdatastr.'
			            		"filter" : {
							 	"bool" : {
			                   	 			"must" : [
			                    					'.$concatstr.'
			                    				]
			                			}
			            		}
					}
				}
			}';
			$retDoc = $this->client->search($searchParams);

			$retDoc1 = $this->client->search($searchParams1);
			$results=0;
			$results1=0;

			if($retDoc['hits']['total']>=1)
			{
				$results=$retDoc['hits']['hits'];
			}
			if($retDoc1['hits']['total']>=1)
			{
				$results1=$retDoc1['hits']['hits'];
			}


			$mincount = count($results);
			$totalcount = count($results1);
			if(isset($results) && $results>0)
			{
				foreach($results as $r)
				{
					/*
						"_id" : ObjectId("5b5846fed76b62433382486d"),
					    "sno" : NumberLong(1532511998),
					    "newsno" : NumberLong(1532511998),
					    "enduserid" : "622",
					    "endownerid" : "1002",
					    "dtype" : "20",
					    "charcount" : "0",
					    "ddata" : "snapshot/2018/07/25/1002/622/622-1532511998.jpg",
					    "app_title" : "[explorer]",
					    "other" : "ALAM-PC [Admin]",
					    "mouse_clicks" : "0",
					    "wordcount" : "0",
					    "linecount" : "0",
					    "decdata" : "snapshot/2018/07/25/1002/622/622-1532511998.jpg",
					    "ttype" : "1",
					    "delete" : "1",
					    "ipaddress" : "61.12.91.172",
					    "hostname" : null,
					    "endversion" : "700",
					    "ddate" : NumberLong(1532511998)
					*/
					if($r['_source']['dtype']=="21" || $r['_source']['dtype']=="20")
					{
						$r['_source']['decdata']="http://192.168.1.4/".$r['_source']['decdata'];
					}
					// find username
					$finduserquery = $this->db->query("select name from U_endusers where sno='".$r['_source']['enduserid']."'");
	    			$findusernum = $finduserquery->num_rows();
	    			if($findusernum>0)
	    			{
	    				$findusernamerow = $finduserquery->row();
	    				@$endusername = $findusernamerow->name;
	    			}
	    			else
	    			{
	    				@$endusername="";
	    			}
	    			$feedsno = (string)$r['_source']['sno'];
						$arr[] =array(
						"feedsno"		=> $feedsno,
						"ddate"			=> $r['_source']['ddate'],
	    				"enduserid"		=> $r['_source']['enduserid'],
	    				"endownerid"	=> $r['_source']['endownerid'],
	    				"dtype"			=> $r['_source']['dtype'],
	    				"app_title"		=> $r['_source']['app_title'],
						"other"			=> $r['_source']['other'],
						"mouse_clicks"	=> $r['_source']['mouse_clicks'],
						"wordcount"		=> $r['_source']['wordcount'],
						"linecount"		=> $r['_source']['linecount'],
						"decdata"		=> $r['_source']['decdata'],
						"delete"		=> $r['_source']['delete'],
						"ttype" 		=> $r['_source']['ttype'],
						"watchlist" 	=> @$r['_source']['watchlist'],
						"noteflag"		=> @$r['_source']['noteflag'],
						"note"			=> @$r['_source']['note'],
						"status"		=> "1",
						"endusername"		=> $endusername
					);
				}
				echo json_encode($arr);
			}
			else
			{
				$arr[] = array("status"=>"error", "msg"=>"No records found");
				echo json_encode($arr);
			}
		}
		else
		{
			$checkdbquery = $this->db->query("select * from U_getaction where ownerid='".$_REQUEST['ownerid']."' AND status='1' AND notify='1' order by sno desc");
			if($checkdbquery->num_rows() > 0) {
				foreach($checkdbquery->result() as $fetcharraydb) {

					// find endusername
					$finduserquery = $this->db->query("select name from U_endusers where sno='".$fetcharraydb->enduserid."'");
	    			$findusernum = $finduserquery->num_rows();
	    			if($findusernum>0)
	    			{
	    				$findusernamerow = $finduserquery->row();
	    				@$endusername = $findusernamerow->name;
	    			}
	    			else
	    			{
	    				@$endusername="";
	    			}

					$arr[] =array(
						"feedsno"		=> $fetcharraydb->sno,
	    				"enduserid"		=> $fetcharraydb->enduserid,
	    				"endownerid"	=> $fetcharraydb->ownerid,
	    				"dtype"			=> $fetcharraydb->action,
	    				"app_title"		=> $fetcharraydb->apptitle,
						"other"			=> '',
						"mouse_clicks"	=> '',
						"wordcount"		=> '',
						"linecount"		=> '',
						"decdata"		=> "http://192.168.1.4/".$fetcharraydb->actionpath,
						"delete"		=> '',
						"ttype" 		=> '',
						"status"		=> "1",
						"endusername"		=> $endusername
					);
				}
				echo json_encode($arr);
			}

		}
    }
    function getSnapshotImage()
    {	if((isset($_REQUEST['loadimage'])) && ($_REQUEST['loadimage']=='1'))
		{
	    	if(strlen($_REQUEST['loadimage'])>0)
			{
				$userdet = $this->db->query("select * from U_getaction where sno='".$_REQUEST['actionid']."'");
				if($userdet->num_rows() > 0) {
					$fetcharraydb = $userdet->row();
					if($fetcharraydb->actionpath!="")
					{
						$queryimage = $this->db->query("select profilepic,name,designation,dept,groupid from U_endusers where sno='".$fetcharraydb->enduserid."'");
						$queryrow = $queryimage->row();
						echo json_encode(array('status' => 'success','actionpath'=>"http://192.168.1.4/".$fetcharraydb->actionpath,'apptitle'=>$fetcharraydb->apptitle,'name'=>$queryrow->name,'designation'=>$queryrow->designation));
					}
					else
					{
						echo json_encode(array('status' => 'error'));
					}
				}
				else
				{
					echo json_encode(array('status' => 'error'));
				}
			}
			else
			{
				echo json_encode(array('status' => 'error'));
			}
		}
		else
		{
			echo json_encode(array('status' => 'error'));
		}
    }
    function getOnDemandSnapshot()
    {
    	$query = $this->db->query("insert into U_getaction set ownerid='".$_REQUEST['ownerid']."', enduserid='".$_REQUEST['enduserid']."', action='".$_REQUEST['action']."',actiondate='".date("Y-m-d H:i:s",time())."',ipaddress='".$_SERVER['REMOTE_ADDR']."',hostname='".gethostbyaddr($_SERVER['REMOTE_ADDR'])."'");
    	if($query)
    	{
	    	$lastid = $this->db->insert_id();
	    	echo json_encode(array('status' => 'success','lastid'=>$lastid));
    	}
    	else
    	{
    		echo json_encode(array('status' => 'error'));
    	}
    }
    function getWatchlist()
    {
    	$recordid 	= $_REQUEST["recordid"];
    	$action 	= $_REQUEST["action"];
    	$params = [
		    'index' => 'mongoindex1',
		    'type' => 'u_endata',
		    'id' => $recordid,
		    'body' => [
		        'doc' => [
		            'watchlist' => $action
		        ]
		    ]
		];
		$response = $this->client->update($params);
		if($response)
		{
			echo json_encode(array('status' => 'success'));
		}
    }
	function addNote()
	{

		$noteflag = $_REQUEST['noteflag'];
		$recordid = $_REQUEST['recordid'];
		$action = (string)$_REQUEST['action'];
		$sno = intval($recordid);



		$params = [
		    'index' => 'mongoindex1',
		    'type' => 'u_endata',
		    'id' => $sno,
		    'body' => [
		        'doc' => [
		            'note' => $action,'noteflag' => $noteflag
		        ]
		    ]
		];

		// Update doc at /my_index/my_type/my_id
		$response = $this->client->update($params);
		if($response)
		{
			echo json_encode(array('status' => 'success'));
		}
	}
	function ago($time)
	{
		$periods = array("s", "m", "h", "d", "w", "", "", "");
		$lengths = array("60","60","24","7","4.35","12","10");

		$now = time();

		$difference     = $now - $time;
		$tense         = "ago";

		for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
			$difference /= $lengths[$j];
		}

		$difference = round($difference);

		if($difference != 1) {
			$periods[$j].= "";
		}

		if(($periods[$j]=='h') && (($difference>=1) && ($difference<8)))
		{
			return "ideal";
		}
		else if(($periods[$j]=='s') || ($periods[$j]=='m'))
		{
			return "$difference $periods[$j]";
		}
		else
		{
			return "offline";
		}
	}

	function ago1($time)
	{
		$periods = array("s", "m", "h", "d", "w", "", "", "");
		$lengths = array("60","60","24","7","4.35","12","10");

		$now = time();

		$difference     = $now - $time;
		$tense         = "ago";

		for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
			$difference /= $lengths[$j];
		}

		$difference = round($difference);

		if($difference != 1) {
			$periods[$j].= "";
		}


		return "$difference $periods[$j]";

	}
	function EditUserDet()
	{
		$query = $this->db->query("update U_endusers set name='".$_REQUEST['name']."',groupid='".$_REQUEST['group']."',dept='".$_REQUEST['dept']."',designation='".$_REQUEST['designation']."' where sno='".$_REQUEST['enduserid']."'");
		if($query)
		{
			echo json_encode(array('status' => 'success'));
		}
	}
	function deleteFeed()
	{
		$recordid = $_REQUEST["recordid"];
		$action = $_REQUEST["action"];
		$params = [
		    'index' => 'mongoindex1',
		    'type' => 'u_endata',
		    'id' => $recordid,
		    'body' => [
		        'doc' => [
		            'delete' => 0
		        ]
		    ]
		];
		$response = $this->client->update($params);
		if($response)
		{
			echo json_encode(array('status' => 'success'));
		}
	}
	function encryptRJ256($string_to_encrypt)
	{
		$string_to_encrypt= $_REQUEST["string_to_encrypt"];
		$key = "9aedffb2ac55b473b4d1c6d5f2dd4142b1340668"; //INSERT THE KEY GENERATED BY THE C# CLASS HERE
		$iv = "bf4451b4c1639f4f7701e901f7fa5e72ac74f35a"; //INSERT THE IV GENERATED BY THE C# CLASS HERE
		$rtn = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $string_to_encrypt, MCRYPT_MODE_CBC, $iv);
		$rtn = base64_encode($rtn);
		return($rtn);
	}

	function decryptRJ256($encrypted)
	{
		$key = "9aedffb2ac55b473b4d1c6d5f2dd4142b1340668"; //INSERT THE KEY GENERATED BY THE C# CLASS HERE
		$iv = "bf4451b4c1639f4f7701e901f7fa5e72ac74f35a"; //INSERT THE IV GENERATED BY THE C# CLASS HERE
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
	function deleteUser()
	{
		if($_REQUEST["enduserid"]!="")
		{
			$query = $this->db->query("update U_endusers set active=0 where sno='".$_REQUEST["enduserid"]."'");
			echo json_encode(array('status' => 'success'));
    	}
		else
    	{
    		echo json_encode(array('status' => 'error'));
    	}
	}
	function generateRandomstring()
	{
		$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
		// Output: 54esmdr0qf
		$string = substr(str_shuffle($permitted_chars), 0, 10);
		// check already license alotted or not
		$checkquery = $this->db->query("select * from U_endusers where endlickey='".$string."'");
		if($checkquery->num_rows()>0)
		{
			generateRandomstring();
		}
		else
		{
			return $string;
		}
	}
	function enUserDetails()
	{
		$enduserid = $_POST["enduserid"];
		$ownerid = $_POST["ownerid"];
		$lickey = $_POST["lickey"];
		$checkownerlicense = "select * from U_endusers where sno='".$enduserid."' AND ownerid='".$ownerid."' AND lickey='".$_POST["lickey"]."' limit 1";
		$col_query = $this->db->query($checkownerlicense);
		if($col_query->num_rows()>0)
		{
			$fetarrayrow = $col_query->row();
			$activationdate = $fetarrayrow->act_date;
			$expirationdate = $fetarrayrow->expiry_date;
			$keystore = $fetarrayrow->keystore;
			$trayicon = $fetarrayrow->trayicon;
			$screenshot = $fetarrayrow->screenshot;
			$screenshotinterval = $fetarrayrow->screenshot_interval;
			$suspendedlic = $fetarrayrow->suspendeduser;

			if(time() > strtotime($expirationdate))
			{
				$arr = "status=600";
				echo $arr;
			}
			else if($suspendedlic=="1")
			{
				$arr = "status=700";
				echo $arr;
			}
			else
			{
				$arr = "status=500&enduserid=".$enduserid."&ownerid=".$fetarrayrow->ownerid."&startdate=".Date("d/m/Y H:i:s",strtotime($activationdate))."&enddate=".Date("d/m/Y H:i:s",strtotime($expirationdate))."&trayicon=".$fetarrayrow->trayicon."&keystore=".$fetarrayrow->keystore."&screenshot=".$fetarrayrow->screenshot."&screenshotinterval=".$fetarrayrow->screenshot_interval;
				echo $arr;
			}
		}
		else
		{
			$arr = "status=900";
				echo $arr;
		}
	}
	function enActivateLicense()
	{
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
			$col_query = $this->db->query($checkownerlicense);
			if($col_query->num_rows()>0)
			{
				$fetarrayrow = $col_query->row();
				$currentdate = Date("Y-m-d H:i:s",time());
				$license_expire_date = strtotime($fetarrayrow->licexp_date);
				$endlickey = $this->generateRandomstring();
				$expirydate = date('Y-m-d H:i:s', strtotime($currentdate. ' + 1 year'));
				if($fetarrayrow->license_used<$fetarrayrow->total_lic)
				{
					if((strtotime($currentdate)>$license_expire_date) && ($license_expire_date!="0000-00-00 00:00:00"))
					{
						print("status=600");
					}
					else
					{
						if($fetarrayrow->suspendedlic!="0")
						{
							print("status=700");
						}
						else
						{
							$enduserquery = $this->db->query("insert into U_endusers set
								ownerid='".$fetarrayrow->owner_id."',
								endlickey='".$endlickey."',
								act_date='".$currentdate."',
								expiry_date='".$expirydate."',
								macaddress='".$mac."',
								name='".$fullname."',
								pcname='".$pcname."',
								trackkey='".$tracks."',
								lickey='".$lickey."',
								hint='".$notes."',
								version='".$version."',
								keystore='".$fetarrayrow->keystore."',
								trayicon='".$fetarrayrow->trayicon."',
								screenshot='".$fetarrayrow->screenshot."',
								screenshot_interval='".$fetarrayrow->screenshotinterval."'");
							$enduserid = $this->db->insert_id();
							//oli stands for owner license increment used license value
							$oli = $this->db->query("update U_license set license_used=license_used+1 where owner_id='".$fetarrayrow->owner_id."' AND  lickey ='".$lickey."'");
							print("status=500&enduserid=".$enduserid."&ownerid=".$fetarrayrow->owner_id."&startdate=".Date('d/m/Y H:i:s',strtotime($currentdate))."&enddate=".Date('d/m/Y H:i:s',strtotime($expirydate))."&trayicon=".$fetarrayrow->trayicon."&keystore=".$fetarrayrow->keystore."&screenshot=".$fetarrayrow->screenshot."&screenshotinterval=".$fetarrayrow->screenshotinterval);
						}
					}
				}
				else
				{
					print("status=800");
				}
			}
			else
			{
				print("status=900");
			}
		}
		else
		{
			print("status=1000");
		}

	}
	function getUserStatus()
	{
		$ownerid = $_REQUEST["ownerid"];
		$fg= $_REQUEST["flag"];
		$currentdate=date('Y-m-d',time());
		if($_REQUEST["flag"]=="1")
		{
			$lastaccesstime =  date('Y-m-d H:i:s', strtotime('-1 hours', time()));
			$query = "select * from U_endusers where 1 AND active=1 AND licenseflag=1 AND deleteuser=0 AND ownerid=".$ownerid." AND lastaccesstime>'".$lastaccesstime."' order by lastaccesstime desc";
		}
		else if($_REQUEST["flag"]=="2")
		{
			$lastaccesstime =  date('Y-m-d H:i:s', strtotime('-1 hours', time()));
			$lastaccesstime1 =  date('Y-m-d H:i:s', strtotime('-8 hours', time()));
			$query = "select * from U_endusers where 1 AND active=1 AND licenseflag=1 AND deleteuser=0 AND ownerid=".$ownerid." AND (lastaccesstime between '".$lastaccesstime1."' AND '".$lastaccesstime."') order by lastaccesstime desc";
		}
		else if($_REQUEST["flag"]=="3")
		{
			$lastaccesstime =  date('Y-m-d H:i:s', strtotime('-8 hours', time()));
			$query = "select * from U_endusers where 1 AND active=1 AND licenseflag=1 AND deleteuser=0 AND ownerid=".$ownerid." AND lastaccesstime<'".$lastaccesstime."' order by lastaccesstime desc";
		}
		$checkquery = $this->db->query($query);
		$num = $checkquery->num_rows();
		if($num>0)
		{
			foreach($checkquery->result() as $fetcharraydb)
			{

				if($fetcharraydb->lastaccesstime=="0000-00-00 00:00:00")
				{
					$lastaccess="";
				}
				else
				{
					$lastaccess 	= $this->ago(strtotime($fetcharraydb->lastaccesstime));
				}

				if($lastaccess=='ideal')
				{
					$lastaccess = $this->ago1(strtotime($fetcharraydb->lastaccesstime));
					$personalimg = "images/ideal.png";
				}
				else if($lastaccess=='offline')
				{
					$lastaccess = "";
					$personalimg = "images/offline.png";
				}
				else
				{
					$personalimg = "images/online.png";
				}

				$startuptime 	= date('Y-m-d',strtotime($fetcharraydb->startuptime));

				$checklastaccess	= date('Y-m-d',strtotime($fetcharraydb->lastaccesstime));

				if(($startuptime!=$currentdate) || ($checklastaccess!=$currentdate))
				{
					$startup='Offline';
					$personalimg = "images/offline.png";
				}
				else
				{
					$startup="Start up time : ".date('H:i A',strtotime($fetcharraydb->startuptime));
				}
				$arr[] = array(
					"id"=>$fetcharraydb->sno,"version"=>$fetcharraydb->version, "profilepic"=>"http://192.168.1.4/uploads/default.jpg","username"=>$fetcharraydb->name,"lastaccess"=>$lastaccess,"startup"=>$startup,"userloggedtime"=>$fetcharraydb->lastaccesstime

				);
			}
			echo json_encode($arr);
		}
		else
		{
			$arr = array("status"=>"error", "msg"=> "No records found");
			echo json_encode($arr);
		}
	}
	function getLicense()
	{
		$packageid = $_POST["packageid"];
		$query = "select * from package where id='".$packageid."'";
		$checkquery = $this->db->query($query);
		$num = $checkquery->num_rows();
		if($num>0)
		{
			$checkRow = $checkquery->row();
			echo json_encode($checkRow);
		}
	}
	function promocode()
    {
    	$licenseno = $_POST["licenseno"];
		$mobileno = $_POST["mobileno"];
		$packageid = $_POST["packageid"];
		$pincode = $_POST["pincode"];
		$promocode = $_POST["promocode"];

		$promocodeDet = $this->calculatePackageAmount($packageid, $licenseno, $promocode,"",$pincode);
		echo $promocodeDet;
    }
    function calculatePackageAmount($packageid, $totallicense, $promocode, $paymenttype, $pincode, $mobile)
    {
        $cgstpercentage="";
    	$sgstpercentage="";
    	$igstpercentage="";
    	$totalcgstcharge="";
    	$totalsgstcharge="";
    	$totaligstcharge="";
        $concatstr="";
    	$currentdatetime = Date("Y-m-d H:i:s", time());
        if($mobile!="")
        {
            $concatstr=" AND mobile_no='".$mobile."'";
        }
        else
        {
            $concatstr="";
        }

		$promoquery = $this->db->query("select * from U_discount where coupon_code='".$promocode."' AND status='1' AND ('$currentdatetime' between startdate AND enddate)");
		$promoquerynum = $promoquery->num_rows();
		$promoflag=0;
        if(($promoquerynum)>0)
		{
			$promoDet1 = $promoquery->row(); //$row
            if($promoDet1->mobile_no==$mobile)
            {
                $promoDet = $promoquery->row(); //$row
                $promoflag=1;
            }
            else if($promoDet1->mobile_no=="0" || $promoDet1->mobile_no==0)
            {
                $promoDet = $promoquery->row(); //$row
                $promoflag=1;
            }
		}

		$packagequery = $this->db->query("select * from package where id='".$packageid."'");

        if(($packagequery->num_rows())>0)
		{
			$packageDet = $packagequery->row(); //packagerow
		}

		$packageamount = $packageDet->amount;

        if($packageDet->packagedurationtype=="year")
        {
            $packagemonth = (12 * ($packageDet->packageduration));
        }
        else
        {
            $packagemonth = $packageDet->packageduration;
		}
        // number of licenses
		$totalamount = $totallicense * $packageamount * ($packagemonth);
		if(($promoquerynum>0) && ($promoflag==1))
		{
			if($promoDet->type=="1")
			{
				// percentage
				$percval = ($totalamount * ($promoDet->typeval)) / 100;
				$totalafterpromocode = $totalamount-$percval;
			}
			else if($promoDet->type=="2")
			{
				// rupees
				$percval = $promoDet->typeval;
				$totalafterpromocode = $totalamount-$percval;

			}
            // calculate gst
			if($pincode>110001 && $pincode<110097)
			{
                $cgstpercentage = $packageDet->cgst;
				$sgstpercentage = $packageDet->sgst;
				$totalcgstcharge = ($totalafterpromocode * $cgstpercentage)/100;
				$totalsgstcharge = ($totalafterpromocode * $sgstpercentage)/100;
				$totalaftergst = $totalcgstcharge + $totalsgstcharge + $totalafterpromocode;
				$calgst = $totalcgstcharge + $totalsgstcharge;
			}
			else
			{
                $igstpercentage = $packageDet->igst;
				$totaligstcharge = ($totalafterpromocode * $igstpercentage)/100;
				$totalaftergst = $totaligstcharge + $totalafterpromocode;
				$calgst = $totaligstcharge;
			}

			$arr = array(
            "promocode"=> $promocode,
			"totalaftergst"=> $totalaftergst,
			"totalamount"=> $totalafterpromocode,
			"promostatus"=> "1",
			"gstamount"=> $calgst,
			"promodiscount"=> $percval,
            "packagebaseprice"=>$packageDet->amount,
			"baseprice"=> $totalamount,
			"packagename"=> $packageDet->packages,
			"cgstpercentage" =>$cgstpercentage,
			"sgstpercentage" =>$sgstpercentage,
			"igstpercentage" =>$igstpercentage,
			"totalcgstcharge" =>$totalcgstcharge,
			"totalsgstcharge" =>$totalsgstcharge,
			"totaligstcharge" => $totaligstcharge);
		}
		else
		{
			$totalamount = $totallicense * $packageamount * ($packagemonth);
			$totalafterpromocode = $totalamount;
			// calculate gst
            if($pincode>110001 && $pincode<110097)
			{
                $cgstpercentage = $packageDet->cgst;
				$sgstpercentage = $packageDet->sgst;
				$totalcgstcharge = ($totalafterpromocode * $cgstpercentage)/100;
				$totalsgstcharge = ($totalafterpromocode * $sgstpercentage)/100;
				$totalaftergst = $totalcgstcharge + $totalsgstcharge + $totalafterpromocode;
				$calgst = $totalcgstcharge + $totalsgstcharge;
			}
			else
			{
                $igstpercentage = $packageDet->igst;
				$totaligstcharge = ($totalafterpromocode * $igstpercentage)/100;
				$totalaftergst = $totaligstcharge + $totalafterpromocode;
				$calgst = $totaligstcharge;
			}
			$arr = array("promocode"=> "",
				"totalaftergst"=> $totalaftergst,
				"totalamount"=> $totalafterpromocode,
				"promostatus"=> "0",
				"gstamount"=> $calgst,
				"promodiscount"=> "",
				"baseprice"=> $totalamount,
                "packagebaseprice"=>$packageDet->amount,
				"packagename"=> $packageDet->packages,
				"cgstpercentage" =>$cgstpercentage,
				"sgstpercentage" =>$sgstpercentage,
				"igstpercentage" =>$igstpercentage,
				"totalcgstcharge" =>$totalcgstcharge,
				"totalsgstcharge" =>$totalsgstcharge,
				"totaligstcharge" => $totaligstcharge
			);
		}
		return json_encode($arr);
    }
    function globalsettings()
    {
    	$getGlobalSettings = $this->db->query("select * from global_settings where id=1");
    	if($getGlobalSettings->num_rows())
    	{
    		$arr = $getGlobalSettings->row();
    	}
    	return json_encode($arr);
    }
    function getOwnerTrasactionDet()
    {
    	$totallicense = $_POST["totallicenses"];
    	$packageid = $_POST["packageid"];
    	$promocode = base64_decode($_POST["promocode"]);
    	$lickey = base64_decode($_POST["lickey"]);

    	$globalSettings = json_decode($this->globalsettings());

    	//get subscription id from transaction table from lickey
    	$transOwner = $this->db->query("select * from U_transaction where lickey='".$lickey."' order by transaction_id desc limit 1");
		$transOwnerNum = $transOwner->num_rows();
		if(($transOwnerNum)>0)
		{
			$transOwnerDet = $transOwner->row(); //$row
		}
		$endOwnerQ = $this->db->query("select * from U_endowners where sno='".$_SESSION["ownerid"]."'");
		$endOwnerNum = $endOwnerQ->num_rows();
		if(($endOwnerNum)>0)
		{
			$endOwnerDet = $endOwnerQ->row(); //$row
			$pincode = $endOwnerDet->zipcode;
			$mobile = $endOwnerDet->phone;
		}
		if($transOwnerDet->subscriberid!="")
		{
				$endOwnerQ = $this->db->query("select * from U_endowners where sno='".$_SESSION["ownerid"]."'");
				$endOwnerNum = $endOwnerQ->num_rows();
				if(($endOwnerNum)>0)
				{
					$endOwnerDet = $endOwnerQ->row(); //$row
					$pincode = $endOwnerDet->zipcode;
					$mobile = $endOwnerDet->phone;
				}


				$getPincodeData = $this->db->query("select * from pincode where pin='".$pincode."' limit 1");
		    	if($getPincodeData->num_rows() > 0) {
		    		$pinDet = $getPincodeData->row();
		    	}

				$packDet = $this->calculatePackageAmount($packageid, $totallicense, $promocode, "", $pincode, $mobile);
				$packDet = json_decode($packDet);
				/*
					$arr = array(
		            "promocode"=> $promocode,
					"totalaftergst"=> $totalaftergst,
					"totalamount"=> $totalafterpromocode,
					"promostatus"=> "1",
					"gstamount"=> $calgst,
					"promodiscount"=> $percval,
		            "packagebaseprice"=>$packageDet->amount,
					"baseprice"=> $totalamount,
					"packagename"=> $packageDet->packages,
					"cgstpercentage" =>$cgstpercentage,
					"sgstpercentage" =>$sgstpercentage,
					"igstpercentage" =>$igstpercentage,
					"totalcgstcharge" =>$totalcgstcharge,
					"totalsgstcharge" =>$totalsgstcharge,
					"totaligstcharge" => $totaligstcharge);
				*/
				$cnttranquery = $this->db->query("select * from U_transaction");
		    	$trancnt = $cnttranquery->num_rows();
		    	$endyid = 10000+($trancnt+1);
		    	// insert U_transaction with the orderid
				$transaction_query = $this->db->query("insert into U_transaction set owner_id='".$_SESSION["ownerid"]."',
		    	package_id='".$packageid."',
		    	total_licenses='".$totallicense."',
		    	package_type='".$packDet->packagename."',
		    	total_base_price='".$packDet->baseprice."',
		    	total_amount='".$packDet->totalamount."',
		    	promo_code='".$promocode."',
		        package_base_price='".$packDet->packagebaseprice."',
		    	promo_discount ='".$packDet->promodiscount."',
		    	total_after_discount='".$packDet->totalamount."',
		    	total_amount_after_gst='".$packDet->totalaftergst."',
		    	igst_percentage='".$packDet->igstpercentage."',
		    	igst_value='".$packDet->totaligstcharge."',
		    	cgst_percentage='".$packDet->cgstpercentage."',
		    	cgst_value='".$packDet->totalcgstcharge."',
		    	sgst_percentage='".$packDet->sgstpercentage."',
		    	sgst_value='".$packDet->totalsgstcharge."',
		    	order_status='',
		    	endyid='".$endyid."',
		    	billing_name='".@$endOwnerDet->name."',
		    	billing_address='".@$endOwnerDet->address1."',
		    	billing_city='".@$endOwnerDet->city."',
		    	billing_state='".$endOwnerDet->state."',
		    	billing_zip='".$endOwnerDet->zipcode."',
		    	billing_country='".$endOwnerDet->country."',
		    	billing_email='".@$endOwnerDet->email."',
		    	billing_mobile='".@$endOwnerDet->phone."',
		    	ipaddress='".$_SERVER["REMOTE_ADDR"]."',
		    	state_code='".$pinDet->state_code."',
		    	gst_code='".$pinDet->gst_code."',
		    	subscriberid='".$transOwnerDet->subscriberid."',
		    	paymenturl='".$transOwnerDet->paymenturl."',
		    	created_date='".Date("Y-m-d H:i:s",time())."'");
		    	$transactionid = $this->db->insert_id();

				$arr = array(
		    			"razorpayapikey"=>base64_encode($globalSettings->razorpayapikey),
		    			"transactiontype"=> "1",
		    			"transactionid"=>base64_encode($transactionid),
		    			"order_id"=>base64_encode($transOwnerDet->subscriberid),
		    			"amount"=>base64_encode($transOwnerDet->total_amount_after_gst),
		    			"name"=>@base64_encode($endOwnerDet->name),
		    			"companyname"=>$globalSettings->companyname,
		    			"description"=>$globalSettings->companydesc,
		    			"email"=>@base64_encode($endOwnerDet->email),
		    			"mobile"=>@base64_encode($endOwnerDet->phone),
		    			"eid"=>base64_encode($transOwnerDet->endyid),
		    			"logoimage"=>$globalSettings->companylogoimg,
		                "callbackurl"=>$globalSettings->razorpaycallbackurl
					);
		    	echo json_encode($arr);

		}
		else
		{

			// get package details
		    	$endOwnerQ = $this->db->query("select * from U_endowners where sno='".$_SESSION["ownerid"]."'");
				$endOwnerNum = $endOwnerQ->num_rows();
				if(($endOwnerNum)>0)
				{
					$endOwnerDet = $endOwnerQ->row(); //$row
					$pincode = $endOwnerDet->zipcode;
					$mobile = $endOwnerDet->phone;
				}


				$getPincodeData = $this->db->query("select * from pincode where pin='".$pincode."' limit 1");
		    	if($getPincodeData->num_rows() > 0) {
		    		$pinDet = $getPincodeData->row();
		    	}

				$packDet = $this->calculatePackageAmount($packageid, $totallicense, $promocode, "", $pincode, $mobile);
				$packDet = json_decode($packDet);
				/*
					$arr = array(
		            "promocode"=> $promocode,
					"totalaftergst"=> $totalaftergst,
					"totalamount"=> $totalafterpromocode,
					"promostatus"=> "1",
					"gstamount"=> $calgst,
					"promodiscount"=> $percval,
		            "packagebaseprice"=>$packageDet->amount,
					"baseprice"=> $totalamount,
					"packagename"=> $packageDet->packages,
					"cgstpercentage" =>$cgstpercentage,
					"sgstpercentage" =>$sgstpercentage,
					"igstpercentage" =>$igstpercentage,
					"totalcgstcharge" =>$totalcgstcharge,
					"totalsgstcharge" =>$totalsgstcharge,
					"totaligstcharge" => $totaligstcharge);
				*/
				$cnttranquery = $this->db->query("select * from U_transaction");
		    	$trancnt = $cnttranquery->num_rows();
		    	$endyid = 10000+($trancnt+1);
		    	// insert U_transaction with the orderid
				$transaction_query = $this->db->query("insert into U_transaction set owner_id='".$_SESSION["ownerid"]."',
		    	package_id='".$packageid."',
		    	total_licenses='".$totallicense."',
		    	package_type='".$packDet->packagename."',
		    	total_base_price='".$packDet->baseprice."',
		    	total_amount='".$packDet->totalamount."',
		    	promo_code='".$promocode."',
		        package_base_price='".$packDet->packagebaseprice."',
		    	promo_discount ='".$packDet->promodiscount."',
		    	total_after_discount='".$packDet->totalamount."',
		    	total_amount_after_gst='".$packDet->totalaftergst."',
		    	igst_percentage='".$packDet->igstpercentage."',
		    	igst_value='".$packDet->totaligstcharge."',
		    	cgst_percentage='".$packDet->cgstpercentage."',
		    	cgst_value='".$packDet->totalcgstcharge."',
		    	sgst_percentage='".$packDet->sgstpercentage."',
		    	sgst_value='".$packDet->totalsgstcharge."',
		    	order_status='',
		    	endyid='".$endyid."',
		    	billing_name='".@$endOwnerDet->name."',
		    	billing_address='".@$endOwnerDet->address1."',
		    	billing_city='".@$endOwnerDet->city."',
		    	billing_state='".$endOwnerDet->state."',
		    	billing_zip='".$endOwnerDet->zipcode."',
		    	billing_country='".$endOwnerDet->country."',
		    	billing_email='".@$endOwnerDet->email."',
		    	billing_mobile='".@$endOwnerDet->phone."',
		    	ipaddress='".$_SERVER["REMOTE_ADDR"]."',
		    	state_code='".$pinDet->state_code."',
		    	gst_code='".$pinDet->gst_code."',
		    	created_date='".Date("Y-m-d H:i:s",time())."'");
		    	$transactionid = $this->db->insert_id();

				//$orderRef = file_get_contents("http://localhost/services/razorpayservice.php?p=".$endOwnerDet->phone."&t=".$packDet->totalaftergst."&o=".$transactionid);
		    	$keyId = 'rzp_live_8A7vvlogPC2sCX';
				$keySecret = 'jOFj4xUloTX4lOrT3VFroaWp';
    			$api = new Api($keyId, $keySecret);
    			 if($mobile == '9781845300') {
		            $packDet->totalaftergst = 1;
		        }
		        else if($mobile == '8447103081') {
		            $packDet->totalaftergst = 1;
		        }
		        else if($mobile == '9999558552') {
		            $packDet->totalaftergst = 1;
		        }
				if($packageid==1)
		        {
		            $currentdate = Date("Y-m-d H:i:s",time());
		            $nextbillingdate = Date("Y-m-d H:i:s",strtotime('+1 year', strtotime($currentdate)));
		            $plan = $api->plan->create(array(
		              'period' => 'yearly',
		              'interval' => 1,
		              'item' => array(
		                'name' => 'Billed annually',
		                'description' => 'billed annually',
		                'amount' => $packDet->totalaftergst * 100,
		                'currency' => 'INR'
		                )
		              )
		            );

		             $subscription  = $api->subscription->create(array(
		                  'plan_id' => $plan->id,
		                  'quantity' => 1,
		                  'total_count' => 1,
		                  'notify_info' => array(
		                    "notify_email"=> $endOwnerDet->email,
		                    "notify_phone"=> $endOwnerDet->phone
		                  )
		                  )
		                );
		             $subscriberid = $subscription->id;
		             $subscriptionurl = $subscription->short_url;
		        }
		        else if($packageid==2)
		        {
		            $currentdate = Date("Y-m-d H:i:s",time());
		            $nextbillingdate = Date("Y-m-d H:i:s",strtotime('+1 month', strtotime($currentdate)));
		             $plan = $api->plan->create(array(
		              'period' => 'monthly',
		              'interval' => 1,
		              'item' => array(
		                'name' => 'Billed Monthly',
		                'description' => 'Billed Monthly',
		                'amount' => $packDet->totalaftergst * 100,
		                'currency' => 'INR'
		                )
		              )
		            );

		             $subscription  = $api->subscription->create(array(
		                  'plan_id' => $plan->id,
		                  'quantity' => 1,
		                  'total_count' => 1,
		                  'notify_info' => array(
		                     "notify_email"=> $endOwnerDet->email,
		                    "notify_phone"=> $endOwnerDet->phone
		                  )
		                  )
		                );
		             $subscriberid = $subscription->id;
		             $subscriptionurl = $subscription->short_url;
		        }

				// update order referrence number in transaction table
				$updateTrans = $this->db->query("update U_transaction set subscriberid='".$subscriberid."',paymenturl='".$subscriptionurl."' where transaction_id='".$transactionid."'");
				// create razorpay
				$globalSettings = json_decode($this->globalsettings());
				$arr = array(
		    			"razorpayapikey"=>base64_encode($globalSettings->razorpayapikey),
		    			"transactiontype"=> "1",
		    			"transactionid"=>base64_encode($transactionid),
		    			"order_id"=>base64_encode($subscriberid),
		    			"amount"=>base64_encode($packDet->totalaftergst),
		    			"name"=>@base64_encode($endOwnerDet->name),
		    			"companyname"=>$globalSettings->companyname,
		    			"description"=>$globalSettings->companydesc,
		    			"email"=>@base64_encode($endOwnerDet->email),
		    			"mobile"=>@base64_encode($endOwnerDet->phone),
		    			"eid"=>base64_encode($endyid),
		    			"logoimage"=>$globalSettings->companylogoimg,
		                "callbackurl"=>$globalSettings->razorpaycallbackurl
					);
		    	echo json_encode($arr);
		}


    }
}
?>
