<?php

/* Mongo Connection*/

$m = new MongoClient("mongodb://mongoadmine:KL7w51cfRAT@localhost:27017");
$db = $m->singleho_endetect;
$collection = $db->u_endata;


$a=0;
$cursor = $collection->find(array("dtype" => "21"));
foreach ($cursor as $document) {
	$a++;
	
	
	
	$recordid = (int)$document['sno'];
	$changefile = $document['decdata'];
	$arrimgfile = explode(".",$changefile);
	$changefileto = $arrimgfile['0'].".jpg";
	echo $recordid." ".$changefileto."<br>";
	
	rename($changefile,$changefileto);
	
	$newdata = array('$set' => array("decdata" => $changefileto));
    $newdata1 = array('$set' => array("ddata" => $changefileto));
	
	if($collection->update(array("sno" => $recordid), $newdata))
	{
		if($collection->update(array("sno" => $recordid), $newdata1))
		{
			echo "1";
		}
	}
	
}

?>