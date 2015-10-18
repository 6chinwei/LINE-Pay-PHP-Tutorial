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

        $ch = curl_init();     
        curl_setopt($ch, CURLOPT_URL, $this->apiEndpoint);
        curl_setopt($ch, CURLOPT_POST, true); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($reserveParams->getParams()));   
        curl_setopt($ch, CURLOPT_SSLVERSION, 'CURL_SSLVERSION_TLSv1');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true); 

        $response = json_decode(curl_exec($ch));

        if (curl_errno($ch)) {
            throw new \Exception(curl_error($ch));
        } else {
            echo json_encode($response, JSON_PRETTY_PRINT);
        }

        curl_close($ch);
    }

    public function confirm($params = []) {
        $confirmParams = new LinePayAPIConfirmParams($params);
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
        return $this->params;
    } 
}