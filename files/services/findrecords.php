<?php
/* Mongo Connection*/
$m = new MongoClient("mongodb://mongoadmine:KL7w51cfRAT@localhost:27017");
$db = $m->singleho_endetect;
$collection = $db->u_endata;
$recordid = (int)$_REQUEST['recordid'];
$cursor = $collection->find(array('enduserid' => '301'))->limit(10);
foreach ($cursor as $doc) {
    echo $imagetype = $doc['dtype'];
	echo "a<br />";
	echo $imagestr = $doc['decdata'];
	echo "<br />";
	
}

?>