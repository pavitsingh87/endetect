<?php
include_once("../connection.php");

/* Mongo Connection*/
function curl_get_contents($url)
{
	$ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}
/*$m = new MongoClient("mongodb://mongoadmine:KL7w51cfRAT@localhost:27017");
$db = $m->singleho_endetect;
$collection = $db->u_endata;*/

$noteflag = (string)$_REQUEST['noteflag'];
$recordid = (int)$_REQUEST['postid'];
$action = (string)$_REQUEST['notetext'];

/*$newdata = array('$set' => array("note" => $action));
$flagdata = array('$set' => array("noteflag"=>$noteflag));
*/
/*if($collection->update(array("sno" => $recordid), array('$set' => array("note" => $action))))
{
	if($collection->update(array("sno" => $recordid), array('$set' => array("noteflag" => $noteflag))))
	{
*/	echo json_encode(array('status' => 'success'));
	// curl job elasticsearch update
	$newurl = baseurl ."services/notesave_es.php?noteflag=".urlencode($noteflag)."&recordid=".urlencode($recordid)."&action=".urlencode($action);
	curl_get_contents($newurl);
/*	}

}
*/

?>
