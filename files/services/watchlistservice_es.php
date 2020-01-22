<?php
header("access-control-allow-origin: *");
header('Content-Type: text/html; charset=utf-8'); 

use Elasticsearch\ClientBuilder;

require '../vendor/autoload.php';
$client = ClientBuilder::create()->build();
$recordid = urldecode($_REQUEST['recordid']);
$action = urldecode($_REQUEST['action']);
$sno = intval($recordid);
$action = intval($action);


$params = [
    'index' => 'mongoindex1',
    'type' => 'u_endata',
    'id' => $sno,
    'body' => [
        'doc' => [
            'watchlist' => $action
        ]
    ]
];

// Update doc at /my_index/my_type/my_id
$response = $client->update($params);
print_r($response);
?>