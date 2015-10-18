<?php


$arr=array(
          "amount"=> "20" , 
          "currency"=>"TWD", 
          );

$headers = array(
    'Content-Type:application/json; charset=UTF-8',   
    'X-LINE-ChannelId:1440378294',
    'X-LINE-ChannelSecret:a831f7189daf35e0282e3f2455494379',
);
//API_ENDPOINT: https://sandbox-api-pay.line.me/v2/payments/request
$ch = curl_init();     
curl_setopt($ch, CURLOPT_URL,"https://api-pay.line.me/v2/payments/2015091710452250210/confirm");
curl_setopt($ch, CURLOPT_POST, true); 
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr));   
curl_setopt($ch, CURLOPT_SSLVERSION, 'CURL_SSLVERSION_TLSv1');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);     

$result = curl_exec($ch);
 //echo $data_header= json_encode($ch)
curl_close($ch);
echo $result;


?>







        