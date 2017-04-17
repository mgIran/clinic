<?php
class WebUser extends CWebUser
{
    public $allowActiveSessions = false;

    function getRealIp()
    {
        if(!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
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
        if((is_array($operation) && in_array('admin', $operation)) || $operation === 'admin')
            Yii::app()->user->loginUrl = array('/admins/login');
        else
            Yii::app()->user->loginUrl = array('/login');
        if(empty($this->id)){
            // Not identified => no rights
            return false;
        }

        $role = $this->getState("roles");

        if($role === 'admin'){
            return true; // admin role has access to everything
        }
        if(is_array($operation)){ // Check if multiple roles are available
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
        if($beforeFlag === true){
            $this->changeIdentity($id, $identity->getName(), $states);
            if($duration > 0){
                if($this->allowAutoLogin)
                    $this->saveToCookie($duration);
                else
                    throw new CException(Yii::t('yii', '{class}.allowAutoLogin must be set true in order to use cookie-based authentication.',
                        array('{class}' => get_class($this))));
            }

            if($this->absoluteAuthTimeout)
                $this->setState(self::AUTH_ABSOLUTE_TIMEOUT_VAR, time() + $this->absoluteAuthTimeout);
            $this->afterLogin(false);
        }else if($beforeFlag == -1){
            $allowLogin = Controller::parseNumbers($this->allowActiveSessions);
            throw new CHttpException(401, "شما تنها مجاز به ورود همزمان با {$allowLogin} دستگاه هستید. برای ورود لطفا از حساب کاربری خود در دستگاه دیگر خارج شوید.");
        }
        return !$this->getIsGuest();
    }

    protected function beforeLogin($id, $states, $fromCookie)
    {
        if($this->allowActiveSessions){
            $activeSessions = Yii::app()->db->createCommand()
                ->select('COUNT(id)')
                ->from('ym_sessions')
                ->where('user_id = :user_id AND user_type=:type', array('user_id' => $id, ':type' => $states['type']))
                ->queryScalar();
            if($activeSessions < $this->allowActiveSessions)
                return true;
            return -1;
        }else
            return true;
    }

    protected function afterLogin($fromCookie, $id = false, $type = false)
    {
//        $device = new DetectDevice();
//        Yii::app()->db->createCommand()
//            ->update('ym_sessions', array(
//                'user_id' => $id?$id:Yii::app()->user->getId(),
//                'user_type' => $type?$type:Yii::app()->user->type,
//                'device_platform' => 'web',
//                'device_ip' => $this->getRealIp(),
//                'device_type' => $device->getDeviceType(),
//            ), 'id = :id', array(':id' => session_id()));
    }

    protected function restoreFromCookie()
    {
        $app = Yii::app();
        $request = $app->getRequest();
        $cookie = $request->getCookies()->itemAt($this->getStateKeyPrefix());
        if($cookie && !empty($cookie->value) && is_string($cookie->value) && ($data = $app->getSecurityManager()->validateData($cookie->value)) !== false){
            $data = @unserialize($data);
            if(is_array($data) && isset($data[0], $data[1], $data[2], $data[3])){
                list($id, $name, $duration, $states) = $data;
                $beforeFlag = $this->beforeLogin($id, $states, true);
                if($beforeFlag === true){
                    $this->changeIdentity($id, $name, $states);
                    if($this->autoRenewCookie){
                        $this->saveToCookie($duration);
                    }
                    $this->afterLogin(true, $id, $states['type']);
                }else if($beforeFlag == -1){
                    $this->saveToCookie($duration - 3600);
                    $allowLogin = Controller::parseNumbers($this->allowActiveSessions);
                    throw new CHttpException(401, "شما تنها مجاز به ورود همزمان با {$allowLogin} دستگاه هستید. برای ورود لطفا از حساب کاربری خود در دستگاه دیگر خارج شوید.");
                }
            }
        }
    }
}