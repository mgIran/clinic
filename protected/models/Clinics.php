<?php

/**
 * This is the model class for table "{{clinics}}".
 *
 * The followings are the available columns in table '{{clinics}}':
 * @property string $id
 * @property string $clinic_name
 * @property string $town_id
 * @property string $place_id
 * @property string $zip_code
 * @property string $address
 * @property string $phone
 * @property string $fax
 * @property string $description
 * @property string $contracts
 *
 * The followings are the available model relations:
 * @property ClinicPersonnels[] $clinicPersonnels
 * @property Places $place
 * @property Towns $town
 * @property DoctorSchedules[] $doctorSchedules
 * @property Visits[] $visits
 */
class Clinics extends CActiveRecord
{
	public $post;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{clinics}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('clinic_name', 'required'),
			array('clinic_name', 'length', 'max'=>255),
			array('town_id, place_id, zip_code', 'length', 'max'=>10),
			array('phone, fax', 'length', 'max'=>11),
			array('address, description, contracts', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, clinic_name, town_id, place_id, zip_code, address, phone, fax, description, contracts', 'safe', 'on'=>'search'),
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
			'clinicPersonnels' => array(self::HAS_MANY, 'ClinicPersonnels', 'clinic_id'),
			'place' => array(self::BELONGS_TO, 'Places', 'place_id'),
			'town' => array(self::BELONGS_TO, 'Towns', 'town_id'),
			'doctorSchedules' => array(self::HAS_MANY, 'DoctorSchedules', 'clinic_id'),
			'visits' => array(self::HAS_MANY, 'Visits', 'clinic_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'clinic_name' => 'نام مطب',
			'town_id' => 'استان',
			'place_id' => 'شهر',
			'zip_code' => 'کدپستی',
			'address' => 'آدرس',
			'phone' => 'تلفن',
			'fax' => 'فکس',
			'description' => 'توضیحات',
			'contracts' => 'طرف قرارداد ها',
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
		$criteria->compare('clinic_name',$this->clinic_name,true);
		$criteria->compare('town_id',$this->town_id);
		$criteria->compare('place_id',$this->place_id);
		$criteria->compare('zip_code',$this->zip_code,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('fax',$this->fax,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('contracts',$this->contracts,true);
        $criteria->order='id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Clinics the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
