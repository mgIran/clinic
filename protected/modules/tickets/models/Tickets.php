<?php

/**
 * This is the model class for table "ym_tickets".
 *
 * The followings are the available columns in table 'ym_tickets':
 * @property string $id
 * @property string $code
 * @property string $user_id
 * @property string $status
 * @property string $date
 * @property string $subject
 * @property string $department_id
 * @property string $attachment
 * @property string $text
 * @property string $firstMessageId
 *
 * The followings are the available model relations:
 * @property TicketMessages[] $messages
 * @property Users $user
 * @property TicketDepartments $department
 */
class Tickets extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ym_tickets';
	}

	public $firstMessageId;

	public $text;

	public $attachment;

	public $statusLabels = array(
		'waiting' => 'در انتظار پاسخ',
		'pending' => 'در حال بررسی توسط کارشناس',
		'open' => 'باز',
		'close' => 'بسته',
	);

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code, date', 'length', 'max'=>20),
			array('date', 'default', 'value'=>time()),
			array('user_id', 'default', 'value'=>Yii::app()->user->getId() ,'on' => 'insert'),
			array('code', 'codeGenerator', 'on'=>'insert'),
			array('user_id, department_id', 'length', 'max'=>10),
			array('status', 'length', 'max'=>7),
			array('subject', 'length', 'max'=>255),
			array('attachment', 'length', 'max'=>500),
			array('text, firstMessageId', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, code, user_id, status, date, subject, department_id', 'safe', 'on'=>'search'),
		);
	}

	public function codeGenerator(){
		$maxCode = Tickets::find();
		if(!$maxCode)
			$this->code = 10000;
		else
			$this->code = Yii::app()->db->createCommand()
				->select("MAX(code)")
				->from('ym_tickets')
				->queryScalar() + 1;
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'messages' => array(self::HAS_MANY, 'TicketMessages', 'ticket_id' ,'order' => 'date DESC'),
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
			'department' => array(self::BELONGS_TO, 'TicketDepartments', 'department_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'code' => 'شناسه تیکت',
			'user_id' => 'User',
			'status' => 'وضعیت تیکت',
			'date' => 'تاریخ',
			'subject' => 'موضوع',
			'department_id' => 'بخش',
			'text' => 'متن',
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('department_id',$this->department_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Tickets the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	protected function afterSave(){
		if($this->text)
		{
			$message = new TicketMessages();
			$message->text = $this->text;
			$message->ticket_id = $this->id;
			$message->attachment = $this->attachment;
			if($message->save())
				$this->firstMessageId = $message->id;
		}
		parent::afterSave();
	}

	public function getCssClass(){
		$criteria = new CDbCriteria();
		$criteria->compare('visit',0);
		$criteria->compare('ticket_id',$this->id);
		$criteria->compare('sender','user');
		return TicketMessages::model()->count($criteria)?'unread':'';
	}
}
