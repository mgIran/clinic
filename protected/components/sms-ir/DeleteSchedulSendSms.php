<?php
$client=new SoapClient('http://n.sms.ir/ws/SendReceive.asmx?wsdl');
$params= array('userName'=>test,'password'=>test,'schedulSendSmsID'=>'1');
print_r($client->DeleteSchedulSendSms($params));
echo $client->DeleteSchedulSendSms($params)->message;
?>