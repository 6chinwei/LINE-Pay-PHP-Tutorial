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

// Determine if token has been taken, if not, will execute Confrim to initialize
$token = $_REQUEST["transactionId"];
if (!isset($token)) { // Call Reserve API
	$API_Endpoint .= '/request';
	
    $serverName = $_SERVER["SERVER_NAME"];
    $serverPort = $_SERVER["SERVER_PORT"];
    $url = dirname("http://" . $serverName . ":" . $serverPort . $_SERVER["REQUEST_URI"]);
    
	// confirmURL  (After confirm payment in LINE Pay will return to this URL)
    $confirmURL = $url . "/request.php" . '?environ=' . $environ . '&account=' . $account;
	
    // cancelURL  (If cancel payment from LINE Pay checkout page, will return to this URL)
    $cancelURL = $url;

    $amt0 = $_REQUEST["AMT0"];
    $amt1 = $_REQUEST["AMT1"];
    $qty0 = $_REQUEST["QTY0"];
    $qty1 = $_REQUEST["QTY1"];
    $shipAmt = $_REQUEST["shippingAmt"];

    $amt = $amt0 * $qty0 + $amt1 * $qty1 +$shipAmt;
	$count = $qty0 + $qty1;
    $_SESSION["amt"] = $amt;
	
	$count==1? $productName = $_REQUEST["NAME0"]: $productName = $_REQUEST["NAME0"]. "...等兩項";
    $rArray = array(
        "productName" => $productName,
        "productImageUrl" => $_REQUEST["logo"],
        "amount" => $amt,
        "currency" => "TWD",
        "confirmUrl" => $confirmURL,
        "cancelUrl" => $cancelURL,
        "orderId" => $_REQUEST["orderId"],
        "confirmUrlType" => $_REQUEST["confirmUrlType"],
        "checkConfirmUrlBrowser" => $_REQUEST["checkConfirmUrlBrowser"],
        "payType" => $_REQUEST["payType"],
        "capture" => $_REQUEST["capture"]
        );
    
    // Pass everything to PayPal, response will be set in $resArray
    $resArray = hash_call($rArray);
    $_SESSION["reshash"] = $resArray;

    $ack = json_decode($resArray);
	
    // Determine if it is success
    if ($ack->returnCode == "0000") {
        // Redirect to buyer auth process if success
        $paymentURL = $ack->info->paymentUrl->web;
        //header("Location: " . $paymentURL);

        // var_dump(json_decode($resArray, true));    
		$output = json_encode($ack,JSON_PRETTY_PRINT);    
        echo "<pre>" . $output . "</pre>";

        echo "<a href=$paymentURL>Click to next step</a>";
        
    } else {
        print_r($resArray);
        echo "</br>";
        echo $confirmURL;
    }
} else { // Call Confirm API
    // Token retrieval success means buyer auth is successfully passed
    $tnxId = $_REQUEST["transactionId"];
    $API_Endpoint .= '/'. $tnxId . '/confirm';

    $cArray = array(
        "amount" => $_SESSION["amt"],
        "currency" => "TWD"
        );

    $resArray = hash_call($cArray);
    $_SESSION["conhash"] = $resArray;
	
	// display response resArray and make it more readable./////////////////
	$output = json_decode($resArray);
	$output = json_encode($output,JSON_PRETTY_PRINT);

	echo $API_Endpoint;
	echo "<br/>";
	echo "<pre>" . $output . "</pre>";

 }   
?>