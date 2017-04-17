<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<?php
$client=new SoapClient('http://n.sms.ir/ws/SendReceive.asmx?wsdl');
$params= array('userName'=>test,'password'=>test,'fromDate'=>'2014-04-18T11:47:25','toDate'=>'2014-07-20T11:47:25');
print_r($client->GetSentMessages($params));
?>