<?php
require_once("../Chinwei6_LinePay.php");
session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>LinePay API Test</title>
        <link rel="stylesheet" href="./kule-lazy-full.3.0.1007beta.min.css" />
    </head>
    <body>
        <header>
            <?php include('./header.php'); ?>
        </header>
        <div class="container">
            <div class="panel">
                <div class="panel-header">
                    <h3 class="panel-title">LinePay 伺服器回應</h3>
                </div>
                <div class="panel-box">
                    <?php
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
                                $LinePay = new Chinwei6\LinePay($apiEndpoint, $channelId, $channelSecret);
                                $_SESSION['cache'] = [
                                    "apiEndpoint"   => $_POST['apiEndpoint'],
                                    "channelId"     => $_POST['channelId'],
                                    "channelSecret" => $_POST['channelSecret'],
                                    "amount"        => $_POST['amount'],
                                    "currency"      => $_POST['currency'],
                                ];

                                $result = $LinePay->reserve($params);
                                echo '<pre class="code">';
                                echo json_encode($result, JSON_PRETTY_PRINT);
                                echo '</pre>';

                                if(isset($result['info']['paymentUrl']['web']))
                                    echo '<a target="_blank" href="' . $result['info']['paymentUrl']['web'] . '">點此連至 Line 頁面登入帳戶</a>';
                            }
                            catch(Exception $e) {
                                echo '<pre class="code">';
                                echo $e->getMessage();
                                echo '</pre>';
                            }
                        }
                        else {
                            echo "No Data";
                        }
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>