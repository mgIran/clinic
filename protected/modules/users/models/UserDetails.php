<?php

/**
 * This is the model class for table "{{user_details}}".
 *
 * The followings are the available columns in table '{{user_details}}':
 * @property string $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $phone
 * @property string $zip_code
 * @property string $address
 * @property string $avatar
 * @property string $mobile
 * @property string $doctor_resume
 *
 * The followings are the available model relations:
 * @property Users $user
 */
class UserDetails extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{user_details}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id', 'required'),
			array('first_name, last_name, mobile', 'required', 'on' => 'update'),
			array('user_id, zip_code', 'length', 'max'=>10),
			array('first_name, last_name', 'length', 'max'=>50),
			array('mobile', 'length', 'is'=>11, 'message'=>'شماره موبایل اشتباه است'),
			array('phone', 'length', 'max'=>11),
			array('address', 'length', 'max'=>1000),
			array('avatar', 'length', 'max'=>255),
			array('phone, mobile', 'numerical', 'integerOnly' => true),
			array('mobile', 'unique', 'on' => 'insert'),
			array('doctor_resume', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_id, first_name, last_name, phone, zip_code, address, avatar, mobile', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => 'کاربر',
			'first_name' => 'نام',
			'last_name' => 'نام خانوادگی',
			'phone' => 'تلفن',
			'zip_code' => 'کد پستی',
			'address' => 'آدرس',
			'avatar' => 'آواتار',
			'mobile' => 'موبایل',
			'doctor_resume' => 'رزومه پزشکی',
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

		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('zip_code',$this->zip_code,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('avatar',$this->avatar,true);
		$criteria->compare('mobile',$this->mobile,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserDetails the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * @return array
     */
    public function getRoleLabels(){
        return CHtml::listData(UserRoles::model()->findAll(), 'role', 'name');
    }

    /**
     * @return string if user`s name is not empty return name ,otherwise return email
     */
    public function getShowName()
    {
        if ($this->first_name or $this->last_name)
            return $this->first_name . ' ' . $this->last_name;
        else
            return $this->user->email;
    }

	public function getAvatar()
	{
		if($this->avatar)
            return Yii::app()->baseUrl.'/uploads/users/'.$this->avatar;
        else
            return Yii::app()->theme->baseUrl.'/svg/default-user.svg';
	}

	public function getApiAvatar()
	{
		if ($this->avatar)
			return Yii::app()->baseUrl . '/uploads/users/' . $this->avatar;
		else
			return '';
	}
}
