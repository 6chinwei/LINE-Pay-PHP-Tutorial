<?php
/**
 * Chinwei6\LinePay v20151101 BETA - PHP Libary for LINE Pay API
 * by 6chinwei
 */
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

            $this->headers = self::getRequestHeader($channelId, $channelSecret);
        }

        /**
         * 發送 Reserve 請求
         * @param  [Array]  $params 訂單參數
         * @return [Array]          LINE Pay 伺服器回傳的結果(JSON)
         */
        public function reserve($params = []) {
            $reserveParams = new LinePay\ReserveParams($params);

            return $this->request('POST', 
                                  'request', 
                                  $reserveParams->getParams());
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

            return $this->request('POST', 
                                  $transactionId . '/confirm', 
                                  $confirmParams->getParams());
        }

        /**
         * 發送 Check payment 請求
         */
        public function checkPayment($params = []) {
            $checkPaymentParams = new LinePay\CheckPaymentParams($params);

            return $this->request('GET', 
                                  '', 
                                  $checkPaymentParams->getParams());
        }

        protected function request($method = 'GET', $relativeUrl = null, $params = []) {
            if(is_null($relativeUrl)) {
                throw new \Exception('API endpoint is required.');
            }

            $ch = curl_init();

            if ($method === 'GET') {
                $relativeUrl .= '?'.http_build_query($params);
            }
            else if ($method === 'POST') {
                curl_setopt($ch, CURLOPT_POST, true); 
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
            }

            curl_setopt($ch, CURLOPT_URL, $this->apiEndpoint . $relativeUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
            curl_setopt($ch, CURLOPT_SSLVERSION, 'CURL_SSLVERSION_TLSv1');

            if (curl_errno($ch)) {
                throw new \Exception(curl_error($ch));
            }

            $response = json_decode(curl_exec($ch), true);

            curl_close($ch);

            return $response;
        }

        protected static function getRequestHeader($channelId = null, $channelSecret = null) {
            if( is_null($channelId) || is_null($channelSecret)) {
                throw new \Exception('Header info are required.');
            }
            
            return [
                'Content-Type:application/json; charset=UTF-8',
                'X-LINE-ChannelId:' . $channelId,
                'X-LINE-ChannelSecret:' . $channelSecret,
            ];   
        }
    }
}

namespace Chinwei6\LinePay {
    /**
     * Params Class (抽象類別)
     */
    abstract class Params {
        protected $requiredFields = [];
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
                    throw new \Exception('Parameter "' . $field . '" is required.');
                }
            }

            $this->params = $params;
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
     * Reserve 請求的參數
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
    }

    /**
     * ConfirmParams Class
     * Confirm 請求的參數
     */
    class ConfirmParams extends Params {
        // 必要欄位
        protected $requiredFields = [
            'amount',
            'currency',
        ];
    }

    /**
     * CheckPaymentsParams Class
     * Check Payment 請求的參數
     */
    class CheckPaymentParams extends Params {
        protected $requiredFields = []; 

        protected function valitate($params) {
            parent::valitate($params);

            if( !isset($this->params['orderId']) && !isset($this->params['transactionId']) )
                throw new \Exception('Parameter "orderId" or "transactionId" is required.');
        }
    }
}