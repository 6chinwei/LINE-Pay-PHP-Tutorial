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
            

            <form class="form-horizontal" id="reserveForm" method="POST" action="reserve.php">
                <div class="panel">
                    <div class="panel-header">
                        <h3 class="panel-title">API 位置與商家資料</h3>
                    </div>
                    <div class="panel-box">
                        <div class="ctrl-grp columns-12">
                            <label class="ctrl-label col-3">Line Pay API Server</label>
                            <div class="ctrls col-9">
                                <input type="text" class="ctrl-input" name="apiEndpoint" value="https://sandbox-api-pay.line.me/v2/payments/">
                            </div>
                        </div>
                        <div class="ctrl-grp columns-12">
                            <label class="ctrl-label col-3">商家 ID</label>
                            <div class="ctrls col-9">
                                <input type="text" class="ctrl-input" name="channelId" value="1437558894">
                            </div>
                        </div>
                        <div class="ctrl-grp columns-12">
                            <label class="ctrl-label col-3">商家密鑰</label>
                            <div class="ctrls col-9">
                                <input type="text" class="ctrl-input" name="channelSecret" value="bbf2805a78d23d602092fdb590f99740">
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
                            <label class="ctrl-label col-3">商品名稱</label>
                            <div class="ctrls col-9">
                                <input type="text" class="ctrl-input" name="productName" value="Product A">
                            </div>
                        </div>
                        <div class="ctrl-grp columns-12">
                            <label class="ctrl-label col-3">訂單編號</label>
                            <div class="ctrls col-9">
                                <input type="text" class="ctrl-input" name="orderId" value="A123456">
                            </div>
                        </div>
                        <div class="ctrl-grp columns-12">
                            <label class="ctrl-label col-3">訂單照片</label>
                            <div class="ctrls col-9">
                                <input type="text" class="ctrl-input" name="productImageUrl" value="http://6chinwei.cc/images.jpg">
                            </div>
                        </div>
                        <div class="ctrl-grp columns-12">
                            <label class="ctrl-label col-3">訂單金額</label>
                            <div class="ctrls col-9">
                                <div class="input-grp">
                                    <span class="adorn">TWD</span>
                                    <input type="text" class="ctrl-input" name="amount" value="20">
                                    <input type="hidden" class="ctrl-input" name="currency" value="TWD">
                                </div>
                            </div>
                        </div>
                        <div class="ctrl-grp columns-12">
                            <label class="ctrl-label col-3">confirmUrl</label>
                            <div class="ctrls col-9">
                                <input type="text" class="ctrl-input" name="confirmUrl" value="http://6chinwei.cc/linepay/confirm.php">
                            </div>
                        </div>
                        <div class="ctrl-grp columns-12">
                            <label class="ctrl-label col-3">confirmUrl 類型</label>
                            <div class="ctrls col-9">
                                <div class="kui-opts">
                                    <label class="kui-opt">
                                        <input type="radio" name="confirmUrlType" value="CLIENT" checked>
                                        <span class="kui-opt-input">CLIENT</span>
                                    </label>
                                    <label class="kui-opt">
                                        <input type="radio" name="confirmUrlType" value="SERVER">
                                        <span class="kui-opt-input">SERVER</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="ctrl-grp columns-12">
                            <div class="ctrls col-9 col-offset-3">
                                <!-- <button type="button" class="btn" id="reserveSubmit">送出</button> -->
                                <input type="submit" class="btn" value="送出">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>