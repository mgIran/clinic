<?php

/**
 * This is the model class for table "{{users}}".
 *
 * The followings are the available columns in table '{{users}}':
 * @property string $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $role_id
 * @property string $create_date
 * @property string $status
 * @property string $verification_token
 * @property integer $change_password_request_count
 * @property integer $auth_mode
 * @property string $repeatPassword
 * @property string $oldPassword
 * @property string $newPassword
 *
 * The followings are the available model relations:
 * @property ShopAddresses[] $addresses
 * @property BookBuys[] $bookBuys
 * @property Books[] $books
 * @property Books[] $bookmarkedBooks
 * @property UserDetails $userDetails
 * @property UserDevIdRequests $userDevIdRequests
 * @property UserTransactions[] $transactions
 * @property UserRoles $role
 * @property ClinicPersonnels $clinicPersonnels
 * @property Clinics[] $clinics
 */
class Users extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{users}}';
    }

    public $statusLabels = array(
        'pending' => 'در انتظار تایید',
        'active' => 'فعال',
        'blocked' => 'مسدود',
        'deleted' => 'حذف شده'
    );
    public $first_name;
    public $last_name;
    public $statusFilter;
    public $repeatPassword;
    public $oldPassword;
    public $newPassword;
    public $roleId;
    public $type;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('email, password', 'required', 'on' => 'insert,create'),
            array('role_id', 'default', 'value' => 1),
            array('email', 'required', 'on' => 'email, OAuthInsert'),
            array('email', 'unique', 'on' => 'insert,create,OAuthInsert'),
            array('change_password_request_count', 'numerical', 'integerOnly' => true),
            array('email', 'email'),
            array('oldPassword ,newPassword ,repeatPassword', 'required', 'on' => 'update'),
            array('password', 'required', 'on' => 'change_password'),
            array('repeatPassword', 'compare', 'compareAttribute' => 'password', 'on' => 'change_password'),
            array('email', 'filter', 'filter' => 'trim', 'on' => 'create'),
            array('username, password, verification_token', 'length', 'max' => 100, 'on' => 'create'),
            array('oldPassword', 'oldPass', 'on' => 'update'),
            array('email', 'length', 'max' => 255),
            array('role_id', 'length', 'max' => 10),
            array('status', 'length', 'max' => 8),
            array('create_date', 'length', 'max' => 20),
            array('type', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('type, roleId, create_date, status, verification_token, change_password_request_count ,first_name ,last_name ,email ,statusFilter', 'safe', 'on' => 'search'),
        );
    }

    /**
     * Check this username is exist in database or not
     */
    public function oldPass($attribute, $params)
    {
        $bCrypt = new bCrypt();
        $record = Users::model()->findByAttributes(array('email' => $this->email));
        if (!$bCrypt->verify($this->$attribute, $record->password))
            $this->addError($attribute, 'کلمه عبور فعلی اشتباه است');
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'bookBuys' => array(self::HAS_MANY, 'BookBuys', 'user_id'),
            'books' => array(self::HAS_MANY, 'Books', 'publisher_id'),
            'userDetails' => array(self::HAS_ONE, 'UserDetails', 'user_id'),
            'userDevIdRequests' => array(self::HAS_ONE, 'UserDevIdRequests', 'user_id'),
            'transactions' => array(self::HAS_MANY, 'UserTransactions', 'user_id'),
            'role' => array(self::BELONGS_TO, 'UserRoles', 'role_id'),
            'bookmarkedBooks' => array(self::MANY_MANY, 'Books', '{{user_book_bookmark}}(user_id, book_id)'),
            'bookRate' => array(self::BELONGS_TO, 'BookRatings', 'id'),
            'sessions' => array(self::HAS_MANY, 'Sessions', 'user_id', 'on' => 'user_type = "user"'),
            'addresses' => array(self::HAS_MANY, 'ShopAddresses', 'user_id', 'on' => 'addresses.deleted = 0'),
            'clinicPersonnels' => array(self::BELONGS_TO, 'ClinicPersonnels', 'id'),
            'clinics' => array(self::MANY_MANY, 'Clinics', '{{clinic_personnels}}(user_id, clinic_id)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'username' => 'نام کاربری',
            'password' => 'کلمه عبور',
            'role_id' => 'نقش',
            'email' => 'پست الکترونیک',
            'repeatPassword' => 'تکرار کلمه عبور',
            'oldPassword' => 'کلمه عبور فعلی',
            'newPassword' => 'کلمه عبور جدید',
            'create_date' => 'تاریخ ثبت نام',
            'status' => 'وضعیت کاربر',
            'verification_token' => 'Verification Token',
            'change_password_request_count' => 'تعداد درخواست تغییر کلمه عبور',
            'type' => 'نوع کاربری',
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

        $criteria = new CDbCriteria;

        $criteria->compare('username', $this->username, true);
        $criteria->compare('status', $this->statusFilter, true);
        $criteria->compare('role_id', $this->role_id);
        $criteria->addSearchCondition('userDetails.first_name', $this->first_name);
        $criteria->addSearchCondition('userDetails.last_name', $this->last_name);
        $criteria->with = array('userDetails');
        $criteria->order = 'status ,t.id DESC';
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Users the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    
}