<?php

/**
 * This is the model class for table "{{clinic_personnels}}".
 *
 * The followings are the available columns in table '{{clinic_personnels}}':
 * @property string $clinic_id
 * @property string $user_id
 * @property integer $post
 *
 * The followings are the available model relations:
 * @property Clinics $clinic
 * @property Users $user
 */
class ClinicPersonnels extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{clinic_personnels}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('clinic_id, user_id, post', 'required'),
			array('clinic_id, user_id', 'length', 'max'=>10),
			array('post', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('clinic_id, user_id, post', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'clinic_id' => 'بیمارستان/درمانگاه/مطب',
			'user_id' => 'کاربر',
			'post' => 'سمت',
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
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('post',$this->post,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ClinicPersonnels the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
