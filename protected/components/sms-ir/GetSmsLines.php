<?php

$client = new SoapClient("http://n.sms.ir/ws/SendReceive.asmx?wsdl");
$params= array("userName"=>test,"password"=>test));
print_r( $client->GetSmsLines($params);  
echo $client->GetSmsLines($params)->message;
?>