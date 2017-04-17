<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
$client=new SoapClient('http://n.sms.ir/ws/SendReceive.asmx?wsdl');
$params= array('userName'=>test,'password'=>test,'dailyScheduleSend'=>'');
print_r($client->SaveNewSchedulSendSms_Daily($params));
echo $client->SaveNewSchedulSendSms_Daily($params)->message;
?>
