<?php
/**
 * Created by PhpStorm.
 * User: Yusef Mobasheri
 * Date: 12/10/2016
 * Time: 5:49 PM
 */
class Notify
{
    /**
     * @param $text
     * @param $phone
     * @param $email
     * @param null $emailSubject
     */
    public static function Send($text, $phone, $email, $emailSubject=null)
    {
        self::SendSms($text, $phone);
        self::SendEmail($text, $email, $emailSubject);
    }


    /**
     * Send Sms
     *
     * @param $message
     * @param $phone
     * @throws CException
     */
    public static function SendSms($message, $phone)
    {
        if($phone && !empty($phone)){
            $sms = new SendSMS();
            $sms->AddNumber($phone);
            if($sms->getNumbers()){
                $sms->AddMessage($message);
                @$sms->SendWithLine();
            }
        }
    }

    public static function SendEmail($message, $email, $emailSubject)
    {
        if($email && !empty($email)){
            $html = '<div style="font-family:tahoma,arial;font-size:12px;white-space: pre-line;text-align: right;background:#F5F5F5;min-height:100px;padding:5px 30px 5px;direction:rtl;line-height:25px;color:#4b4b4b;">';
            $html .= '<h1 style="direction:ltr;">اطلاعیه جدید</h1>';
            $html .= '<span>' . (CHtml::encode($message)) . '</span>';
            $html .= "</div>";
            $subject = $emailSubject && !empty($emailSubject)?$emailSubject:'اطلاعیه جدید - وبسایت آوای شهیر';
            @(new Mailer())->mail($email, $subject, $html, Yii::app()->params['no-reply-email']);
        }
    }

    /**
     * @param array $adminIDs
     * @param bool $sms
     * @param bool $smsMessage
     * @param bool $email
     * @param bool $emailSubject
     * @param bool $emailMessage
     * @return array
     * @throws CException
     */
    public static function AdminsSend($adminIDs = array(), $sms = false, $smsMessage = false, $email = false, $emailSubject = false, $emailMessage = false)
    {
        /* @var $admin Admins */
        Yii::app()->getModule('admins');
        if(!$adminIDs)
            $adminIDs = Admins::GetAdminsColumn('id');
        elseif (!is_array($adminIDs))
            $adminIDs = array($adminIDs);
        $result = array();
        foreach ($adminIDs as $adminID) {
            $admin = Admins::model()->findByPk($adminID);
            if ($admin) {
                // Send Notification with sms
                if ($sms && $smsMessage && !empty($smsMessage) && $admin->mobile && !empty($admin->mobile)) {
                    $sms = new SendSMS();
                    $sms->AddNumber($admin->mobile);
                    if ($sms->getNumbers()) {
                        $sms->AddMessage($smsMessage);
                        $result[$adminID]['sms'] = @$sms->SendWithLine();
                    }
                }
                // Send Notification with email
                if ($email && $admin->email && !empty($admin->email)) {
                    $html = '<html><body>';
                    $html .= '<div style="font-family:tahoma,arial;font-size:12px;width:600px;background:#F5F5F5;min-height:100px;padding:5px 30px 5px;direction:rtl;line-height:25px;color:#4b4b4b;">';
                    $html .= '<h1 style="direction:ltr;">اطلاعیه جدید</h1>';
                    $html .= '<span>'.($emailMessage).'</span>';
                    $html .= "</div>";
                    $html .= "</body></html>";
                    $subject = $emailSubject && !empty($emailSubject) ? $emailSubject : 'اطلاعیه جدید در مترجمان پیشتاز';
                    $result[$adminID]['email'] = @(new Mailer())->mail($admin->email, $subject, $html, "noreply@pishtaztranslation.com")? 1 : 0;
                }
            }else
                $result[$adminID] = false;
        }
        return $result;
    }
}