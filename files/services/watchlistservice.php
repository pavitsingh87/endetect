<?php
header('Access-Control-Allow-Origin: *');
# Include the Autoloader (see "Libraries" for install instructions)
use Elasticsearch\ClientBuilder;
require '../vendor/autoload.php';
$client = ClientBuilder::create()->build();
$recordid = $_REQUEST["recordid"];
$action = $_REQUEST["action"];
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
$response = $client->update($params);
echo "1";
?>