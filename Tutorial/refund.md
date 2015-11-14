# LINE Pay 退款
使用 LINE Pay Refund API 可以將已付款完成的項目進行退款。退款時必須指定 LINE Pay 用戶的付款交易編號(`transactionId`)。除此之外，退款時可以指定退款的金額，**若未指定退款金額則視為全額退款**。

若退款成功， LIEN Pay 伺服器會回傳新的交易編號`refundTransactionId`（19 位數），以及退款的交易日期/時間`refundTransactionDate`（格式為 `2014-01-01T06:17:41Z`）

> 若是要取消已授權但未付款的項目，應使用「授權作廢」而非「退款」

#### Refund API 規格  

項目 | 說明
---- | --- 
Method | POST
Required Request Header | `Content-Type:application/json; charset=UTF-8`<br>`X-LINE-ChannelId:{{channelId}}`<br>`X-LINE-ChannelSecret:{{channelSecretKey}}`
Sandbox 環境 API 地址 | `https://sandbox-api-pay.line.me/v2/payments/{{transactionId}}/refund`
Real 環境 API 地址 | `https://api-pay.line.me/v2/payments/{{transactionId}}/refund`


#### Refund API 請求的參數  

名稱 | 資料型別 | 說明
---- | ------- | ---
refundAmount | Number | 退款金額。非必要，如果未傳遞此參數，則全額退款


#### Refund API 回應 (JSON 格式)

``` php
{
  "returnCode": "0000",        // 結果代碼，例如 `0000` 表示成功
  "returnMessage": "success",  // 結果訊息或失敗理由，例如 `商家驗證資訊錯誤`
  "info": {
    "refundTransactionId": ...,      // 退款的交易編號 (新核發的編號 - 19 位數)
    "refundTransactionDate": "...",  // 退款的交易日期與時間 (ISO 8601)
  }
}
```

#### 退款程式碼範例  

``` php
<?php 
/* refund.php */

// 引用 Chinwei6/LinePay PHP Library
require_once("Chinwei6_LinePay.php");

$apiEndpoint   = "...";  // API 位置
$channelId     = "...";  // 通路ID
$channelSecret = "...";  // 通路密鑰

// 建立 Chinwei6\LinePay 物件
$LinePay = new Chinwei6\LinePay($apiEndpoint, $channelId, $channelSecret);

// 建立 refund API 所需要的參數
$params = [
  "refundAmount" => ...,
];

// transactionId，一般來說，應來自商家資料庫內的訂單記錄
$transactionId = "...";

// 發送 refund 請求
$result = $LinePay->refund($transactionId, $params);

if($result['returnCode'] == '0000') {
// Refund 請求成功!
}
```