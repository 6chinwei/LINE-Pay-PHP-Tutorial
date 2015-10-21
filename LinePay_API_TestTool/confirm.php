<?php
require_once("LinePayAPI.php");
session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>LinePay API Test</title>
        <link rel="stylesheet" href="./kule-lazy-full.min.css" />
        <!-- <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script> -->
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
                                $LinePayAPI = new Chinwei6\LinePayAPI($apiEndpoint, $channelId, $channelSecret);

                                $result = $LinePayAPI->confirm($_GET['transactionId'], $params);
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