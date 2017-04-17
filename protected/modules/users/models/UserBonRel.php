<?php

/**
 * This is the model class for table "{{user_bon_rel}}".
 *
 * The followings are the available columns in table '{{user_bon_rel}}':
 * @property string $user_id
 * @property string $bon_id
 * @property string $date
 * @property string $amount
 *
 * The followings are the available model relations:
 * @property Users $user
 * @property UserBons $bon
 */
class UserBonRel extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{user_bon_rel}}';
    }

    public $sum;
    public $year_altField;
    public $month_altField;
    public $from_date_altField;
    public $to_date_altField;
    public $report_type;

    public $totalAmount;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, bon_id, date', 'required'),
            array('user_id, bon_id', 'length', 'max' => 10),
            array('date', 'length', 'max' => 20),
            array('date', 'default', 'value' => time()),
            array('amount', 'numerical', 'integerOnly' => true),
            array('amount', 'length', 'max' => 12),
            array('report_type', 'filter', 'filter' => 'strip_tags'),
            array('report_type', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('user_id, bon_id, date, amount, year_altField, month_altField, to_date_altField, from_date_altField, report_type', 'safe', 'on' => 'search'),
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
            'bon' => array(self::BELONGS_TO, 'UserBons', 'bon_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'user_id' => 'کاربر',
            'bon_id' => 'بن',
            'date' => 'تاریخ استفاده',
            'amount' => 'اعتبار افزوده شده',
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

        $this->reportConditions($criteria);
        $criteria->order = 't.date DESC';

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array('pageSize' => isset($_GET['pageSize'])?$_GET['pageSize']:20)
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return UserBonRel the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @param $criteria
     */
    public function reportConditions(&$criteria)
    {
        $criteria->compare('user_id', $this->user_id, true);
        $criteria->compare('bon_id', $this->bon_id, true);
        if($this->report_type){
            switch($this->report_type){
                case 'yearly':
                    $startDate = JalaliDate::toGregorian(JalaliDate::date('Y', $this->year_altField, false), 1, 1);
                    $startTime = strtotime($startDate[0] . '/' . $startDate[1] . '/' . $startDate[2]);
                    $endTime = $startTime + (60 * 60 * 24 * 365);
                    $criteria->addCondition('t.date >= :start_date');
                    $criteria->addCondition('t.date <= :end_date');
                    $criteria->params[':start_date'] = $startTime;
                    $criteria->params[':end_date'] = $endTime;
                    break;
                case 'monthly':
                    $startDate = JalaliDate::toGregorian(JalaliDate::date('Y', $this->month_altField, false), JalaliDate::date('m', $this->month_altField, false), 1);
                    $startTime = strtotime($startDate[0] . '/' . $startDate[1] . '/' . $startDate[2]);
                    if(JalaliDate::date('m', $this->month_altField, false) <= 6)
                        $endTime = $startTime + (60 * 60 * 24 * 31);
                    else
                        $endTime = $startTime + (60 * 60 * 24 * 30);
                    $criteria->addCondition('t.date >= :start_date');
                    $criteria->addCondition('t.date <= :end_date');
                    $criteria->params[':start_date'] = $startTime;
                    $criteria->params[':end_date'] = $endTime;
                    break;
                case 'by-date':
                    $criteria->addCondition('t.date >= :start_date');
                    $criteria->addCondition('t.date <= :end_date');
                    $criteria->params[':start_date'] = $this->from_date_altField;
                    $criteria->params[':end_date'] = $this->to_date_altField;
                    break;
            }
        }
    }

    public function getTotalAmount()
    {
        $criteria = new CDbCriteria;
        $criteria->select = 'SUM(amount) as totalAmount';
        $this->reportConditions($criteria);
        $record = $this->find($criteria);
        return $record?$record->totalAmount:0;
    }
}