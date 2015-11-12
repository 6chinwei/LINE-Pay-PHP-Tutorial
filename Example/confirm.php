<?php
require_once("../Chinwei6_LinePay.php");
session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>LinePay API Test - Confirm</title>
        <link rel="stylesheet" href="kule-lazy-full.3.0.1007beta.min.css" />
        <style type="text/css">
            body {
                min-width: 360px;
            }   
        </style>
    </head>
    <body>
        <header>
            <?php include('./blocks/header.php'); ?>
        </header>

        <?php include('./payment_steps.php'); ?>

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

                            unset($_SESSION['cache']);
                        }
                        else {
                            echo '<pre class="code">';
                            echo "No Params";
                            echo '</pre>';
                        }
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>