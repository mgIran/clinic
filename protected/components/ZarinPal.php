<?php
/**
 * Class ZarinPal
 */
class ZarinPal extends CComponent
{
    public $callback_url;
    
    private $_merchant_id = 'b5c45350-3fc8-11e7-ba1c-005056a205be';
    private $_gateway_name = 'زرین پال';
    private $_status;
    private $_authority;
    private $_ref_id;

    public function init(){

    }

    public function request($amount, $description, $email = null, $mobile = '0')
    {
        try {
            @$client = new SoapClient('https://www.zarin.link/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);
            $result = $client->PaymentRequest(
                [
                    'MerchantID'  => $this->_merchant_id,
                    'Amount'      => $amount,
                    'Description' => $description,
                    'Email'       => $email,
                    'Mobile'      => $mobile,
                    'CallbackURL' => $this->callback_url,
                ]
            );
        } catch (Exception $e) {
            throw new CHttpException(501, $e->getMessage());
        }
        $this->_status = $result->Status;
        $this->_authority = $result->Authority;
        return $this;
    }

    public function verify($authority, $amount)
    {
        $this->_authority = $authority;
        if ($_GET['Status'] == 'OK') {
            try {
                @$client = new SoapClient('https://www.zarin.link/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);
                @$result = $client->PaymentVerification(
                    [
                        'MerchantID' => $this->_merchant_id,
                        'Authority' => $this->_authority,
                        'Amount' => $amount,
                    ]
                );
            } catch (Exception $e) {
                throw new CHttpException(501, $e->getMessage());
            }
        } else
            throw new CHttpException(500, 'عملیات پرداخت ناموفق بوده یا توسط کاربر لغو شده است.');
        $this->_status = $result->Status;
        $this->_ref_id = $result->RefID;
        return $this;
    }

    public function getRedirectUrl($zaringate = false)
    {
        $url = 'https://www.zarin.link/pg/StartPay/'.$this->_authority;
        $url .=  ($zaringate) ? '/ZarinGate' : '';

        return $url;
    }

    public function getAuthority()
    {
        return $this->_authority;
    }

    public function getStatus()
    {
        return $this->_status;
    }
    
    public function getRefId()
    {
        return $this->_ref_id;
    }

    public function getGatewayName()
    {
        return $this->_gateway_name;
    }

    public function getError()
    {

        $errors = array(
            '0' => '.عملیات پرداخت بصورت کامل طی نشده است',
            '-1' => 'اطلاعات ارسال شده ناقص است.',
            '-2' => 'IP یا کد پذیرنده صحیح نیست.',
            '-3' => 'با توجه به محدودیت ها امکان پرداخت رقم درخواست شده میسر نمی باشد.',
            '-4' => 'سطح تایید پذیرنده پایین تر از سطح نقره ای است.',
            '-11' => 'درخواست مورد نظر یافت نشد.',
            '-12' => 'امکان ویرایش درخواست میسر نمی باشد.',
            '-21' => 'هیچ نوع عملیات مالی برای این تراکنش یافت نشد.',
            '-22' => 'تراکنش ناموفق بود.',
            '-33' => 'رقم تراکنش با رقم پرداخت شده مطابقت ندارد.',
            '-34' => 'سقف تقسیم تراکنش از لحاظ تعداد یا رقم عبور نموده است.',
            '-40' => 'اجازه دسترسی به متد مربوطه وجود ندارد.',
            '-41' => 'اطلاعات ارسال شده مربوط به AdditionalData غیر معتبر می باشد.',
            '-42' => 'مدت زمان معتبر طول عمر شناسه پرداخت باید بین 30 دقیقه تا 45 روز باشد.',
            '-54' => 'درخواست مورد نظر آرشیو شده است.',
            '101' => 'عملیات پرداخت موفق بوده و قبلا بررسی تراکنش انجام شده است.',
        );
        return isset($errors[$this->_status]) ? $errors[$this->_status] : 'در انجام عملیات پرداخت خطایی رخ داده است.';
    }
}