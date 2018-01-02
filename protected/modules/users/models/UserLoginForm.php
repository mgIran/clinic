<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class UserLoginForm extends CFormModel
{
	public $verification_field_value;
	public $verification_field;
	public $username;
    public $email;
	public $password;
	public $rememberMe;
	public $OAuth;
    public $authenticate_field;
    public $oauth_authenticate_field;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('verification_field_value, password', 'required' ,'except' => 'OAuth'),
			array('email ,OAuth', 'required' ,'on' => 'OAuth'),
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
            array('email', 'email', 'on' => 'OAuth'),
            array('verification_field_value', 'email', 'on' => 'emailAuth'),
            array('verification_field_value', 'numerical', 'integerOnly' => true, 'on' => 'mobileAuth, nationalAuth'),
            array('verification_field_value', 'length', 'is' => 10, 'on' => 'mobileAuth, nationalAuth'),
			// multiple username
			array('verification_field_value, verification_field', 'safe'),
			array('verification_field_value', 'check', 'fields' => ['national_code', 'mobile', 'email']),
			// authenticate_field needs to be authenticated
			array('verification_field_value', 'authenticate','except' => 'OAuth'),
		);
	}

	public function check($attribute, $params)
	{
		$criteria = new CDbCriteria();
		$criteria->compare('national_code', $this->{$attribute});
		$criteria->limit = 1;
		$national_code = Users::model()->count($criteria);
		$criteria = new CDbCriteria();
		$criteria->compare('mobile', $this->{$attribute});
		$criteria->limit = 1;
		$mobile = UserDetails::model()->count($criteria);
		$criteria = new CDbCriteria();
		$criteria->compare('email', $this->{$attribute});
		$criteria->limit = 1;
		$email = Users::model()->count($criteria);
		if($national_code){
			$this->verification_field = 'national_code';
			$this->scenario = 'nationalAuth';
		}else if($mobile){
			$this->verification_field = 'mobile';
			$this->scenario = 'mobileAuth';
		}else if($email){
			$this->verification_field = 'email';
			$this->scenario = 'emailAuth';
		}
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
            'username' => 'نام کاربری',
            'password' => 'کلمه عبور',
			'rememberMe'=>'مرا بخاطر بسپار',
            'email' => 'پست الکترونیک',
            'authenticate_field' => 'Authenticate Field',
            'verification_field_value' => 'شماره موبایل یا کدملی یا پست الکترونیکی',
		);
	}

	/**
	 * Authenticates the authenticate_field.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			if($this->OAuth)
				$this->_identity = new UserIdentity($this->email,null,$this->OAuth);
			else
				$this->_identity = new UserIdentity($this->verification_field_value,$this->password,null,$this->verification_field);
            if(!$this->_identity->authenticate())
            {
                if($this->_identity->errorCode===3)
                    $this->addError($attribute,'این حساب کاربری فعال نشده است.<br><a href="'.Yii::app()->createUrl('/users/public/resendVerification', array('email'=>$this->email)).'">ارسال مجدد لینک فعال سازی</a>');
                elseif($this->_identity->errorCode===4)
                    $this->addError($attribute,'این حساب کاربری مسدود شده است.');
                elseif($this->_identity->errorCode===5)
                    $this->addError($attribute,'این حساب کاربری حذف شده است.');
                else
                    $this->addError($attribute,'پست الکترونیک یا کلمه عبور اشتباه است .');
            }
		}
	}


	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login($clearIdentity = false)
	{
        if($clearIdentity)
            $this->_identity=null;
		if($this->_identity===null) {
			if ($this->OAuth)
				$this->_identity = new UserIdentity($this->email, null, $this->OAuth);
			else
				$this->_identity = new UserIdentity($this->email, $this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			if(!$this->OAuth)
				$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			else
				$duration=3600*24*30; // 30 days
			Yii::app()->user->login($this->_identity,$duration,$this->OAuth?$this->OAuth:NULL);
			return true;
		}
		else{
			if ($this->OAuth)
				return $this->_identity->errorCode;
			else
				return false;
		}
	}
    protected function afterValidate()
    {
        $this->password = $this->encrypt($this->password);
        return parent::afterValidate();
    }

    public function encrypt($value)
    {
        $enc = NEW bCrypt();
        return $enc->hash($value);
    }

	public function showError(){
		if($this->_identity->errorCode===3)
			return 'این حساب کاربری فعال نشده است.';
		elseif($this->_identity->errorCode===4)
			return 'این حساب کاربری مسدود شده است.';
		elseif($this->_identity->errorCode===5)
			return 'این حساب کاربری حذف شده است.';
		else
			return 'پست الکترونیک یا کلمه عبور اشتباه است .';
	}
}
