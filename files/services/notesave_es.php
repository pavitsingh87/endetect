<?php
header("access-control-allow-origin: *");
header('Content-Type: text/html; charset=utf-8'); 

use Elasticsearch\ClientBuilder;
require '../vendor/autoload.php';
$client = ClientBuilder::create()->build();

$noteflag = urldecode($_REQUEST['noteflag']);
$recordid = urldecode($_REQUEST['recordid']);
$action = urldecode($_REQUEST['action']);
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
$response = $client->update($params);
?>