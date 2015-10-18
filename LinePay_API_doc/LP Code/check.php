<?php

require_once "SendRequest.php";

header('Content-type: text/html; charset=utf-8');
define('API_ENDPOINT', 'https://api-pay.line.me/v2/payments');

$account = $_REQUEST["account"];
$environ = $_REQUEST["environ"];

setup($account, $environ); // setup CHANNELID & CHANNELSECRET via the account and environment selected

if ($account == "bill.test" && $environ == "sandbox") {
	$API_Endpoint = substr_replace(API_ENDPOINT, "sandbox-", 8, 0); // make $API_Endpoint be 'https://sandbox-api-pay.line.me/v2/payments'
}
else { 
	$API_Endpoint = API_ENDPOINT;
}
$channelId = CHANNELID;
$channelSecret = CHANNELSECRET;

session_start();

$tocheck = $_REQUEST["toCheck"];
$txnId = $_REQUEST["tcTranId"];
$orderId = $_REQUEST["tcOrderId"];

if ($tocheck == "AUTH") {
	$API_Endpoint = API_ENDPOINT . '/authorizations';
}

// set txnId or orderId
if (!empty($txnId) && empty($orderId)) { // use txnId
	$API_Endpoint .= '?transactionId=' . $txnId;
}
if (empty($txnId) && !empty($orderId)) { // use orderId
	$API_Endpoint .= '?orderId=' . $orderId;
}

$useGet = 1; // declare this val to tell SendRequest calling API via GET
$resArray = hash_call(null);
$_SESSION["chhash"] = $resArray;

// display response resArray and make it more readable./////////////////
$output = json_decode($resArray);
$output -> info[0] -> productName = urlencode($output -> info[0] -> productName); // 中文內容直接json_encode會產生亂碼，先做urlencode轉碼
$output = urldecode(json_encode($output, JSON_PRETTY_PRINT)); // 用urldecode轉回中文顯示

echo $API_Endpoint;
echo "<br/>";
echo "<pre>" . $output . "</pre>";
?>