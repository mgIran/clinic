<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
$client=new SoapClient('http://n.sms.ir/ws/SendReceive.asmx?wsdl');
$params= array('userName'=>test,'password'=>test,'monthlyScheduleSend'=>'');
print_r($client->SaveNewSchedulSendSms_Monthly($params));
echo $client->SaveNewSchedulSendSms_Monthly($params)->message;
?>