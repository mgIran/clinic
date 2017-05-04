<?php

/**
 * This is the model class for table "{{doctor_schedules}}".
 *
 * The followings are the available columns in table '{{doctor_schedules}}':
 * @property string $clinic_id
 * @property string $doctor_id
 * @property string $week_day
 * @property string $visit_count
 * @property string $entry_time
 * @property string $exit_time
 *
 * The followings are the available model relations:
 * @property Clinics $clinic
 * @property Users $doctor
 */
class DoctorSchedules extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{doctor_schedules}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('clinic_id, doctor_id, week_day', 'required'),
			array('clinic_id, doctor_id', 'length', 'max'=>10),
			array('week_day', 'length', 'max'=>1),
			array('visit_count', 'length', 'max'=>3),
			array('entry_time, exit_time', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('clinic_id, doctor_id, week_day, visit_count, entry_time, exit_time', 'safe', 'on'=>'search'),
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
			'clinic_id' => 'Clinic',
			'doctor_id' => 'دکتر',
			'week_day' => 'روز هفته',
			'visit_count' => 'تعداد ویزیت',
			'entry_time' => 'زمان مراجعه',
			'exit_time' => 'زمان خروج',
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

		$criteria->compare('clinic_id',$this->clinic_id,true);
		$criteria->compare('doctor_id',$this->doctor_id,true);
		$criteria->compare('week_day',$this->week_day,true);
		$criteria->compare('visit_count',$this->visit_count,true);
		$criteria->compare('entry_time',$this->entry_time,true);
		$criteria->compare('exit_time',$this->exit_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DoctorSchedules the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
