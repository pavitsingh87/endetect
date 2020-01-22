<?php
@session_start();

$m = new MongoClient("mongodb://mongoadmine:KL7w51cfRAT@localhost:27017");
$db = $m->singleho_endetect;
$collection = $db->u_endata;

$cursor = $collection->find();
   // iterate cursor to display title of documents
   foreach ($cursor as $document) {
   		$sno = (int)$document["sno"];
		print_r( $collection->update(array("sno"=>$sno), array('$set'=>array("newsno"=>$sno))));
		
   }	
	
?>

