<?php

/**
 * This is the model class for table "ym_book_buys".
 *
 * The followings are the available columns in table 'ym_book_buys':
 * @property string $id
 * @property string $book_id
 * @property string $user_id
 * @property string $date
 * @property string $method
 * @property string $package_id
 * @property string $rel_id
 * @property string $price
 * @property string $base_price
 * @property string $publisher_commission
 * @property string $publisher_commission_amount
 * @property string $site_amount
 * @property string $discount_code_type
 * @property string $discount_code_amount
 * @property string $tax_amount
 *
 * The followings are the available model relations:
 * @property Books $book
 * @property Users $user
 * @property BookPackages $package
 * @property UserTransactions $transaction
 */
class BookBuys extends CActiveRecord
{
    const DISCOUNT_CODE_TYPE_PERCENT = 1;
    const DISCOUNT_CODE_TYPE_AMOUNT = 2;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'ym_book_buys';
    }

    public $methodLabels = [
        'credit' => 'اعتبار',
        'gateway' => 'درگاه'
    ];

    public $publisher_id;
    public $year_altField;
    public $month_altField;
    public $from_date_altField;
    public $to_date_altField;
    public $report_type;

    public $totalPrice;
    public $totalBasePrice;
    public $publisherCommissionAmount;
    public $siteAmount;
    public $taxAmount;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('book_id, user_id', 'required'),
            array('date', 'default', 'value' => time()),
            array('book_id, user_id, method, package_id, rel_id, price, base_price, publisher_commission_amount, site_amount, discount_code_amount, tax_amount', 'length', 'max' => 10),
            array('discount_code_type', 'length', 'max' => 1),
            array('publisher_commission', 'length', 'max' => 3),
            array('date', 'length', 'max' => 20),
            array('report_type', 'filter', 'filter'=> 'strip_tags'),
            array('report_type', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, book_id, user_id, method, publisher_id, year_altField, month_altField, to_date_altField, from_date_altField, report_type', 'safe', 'on' => 'search'),
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
            'book' => array(self::BELONGS_TO, 'Books', 'book_id'),
            'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
            'package' => array(self::BELONGS_TO, 'BookPackages', 'package_id'),
            'transaction' => array(self::BELONGS_TO, 'UserTransactions', 'rel_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'book_id' => 'Book',
            'user_id' => 'User',
            'date' => 'تاریخ',
            'method' => 'روش خرید',
            'package_id' => 'نسخه',
            'rel_id' => 'تراکنش',
            'price' => 'مبلغ',
            'base_price' => 'مبلغ پایه',
            'publisher_commission' => 'درصد ناشر',
            'publisher_commission_amount' => 'سهم ناشر',
            'site_amount' => 'سهم سایت',
            'discount_code_type' => 'نوع کد تخفیف',
            'discount_code_amount' => 'مبلغ',
            'tax_amount' => 'مالیات',
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
        $criteria->order='t.id DESC';
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array('pageSize' => isset($_GET['pageSize'])?$_GET['pageSize']:20)
        ));
    }

    /**
     * @param $criteria
     */
    public function reportConditions(&$criteria)
    {
        $criteria->compare('id', $this->id, true);
        $criteria->compare('book_id', $this->book_id, true);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('method', $this->method);
        if($this->publisher_id){
            $criteria->compare('book.publisher_id', $this->publisher_id, true);
            $criteria->with = array('book');
        }
        if($this->report_type){
            switch($this->report_type){
                case 'yearly':
                    $startDate = JalaliDate::toGregorian(JalaliDate::date('Y', $this->year_altField, false), 1, 1);
                    $startTime = strtotime($startDate[0] . '/' . $startDate[1] . '/' . $startDate[2]);
                    $endTime = $startTime + (60 * 60 * 24 * 365);
                    $criteria->addCondition('date >= :start_date');
                    $criteria->addCondition('date <= :end_date');
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
                    $criteria->addCondition('date >= :start_date');
                    $criteria->addCondition('date <= :end_date');
                    $criteria->params[':start_date'] = $startTime;
                    $criteria->params[':end_date'] = $endTime;
                    break;
                case 'by-date':
                    $criteria->addCondition('date >= :start_date');
                    $criteria->addCondition('date <= :end_date');
                    $criteria->params[':start_date'] = $this->from_date_altField;
                    $criteria->params[':end_date'] = $this->to_date_altField;
                    break;
            }
        }
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return BookBuys the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getMethodLabel()
    {
        return $this->methodLabels[$this->method];
    }

    public function getTotalPrice(){
        $criteria = new CDbCriteria;
        $criteria->select = 'SUM(price) as totalPrice';
        $this->reportConditions($criteria);
        $record = $this->find($criteria);
        return $record?$record->totalPrice:0;
    }
    
    public function getTotalBasePrice(){
        $criteria = new CDbCriteria;
        $criteria->select = 'SUM(base_price) as totalBasePrice';
        $this->reportConditions($criteria);
        $record = $this->find($criteria);
        return $record?$record->totalBasePrice:0;
    }

    public function getTotalPublisherCommission(){
        $criteria = new CDbCriteria;
        $criteria->select = 'SUM(publisher_commission_amount) as publisherCommissionAmount';
        $this->reportConditions($criteria);
        $record = $this->find($criteria);
        return $record?$record->publisherCommissionAmount:0;
    }

    public function getTotalSiteCommission(){
        $criteria = new CDbCriteria;
        $criteria->select = 'SUM(site_amount) as siteAmount';
        $this->reportConditions($criteria);
        $record = $this->find($criteria);
        return $record?$record->siteAmount:0;
    }

    public function getTotalTax(){
        $criteria = new CDbCriteria;
        $criteria->select = 'SUM(tax_amount) as taxAmount';
        $this->reportConditions($criteria);
        $record = $this->find($criteria);
        return $record?$record->taxAmount:0;
    }
}