<div class="container">
    <div class="navbar">
        <div class="navbar-header">
            <a class="navbar-toggle"></a>
            <a class="navbar-brand" href="index.php">LinePay API - PHP 測試工具 Beta</a>
        </div>
        <div class="navbar-content">
            <ul class="navbar-nav right">
                <li class="navbar-nav-item <?php echo isActive('index.php').' '.isActive('reserve.php').' '.isActive('confirm.php');  ?>">
                    <a class="navbar-nav-link" href="index.php">付款</a>
                </li>
                <li class="navbar-nav-item <?php echo isActive('refund.php'); ?>">
                    <a class="navbar-nav-link" href="refund.php">退款</a>
                </li>
                <li class="navbar-nav-item <?php echo isActive('record.php'); ?>">
                    <a class="navbar-nav-link" href="record.php">付款記錄查詢</a>
                </li>
                <li class="navbar-nav-item <?php echo isActive('about.php'); ?>">
                    <a class="navbar-nav-link" href="about.php">關於</a>
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
</div>