<?php
/**
 * Class SendSMS
 */
class SendSMS extends CComponent
{
    public $username = '9160336621';
    public $password = '665d43';
    public $lineNumber = '30004554554153';
    public $messagePostfix = 'http://visit365.ir';

    private $_client;
    private $_invalid_numbers=array();
    private $_numbers=array();
    private $_messages = array();

    private $_result;

    public function __construct($line=false)
    {
        if($line)
            $this->lineNumber = $line;
        date_default_timezone_set('Asia/Tehran');
        try {
            @$this->_client = new SoapClient('http://n.sms.ir/ws/SendReceive.asmx?wsdl',array('encoding' => 'UTF-8'));
        }catch (Exception $e){
            throw new CHttpException(501, $e->getMessage());
        }
    }


    /**
     * @param $number
     * @return $this
     */
    public function AddNumber($number){
        $numberVal = doubleval($number);
        if($numberVal && $this->ValidateNumber($numberVal))
            $this->_numbers[] = $numberVal;
        else
            $this->_invalid_numbers[] = $number;
        return $this;
    }

    /**
     * @param $numbers
     * @return $this
     * @throws CException
     */
    public function AddNumbers($numbers){
        if($numbers && is_array($numbers))
            foreach ($numbers as $number)
                $this->AddNumber($number);
        else
            throw new CException('پارامتر تابع AddNumbers باید یک آرایه باشد.');
        return $this;
    }

    /**
     * Validate Mobile Number
     * @param $number
     * @return bool|int
     */
    public function ValidateNumber($number) {
        if(array_search($number, $this->_numbers) === false)
            return preg_match('/^[9]+[0-9]{9}+$/', $number);
        return false;
    }

    /**
     * Validates Mobile Numbers array
     * @return $this
     */
    public function ValidateNumbers() {
        foreach ($this->_numbers as $number)
            $this->ValidateNumber($number);
        return $this;
    }

    /**
     * @param $message
     * @return $this
     */
    public function AddMessage($message){
        $this->_messages[] = $message.($this->messagePostfix?"
$this->messagePostfix":'');
        return $this;
    }

    /**
     * @param $messages
     * @return $this
     * @throws CException
     */
    public function AddMessages($messages){
        if($messages && is_array($messages))
            foreach ($messages as $message)
                $this->AddMessage($message);
        else
            throw new CException('پارامتر تابع AddMessages باید یک آرایه باشد.');
        return $this;
    }

    /**
     * Send Sms to receivers
     * @throws CException
     */
    public function SendWithLine()
    {
        if (!$this->lineNumber)
            throw new CException('شماره خط ارسال پیامک مشخص نشده است.');
        if (count($this->_numbers) < 1)
            throw new CException('شماره موبایلی وارد نشده است.');
        if (!$this->messages || empty($this->messages))
            throw new CException('متن پیامک وارد نشده است.');
        $parameters['userName'] = $this->username;
        $parameters['password'] = $this->password;
        $parameters['mobileNos'] = $this->getNumbers();
        $parameters['messages'] = $this->getMessages();
        $parameters['lineNumber'] = $this->lineNumber;
        $parameters['sendDateTime'] = date("Y-m-d")."T".date("H:i:s");
        try {
            $this->_result = $this->_client->SendMessageWithLineNumber($parameters);
        } catch (Exception $e) {
            throw new CException('ارسال پیامک با مشکل مواجه است.');
        }
        return $this->_result;
    }

    public function getNumbers(){
        return $this->ValidateNumbers()->_numbers;
    }

    public function getInvalidNumbers(){
        return $this->ValidateNumbers()->_invalid_numbers;
    }

    public function getMessages(){
        return is_array($this->_messages)?$this->_messages:array($this->_messages);
    }

    public function getResult(){
        return $this->_result;
    }
}