<?php

/**
 * This is the model class for table "{{visits}}".
 *
 * The followings are the available columns in table '{{visits}}':
 * @property string $id
 * @property string $user_id
 * @property string $clinic_id
 * @property string $doctor_id
 * @property string $date
 * @property string $time
 * @property string $status
 * @property string $tracking_code
 *
 * The followings are the available model relations:
 * @property Users $user
 * @property Clinics $clinic
 * @property Users $doctor
 */
class Visits extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{visits}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, clinic_id, doctor_id', 'length', 'max'=>10),
			array('date, tracking_code', 'length', 'max'=>20),
			array('time, status', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, clinic_id, doctor_id, date, time, status, tracking_code', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
			'clinic' => array(self::BELONGS_TO, 'Clinics', 'clinic_id'),
			'doctor' => array(self::BELONGS_TO, 'Users', 'doctor_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'کاربر',
			'clinic_id' => 'بیمارستان / درمانگاه / مطب',
			'doctor_id' => 'پزشک',
			'date' => 'تاریخ',
			'time' => 'نوبت',
			'status' => 'وضعیت',
			'tracking_code' => 'کد رهگیری',
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
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('clinic_id',$this->clinic_id,true);
		$criteria->compare('doctor_id',$this->doctor_id,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('tracking_code',$this->tracking_code,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Visits the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
