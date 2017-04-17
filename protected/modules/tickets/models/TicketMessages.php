<?php

/**
 * This is the model class for table "ym_ticket_messages".
 *
 * The followings are the available columns in table 'ym_ticket_messages':
 * @property string $id
 * @property string $ticket_id
 * @property string $sender
 * @property string $date
 * @property string $text
 * @property string $attachment
 * @property integer $visit
 *
 * The followings are the available model relations:
 * @property Tickets $ticket
 */
class TicketMessages extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ym_ticket_messages';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		$sender = null;
		if(!Yii::app()->user->isGuest && Yii::app()->user->type == 'admin')
			$sender = 'admin';
		if(!Yii::app()->user->isGuest && Yii::app()->user->type == 'user')
			$sender = 'user';
		return array(
			array('text', 'required'),
			array('visit', 'numerical', 'integerOnly' => true),
			array('ticket_id', 'length', 'max' => 10),
			array('date', 'length', 'max' => 20),
			array('sender', 'default', 'value' => $sender),
			array('date', 'default', 'value' => time()),
			array('visit', 'default', 'value' => 0),
			array('attachment', 'length', 'max' => 500),
			array('text', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, ticket_id, date, text, attachment, visit', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'ticket' => array(self::BELONGS_TO, 'Tickets', 'ticket_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'ticket_id' => 'تیکت',
			'date' => 'تاریخ',
			'text' => 'متن',
			'attachment' => 'فایل ضمیمه',
			'visit' => 'Visit',
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
		$criteria->compare('ticket_id',$this->ticket_id,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('text',$this->text,true);
		$criteria->compare('attachment',$this->attachment,true);
		$criteria->compare('visit',$this->visit);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TicketMessages the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
