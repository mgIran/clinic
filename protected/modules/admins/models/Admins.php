<?php

/**
 * This is the model class for table "ym_admins".
 *
 * The followings are the available columns in table 'ym_admins':
 * @property string $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $role_id
 * @property string $name_family
 *
 * The followings are the available model relations:
 * @property AdminRoles $role
 */
class Admins extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{admins}}';
	}

    /**
     * @var string $repeatPassword for create admin
     */
    public $repeatPassword;
    /**
     * @var string $oldPassword for Update admin password
     * @var string $newPassword for Update admin password
     */
    public $oldPassword ,$newPassword;

    public $roleId;
    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name_family, username, password ,repeatPassword , email ,role_id', 'required' , 'on' => 'create'),
            array('email' , 'email'),
            array('email' , 'unique'),
            array('email' , 'filter' , 'filter' => 'trim'),
            array('username, password', 'length', 'max'=>100),
            array('name_family', 'length', 'max'=>255),
            array('username', 'checkExist' , 'on'=>'create'),
            array('email, role_id', 'required' , 'on'=>'update'),
            array('oldPassword ,newPassword ,repeatPassword, email, role_id', 'required' , 'on'=>'changePassword'),
            array('oldPassword', 'oldPass' , 'on'=>'changePassword'),
            array('repeatPassword', 'compare', 'compareAttribute'=>'newPassword' ,'operator'=>'==', 'message' => 'کلمه های عبور همخوانی ندارند' , 'on'=>'changePassword'),
            array('repeatPassword', 'compare', 'compareAttribute'=>'password' ,'operator'=>'==', 'message' => 'کلمه های عبور همخوانی ندارند' , 'on'=>'create'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, username, password,role_id,email ,roleId', 'safe', 'on'=>'search'),
        );
    }

    /**
     * Check this username is exist in database or not
     */
    public function checkExist($attribute,$params)
    {
        $record = Admins::model()->findByAttributes(array('username'=>$this->username));
        if ( $record )
            $this->addError( $attribute, 'این نام کاربری موجود است' );
    }

    /**
     * Check this username is exist in database or not
     */
    public function oldPass($attribute,$params)
    {
        $bCrypt = new bCrypt();
        $record = Admins::model()->findByAttributes( array( 'username' => $this->username ) );
        if ( !$bCrypt->verify( $this->$attribute, $record->password ) )
            $this->addError( $attribute, 'کلمه عبور فعلی اشتباه است' );
    }


    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'شناسه',
            'name_family' => 'نام و نام خانوادگی',
            'username' => 'نام کاربری',
            'password' => 'کلمه عبور',
            'oldPassword' => 'کلمه عبور فعلی',
            'newPassword' => 'کلمه عبور جدید',
            'repeatPassword' => 'تکرار کلمه عبور',
            'role_id' => 'نقش',
            'email' => 'پست الکترونیک'
        );
    }

    protected function afterValidate()
    {
        if($this->scenario=='create' || $this->scenario=='changePassword')
			$this->password = $this->encrypt($this->password);
        return parent::afterValidate();
    }

    public function encrypt($value)
    {
        $enc = NEW bCrypt();
        return $enc->hash($value);
    }
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'role' => array(self::BELONGS_TO, 'AdminRoles', 'role_id'),
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
		$criteria->compare('name_family',$this->name_family,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('email',$this->email,true);
        $criteria->addSearchCondition('role.id' , $this->roleId );
        $criteria->with = array('role');
		$criteria->addCondition('username <> "rahbod"');
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Admins the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
