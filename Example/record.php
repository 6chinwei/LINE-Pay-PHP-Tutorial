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
        <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
        <script type="text/javascript" src="app.js"></script>
    </head>
    <body>
        <header>
            <?php include('./blocks/header.php'); ?>
        </header>

        <div class="container">
            <form class="form-horizontal" id="recordForm">
                <?php include('./blocks/api_basic_info.php'); ?>
                
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
                        <div class="columns-12">
                            <div class="col-9 col-offset-3">
                                ＊交易編號與商家訂單編號至少要填一項
                            </div>
                        </div>
                    </div>
                    <div class="ctrl-grp columns-12">
                        <div class="ctrls col-9 col-offset-3">
                            <input type="hidden" name="checkPaymentSubmit" value="ture">
                            <input type="submit" class="btn color-primary" value="送出"> 
                        </div>
                    </div>
                </div>
            </form>
            <div class="panel">
                <div class="panel-header">
                    <h3 class="panel-title">LinePay 伺服器回應</h3>
                </div>
                <div class="panel-box">
                    <pre class="code" id="response">
                        
                    </pre>
                </div>
            </div>
        </div>   
    </body>
</html>