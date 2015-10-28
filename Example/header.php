<div class="container">
    <div class="navbar">
        <div class="navbar-header">
            <a class="navbar-toggle"></a>
            <a class="navbar-brand" href="index.php">LinePay API - PHP 測試工具 Beta</a>
        </div>
        <div class="navbar-content">
            <ul class="navbar-nav right">
                <li class="navbar-nav-item <?php echo isActive('index.php').' '.isActive('reserve.php').' '.isActive('confirm.php');  ?>">
                    <a class="navbar-nav-link">支付測試</a>
                </li>
                <li class="navbar-nav-item <?php echo isActive('record.php'); ?>">
                    <a class="navbar-nav-link">付款記錄查詢</a>
                </li>
                <li class="navbar-nav-item <?php echo isActive('about.php'); ?>">
                    <a class="navbar-nav-link">關於</a>
                </li>
            </ul>
        </div>
    </div>
    <?php 
        function isActive($fileName) {
            if($fileName == basename($_SERVER["PHP_SELF"]))
                return 'active';
        }
    ?>
    <div id="steps">
        <ul class="step">
            <li class="step-item <?php echo isActive('index.php'); ?>">
                <h6 class="step-title">訂單確認與付款</h6>
                <p class="step-text">Payment Reserve</p>
            </li>
            <li class="step-item <?php echo isActive('reserve.php'); ?>">
                <h6 class="step-title">身分驗證</h6>
                <p class="step-text">Authentication</p>
            </li>
            <li class="step-item <?php echo isActive('confirm.php'); ?>">
                <h6 class="step-title">付款結果</h6>
                <p class="step-text">Payment Confirm</p>
            </li>
        </ul>
    </div>
</div>