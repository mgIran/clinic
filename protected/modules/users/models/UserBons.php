<?php

/**
 * This is the model class for table "{{user_bons}}".
 *
 * The followings are the available columns in table '{{user_bons}}':
 * @property string $id
 * @property string $title
 * @property string $code
 * @property string $amount
 * @property string $start_date
 * @property string $end_date
 * @property integer $user_limit
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property Users[] $users
 * @property UserBonRel[] $rel
 * @property UserBonRel $relOne
 */
class UserBons extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{user_bons}}';
    }

    public $statusLabels = array(
        1 => 'فعال' ,
        0 => 'غیر فعال' ,
    );

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('code, title, start_date, end_date' ,'required') ,
            array('user_limit, status, amount' ,'numerical' ,'integerOnly' => true) ,
            array('code, title' ,'length' ,'max' => 50) ,
            array('code' ,'unique') ,
            array('amount' ,'length' ,'max' => 12) ,
            array('start_date, end_date' ,'length' ,'max' => 20) ,
            array('amount' ,'compare' ,'operator' => '!=' ,'compareValue' => 0 ,'message' => 'مبلغ نمی تواند 0 تومان باشد.') ,
            array('end_date' ,'compare' ,'operator' => '>' ,'compareAttribute' => 'start_date' ,'message' => 'تاریخ پایان باید از تاریخ شروع بیشتر باشد.') ,
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('title, code, amount, start_date, end_date, user_limit, status' ,'safe' ,'on' => 'search') ,
        );
    }

    public function codeGenerator()
    {
        $len = 5;
        $this->code = Controller::generateRandomString($len);
        $i = 0;
        while($this->findByAttributes(array('code' => $this->code))){
            $this->code = Controller::generateRandomString($len);
            $i++;
            if($i > 5)
                $len++;
        }
        return $this->code;
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'users' => array(self::MANY_MANY ,'Users' ,'{{user_bon_rel}}(bon_id, user_id)') ,
            'rel' => array(self::HAS_MANY ,'UserBonRel' ,'bon_id'),
            'relOne' => array(self::HAS_ONE ,'UserBonRel' ,'bon_id')
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'شناسه' ,
            'title' => 'عنوان' ,
            'code' => 'کد بن' ,
            'amount' => 'مبلغ بن' ,
            'start_date' => 'تاریخ شروع' ,
            'end_date' => 'تاریخ انقضا' ,
            'user_limit' => 'محدودیت تعداد کاربر' ,
            'status' => 'وضعیت' ,
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

        $criteria->compare('title' ,$this->title ,true);
        $criteria->compare('code' ,$this->code ,true);
        $criteria->compare('amount' ,$this->amount ,true);
        $criteria->compare('start_date' ,$this->start_date ,true);
        $criteria->compare('end_date' ,$this->end_date ,true);
        $criteria->compare('user_limit' ,$this->user_limit);
        $criteria->compare('status' ,$this->status);
        $criteria->order = 'status DESC,id DESC';
        return new CActiveDataProvider($this ,array(
            'criteria' => $criteria ,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return UserBons the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getStatusLabel()
    {
        return $this->statusLabels[$this->status];
    }

    public function getActiveBons()
    {
        $criteria = new CDbCriteria;
        $criteria->addCondition('status = 1');
        $criteria->order = 'id DESC';
        return $criteria;
    }

    public function getNumberUsed()
    {
        return UserBonRel::model()->count('bon_id = :bon_id' ,array('bon_id' => $this->id));
    }

    public function getSumAmountUsed()
    {
        $criteria = new CDbCriteria();
        $criteria->select = 'SUM(amount) as sum';
        $criteria->condition = 'bon_id = :bon_id';
        $criteria->params[':bon_id'] = $this->id;
        $sumModel = UserBonRel::model()->find($criteria);
        return $sumModel ? $sumModel->sum : null;
    }

    /**
     * @return $this
     */
    public function setDisable()
    {
        $this->status = 0;
        return $this;
    }

    /**
     *
     * check user before used from voucher or not
     * @param $user_id
     * @return bool
     */
    public function checkUserUsed($user_id){
        $criteria = new CDbCriteria();
        $criteria->addCondition('user_id = :user_id');
        $criteria->params[':user_id'] = $user_id;
        return $this->relOne($criteria)?true:false;
    }
}