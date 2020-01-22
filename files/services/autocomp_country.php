<?php
include("connection.php");
$user_input="";
/*====================== Database Connection Code End Here ========================== */

// Here, we will get user input data and trim it, if any space in that user input data
$user_input = trim(@$_REQUEST['term']);


// Define two array, one is to store output data and other is for display
$display_json = array();
$json_arr = array();

$user_input = preg_replace('/\s+/', ' ', $user_input);



$query = 'SELECT * FROM country_master WHERE country LIKE "%'.$user_input.'%" or country_code LIKE "%'.$user_input.'%"';

 
$recSql = $conn->query($query);
if($recSql->num_rows>0){
while($recResult = $recSql->fetch_assoc()) {
  
  //retireve value in json assign it placeofbirth and after explode function fetch all details
  

  	$sno				=	$recResult['sno'];

	$country		=	$recResult['country'];

	$country_code		=	$recResult['country_code'];

	$json_arr["id"] = $country_code;
	  $json_arr["label"] = $country;
	  
	  $json_arr["value"] = $country;
	
	

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