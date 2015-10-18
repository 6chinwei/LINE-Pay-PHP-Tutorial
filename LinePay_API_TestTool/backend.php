<?php
require_once("LinePayAPI.php");

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
        echo $LinePayAPI->reserve($params);
    }
    catch(Exception $e) {
        echo $e->getMessage();
    }
}