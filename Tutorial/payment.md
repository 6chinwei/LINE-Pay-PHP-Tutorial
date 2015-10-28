# LINE Pay 一般付款的教學與範例
此教學以在 Sandbox 進行一般付款（付款授權與請款同時進行）為範例  

## 目錄
* [LINE Pay 付款流程說明](#line-pay-%E4%BB%98%E6%AC%BE%E6%B5%81%E7%A8%8B%E8%AA%AA%E6%98%8E)
  * 角色
  * 流程
* [PHP 付款功能開發範例](#php-%E4%BB%98%E6%AC%BE%E5%8A%9F%E8%83%BD%E9%96%8B%E7%99%BC%E7%AF%84%E4%BE%8B)
  * LINE Pay 支付功能與頁面轉換流程圖
  * 步驟

## LINE Pay 付款流程說明：
以下流程是以**電腦版的網頁介面來進行「付款授權與請款同時進行」一般付款**為例：
> LINE Pay 的一般付款包含兩種：「同時進行」付款授權與請款，或「分開進行」

#### 角色
在一次的付款的流程中，可以想像有三種角色參與其中：
* 使用者
* 商家的購物網站
* LINE Pay 伺服器  

#### 流程
1. 使用者在購物網站上選擇使用 LINE Pay 付款（假設訂單已建立好）
2. 購物網站發送 Reserve 請求給 LINE Pay 伺服器，參數包含訂單的名稱、金額、確認付款頁面的位置(confirmUrl)等
3. LINE Pay 伺服器成功收到 Reserve 請求後，會回傳交易編號與支付頁面的位址(paymentUrl)給商家的購物網站
4. 購物網站把使用者導向到 paymentUrl 的頁面（此頁面由 LINE 提供）
5. 使用者用 LINE 帳號登入與確認付款
6. 若使用者確認付款後，會再次被導向確認付款頁面的位置（即第 2 步驟內的 confirmUrl）  
7. 購物網站發送 Confirm 請求給 LINE Pay 伺服器，向 LINE Pay 確認使用者是否已經進行付款  
8. LINE Pay 伺服器回傳付款結果
  
## PHP 付款功能開發範例
* PHP 版本: v5.5.9
* LINE Aay API 版本: v2
* [Sandbox 測試工具原始碼（PHP 網頁介面）](../Example)
* [PHP Libary (Chinwei6/LinePay) for LINE Pay API](../Chinwei6_LinePay.php) (使用 php_curl 實作)

### LINE Pay 支付功能與頁面轉換流程圖
依照上面的 LINE Pay 付款流程，我們可以把購物網站的 LINE Pay 支付功能流程設計為：  
![流程圖](./img/flow.png)

`reserve.php` 為負責接收訂單詳情資訊的頁面，並發送 Reserve 請求給 LINE Pay 伺服器  
`confirm.php` 為使用者支付完成導向進來的頁面，並發送 Confirm 請求給 LINE Pay 伺服器


### 流程步驟與說明
1. 在購物網站的支付頁面（或是訂單確認頁面）放上「使用 LINE Pay 支付」的按鈕，點擊可使用 `reserve.php` 發送訂單資訊給 LINE Pay 伺服器。

  > 按鈕樣式可參考官方[LOGO 使用指南](https://pay.line.me/tw/intro/logoUsageGuide)。  
  > `reserve.php` 的功能其實只是要發送請求，可以不用 UI 畫面。

2. 在 `reserve.php` 將訂單的資訊，作為參數發送給 LINE Pay 伺服器，參數包含訂單的名稱、金額、確認付款頁面的位置(`confirmUrl`)等。 Reserve API 的規格與必要參數如下：

  #### Reserve API 規格  

  項目 | 說明
  ---- | --- 
  Method | POST
  Required Request Header | `Content-Type:application/json; charset=UTF-8`<br>`X-LINE-ChannelId:{{channelId}}`<br>`X-LINE-ChannelSecret:{{channelSecretKey}}`
  Sandbox 環境 API 地址 | https://sandbox-api-pay.line.me/v2/payments/request
  Real 環境 API 地址 | https://api-pay.line.me/v2/payments/request

    
  #### Reserve API 請求的必要參數  

  名稱 | 資料型別 | 說明
  ---- | ------- | ---
  productName | String | 訂單名稱，例如：`商品XXX..等三項`
  productImageUrl | String | 產品影像 URL，顯示於付款畫面上的影像
  amount | Number | 付款金額
  currency | String | 付款貨幣 (ISO 4217)，例如 `TWD`、`JPY`、`USD`
  confirmUrl | String | 買家在 LINE Pay 選擇付款方式並輸入密碼後，被重新導向到商家的 URL
  orderId | String | 商家與該筆付款請求對應的訂單編號（這是商家自行管理的唯一編號）

  > 還有其他的非必要參數請參考官方 API 說明文件

  #### reserve.php 程式碼範例  

  ``` php
  <?php 
  /* reserve.php */

  // 引用 Chinwei6/LinePay PHP Libary
  require_once("Chinwei6_LinePay.php");

  $apiEndpoint   = "...";  // API 位置
  $channelId     = "...";  // 通路ID
  $channelSecret = "...";  // 通路密鑰

  // 建立 Chinwei6\LinePay 物件
  $LinePay = new Chinwei6\LinePay($apiEndpoint, $channelId, $channelSecret);

  // 建立訂單資訊作為 POST 的參數
  $params = [
      "productName"     => "...",
      "productImageUrl" => "...",
      "amount"          => "...",
      "currency"        => "...",
      "confirmUrl"      => "...",
      "orderId"         => "...",
      "confirmUrlType"  => "...",
  ];

  // 發送 reserve 請求，reserve() 回傳的結果為 Associative Array 格式
  $result = $LinePay->reserve($params);

  if($result['returnCode'] == '0000') {
    // Reserve 請求成功!
    $paymentUrl = $result['info']['paymentUrl']['web'];
  }
  ```
3. 若請求成功，LINE Pay 伺服器會回傳：

  #### Reserve API 回應 (JSON 格式)

  ```
  {
    "returnCode": "0000",          // 結果代碼，例如 `0000` 表示成功
    "returnMessage": "success",    // 結果訊息或失敗理由，例如 `商家驗證資訊錯誤`
    "info": {
      "paymentUrl": {             
        "web": "...",              // 付款請求後所前往的網頁 URL (LINE Pay 等待付款畫面的 URL)
        "app": "..."               // 前往付款畫面的應用程式 URL
      },
      "transactionId": ...,        // 交易編號 (19 位數)
      "paymentAccessToken": "..."  // 在 LINE Pay app 輸入的代碼（在本範例未使用到）
    }
  }
  ```

4. 在 `reserve.php` 收到請求成功的回應後，就可以把使用者導向到 `paymentUrl` 的頁面，此頁面為 LINE 提供的登入與支付頁面，如圖：
  ![04](./img/04.png)

5. 使用者登入 LINE 的帳號後，會出現 LINE Pay 的彈出視窗，確認商品名稱與金額無誤後，點擊 PAY NOW 進行付款。
  > 如果瀏覽器有封鎖此頁面的彈出視窗，可先解除對此 LINE Pay 網域的封鎖  

  <img src="./img/05.png" width="360px"/>

6. 付款完成後，剛剛開啟 `paymentUrl` 的頁面會導向到 `confirmUrl`，`confirmUrl` 就是在第 2 步發出 Reserve 請求時所傳遞給 LINE Pay 伺服器的參數之一。在本範例中，也就是把使用者導向至 `confirm.php` 頁面。

7. 此時在 `confirm.php` 再發送一個 Confirm 請求給 LINE Pay 伺服器，來確認使用者是否已經完成付款。Confirm API 的規格與必要參數如下：
  
  #### Confirm API 規格  

  項目 | 說明
  ---- | --- 
  Method | POST
  Required Request Header | `Content-Type:application/json; charset=UTF-8`<br>`X-LINE-ChannelId:{{channelId}}`<br>`X-LINE-ChannelSecret:{{channelSecretKey}}`
  Sandbox 環境 API 地址 | https://sandbox-api-pay.line.me/v2/payments/{{transactionId}}/confirm
  Real 環境 API 地址 | https://api-pay.line.me/v2/payments/{{transactionId}}/confirm

  #### Confirm API 請求的必要參數  

  名稱 | 資料型別 | 說明
  ---- | ------- | ---
  amount | Number | 付款金額
  currency | String | 付款貨幣 (ISO 4217)，例如 `TWD`、`JPY`、`USD`

  #### confirm.php 程式碼範例  

  ``` php
  <?php 
  /* confirm.php */

  // 引用 Chinwei6/LinePay PHP Libary
  require_once("Chinwei6_LinePay.php");

  $apiEndpoint   = "...";  // API 位置
  $channelId     = "...";  // 通路ID
  $channelSecret = "...";  // 通路密鑰

  // 建立 Chinwei6\LinePay 物件
  $LinePay = new Chinwei6\LinePay($apiEndpoint, $channelId, $channelSecret);

  // 建立 confirm API 所需要的參數
  $params = [
      "amount"   => "...",
      "currency" => "...",
  ];

  // transactionId 來自之前 Reserve API 請求的回應
  $transactionId = "...";

  // 發送 confirm 請求，confirm() 回傳的結果為 Associative Array 格式
  $result = $LinePay->confirm($transactionId, $params);

  if($result['returnCode'] == '0000') {
    // Confirm 請求成功!
  }
  ```
  
8. 若請求成功，LINE Pay 伺服器會回傳：

  #### Confirm API 回應 (JSON 格式)

  ```
  {
    "returnCode": "0000",        // 結果代碼，例如 `0000` 表示成功
    "returnMessage": "success",  // 結果訊息或失敗理由，例如 `商家驗證資訊錯誤`
    "info": {
      "transactionId": ...,      // 付款 reserve 後,做為結果所收到的交易編號
      "orderId": "...",          // 商家在付款reserve 時傳送的訂單編號
      "payInfo": [
        {
          "method": "...",       // 使用的付款方式 (信用卡: CREDIT_CARD、餘額: BALANCE,折扣: DISCOUNT)
          "amount": ...          // 付款金額
        }
      ]
    }
  }
  ```

#### 其他補充