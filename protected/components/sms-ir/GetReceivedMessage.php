<?php

$Client = new SoapClient('http://n.sms.ir/ws/SendReceive.asmx?wsdl');
$Params= array('userName'=>test,'password'=>test,'fromDate'=>'2014-05-18T11:47:25','toDate'=>'2014-06-18T11:47:25');
print_r( $Client->GetReceivedMessages($Params));

?>