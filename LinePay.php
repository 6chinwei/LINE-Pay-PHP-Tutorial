<?php
namespace Chinwei6 {
    class LinePay {
        protected $apiEndpoint;
        protected $headers;

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

        public function reserve($params = []) {
            $reserveParams = new LinePay\ReserveParams($params);

            return $this->postRequest($this->apiEndpoint . 'request', $reserveParams->getParams());
        }

        public function confirm($transactionId = null, $params = []) {
            if(is_null($transactionId))
                throw new \Exception('transactionId is required');

            $confirmParams = new LinePay\ConfirmParams($params);

            return $this->postRequest($this->apiEndpoint . $transactionId . '/confirm', $confirmParams->getParams());
        }

        /**
         * Send a post request by curl
         * @param  [type] $url        [description]
         * @param  array  $postFields [description]
         * @return [type]             [description]
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
    class Headers {
        protected $channelId;
        protected $channelSecret;

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

        public function getHeaders() {
            return [
                'Content-Type:application/json; charset=UTF-8',
                'X-LINE-ChannelId:' . $this->channelId,
                'X-LINE-ChannelSecret:' . $this->channelSecret,
            ];
        } 
    }

    abstract class Params {
        protected $requiredFields = [];
        protected $optionalFields = [];
        protected $params = [];

        protected function valitate($params) {
            foreach($this->requiredFields as $field)
            {
                if(!isset($params[$field])) {
                    throw new \Exception($field . ' is required in payment reserve');
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

        public function getParams() {
            return $this->params;
        }       
    }

    class ReserveParams extends Params {
        protected $requiredFields = [
            'currency',
            'confirmUrl',
            'orderId',
            'productName',
            'productImageUrl',
            'amount',
        ];

        protected $optionalFields = [
            'confirmUrlType',
            'checkConfirmUrlBrow',
            'capture',
        ]; 
    }

    class ConfirmParams extends Params {
        protected $requiredFields = [
            'amount' => '',
            'currency' => '',
        ];
    }
}