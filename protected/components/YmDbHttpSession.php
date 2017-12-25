<?php
class YmDbHttpSession extends CHttpSession
{
    /**
     * @var string the ID of a {@link CDbConnection} application component. If not set, a SQLite database
     * will be automatically created and used. The SQLite database file is
     * is <code>protected/runtime/session-YiiVersion.db</code>.
     */
    public $connectionID;
    /**
     * @var string the name of the DB table to store session content.
     * Note, if {@link autoCreateSessionTable} is false and you want to create the DB table manually by yourself,
     * you need to make sure the DB table is of the following structure:
     * <pre>
     * (id CHAR(32) PRIMARY KEY, expire INTEGER, data BLOB)
     * </pre>
     * @see autoCreateSessionTable
     */
    public $sessionTableName='YiiSession';
    /**
     * @var boolean whether the session DB table should be automatically created if not exists. Defaults to true.
     * @see sessionTableName
     */
    public $autoCreateSessionTable=true;
    /**
     * @var CDbConnection the DB connection instance
     */
    private $_db;


    /**
     * Returns a value indicating whether to use custom session storage.
     * This method overrides the parent implementation and always returns true.
     * @return boolean whether to use custom storage.
     */
    public function getUseCustomStorage()
    {
        return true;
    }

    /**
     * Updates the current session id with a newly generated one.
     * Please refer to {@link http://php.net/session_regenerate_id} for more details.
     * @param boolean $deleteOldSession Whether to delete the old associated session file or not.
     * @since 1.1.8
     */
    public function regenerateID($deleteOldSession=false)
    {
        $oldID=session_id();

        // if no session is started, there is nothing to regenerate
        if(empty($oldID))
            return;

        parent::regenerateID(false);
        $newID=session_id();
        $db=$this->getDbConnection();

        $row=$db->createCommand()
            ->select()
            ->from($this->sessionTableName)
            ->where('id=:id',array(':id'=>$oldID))
            ->queryRow();
        if($row!==false)
        {
            if($deleteOldSession)
                $db->createCommand()->update($this->sessionTableName,array(
                    'id'=>$newID
                ),'id=:oldID',array(':oldID'=>$oldID));
            else
            {
                $row['id']=$newID;
                $db->createCommand()->insert($this->sessionTableName, $row);
            }
        }
        else
        {
            // shouldn't reach here normally
            $db->createCommand()->insert($this->sessionTableName, array(
                'id'=>$newID,
                'expire'=>time()+$this->getTimeout(),
                'data'=>'',
            ));
        }
    }

    /**
     * Creates the session DB table.
     * @param CDbConnection $db the database connection
     * @param string $tableName the name of the table to be created
     */
    protected function createSessionTable($db,$tableName)
    {
        switch($db->getDriverName())
        {
            case 'mysql':
                $blob='LONGBLOB';
                break;
            case 'pgsql':
                $blob='BYTEA';
                break;
            case 'sqlsrv':
            case 'mssql':
            case 'dblib':
                $blob='VARBINARY(MAX)';
                break;
            default:
                $blob='BLOB';
                break;
        }
        $db->createCommand()->createTable($tableName,array(
            'id'=>'CHAR(32) PRIMARY KEY',
            'expire'=>'integer',
            'data'=>$blob,
        ));
    }

    /**
     * @return CDbConnection the DB connection instance
     * @throws CException if {@link connectionID} does not point to a valid application component.
     */
    protected function getDbConnection()
    {
        if($this->_db!==null)
            return $this->_db;
        elseif(($id=$this->connectionID)!==null)
        {
            if(($this->_db=Yii::app()->getComponent($id)) instanceof CDbConnection)
                return $this->_db;
            else
                throw new CException(Yii::t('yii','CDbHttpSession.connectionID "{id}" is invalid. Please make sure it refers to the ID of a CDbConnection application component.',
                    array('{id}'=>$id)));
        }
        else
        {
            $dbFile=Yii::app()->getRuntimePath().DIRECTORY_SEPARATOR.'session-'.Yii::getVersion().'.db';
            return $this->_db=new CDbConnection('sqlite:'.$dbFile);
        }
    }

    /**
     * Session open handler.
     * Do not call this method directly.
     * @param string $savePath session save path
     * @param string $sessionName session name
     * @return boolean whether session is opened successfully
     */
    public function openSession($savePath,$sessionName)
    {
        if($this->autoCreateSessionTable)
        {
            $db=$this->getDbConnection();
            $db->setActive(true);
            try
            {
                $db->createCommand()->delete($this->sessionTableName,'expire<:expire AND device_type = :device_type',array(':expire'=>time(), ':device_type' => 'computer'));
            }
            catch(Exception $e)
            {
                $this->createSessionTable($db,$this->sessionTableName);
            }
        }
        return true;
    }

    /**
     * Session read handler.
     * Do not call this method directly.
     * @param string $id session ID
     * @return string the session data
     */
    public function readSession($id)
    {
        $db=$this->getDbConnection();
        if($db->getDriverName()=='sqlsrv' || $db->getDriverName()=='mssql' || $db->getDriverName()=='dblib')
            $select='CONVERT(VARCHAR(MAX), data)';
        else
            $select='data';
        $data=$db->createCommand()
            ->select($select)
            ->from($this->sessionTableName)
            ->where('expire>:expire AND id=:id',array(':expire'=>time(),':id'=>$id))
            ->queryScalar();
        return $data===false?'':$data;
    }

    /**
     * Session write handler.
     * Do not call this method directly.
     * @param string $id session ID
     * @param string $data session data
     * @return boolean whether session write is successful
     */
    public function writeSession($id,$data)
    {
        // exception must be caught in session write handler
        // http://us.php.net/manual/en/function.session-set-save-handler.php
        try
        {
            $device = new DetectDevice();
            $expire=time()+$this->getTimeout();
            $db=$this->getDbConnection();
            if($db->getDriverName()=='sqlsrv' || $db->getDriverName()=='mssql' || $db->getDriverName()=='dblib')
                $data=new CDbExpression('CONVERT(VARBINARY(MAX), '.$db->quoteValue($data).')');
            $session = $db->createCommand()->select('*')->from($this->sessionTableName)->where('id=:id',array(':id'=>$id))->queryRow();
            if($session===false)
                $db->createCommand()->insert($this->sessionTableName,array(
                    'id'=>$id,
                    'data'=>$data,
                    'expire'=>$expire,
                    'user_id' => Yii::app()->user->isGuest?null:Yii::app()->user->getId(),
                    'user_type' => Yii::app()->user->isGuest?null:Yii::app()->user->type,
                    'device_platform' => 'web',
                    'device_ip' => $this->getRealIp(),
                    'device_type' => $device->getDeviceType(),
                    'refresh_token' => Controller::generateRandomString(50),
                ));
            else
                $db->createCommand()->update($this->sessionTableName,array(
                    'data'=>$data,
                    'expire'=>$expire,
                    'user_id' => Yii::app()->user->isGuest?null:Yii::app()->user->getId(),
                    'user_type' => Yii::app()->user->isGuest?null:Yii::app()->user->type,
                    'device_platform' => 'web',
                    'device_ip' => $this->getRealIp(),
                    'device_type' => $device->getDeviceType(),
                    'refresh_token' => $session['refresh_token']?:Controller::generateRandomString(50),
                ),'id=:id',array(':id'=>$id));
        }
        catch(Exception $e)
        {
            if(YII_DEBUG)
                echo $e->getMessage();
            // it is too late to log an error message here
            return false;
        }
        return true;
    }

    /**
     * Session destroy handler.
     * Do not call this method directly.
     * @param string $id session ID
     * @return boolean whether session is destroyed successfully
     */
    public function destroySession($id)
    {
        $this->getDbConnection()->createCommand()
            ->delete($this->sessionTableName,'id=:id',array(':id'=>$id));
        return true;
    }

    /**
     * Session GC (garbage collection) handler.
     * Do not call this method directly.
     * @param integer $maxLifetime the number of seconds after which data will be seen as 'garbage' and cleaned up.
     * @return boolean whether session is GCed successfully
     */
    public function gcSession($maxLifetime)
    {
        $this->getDbConnection()->createCommand()->delete($this->sessionTableName,'expire<:expire AND device_type = :device_type',array(':expire'=>time(), ':device_type' => 'computer'));
        return true;
    }

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
}
