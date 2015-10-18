<?php

function hash_call($nvpreq) {
    //declaring of global variables
    global $API_Endpoint, $channelId, $channelSecret, $useGet;

    //setting the curl parameters.
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);

    //turning off the server and peer verification(TrustManager Concept).
    //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    
    curl_setopt($ch, CURLOPT_SSLVERSION, 'CURL_SSLVERSION_TLSv1');
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if (!isset($useGet)) { // determine using GET/POST to call API, return TRUE when $useGet is not declared, setting to call API via POST
        curl_setopt($ch, CURLOPT_POST, 1);
        //setting the nvpreq as POST FIELD to curl if nvpreq is not empty
        if (!empty($nvpreq)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($nvpreq));
        }
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type:application/json; charset=UTF-8',
        'X-LINE-ChannelId:'.$channelId,
        'X-LINE-ChannelSecret:'.$channelSecret
    ));
    //getting response from server
    $response = curl_exec($ch);

    $_SESSION['nvpReqArray'] = $response;

    if (curl_errno($ch)) {
        // moving to display page to display curl errors
        $_SESSION['curl_error_no'] = curl_errno($ch);
        $_SESSION['curl_error_msg'] = curl_error($ch);
        print_r(curl_error($ch));
    } else {
        echo 'REQUEST: ';
        var_dump(curl_getinfo($ch, CURLINFO_HEADER_OUT));
        echo 'PARAMS: ';
        var_dump($nvpreq);
        echo 'RESPONSE: ';
        var_dump($response);
        //closing the curl
        curl_close($ch);
    }
    return $response;
}

function setup($account, $environ) {
    switch ($account) {
        case 'bill.ctbc':
            {
                define('CHANNELID', '1440394723');
                define('CHANNELSECRET', '3c9be69581b54e10a4d5c7ab85092a86');
            }
            break;
        case 'bill.cub':
            {
                define('CHANNELID', '1440378294');
                define('CHANNELSECRET', 'a831f7189daf35e0282e3f2455494379');
            }
            break;
        case 'bill.noncub':
            {
                define('CHANNELID', '1440367794');
                define('CHANNELSECRET', '30b9f4d6b06e5c7264fc8d3255f83a92');
            }
            break;
        case 'bill.test':
            {
                switch ($environ) {
                    case 'sandbox' : // sandbox
                        {
                            define('CHANNELID', '1437558894');
                            define('CHANNELSECRET', 'bbf2805a78d23d602092fdb590f99740');
                        }
                        break;
                    case 'test' : // 測試環境
                        {
                            define('CHANNELID', '1437558894');
                            define('CHANNELSECRET', 'bbf2805a78d23d602092fdb590f99740');
                        }
                        break;
                    case 'real' : // 正式環境
                        {
                            define('CHANNELID', '1437558592');
                            define('CHANNELSECRET', 'b0b191eef5d335a54aab369f70a6e9e6');
                        }
                        break;
                    default : // 未選擇
                        echo "未選擇執行環境! </br>";
                        break;
                }
            }
            break;
        default:
            echo "未選擇後台帳號! </br>";
            break;
    }
}

?>
