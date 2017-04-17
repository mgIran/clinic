<?php
$client=new SoapClient('http://n.sms.ir/ws/SendReceive.asmx?wsdl');
$params= array('userName'=>test,'password'=>test,
'batchKey'=>'11111111-1111-1111-1111-111111111111','requestedPageNumber'=>'100','rowsPerPage'=>'100','countOfAll'=>'5','sendDateTime'=>'2014-05-18T11:47:25');
print_r($client->GetSentMessageStatus($params));
echo $client->GetSentMessageStatus($params)->message;
?>