<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<?php
$client=new SoapClient('http://n.sms.ir/ws/SendReceive.asmx?wsdl');
$params= array('userName'=>test,'password'=>test);
print_r($client->GetSendToBranchFilterConditions($params));
echo $client->GetSendToBranchFilterConditions($params)->message;
?>
