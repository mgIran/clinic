<?php

/**
 * This is the model class for table "{{doctor_leaves}}".
 *
 * The followings are the available columns in table '{{doctor_leaves}}':
 * @property string $id
 * @property string $clinic_id
 * @property string $doctor_id
 * @property string $date
 *
 * The followings are the available model relations:
 * @property Clinics $clinic
 * @property Users $doctor
 */
class DoctorLeaves extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{doctor_leaves}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		$time = (time() - 60 * 60 * 24);
		return array(
			array('clinic_id, doctor_id', 'length', 'max' => 10),
			array('date', 'length', 'max' => 20),
			array('date', 'unique', 'message' => 'این تاریخ قبلا ثبت شده است.'),
			array('date', 'compare', 'compareValue' => $time, 'operator' => '>', 'message' => 'این تاریخ معتبر نیست، تاریخ باید بیشتر از امروز باشد.'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, clinic_id, doctor_id, date', 'safe', 'on' => 'search'),
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
			'clinic_id' => 'درمانگاه',
			'doctor_id' => 'دکتر',
			'date' => 'تاریخ',
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
		$criteria->compare('clinic_id',$this->clinic_id,true);
		$criteria->compare('doctor_id',$this->doctor_id,true);
		$criteria->compare('date',$this->date,true);
		$criteria->addCondition('date >= :now');
		$criteria->params[':now'] = strtotime(date("Y/m/d", time()) . " 00:00");
		$criteria->order = 't.date';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DoctorLeaves the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
