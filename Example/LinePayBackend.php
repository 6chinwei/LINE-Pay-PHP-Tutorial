<?php 
// Back-end for refund and record

require_once("../Chinwei6_LinePay.php");

if(isset($_POST['checkPaymentSubmit'])) {
    if(empty($_POST['transactionId']) && empty($_POST['orderId'])) {
        echo "transactionId or orderId is required.";
        return;
    }

    $apiEndpoint   = $_POST['apiEndpoint'];
    $channelId     = $_POST['channelId'];
    $channelSecret = $_POST['channelSecret'];

    $params = [
        "orderId"       => isset($_POST['orderId']) ? $_POST['orderId'] : null,
        "transactionId" => isset($_POST['transactionId']) ? $_POST['transactionId'] : null,
    ];

    try {
        $LinePay = new Chinwei6\LinePay($apiEndpoint, $channelId, $channelSecret);
        $result = $LinePay->checkPayment($params);
        echo json_encode($result, JSON_PRETTY_PRINT);
    }
    catch(Exception $e) {
        echo $e->getMessage();
    }

} 
else if(isset($_POST['refundSubmit'])) {
    if(empty($_POST['transactionId'])) {
        echo "transactionId is required.";
        return;
    }

    $apiEndpoint   = $_POST['apiEndpoint'];
    $channelId     = $_POST['channelId'];
    $channelSecret = $_POST['channelSecret'];

    $transactionId = isset($_POST['transactionId']) ? $_POST['transactionId'] : null;
    $params = [
        "refundAmount" => isset($_POST['refundAmount']) ? $_POST['refundAmount'] : null,
    ];

    try {
        $LinePay = new Chinwei6\LinePay($apiEndpoint, $channelId, $channelSecret);
        $result = $LinePay->refund($transactionId, $params);
        echo json_encode($result, JSON_PRETTY_PRINT);
    }
    catch(Exception $e) {
        echo $e->getMessage();
    }

}