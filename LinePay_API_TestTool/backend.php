<?php
require_once("LinePayAPI.php");

session_start();

// Store Webpage -> Store Server
if(isset($_POST['productName'])) 
{
    $apiEndpoint   = $_POST['apiEndpoint'];
    $channelId     = $_POST['channelId'];
    $channelSecret = $_POST['channelSecret'];

    $params = [
        "productName"     => $_POST['productName'],
        "productImageUrl" => $_POST['productImageUrl'],
        "amount"          => $_POST['amount'],
        "currency"        => $_POST['currency'],
        "confirmUrl"      => $_POST['confirmUrl'],
        "orderId"         => $_POST['orderId'],
        "confirmUrlType"  => $_POST['confirmUrlType'],
    ];

    try {
        $LinePayAPI = new Chinwei6\LinePayAPI($apiEndpoint, $channelId, $channelSecret);
        $_SESSION['cache'] = [
            "apiEndpoint"   => $_POST['apiEndpoint'],
            "channelId"     => $_POST['channelId'],
            "channelSecret" => $_POST['channelSecret'],
            "amount"        => $_POST['amount'],
            "currency"      => $_POST['currency'],
        ];

        echo $LinePayAPI->reserve($params);
    }
    catch(Exception $e) {
        echo $e->getMessage();
    }
}
// LinePay Server -> Store Server (calling confirmUrl)
else if(isset($_GET['transactionId']))
{
    echo "_SESSION['cache']";
    echo json_encode($_SESSION['cache'], JSON_PRETTY_PRINT);

    if( !isset($_SESSION['cache']) ) {
        // Redirect to iindex.html
        echo "No Session";
        return false;
    }

    $params = [
        "amount" => $_SESSION['cache']['amount'], 
        "currency" => $_SESSION['cache']['currency'],
    ];

    echo json_encode($params, JSON_PRETTY_PRINT);

    $headers = array(
        'Content-Type:application/json; charset=UTF-8',   
        'X-LINE-ChannelId:' . $_SESSION['cache']['channelId'],
        'X-LINE-ChannelSecret:' . $_SESSION['cache']['channelSecret'],
    );
    //API_ENDPOINT: https://sandbox-api-pay.line.me/v2/payments/request
    $ch = curl_init();     
    curl_setopt($ch, CURLOPT_URL, $_SESSION['cache']['apiEndpoint'] . $_GET['transactionId']."/confirm");
    curl_setopt($ch, CURLOPT_POST, true); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));   
    curl_setopt($ch, CURLOPT_SSLVERSION, 'CURL_SSLVERSION_TLSv1');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);     

    $result = curl_exec($ch);
    curl_close($ch);
    echo json_encode($result, JSON_PRETTY_PRINT);

    session_destroy();
}