<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
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
     * @var string email
     */
    public $email;

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
     * @param string $email
     * @param string $password
     * @param string $OAuth
     */
    public function __construct($email, $password, $OAuth = null)
    {
        $this->email = $email;
        $this->password = $password;
        $this->OAuth = $OAuth;
        parent::__construct($email, $password);
    }

    public function authenticate()
    {
        if ($this->OAuth)
            $record = Users::model()->findByAttributes(array('email' => $this->email));
        else {
            $bCrypt = new bCrypt;
            $record = Users::model()->findByAttributes(array('email' => $this->email));
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
                $this->setState('fa_name', $record->userDetails->fa_name);
                $this->setState('en_name', $record->userDetails->en_name);
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