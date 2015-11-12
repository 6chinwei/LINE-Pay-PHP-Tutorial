<div class="container">
    <ul class="step">
        <li class="step-item <?php echo isStepActive('index.php'); ?>">
            <h6 class="step-title">1. 訂單確認與付款</h6>
            <p class="step-text">Payment Reserve</p>
        </li>
        <li class="step-item <?php echo isStepActive('reserve.php'); ?>">
            <h6 class="step-title">2. 身分驗證</h6>
            <p class="step-text">Authentication</p>
        </li>
        <li class="step-item <?php echo isStepActive('confirm.php'); ?>">
            <h6 class="step-title">3. 付款結果</h6>
            <p class="step-text">Payment Confirm</p>
        </li>
    </ul>
</div>
<?php 
    function isStepActive($fileName) {
        if($fileName == basename($_SERVER["PHP_SELF"]))
            return 'active';
    }
?>