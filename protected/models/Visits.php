<?php

/**
 * This is the model class for table "{{visits}}".
 *
 * The followings are the available columns in table '{{visits}}':
 * @property string $id
 * @property string $user_id
 * @property string $clinic_id
 * @property string $doctor_id
 * @property string $date
 * @property string $time
 * @property string $status
 * @property string $tracking_code
 * @property string $check_date
 * @property string $clinic_checked_number
 *
 * The followings are the available model relations:
 * @property Users $user
 * @property Clinics $clinic
 * @property Users $doctor
 */
class Visits extends CActiveRecord
{
    const TIME_AM = 1;
    const TIME_PM = 2;

    const STATUS_DELETED = 0;
    const STATUS_PENDING = 1;
    const STATUS_ACCEPTED = 2;
    const STATUS_CLINIC_CHECKED = 3;
    const STATUS_CLINIC_VISITED = 4;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{visits}}';
    }

    public $timeLabels = [
        self::TIME_AM => 'صبح',
        self::TIME_PM => 'بعدازظهر'
    ];

    public $statusLabels = [
        self::STATUS_DELETED => 'حذف شده',
        self::STATUS_PENDING => 'در انتظار تایید',
        self::STATUS_ACCEPTED => 'تایید شده',
        self::STATUS_CLINIC_CHECKED => 'حضور در مطب',
        self::STATUS_CLINIC_VISITED => 'ویزیت شده',
    ];

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('clinic_checked_number, user_id, clinic_id, doctor_id', 'length', 'max' => 10),
            array('check_date, date, tracking_code', 'length', 'max' => 20),
            array('time, status', 'length', 'max' => 1),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, user_id, clinic_id, doctor_id, check_date, date, time, status, tracking_code, clinic_checked_number', 'safe', 'on' => 'search'),
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
            'clinic' => array(self::BELONGS_TO, 'Clinics', 'clinic_id'),
            'doctor' => array(self::BELONGS_TO, 'Users', 'doctor_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'user_id' => 'کاربر',
            'clinic_id' => 'بیمارستان / درمانگاه / مطب',
            'doctor_id' => 'پزشک',
            'date' => 'تاریخ',
            'check_date' => 'تاریخ حضور در مطب',
            'time' => 'نوبت',
            'status' => 'وضعیت',
            'tracking_code' => 'کد رهگیری',
            'clinic_checked_number' => 'شماره نوبت',
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

        $criteria->compare('id', $this->id, true);
        $criteria->compare('user_id', $this->user_id, true);
        $criteria->compare('clinic_id', $this->clinic_id, true);
        $criteria->compare('doctor_id', $this->doctor_id, true);
        $criteria->compare('time', $this->time, true);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('tracking_code', $this->tracking_code, true);
        $criteria->compare('clinic_checked_number', $this->clinic_checked_number);

        if($this->date){
            $toDay = strtotime(date("Y/m/d", $this->date) . " 00:00");
            $toNight = $toDay + 24 * 60 * 60;
            $criteria->addBetweenCondition('date', $toDay, $toNight);
        }
        $criteria->order = 't.date ,t.time, t.status DESC, t.clinic_checked_number';

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array('pageSize' => 50)
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Visits the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return mixed
     */
    public function getTimeLabel()
    {
        return $this->timeLabels[$this->time];
    }

    /**
     * @return mixed
     */
    public function getStatusLabel()
    {
        return $this->statusLabels[$this->status];
    }

    /**
     * @return mixed
     */
    public function getGenerateNewVisitNumber()
    {
        $toDay = strtotime(date("Y/m/d", $this->date) . " 00:00");
        $toNight = $toDay + 24 * 60 * 60;
        $lastNumber = Yii::app()->db->createCommand()
            ->select('MAX(clinic_checked_number)')
            ->from($this->tableName())
            ->where('date BETWEEN :toDay AND :toNight', array(':toDay' => $toDay, ':toNight' => $toNight))
            ->queryScalar();
        if(!$lastNumber)
            return 1;
        else
            return (int)$lastNumber + 1;
    }

    /**
     * @param $clinic
     * @param $doctor
     * @param string $date UNIX timestamp
     * @param $time
     * @param bool $status const DELETED|PENDING|ACCEPTED|CLINIC_CHECKED|CLINIC_VISITED
     * @param string $statusOperator
     * @return int
     */
    public static function getAllVisits($clinic, $doctor, $date, $time, $status = false, $statusOperator='>=')
    {
        $toDay = strtotime(date("Y/m/d", $date) . " 00:00");
        $toNight = $toDay + 24 * 60 * 60;
        $where = 'clinic_id = :clinic_id AND doctor_id = :doctor_id AND (date BETWEEN :toDay AND :toNight) AND time = :time';
        $params = array(':clinic_id' => $clinic, ':doctor_id' => $doctor, ':toDay' => $toDay, ':toNight' => $toNight, ':time' => $time);
        if($status){
            $where .= " AND status {$statusOperator} :checked";
            $params[':checked'] = $status;
        }
        $query = Yii::app()->db->createCommand()
            ->select('COUNT(id)')
            ->from(self::tableName())
            ->where($where, $params)
            ->queryScalar();
        return $query;
    }

    /**
     * @param $clinic
     * @param $doctor
     * @param string $date UNIX timestamp
     * @param $time
     * @return int
     */
    public static function getNowVisit($clinic, $doctor, $date, $time)
    {
        $toDay = strtotime(date("Y/m/d", $date) . " 00:00");
        $toNight = $toDay + 24 * 60 * 60;
        $where = 'clinic_id = :clinic_id AND doctor_id = :doctor_id AND (date BETWEEN :toDay AND :toNight) AND time = :time AND status = :checked_status';
        $params = array(':clinic_id' => $clinic, ':doctor_id' => $doctor, ':toDay' => $toDay, ':toNight' => $toNight, ':time' => $time,':checked_status' => Visits::STATUS_CLINIC_CHECKED);
        $query = Yii::app()->db->createCommand()
            ->select('MIN(clinic_checked_number)')
            ->from(self::tableName())
            ->where($where, $params)
            ->queryScalar();
        return $query?$query:'-';
    }
}