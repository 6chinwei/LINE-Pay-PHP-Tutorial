<?php

namespace Chinwei6;

class LinePayAPI {
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

        $headers = new LinePayAPIHeaders($channelId, $channelSecret);
        $this->headers = $headers->getHeaders();
    }

    public function reserve($params = []) {
        $reserveParams = new LinePayAPIReserveParams($params);

        return $this->postRequest($this->apiEndpoint . 'request', $reserveParams->getParams());
    }

    public function confirm($transactionId = null, $params = []) {
        if(is_null($transactionId))
            throw new \Exception('transactionId is required');

        $confirmParams = new LinePayAPIConfirmParams($params);

        return $this->postRequest($this->apiEndpoint . $transactionId . '/confirm', $confirmParams->getParams());
    }

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

class LinePayAPIHeaders {
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

class LinePayAPIReserveParams {
    protected $params = [
        // Required    
        'currency' => '',
        'confirmUrl' => '',
        'orderId' => '',
        'productName' => '',
        'productImageUrl' => '',
        'amount' => '',

        // Optional
        'confirmUrlType' => '',
        'checkConfirmUrlBrow' => '',
        'capture' => '',
    ];
    
    public function __construct($params)
    {
        if( !isset($params['currency']) ) {
            throw new \Exception('currency is required in payment reserve');
        }
        else if( !isset($params['confirmUrl']) ) {
            throw new \Exception('confirmUrl is required in payment reserve');
        }
        else if( !isset($params['orderId']) ) {
            throw new \Exception('orderId is required in payment reserve');
        }
        else if( !isset($params['productName']) ) {
            throw new \Exception('productName is required in payment reserve');
        }
        else if( !isset($params['productImageUrl']) ) {
            throw new \Exception('productImageUrl is required in payment reserve');
        }
        else if( !isset($params['amount']) ) {
            throw new \Exception('amount is required in payment reserve');
        }
        else {
            $this->params = $params;
        }
    }   

    public function getParams() {
        // 應該要確保所有欄位沒有多也沒有少！
        return $this->params;
    } 
}

class LinePayAPIConfirmParams {
    protected $params = [
        // Required    
        'amount' => '',
        'currency' => '',
    ];

    public function __construct($params)
    {
        if( !isset($params['amount']) ) {
            throw new \Exception('amount is required in payment confirm');
        }
        else if( !isset($params['currency']) ) {
            throw new \Exception('currency is required in payment confirm');
        }
        else {
            $this->params = $params;
        }
    }   

    public function getParams() {
        // 應該要確保所有欄位沒有多也沒有少！
        return $this->params;
    } 
}