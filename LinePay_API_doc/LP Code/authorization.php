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

$txnId = $_REQUEST["authTxnId"];
$authAction = $_REQUEST["authAction"];
	
$API_Endpoint .= '/authorizations/'. $txnId;
if ($authAction == "CAPTURE") {
	$API_Endpoint .= '/capture';
	
	$captureArray = array(
		"amount" => $_REQUEST["captureAmount"],
		"currency" => "TWD"
	);

	$resArray = hash_call($captureArray);
	$_SESSION["caphash"] = $resArray;
}
else { // $authAction == "VOID"
	$API_Endpoint .= '/void';

	$resArray = hash_call(null);
	$_SESSION["voidhash"] = $resArray;
}

// display response resArray and make it more readable./////////////////
$output = json_decode($resArray);
$output = json_encode($output,JSON_PRETTY_PRINT);

echo $API_Endpoint;
echo "<br/>";
echo "<pre>" . $output . "</pre>";
	
?>