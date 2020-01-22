<?php
include("connection.php");
$user_input="";
/*====================== Database Connection Code End Here ========================== */

// Here, we will get user input data and trim it, if any space in that user input data
$concatstr="";
$user_input = trim(@$_REQUEST['term']);
$countrycode = trim(@$_REQUEST['countrycode']);

// Define two array, one is to store output data and other is for display
$display_json = array();
$json_arr = array();

$user_input = preg_replace('/\s+/', ' ', $user_input);


if($countrycode!='')
{
	$concatstr.= 'AND country_code ="'.$countrycode.'"'; 
}
if($user_input!='')
{
	$concatstr.='AND place LIKE "%'.$user_input.'%"';
}
$query = 'SELECT * FROM place_master WHERE 1 '.$concatstr;

 
$recSql = $conn->query($query);

if($recSql->num_rows>0){
while($recResult = $recSql->fetch_assoc()) {
  
  //retireve value in json assign it placeofbirth and after explode function fetch all details
  

  	$sno		=	$recResult['sno'];

	$city		=	$recResult['place'];

	

	  $json_arr["id"] 		= $sno;
	  $json_arr["label"] 	= $city;	  
	  $json_arr["value"] 	= $city;
	
	

  array_push($display_json, $json_arr);
}
} else {
  $json_arr["id"] = "#";
  $json_arr["value"] = "";
  $json_arr["label"] = "No Result Found !".$country_code;
  array_push($display_json, $json_arr);
}
 
	
$jsonWrite = json_encode($display_json); //encode that search data
print $jsonWrite;
?>