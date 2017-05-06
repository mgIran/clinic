<?php

/**
 * This is the model class for table "{{doctor_schedules}}".
 *
 * The followings are the available columns in table '{{doctor_schedules}}':
 * @property string $clinic_id
 * @property string $doctor_id
 * @property string $week_day
 * @property string $entry_time_am
 * @property string $exit_time_am
 * @property string $visit_count_am
 * @property string $entry_time_pm
 * @property string $exit_time_pm
 * @property string $visit_count_pm
 *
 * The followings are the available model relations:
 * @property Users $doctor
 * @property Clinics $clinic
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

	public static $weekDays = array(
		1 => 'شنبه',
		2 => 'یکشنبه',
		3 => 'دوشنبه',
		4 => 'سه شنبه',
		5 => 'چهارشنبه',
		6 => 'پنجشنبه',
		7 => 'جمعه'
	);

	public static $AM = array(5,6,7,8,9,10,11,12);
	public static $PM = array(12,13,14,15,16,17,18,19,20,21,22,23,24);

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
			array('entry_time_am, exit_time_am, entry_time_pm, exit_time_pm', 'length', 'max'=>2),
			array('visit_count_am, visit_count_pm', 'length', 'max'=>3),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('clinic_id, doctor_id, week_day, entry_time_am, exit_time_am, visit_count_am, entry_time_pm, exit_time_pm, visit_count_pm', 'safe', 'on'=>'search'),
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
			'doctor' => array(self::BELONGS_TO, 'Users', 'doctor_id'),
			'clinic' => array(self::BELONGS_TO, 'Clinics', 'clinic_id'),
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
			'entry_time_am' => 'زمان ورود نوبت صبح',
			'exit_time_am' => 'زمان خروج نوبت صبح',
			'visit_count_am' => 'تعداد ویزیت نوبت صبح',
			'entry_time_pm' => 'زمان ورود نوبت بعدازظهر',
			'exit_time_pm' => 'زمان خروج نوبت بعدازظهر',
			'visit_count_pm' => 'تعداد ویزیت نوبت بعدازظهر',
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
		$criteria->compare('entry_time_am',$this->entry_time_am,true);
		$criteria->compare('exit_time_am',$this->exit_time_am,true);
		$criteria->compare('visit_count_am',$this->visit_count_am,true);
		$criteria->compare('entry_time_pm',$this->entry_time_pm,true);
		$criteria->compare('exit_time_pm',$this->exit_time_pm,true);
		$criteria->compare('visit_count_pm',$this->visit_count_pm,true);

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
