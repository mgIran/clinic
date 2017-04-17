

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
﻿<?php

$userName=$_POST ['userName'];
$password=$_POST ['password'];
$lineNumber=$_POST ['lineNumber'];
$to=$_POST ['Receiver'];
$text=$_POST ['messageBody'];

date_default_timezone_set('Asia/Tehran');
$client= new SoapClient('http://n.sms.ir/ws/SendReceive.asmx?wsdl');

$parameters['userName'] = $userName;
$parameters['password'] = $password;
$parameters['mobileNos'] = array(doubleval($to));
$parameters['messages'] = array($text);
$parameters['lineNumber'] = $lineNumber;
$parameters['sendDateTime'] = date("Y-m-d")."T".date("H:i:s");
print_r($client->SendMessageWithLineNumber($parameters));
?>
