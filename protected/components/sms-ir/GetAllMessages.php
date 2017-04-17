<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
$client=new SoapClient('http://n.sms.ir/ws/SendReceive.asmx?wsdl');
$params= array('userName'=>test,'password'=>test,'fromDate'=>'2014-05-18T11:47:25','toDate'=>'2014-06-18T11:47:25');
print_r($client->GetAllMessages($params));
?>