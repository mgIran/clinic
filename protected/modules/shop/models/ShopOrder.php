<?php

/**
 * This is the model class for table "{{shop_order}}".
 *
 * The followings are the available columns in table '{{shop_order}}':
 * @property string $id
 * @property string $user_id
 * @property string $delivery_address_id
 * @property string $billing_address_id
 * @property string $ordering_date
 * @property string $update_date
 * @property string $transaction_id
 * @property string $status
 * @property string $payment_method
 * @property string $shipping_method
 * @property double $payment_amount
 * @property double $discount_amount
 * @property double $price_amount
 * @property double $shipping_price
 * @property double $payment_price
 * @property integer $payment_status
 * @property integer $export_code
 *
 * The followings are the available model relations:
 * @property Users $user
 * @property ShopAddresses $deliveryAddress
 * @property ShopAddresses $billingAddress
 * @property ShopShippingMethod $shippingMethod
 * @property ShopPaymentMethod $paymentMethod
 * @property ShopOrderItems[] $items
 * @property UserTransactions $transaction
 * @property UserTransactions[] $transactions
 */
class ShopOrder extends CActiveRecord
{
	const PAYMENT_STATUS_UNPAID = 0;
	const PAYMENT_STATUS_PAID = 1;

	const STATUS_PENDING = 0;
	const STATUS_ACCEPTED = 1;
	const STATUS_PAID = 2;
	const STATUS_STOCK_PROCESS = 3;
	const STATUS_SENDING = 4;
	const STATUS_DELIVERED = 5;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{shop_order}}';
	}

    private $prefixId = 'KBC-';

    public $statusLabels = [
        self::STATUS_PENDING => 'در انتظار بررسی',
        self::STATUS_ACCEPTED => 'در انتظار پرداخت',
        self::STATUS_PAID => 'پرداخت شده',
        self::STATUS_STOCK_PROCESS => 'پردازش انبار',
        self::STATUS_SENDING => 'در حال ارسال',
        self::STATUS_DELIVERED => 'تحویل شده',
    ];

	public $paymentStatusLabels = [
        self::PAYMENT_STATUS_UNPAID => 'در انتظار پرداخت',
        self::PAYMENT_STATUS_PAID => 'پرداخت موفق'
    ];

    public $tax;

    // Report Properties
	public $report_type;
	public $year_altField;
	public $month_altField;
	public $from_date_altField;
	public $to_date_altField;
    public $totalPrice;
    public $totalPayment;
    public $shippingPrice;
    public $paymentPrice;
    public $totalDiscount;
    public $taxAmount;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, ordering_date, payment_method, shipping_method', 'required'),
			array('payment_amount, discount_amount, price_amount, shipping_price, payment_price', 'numerical'),
			array('user_id, delivery_address_id, billing_address_id, payment_method, shipping_method', 'length', 'max' => 10),
			array('ordering_date, update_date', 'length', 'max' => 20),
			array('transaction_id', 'length', 'max' => 10),
			array('payment_status', 'length', 'max' => 1),
			array('payment_status', 'default', 'value' => 0),
			array('status', 'length', 'max' => 1),
			array('export_code', 'length', 'max' => 100),
			array('export_code', 'required', 'on' => 'export-code'),
			array('export_code', 'numerical', 'integerOnly' => true),
			array('transaction_id', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, delivery_address_id, billing_address_id, ordering_date, update_date, status, payment_method, shipping_method, payment_amount, discount_amount, price_amount, shipping_price, payment_price, payment_status, export_code, year_altField, month_altField, to_date_altField, from_date_altField, report_type', 'safe', 'on' => 'search'),
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
			'deliveryAddress' => array(self::BELONGS_TO, 'ShopAddresses', 'delivery_address_id'),
			'billingAddress' => array(self::BELONGS_TO, 'ShopAddresses', 'billing_address_id'),
			'items' => array(self::HAS_MANY, 'ShopOrderItems', 'order_id'),
			'paymentMethod' => array(self::BELONGS_TO, 'ShopPaymentMethod', 'payment_method'),
			'shippingMethod' => array(self::BELONGS_TO, 'ShopShippingMethod', 'shipping_method'),
			'transaction' => array(self::BELONGS_TO, 'UserTransactions', 'transaction_id'),
			'transactions' => array(self::HAS_MANY, 'UserTransactions', 'type_id', 'order' => 'date DESC'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'شماره رسید',
			'user_id' => 'کاربر',
			'delivery_address_id' => 'آدرس تحویل کالا',
			'billing_address_id' => 'آدرس تحویل فاکتور',
			'ordering_date' => 'تاریخ ثبت سفارش',
			'update_date' => 'تاریخ تغییر وضعیت',
			'status' => 'وضعیت',
			'payment_method' => 'شیوه پرداخت',
			'shipping_method' => 'شیوه ارسال',
			'payment_amount' => 'مبلغ پرداختی',
			'discount_amount' => 'تخفیف',
			'price_amount' => 'مبلغ پایه فاکتور',
			'shipping_price' => 'هزینه ارسال',
			'payment_price' => 'هزینه اضافی پرداخت',
			'transaction_id' => 'تراکنش',
			'payment_status' => 'وضعیت پرداخت',
			'export_code' => 'کد مرسوله',
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

		$id = $this->id;
		$id = str_ireplace($this->prefixId,'',$id);
		$criteria->compare('id', $id, true);
		$criteria->compare('delivery_address_id', $this->delivery_address_id, true);
		$criteria->compare('billing_address_id', $this->billing_address_id, true);
		$criteria->compare('status', $this->status);
		$criteria->compare('payment_method', $this->payment_method);
		$criteria->compare('shipping_method', $this->shipping_method);
		$criteria->compare('payment_amount', $this->payment_amount);
		$criteria->compare('discount_amount', $this->discount_amount);
		$criteria->compare('price_amount', $this->price_amount);
		$criteria->compare('shipping_price', $this->shipping_price);
		$criteria->compare('payment_price', $this->payment_price);
		$criteria->compare('payment_status', $this->payment_status);
		$criteria->compare('export_code', $this->export_code, true);
		$criteria->compare('user_id', $this->user_id);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'pagination' => array('pageSize' => isset($_GET['pageSize'])?$_GET['pageSize']:20)
		));
	}

	public function report($pagination = true){
        $criteria = new CDbCriteria;

        $this->reportConditions($criteria);
        $criteria->order='t.id DESC';
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => $pagination?array('pageSize' => isset($_GET['pageSize'])?$_GET['pageSize']:20):false
        ));
    }

    /**
     * @param $criteria
     */
	public function reportConditions(&$criteria)
    {
        $id = $this->id;
		$id = str_ireplace($this->prefixId,'',$id);
		$criteria->compare('id', $id, true);
		$criteria->compare('delivery_address_id', $this->delivery_address_id, true);
		$criteria->compare('billing_address_id', $this->billing_address_id, true);
		$criteria->compare('status', $this->status);
		$criteria->compare('payment_method', $this->payment_method);
		$criteria->compare('shipping_method', $this->shipping_method);
		$criteria->compare('payment_amount', $this->payment_amount);
		$criteria->compare('discount_amount', $this->discount_amount);
		$criteria->compare('price_amount', $this->price_amount);
		$criteria->compare('shipping_price', $this->shipping_price);
		$criteria->compare('payment_price', $this->payment_price);
		$criteria->compare('payment_status', $this->payment_status);
		$criteria->compare('export_code', $this->export_code, true);
		$criteria->compare('user_id', $this->user_id);
		if($this->report_type){
			switch($this->report_type){
				case 'yearly':
					$startDate = JalaliDate::toGregorian(JalaliDate::date('Y', $this->year_altField, false), 1, 1);
					$startTime = strtotime($startDate[0] . '/' . $startDate[1] . '/' . $startDate[2]);
					$endTime = $startTime + (60 * 60 * 24 * 365);
					$criteria->addCondition('ordering_date >= :start_date');
					$criteria->addCondition('ordering_date <= :end_date');
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
					$criteria->addCondition('ordering_date >= :start_date');
					$criteria->addCondition('ordering_date <= :end_date');
					$criteria->params[':start_date'] = $startTime;
					$criteria->params[':end_date'] = $endTime;
					break;
				case 'by-date':
					$criteria->addCondition('ordering_date >= :start_date');
					$criteria->addCondition('ordering_date <= :end_date');
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
	 * @return ShopOrder the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
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
	public function getPaymentStatusLabel()
	{
		return $this->paymentStatusLabels[$this->payment_status];
	}

	/**
	 * Change Payment Method Status
	 *
	 * @param $new_status integer
	 * @return $this ShopOrder
	 */
	public function setStatus($new_status)
	{
		if(key_exists($new_status, $this->statusLabels)){
			if($this->status !== $new_status)
				$this->status = $new_status;
		}
		return $this;
	}

	/**
	 * Change Payment Status
	 * @return $this
	 */
	public function setPaid()
	{
		$this->payment_status = self::PAYMENT_STATUS_PAID;
		return $this;
	}

	/**
	 * Change Payment Status
	 * @return $this
	 */
	public function setUnpaid()
	{
		$this->payment_status = self::PAYMENT_STATUS_UNPAID;
		return $this;
	}

	public function getOrderID(){
		return $this->prefixId.$this->id;
	}

    /**
     * @return float
     */
    public function getTax(){
        $taxRate = SiteSetting::model()->findByAttributes(array('name' => 'tax'))->value;
		return (double)($this->payment_amount * $taxRate / 100);
	}

    public function getTotalPrice(){
        $criteria = new CDbCriteria;
        $criteria->select = 'SUM(price_amount) as totalPrice';
        $this->reportConditions($criteria);
        $record = $this->find($criteria);
        return $record?$record->totalPrice:0;
    }

    public function getTotalPayment(){
        $criteria = new CDbCriteria;
        $criteria->select = 'SUM(payment_amount) as totalPayment';
        $this->reportConditions($criteria);
        $record = $this->find($criteria);
        return $record?$record->totalPayment:0;
    }

    public function getTotalShippingPrice(){
        $criteria = new CDbCriteria;
        $criteria->select = 'SUM(shipping_price) as shippingPrice';
        $this->reportConditions($criteria);
        $record = $this->find($criteria);
        return $record?$record->shippingPrice:0;
    }

    public function getTotalPaymentPrice(){
        $criteria = new CDbCriteria;
        $criteria->select = 'SUM(payment_price) as paymentPrice';
        $this->reportConditions($criteria);
        $record = $this->find($criteria);
        return $record?$record->paymentPrice:0;
    }


    public function getTotalDiscount(){
        $criteria = new CDbCriteria;
        $criteria->select = 'SUM(discount_amount) as totalDiscount';
        $this->reportConditions($criteria);
        $record = $this->find($criteria);
        return $record?$record->totalDiscount:0;
    }

    public function getTotalTax(){
        $taxRate = SiteSetting::model()->findByAttributes(array('name' => 'tax'))->value;
        $payment = $this->totalPayment?$this->totalPayment:$this->getTotalPayment();
        return (double)($payment * $taxRate / 100);
    }
}