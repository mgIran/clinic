<?php

/**
 * This is the model class for table "{{sms_schedules}}".
 *
 * The followings are the available columns in table '{{sms_schedules}}':
 * @property string $id
 * @property string $send_date
 * @property string $text
 * @property string $emails
 * @property string $receivers
 * @property string $responses
 * @property string $status
 */
class SmsSchedules extends CActiveRecord
{
	const SEND_PENDING = 0;
	const SEND_SUCCESSFUL = 1;
	const SEND_FAILED = -1;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{sms_schedules}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('send_date', 'required'),
			array('send_date', 'length', 'max' => 20),
			array('text', 'length', 'max' => 600),
			array('status', 'length', 'max' => 1),
			array('status', 'default', 'value' => 0),
			array('receivers, responses, emails', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, send_date, text, emails, receivers, responses, status', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'send_date' => 'تاریخ ارسال',
			'text' => 'متن ارسالی',
			'emails' => 'ایمیل گیرندگان',
			'receivers' => 'موبایل گیرندگان',
			'responses' => 'پاسخ وبسرویس',
			'status' => 'وضعیت',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id, true);
		$criteria->compare('send_date', $this->send_date, true);
		$criteria->compare('text', $this->text, true);
		$criteria->compare('emails', $this->emails, true);
		$criteria->compare('receivers', $this->receivers, true);
		$criteria->compare('responses', $this->responses, true);
		$criteria->compare('status', $this->status, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SmsSchedules the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Add New SmS Schedule Send
	 * 
	 * @param $sendDate
	 * @param $text
	 * @param $receivers
	 * @param $emails
	 * @return bool|SmsSchedules
	 */
	public static function AddSchedule($sendDate, $text, $receivers, $emails)
	{
		$model = new SmsSchedules();
		$model->send_date = $sendDate;
		$model->text = $text;
		$model->emails = CJSON::encode($emails);
		$model->receivers = CJSON::encode($receivers);
		$model->status = self::SEND_PENDING;
		if($model->save())
			return true;
		else
			return $model;
	}

	/**
	 * Add Receiver to SMS Schedule Send
	 *
	 * @param $id
	 * @param $receiver
	 * @param $email
	 * @return bool|static
	 */
	public static function AddReceiverToSchedule($id, $receiver, $email)
	{
		$model = SmsSchedules::model()->findByPk($id);
		if($model === NULL)
			return false;
		if($model->status != self::SEND_PENDING){
			self::AddSchedule(time(), $model->text, array($receiver), array($email));
			return true;
		}else{
			$receivers = CJSON::decode($model->receivers);
			$receivers[] = $receiver;
			$model->receivers = CJSON::encode($receivers);

			$emails = CJSON::decode($model->emails);
			$emails[] = $email;
			$model->emails = CJSON::encode($emails);

			if($model->save())
				return true;
			else
				return $model;
		}
	}
}
