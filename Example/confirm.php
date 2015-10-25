<?php
require_once("../LinePay.php");
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
                        // LinePay Server -> Store Server (calling confirmUrl)
                        if(isset($_GET['transactionId']) && isset($_SESSION['cache'])) {
                            $apiEndpoint   = $_SESSION['cache']['apiEndpoint'];
                            $channelId     = $_SESSION['cache']['channelId'];
                            $channelSecret = $_SESSION['cache']['channelSecret'];

                            $params = [
                                "amount"   => $_SESSION['cache']['amount'], 
                                "currency" => $_SESSION['cache']['currency'],
                            ];

                            try {
                                $LinePay = new Chinwei6\LinePay($apiEndpoint, $channelId, $channelSecret);

                                $result = $LinePay->confirm($_GET['transactionId'], $params);
                                echo '<pre class="code">';
                                echo json_encode($result, JSON_PRETTY_PRINT);
                                echo '</pre>';
                            }
                            catch(Exception $e) {
                                echo '<pre class="code">';
                                echo $e->getMessage();
                                echo '</pre>';
                            }

                            session_destroy();
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