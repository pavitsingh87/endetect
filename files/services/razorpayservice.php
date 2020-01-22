<?php
require('../library/razorpay_lib/razorpay_config.php');
require('../library/razorpay_lib/razorpay-php/Razorpay.php');
include("connection.php");
use Razorpay\Api\Api;

$phone="9999558552";
$order_id="1";


$getGlobalSettings = $conn->query("select * from global_settings where id=1");
if($getGlobalSettings->num_rows)
{
	$globalSettingsRow 	=  $getGlobalSettings->fetch_assoc();
	$razorPayApiKey 	=  $globalSettingsRow["razorpayapikey"];
	$razorPayApiSecret 	=  $globalSettingsRow["razorpayapisecret"];
}
$api = new Api($razorPayApiKey, $razorPayApiSecret);
if($phone == '9781845300') {
	$amount = 1;
}
else if($phone == '8447103081') {
	$amount = 1;
}
else if($phone == '9999558552') {
    $amount = 1;
}
$orderData = [
	'receipt' => $order_id, //Your system order reference id (Don't Know)
	'amount' => $amount * 100, // 1 rupees in paise
	'currency' => 'INR',
	'payment_capture' => 1 // auto capture
];
$razorpayOrder = $api->order->create($orderData);
$razorpayOrderId = $razorpayOrder['id'];
echo $razorpayOrderId;
?>