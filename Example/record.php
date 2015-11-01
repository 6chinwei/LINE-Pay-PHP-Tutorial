<?php
    require_once("../Chinwei6_LinePay.php");
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>LINE Pay API</title>
        <link rel="stylesheet" href="kule-lazy-full.3.0.1007beta.min.css" />
        <style type="text/css">
            body {
                min-width: 360px;
            }   
        </style>
    </head>
    <body>
        <header>
            <?php include('./header.php'); ?>
        </header>

        <div class="container">
            <form class="form-horizontal" id="reserveForm" method="POST" action="record.php">
                <div class="panel">
                    <div class="panel-header">
                        <h3 class="panel-title">API 位置與商家資料</h3>
                    </div>
                    <div class="panel-box">
                        <div class="ctrl-grp columns-12">
                            <label class="ctrl-label col-3">LINE Pay API Server</label>
                            <div class="ctrls col-9">
                                <input type="text" class="ctrl-input" name="apiEndpoint" value="" readonly required>
                            </div>
                        </div>
                        <div class="ctrl-grp columns-12">
                            <label class="ctrl-label col-3">商家 ID</label>
                            <div class="ctrls col-9">
                                <input type="text" class="ctrl-input" name="channelId" value="" required>
                            </div>
                        </div>
                        <div class="ctrl-grp columns-12">
                            <label class="ctrl-label col-3">商家密鑰</label>
                            <div class="ctrls col-9">
                                <input type="text" class="ctrl-input" name="channelSecret" value="" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel">
                    <div class="panel-header">
                        <h3 class="panel-title">訂單資料</h3>
                    </div>
                    <div class="panel-box">
                        <div class="ctrl-grp columns-12">
                            <label class="ctrl-label col-3">交易編號(transactionId)</label>
                            <div class="ctrls col-9">
                                <input type="text" class="ctrl-input" name="transactionId" value="">
                            </div>
                        </div>
                        <div class="ctrl-grp columns-12">
                            <label class="ctrl-label col-3">商家訂單編號(orderId)</label>
                            <div class="ctrls col-9">
                                <input type="text" class="ctrl-input" name="orderId" value="">
                            </div>
                        </div>
                    </div>
                    <div class="ctrl-grp columns-12">
                            <div class="ctrls col-9 col-offset-3">
                                <input type="submit" name="submit" class="btn color-primary" value="送出"> 
                            </div>
                        </div>
                </div>
            </form>
            <div class="panel">
                <div class="panel-header">
                    <h3 class="panel-title">LinePay 伺服器回應</h3>
                </div>
                <div class="panel-box">
                    <?php
                        if(isset($_POST['submit']))
                            unset($_POST['submit']);
                        else
                            return;

                        if(isset($_POST['transactionId']) || isset($_POST['orderId'])) 
                        {
                            $apiEndpoint   = $_POST['apiEndpoint'];
                            $channelId     = $_POST['channelId'];
                            $channelSecret = $_POST['channelSecret'];

                            $params = [
                                "orderId"       => $_POST['orderId'],
                                "transactionId" => $_POST['transactionId'],
                            ];

                            try {
                                $LinePay = new Chinwei6\LinePay($apiEndpoint, $channelId, $channelSecret);
                                $result = $LinePay->payments($params);
                                echo '<pre class="code">';
                                echo json_encode($result, JSON_PRETTY_PRINT);
                                echo '</pre>';
                            }
                            catch(Exception $e) {
                                echo '<pre class="code">';
                                echo $e->getMessage();
                                echo '</pre>';
                            }
                        }
                        else {
                            echo '<pre class="code">';
                            echo "transactionId or orderId is required.";
                            echo '</pre>';
                        }
                    ?>
                </div>
            </div>
        </div>   
    </body>
</html>