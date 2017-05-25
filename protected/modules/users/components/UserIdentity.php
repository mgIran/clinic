<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    public $defaultAuthenticationField = 'email';
    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     *
     * @var $_id
     */
    private $_id;

    /**
     * @var string verification_field
     */
    public $verification_field;
    /**
     * @var string verification_field
     */
    public $verification_field_value;

    /**
     * @var string OAuth webservice
     */
    public $OAuth;

    /**
     * User status errors
     */
    const ERROR_STATUS_PENDING = 3;
    const ERROR_STATUS_BLOCKED = 4;
    const ERROR_STATUS_DELETED = 5;

    /**
     * UserIdentity constructor.
     * @param string $verification_field_value
     * @param string $password
     * @param string $OAuth
     * @param string $verification_field
     */
    public function __construct($verification_field_value, $password, $OAuth = null, $verification_field = null)
    {
        $this->verification_field = $verification_field;
        if(!$verification_field)
            $this->verification_field = $this->defaultAuthenticationField;
        $this->verification_field_value = $verification_field_value;
        $this->password = $password;
        $this->OAuth = $OAuth;
        parent::__construct($verification_field_value, $password);
    }

    public function authenticate()
    {
        if ($this->OAuth)
            $record = Users::model()->findByAttributes(array($this->verification_field => $this->verification_field_value));
        else {
            $bCrypt = new bCrypt;
            if($this->verification_field == 'mobile')
            {
                $record = UserDetails::model()->findByAttributes(array($this->verification_field => $this->verification_field_value));
                $record = $record && $record->user?$record->user:null;
            }
            else
                $record = Users::model()->findByAttributes(array($this->verification_field => $this->verification_field_value));
        }
        if ($record === null)
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        elseif ($record->status == 'pending')
            $this->errorCode = self::ERROR_STATUS_PENDING;
        elseif ($record->status == 'blocked')
            $this->errorCode = self::ERROR_STATUS_BLOCKED;
        elseif ($record->status == 'deleted')
            $this->errorCode = self::ERROR_STATUS_DELETED;
        elseif ($record->status == 'active') {
            if (!$this->OAuth && !$bCrypt->verify($this->password, $record->password))
                $this->errorCode = self::ERROR_PASSWORD_INVALID;
            else {
                $this->_id = $record->id;
                $this->setState('roles', $record->role->role);
                $this->setState('type', 'user');
                $this->setState('email', $record->email);
                $this->setState('username', $record->username);
                $this->setState('first_name', $record->userDetails->first_name);
                $this->setState('last_name', $record->userDetails->last_name);
                $this->setState('avatar', (is_null($record->userDetails->avatar) ? '' : $record->userDetails->avatar));
                $this->setState('auth_mode', $record->auth_mode);
                $this->errorCode = self::ERROR_NONE;
            }
        }
        return !$this->errorCode;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }
}