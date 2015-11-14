<?php
/**
 * Chinwei6\LinePay v20151112 BETA - PHP Library for LINE Pay API
 * by 6chinwei
 */
namespace Chinwei6 {
    class LinePay {
        protected $apiEndpoint;
        protected $headers;

        /**
         * Chinwei6\LinePay Constructer
         * @param [String] $apiEndpoint
         * @param [String] $channelId   
         * @param [String] $channelSecret 
         */
        public function __construct($apiEndpoint = null, $channelId = null, $channelSecret = null)
        {
            if(is_null($apiEndpoint)) {
                throw new \Exception('API endpoint is required.');
            }
            else {
                $this->apiEndpoint = $apiEndpoint;
            }

            // Generate header content by channelId & channelSecret
            $this->headers = self::getRequestHeader($channelId, $channelSecret);
        }

        /**
         * Reserve API
         * @param  [Array]  $params  Post parameters
         * @return [Array]           Result from LINE Pay server (JSON)
         */
        public function reserve($params = []) {
            $reserveParams = new LinePay\ReserveParams($params);

            return $this->request('POST', 
                                  'request', 
                                  $reserveParams->getParams());
        }

        /**
         * Confirm API
         * @param  [String] $transactionId 
         * @param  [Array]  $params         Post parameters
         * @return [Array]                  Result from LINE Pay server (JSON)
         */
        public function confirm($transactionId = null, $params = []) {
            if(is_null($transactionId))
                throw new \Exception('transactionId is required.');
            else if(!is_string($transactionId))
                throw new \Exception('transactionId must be a string.');

            $confirmParams = new LinePay\ConfirmParams($params);

            return $this->request('POST', 
                                  $transactionId . '/confirm', 
                                  $confirmParams->getParams());
        }

        /**
         * Check payment API
         * @param  [Array]  $params  GET parameters
         * @return [Array]           Result from LINE Pay server (JSON)
         */
        public function checkPayment($params = []) {
            $checkPaymentParams = new LinePay\CheckPaymentParams($params);

            return $this->request('GET', 
                                  '', 
                                  $checkPaymentParams->getParams());
        }

        /**
         * Refund API
         * @param  [String] $transactionId 
         * @param  [Array]  $params         Post parameters
         * @return [Array]                  Result from LINE Pay server (JSON)
         */
        public function refund($transactionId = null, $params = []) {
            if(is_null($transactionId))
                throw new \Exception('transactionId is required.');
            else if(!is_string($transactionId))
                throw new \Exception('transactionId must be a string.');

            $refundParams = new LinePay\RefundParams($params);

            return $this->request('POST', 
                                  $transactionId . '/refund', 
                                  $refundParams->getParams());
        }

        /**
         * Private function: send request by php_curl
         * @param  [String] $method      Request method: 'POST', 'GET'
         * @param  [String] $relativeUrl Target API url path
         * @param  [Array]  $params      Request parameters
         * @return [Array]               Result by the request
         */
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

        /**
         * Static function: Generate header content by channelId & channelSecret
         * @param  [String] $channelId     
         * @param  [String] $channelSecret 
         * @return [Array]                 Header content for CURLOPT_HTTPHEADER
         */
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
     * Params Class (Abstract class)
     */
    abstract class Params {
        protected $requiredFields = [];
        protected $params = [];

        /**
         * Validate the required field of the parameter
         * @param  [Array] $params  
         */
        protected function validate($params) {
            foreach($this->requiredFields as $field)
            {
                if(!isset($params[$field]) || empty($params[$field])) {
                    throw new \Exception('Parameter "' . $field . '" is required.');
                }
            }

            $this->params = $params;
        }

        public function __construct($params)
        {
            $this->validate($params);
        }   

        /**
         * Return a validated parameter array 
         * @return [Array] 
         */
        public function getParams() {
            return $this->params;
        }       
    }

    /**
     * ReserveParams Class
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
     */
    class CheckPaymentParams extends Params {
        protected $requiredFields = []; 

        protected function validate($params) {
            parent::validate($params);

            if( empty($this->params['orderId']) && empty($this->params['transactionId']) )
                throw new \Exception('Parameter "orderId" or "transactionId" is required.');
        }
    }

    /**
     * RefundParams Class
     */
    class RefundParams extends Params {
        protected $requiredFields = []; 
    }
}