<?php
namespace Chinwei6 {
    class LinePay {
        protected $apiEndpoint;
        protected $headers;

        /**
         * Chinwei6\LinePay Constructer
         * @param [String] $apiEndpoint   API 位置
         * @param [String] $channelId     通路ID
         * @param [String] $channelSecret 通路密鑰
         */
        public function __construct($apiEndpoint = null, $channelId = null, $channelSecret = null)
        {
            if(is_null($apiEndpoint)) {
                throw new \Exception('API endpoint is required');
            }
            else {
                $this->apiEndpoint = $apiEndpoint;
            }

            $headers = new LinePay\Headers($channelId, $channelSecret);
            $this->headers = $headers->getHeaders();
        }

        /**
         * 發送 Reserve 請求
         * @param  [Array]  $params 訂單參數
         * @return [Array]          LINE Pay 伺服器回傳的結果(JSON)
         */
        public function reserve($params = []) {
            $reserveParams = new LinePay\ReserveParams($params);

            return $this->postRequest($this->apiEndpoint . 'request', $reserveParams->getParams());
        }

        /**
         * 發送 Confirm 請求
         * @param  [String] $transactionId 交易編號
         * @param  [Array]  $params        參數，即金額與幣別
         * @return [Array]                 LINE Pay 伺服器回傳的結果(JSON)
         */
        public function confirm($transactionId = null, $params = []) {
            if(is_null($transactionId))
                throw new \Exception('transactionId is required');

            $confirmParams = new LinePay\ConfirmParams($params);

            return $this->postRequest($this->apiEndpoint . $transactionId . '/confirm', $confirmParams->getParams());
        }

        /**
         * 使用 CURL 發送 POST 請求
         * @param  [String] $url        POST 請求的 URL
         * @param  [Array]  $postFields POST 請求的參數
         * @return [Array]              收到的回應，回傳 Associative Array 格式
         */
        protected function postRequest($url = null, $postFields = []) {
            $ch = curl_init();     
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true); 
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postFields));   
            curl_setopt($ch, CURLOPT_SSLVERSION, 'CURL_SSLVERSION_TLSv1');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);

            $response = json_decode(curl_exec($ch), true);

            if (curl_errno($ch)) {
                throw new \Exception(curl_error($ch));
            } else {
                return $response;
            }

            curl_close($ch);
        }
    }
}

namespace Chinwei6\LinePay {
    /**
     * Headers Class
     */
    class Headers {
        protected $channelId;
        protected $channelSecret;

        /**
         * 建立 Chinwei6\LinePay\Header 物件，並檢查 channelId 和 channelSecret 是否正確
         * @param [type] $channelId     [description]
         * @param [type] $channelSecret [description]
         */
        public function __construct($channelId = null, $channelSecret = null)
        {
            if( is_null($channelId) || is_null($channelSecret)) {
                throw new \Exception('Header info are required');
            }
            else {
                $this->channelId     = $channelId;
                $this->channelSecret = $channelSecret;
            }
        }   

        /**
         * 回傳 Header 內容，包含 channelId 和 channelSecret
         * @return [Array] Header 內容，提供給 CURLOPT_HTTPHEADER 設定 header
         */
        public function getHeaders() {
            return [
                'Content-Type:application/json; charset=UTF-8',
                'X-LINE-ChannelId:' . $this->channelId,
                'X-LINE-ChannelSecret:' . $this->channelSecret,
            ];
        } 
    }

    /**
     * Params Class (抽象類別)
     */
    abstract class Params {
        protected $requiredFields = [];
        protected $optionalFields = [];
        protected $params = [];

        /**
         * 檢查參數欄位名稱是否符合 + 檢查必要參數是否存在
         * @param  [Array] $params 參數
         * @return None         
         */
        protected function valitate($params) {
            foreach($this->requiredFields as $field)
            {
                // 檢查必要參數
                if(!isset($params[$field])) {
                    throw new \Exception($field . ' is required.');
                }
                else {
                    $this->params[$field] = $params[$field];
                }
            }

            foreach($this->optionalFields as $field)
            {
                if(isset($params[$field])) {
                    $this->params[$field] = $params[$field];
                }
            }
        }

        public function __construct($params)
        {
            $this->valitate($params);
        }   

        /**
         * 回傳檢查過的參數陣列
         * @return [Array] 參數陣列
         */
        public function getParams() {
            return $this->params;
        }       
    }

    /**
     * ReserveParams Class
     * 發送 Reserve 請求的參數
     */
    class ReserveParams extends Params {
        // 必要欄位
        protected $requiredFields = [
            'currency',
            'confirmUrl',
            'orderId',
            'productName',
            'productImageUrl',
            'amount',
        ];

        // 非必要欄位
        protected $optionalFields = [
            'confirmUrlType',
            'checkConfirmUrlBrow',
            'capture',
        ]; 
    }

    /**
     * ConfirmParams Class
     * 發送 Confirm 請求的參數
     */
    class ConfirmParams extends Params {
        // 必要欄位
        protected $requiredFields = [
            'amount' => '',
            'currency' => '',
        ];
    }
}