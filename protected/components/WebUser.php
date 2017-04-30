<?php
class WebUser extends CWebUser
{
    public $allowActiveSessions = false;

    function getRealIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }


    /**
     * Overrides a Yii method that is used for roles in controllers (accessRules).
     *
     * @param string $operation Name of the operation required (here, a role).
     * @param mixed $params (opt) Parameters for this operation, usually the object to access.
     * @return bool Permission granted?
     */
    public function checkAccess($operation, $params = array())
    {
        if ((is_array($operation) && in_array('admin', $operation)) || $operation === 'admin')
            Yii::app()->user->loginUrl = array('/admins/login');
        else
            Yii::app()->user->loginUrl = array('/login');
        if (empty($this->id)) {
            // Not identified => no rights
            return false;
        }

        $role = $this->getState("roles");

        if ($role === 'admin') {
            return true; // admin role has access to everything
        }
        if (is_array($operation)) { // Check if multiple roles are available
            return (array_search($role, $operation) !== false);
        }
        // allow access if the operation request is the current user's role
        return ($operation === $role);
    }

    public function login($identity, $duration = 0, $OAuth = NULL)
    {
        $id = $identity->getId();
        $identity->setState('OAuth', $OAuth);
        $states = $identity->getPersistentStates();
        $beforeFlag = $this->beforeLogin($id, $states, false);
        if ($beforeFlag === true) {
            $this->changeIdentity($id, $identity->getName(), $states);
            if ($duration > 0) {
                if ($this->allowAutoLogin)
                    $this->saveToCookie($duration);
                else
                    throw new CException(Yii::t('yii', '{class}.allowAutoLogin must be set true in order to use cookie-based authentication.',
                        array('{class}' => get_class($this))));
            }

            if ($this->absoluteAuthTimeout)
                $this->setState(self::AUTH_ABSOLUTE_TIMEOUT_VAR, time() + $this->absoluteAuthTimeout);
            $this->afterLogin(false);
        } else if ($beforeFlag == -1) {
            $allowLogin = Controller::parseNumbers($this->allowActiveSessions);
            throw new CHttpException(401, "شما تنها مجاز به ورود همزمان با {$allowLogin} دستگاه هستید. برای ورود لطفا از حساب کاربری خود در دستگاه دیگر خارج شوید.");
        }
        return !$this->getIsGuest();
    }
}