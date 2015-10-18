<?php


$arr=array("productName"=>"abc", 
          //NEED ADD IMAGE URL
          "productImageUrl"=>"http://www.viktorlin.com/ayung/img/images.jpeg", 
          "amount"=> "20" , 
          "currency"=>"TWD", 
          //NEED ADD RETURN URL
          "confirmUrl"=>"https://www.viktorlin.com/ayung/", 
          "orderId"=>"123321",
          // "oneTimeKey"=>"372439301659",
          // "confirmUrlType"=>"SERVER",
          );

$headers = array(
    'Content-Type:application/json; charset=UTF-8',   
    'X-LINE-ChannelId:1437558894',
    'X-LINE-ChannelSecret:bbf2805a78d23d602092fdb590f99740',
);
//API_ENDPOINT: https://sandbox-api-pay.line.me/v2/payments/request
$ch = curl_init();     
curl_setopt($ch, CURLOPT_URL,"https://sandbox-api-pay.line.me/v2/payments/request");
curl_setopt($ch, CURLOPT_POST, true); 
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr));   
curl_setopt($ch, CURLOPT_SSLVERSION, 'CURL_SSLVERSION_TLSv1');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);     

var_dump(curl_getinfo($ch));
$result = curl_exec($ch);
 //echo $data_header= json_encode($ch)
curl_close($ch);
echo 'ChannelId: 1437558894';
echo '<pre>'.$result.'</pre>';


?>







        