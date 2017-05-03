<?php

/**
 * This is the model class for table "{{clinic_personnels}}".
 *
 * The followings are the available columns in table '{{clinic_personnels}}':
 * @property string $clinic_id
 * @property string $user_id
 * @property integer $post
 * @property integer $expertiseID
 * @property string $doctor_name
 * @property string $clinic_name
 *
 * The followings are the available model relations:
 * @property Clinics $clinic
 * @property Users $user
 * @property UserRoles $post_rel
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

	public $email;
	public $password;
	public $role_id;
	public $first_name;
	public $expertiseID;
    public $doctor_name;
    public $clinic_name;
	public $last_name;
	public $phone;
	public $mobile;
	public $national_code;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email, role_id, first_name, last_name, national_code, clinic_id, user_id, post', 'required', 'on' => 'add_personnel'),
			array('email, first_name, last_name, national_code, clinic_id, user_id, post', 'required', 'on' => 'update_personnel'),
			array('clinic_id, user_id, post', 'required'),
			array('clinic_id, user_id', 'length', 'max'=>10),
			array('email', 'email', 'on' => 'add_personnel, update_personnel'),
			array('phone, mobile', 'length', 'max'=>11, 'on' => 'add_personnel, update_personnel'),
			array('first_name, last_name', 'length', 'max'=>50, 'on' => 'add_personnel, update_personnel'),
			array('national_code', 'length', 'is'=>10, 'on' => 'add_personnel, update_personnel'),
			array('post', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('clinic_id, user_id, post', 'safe', 'on'=>'search'),
			array('doctor_name, clinic_name, expertiseID', 'safe', 'on'=>'getDoctorsByExp'),
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
			'post_rel' => array(self::BELONGS_TO, 'UserRoles', 'post'),
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
			'email' => 'ایمیل',
			'password' => 'کلمه عبور',
			'role_id' => 'نقش',
			'first_name' => 'نام',
			'last_name' => 'نام خانوادگی',
			'phone' => 'تلفن ثابت',
			'mobile' => 'موبایل',
			'national_code' => 'کد ملی',
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
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * @return CActiveDataProvider
     */
	public function getDoctorsByExp()
    {
        $criteria = new CDbCriteria;

        $criteria->together = true;
        $criteria->alias = 'clinic_personnels';
        $criteria->with = array('clinic', 'user', 'user.expertises', 'user.userDetails');
        $criteria->order = 'clinic_personnels.user_id DESC, clinic_personnels.clinic_id DESC';

        $criteria->compare('clinic_personnels.post', 3);
        $criteria->compare('expertises.id', $this->expertiseID);
        $criteria->compare('userDetails.first_name', $this->doctor_name, true);
        $criteria->compare('userDetails.last_name', $this->doctor_name, true, 'OR');
        $criteria->compare('clinic.clinic_name', $this->clinic_name, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
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

	public function getValidPosts(){
		return CHtml::listData(UserRoles::model()->findAll('role != "user"'),'id', 'name');
	}

	/**
	 * @param array $values
	 */
	public function loadPropertyValues($values = array()){
		$this->email = isset($values['email']) && !empty($values['email'])?$values['email']:$this->user->email;
		$this->first_name = isset($values['first_name']) && !empty($values['first_name'])?$values['first_name']:$this->user->userDetails->first_name;
		$this->last_name = isset($values['last_name']) && !empty($values['last_name'])?$values['last_name']:$this->user->userDetails->last_name;
		$this->phone = isset($values['phone']) && !empty($values['phone'])?$values['phone']:$this->user->userDetails->phone;
		$this->mobile = isset($values['mobile']) && !empty($values['mobile'])?$values['mobile']:$this->user->userDetails->mobile;
		$this->national_code = isset($values['national_code']) && !empty($values['national_code'])?$values['national_code']:$this->user->userDetails->national_code;
	}
}
