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

$action = $_REQUEST["paAction"];
$regkey = $_REQUEST["regKey"];

$API_Endpoint .= '/preapprovedPay/'. $regkey;
if ($action == "CHECK") {
	$API_Endpoint .= '/check';
	
	$ccAuth = array(
		"creditCardAuth" => $_REQUEST["ccAuth"]
	);
	
	$useGet = 1; // declare this val to tell SendRequest calling API via GET
	$resArray = hash_call($ccAuth);
	$_SESSION["cReghash"] = $resArray;
}
else if ($action == "EXPIRE") {
	$API_Endpoint .= '/expire';
	
	$resArray = hash_call(null);
	$_SESSION["eReghash"] = $resArray;
	
}
else { // $action == "PAYMENT"
	$API_Endpoint .= '/payment';
	
	$pArray = array(
		"productName" => $_REQUEST["pNAME"],
		"amount" => $_REQUEST["pAMT"],
		"currency" => "TWD",
		"orderId" => $_REQUEST["pOrderId"],
		"capture" => $_REQUEST["pCapture"]
	);
	$resArray = hash_call($pArray);
	$_SESSION["prehash"] = $resArray;
}

// display response resArray and make it more readable./////////////////
$output = json_decode($resArray);
$output = json_encode($output,JSON_PRETTY_PRINT);

echo $API_Endpoint;
echo "<br/>";
echo "<pre>" . $output . "</pre>";
	
?>