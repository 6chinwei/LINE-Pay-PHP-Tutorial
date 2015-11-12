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
            <?php include('./blocks/header.php'); ?>
        </header>

        <?php include('./blocks/payment_steps.php'); ?>
        
        <div class="container">
            <form class="form-horizontal" id="reserveForm" method="POST" action="reserve.php">
                <?php include('./blocks/api_basic_info.php'); ?>
                
                <div class="panel">
                    <div class="panel-header">
                        <h3 class="panel-title">訂單資料</h3>
                    </div>
                    <div class="panel-box">
                        <div class="ctrl-grp columns-12">
                            <label class="ctrl-label col-3">商品名稱</label>
                            <div class="ctrls col-9">
                                <input type="text" class="ctrl-input" name="productName" value="Product A" required>
                            </div>
                        </div>
                        <div class="ctrl-grp columns-12">
                            <label class="ctrl-label col-3">訂單編號</label>
                            <div class="ctrls col-9">
                                <input type="text" class="ctrl-input" name="orderId" value="" required>
                            </div>
                        </div>
                        <div class="ctrl-grp columns-12">
                            <label class="ctrl-label col-3">訂單照片</label>
                            <div class="ctrls col-9">
                                <input type="text" class="ctrl-input" name="productImageUrl" value="image.jpeg" >
                            </div>
                        </div>
                        <div class="ctrl-grp columns-12">
                            <label class="ctrl-label col-3">訂單金額</label>
                            <div class="ctrls col-9">
                                <div class="input-grp">
                                    <span class="adorn">TWD</span>
                                    <input type="text" class="ctrl-input" name="amount" value="20" required>
                                    <input type="hidden" class="ctrl-input" name="currency" value="TWD">
                                </div>
                            </div>
                        </div>
                        <div class="ctrl-grp columns-12">
                            <label class="ctrl-label col-3">confirmUrl</label>
                            <div class="ctrls col-9">
                                <input type="text" class="ctrl-input" name="confirmUrl" value="confirm.php" required>
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
                                使用 <input type="image" src="linepay_logo_119x39.png"> 支付
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>