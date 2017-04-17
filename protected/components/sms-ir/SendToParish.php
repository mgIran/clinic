<?php
$client=new SoapClient('http://n.sms.ir/ws/SendReceive.asmx?wsdl');
$params= array('userName'=>test,'password'=>test,'smsLineID'=>'YOURLINENUMBER','sendCount'=>'1','sendMethodID'=>'1','startAt'=>'1','fromNumber'=>'0test',
'toNumber'=>'0test','filterID'=>'','filterValue'=>'','messageBody'=>'test','parishID'=>'','sendSince'=>'','isFlash'=>'0');
print_r($client->SendToParish($params));
echo $client->SendToParish($params)->message;
?>