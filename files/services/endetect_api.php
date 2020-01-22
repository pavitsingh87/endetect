<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Kolkata");
# Include the Autoloader (see "Libraries" for install instructions)

require '../vendor/autoload.php';
use Elasticsearch\ClientBuilder;
$client = ClientBuilder::create()->build();
$data = json_decode(file_get_contents('php://input'), true);
foreach ($data as $key => $value) {
   $stdArray[] = (array) $value;
}
function milliseconds() {
    $mt = explode(' ', microtime());
    return ((int)$mt[1]) * 1000 + ((int)round($mt[0] * 1000));
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
$linecount= urldecode($stdArray['10']['0']);
$ttype=  urldecode($stdArray['11']['0']);
$ipaddress = urldecode($stdArray['12']['0']);
$hostname = urldecode($stdArray['13']['0']);
$delete = urldecode($stdArray['14']['0']);
$endversion = urldecode($stdArray['15']['0']);
$ddate= urldecode($stdArray['16']['0']);
$decdata= urldecode($stdArray['17']['0']);
$ddata= urldecode($stdArray['18']['0']);
/*Create array of data to store*/
$user_data=array(
'sno'=>$sno,
'newsno'=>$newsno,
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
'ddate'=>milliseconds()
);  
$params['body']  = $user_data;
$params['index'] = 'mongoindex1';
$params['type']  = 'u_endata';
$params['id']    = $sno;
$response = $client->index($params);
?>