<?php
$client=new SoapClient('http://n.sms.ir/ws/SendReceive.asmx?wsdl');
$params= array('userName'=>test,'password'=>test,'weeklyScheduleSend'=>'');
print_r($client->SaveNewSchedulSendSms_Weekly($params));
echo $client->SaveNewSchedulSendSms_Weekly($params)->message;

?>