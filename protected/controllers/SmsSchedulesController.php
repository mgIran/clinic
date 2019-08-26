<?php

class SmsSchedulesController extends Controller
{
	public function actionSend()
	{
		$floorTime = strtotime(date('Y/m/d H', time()) . ':00:00');
		$ceilTime = $floorTime + 3600;
		$criteria = new CDbCriteria();
		$criteria->addCondition('send_date >= :floor AND send_date < :ceil AND status = 0');
		$criteria->params[':floor'] = $floorTime;
		$criteria->params[':ceil'] = $ceilTime;
		$schedules = SmsSchedules::model()->findAll($criteria);
		if($schedules){
			foreach($schedules as $schedule){
				$sms = new SendSMS();
				$emails = CJSON::decode($schedule->emails);
				if($emails){
					foreach($emails as $email){
						if($email && !empty($email)){
							$html = '<div style="font-family:tahoma,arial;font-size:12px;white-space: pre-line;text-align: right;background:#F5F5F5;min-height:100px;padding:5px 30px 5px;direction:rtl;line-height:25px;color:#4b4b4b;">';
							$html .= '<h1 style="direction:ltr;">اطلاعیه جدید</h1>';
							$html .= '<span>' . (CHtml::encode($schedule->text)) . '</span>';
							$html .= "</div>";
							$subject = 'اطلاعیه جدید - وبسایت ویزیت 365';
							@(new Mailer())->mail($email, $subject, $html, Yii::app()->params['no-reply-email']);
						}
					}
				}
				$receivers = CJSON::decode($schedule->receivers);
				foreach($receivers as $receiver){
					$sms->AddNumber($receiver);
					$sms->AddMessage($schedule->text);
				}
				$response = $sms->SendWithLine();
				if(empty($response->message)){
					$responses = CJSON::encode($response->SendMessageWithLineNumberResult->long);
					$schedule->responses = $responses;
					$schedule->status = SmsSchedules::SEND_SUCCESSFUL;
				}else
					$schedule->status = $response->message;
				@$schedule->save();
			}
			Yii::app()->end();
		}
	}
}