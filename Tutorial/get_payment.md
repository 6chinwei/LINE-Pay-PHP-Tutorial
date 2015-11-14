# LINE Pay 􏰀查看付款紀錄
使用 LINE Pay Get Payment API 可以取得已付款項目的付款/退款詳細資料。查看時必須指定 LINE Pay 用戶的付款交易編號(`transactionId`)或是商家訂單系統的訂單編號(`orderId`)，兩者擇一即可。

若要一次查看多筆付款記錄，可同時傳遞多個參數，最多可以􏰀同時查看 100 筆記錄。

> 若是要查看已授權但尚未付款的項目記錄，應使用「查看授權紀錄」而非「查看付款紀錄」

#### GetPayment API 規格  

項目 | 說明
---- | --- 
Method | GET
Required Request Header | `Content-Type:application/json; charset=UTF-8`<br>`X-LINE-ChannelId:{{channelId}}`<br>`X-LINE-ChannelSecret:{{channelSecretKey}}`
Sandbox 環境 API 地址 | `https://sandbox-api-pay.line.me/v2/payments`
Real 環境 API 地址 | `https://api-pay.line.me/v2/payments`

#### GetPayment API 請求的參數  

名稱 | 資料型別 | 說明
---- | ------- | ---
transactionId | Number | 由 LINE Pay 核發的交易編號，也就是用於付款或退款的交易編號
orderId | String | 商家訂單系統內的訂單編號

> 若要查看多筆可直接傳遞多組 `transactionId`/`orderId` 作為參數

#### GetPayment API 回應 (JSON 格式)

``` php
{
  "returnCode": "0000",        // 結果代碼，例如 `0000` 表示成功
  "returnMessage": "success",  // 結果訊息或失敗理由，例如 `商家驗證資訊錯誤`
  "info":[
    {
      "transactionId": ...,         // 交易編號
      "transactionDate": "...",     // 交易日期與時間
      "transactionType": "...",     // 交易類型，例如：付款是"PAYMENT"、退款是"PAYMENT_REFUND"、部分退款是"PARTIAL_REFUND"
      "payInfo":[
        {
          "method": "CREDIT_CARD",  // 使用的付款方式
          "amount": 10              // 交易金額
        } 
      ],
      "productName": "...",         // 訂單名稱
      "currency": "TWD",            // 貨幣
      "orderId": "...",             // 商家的訂單編號
      "refundList": [               // 若有退款的話，會回傳退款的紀錄
        { 
          "refundTransactionId": ...,  // 退款的交易編號
          "transactionType": "...",    // 交易類型，例如：部分退款是"PARTIAL_REFUND"
          "refundAmount": 10,          // 退款金額
        }
      ]
    }
  ]
}
```

#### 查看付款記錄程式碼範例  

``` php
<?php 
/* getPayment.php */

// 引用 Chinwei6/LinePay PHP Library
require_once("Chinwei6_LinePay.php");

$apiEndpoint   = "...";  // API 位置
$channelId     = "...";  // 通路ID
$channelSecret = "...";  // 通路密鑰

// 建立 Chinwei6\LinePay 物件
$LinePay = new Chinwei6\LinePay($apiEndpoint, $channelId, $channelSecret);

// 建立 getPayment API 所需要的參數
$params = [
    "transactionId" => ..., 
    "orderId"       => ['...', '...'], // 可用 Array 格式同時查看多筆記錄
];

// 發送 getPayment 請求
$result = $LinePay->checkPayment($params);

if($result['returnCode'] == '0000') {
// getPayment 請求成功!
}
```