<?php

/**
 * This is the model class for table "{{shop_shipping_method}}".
 *
 * The followings are the available columns in table '{{shop_shipping_method}}':
 * @property string $id
 * @property string $title
 * @property string $description
 * @property double $price
 * @property string $status
 * @property string $payment_method
 * @property string $limit_price
 * @property string $order
 * @property string $paymentMethods
 */
class ShopShippingMethod extends SortableCActiveRecord
{
    const STATUS_DEACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{shop_shipping_method}}';
    }

    public $statusLabels = [
        self::STATUS_DEACTIVE => 'غیرفعال',
        self::STATUS_ACTIVE => 'فعال',
    ];

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, price, payment_method', 'required'),
			array('price', 'numerical'),
			array('title', 'length', 'max'=>255),
			array('status', 'length', 'max'=>1),
			array('order, limit_price', 'length', 'max'=>10),
			array('description, payment_method', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, description, price, status, payment_method, order, limit_price', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'عنوان',
			'description' => 'توضیحات',
			'price' => 'هزینه',
			'status' => 'وضعیت',
			'payment_method' => 'روش های پرداخت مجاز',
			'limit_price' => 'سقف خرید برای ارسال رایگان',
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('order',$this->order,true);
		$criteria->compare('limit_price',$this->limit_price,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ShopShippingMethod the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return mixed
	 */
	public function getStatusLabel(){
		return $this->statusLabels[$this->status];
	}

	/**
	 * Change Payment Method Status
	 *
	 * @return $this
	 */
	public function changeStatus(){
		if($this->status == self::STATUS_ACTIVE)
			$this->status = self::STATUS_DEACTIVE;
		else if($this->status == self::STATUS_DEACTIVE)
			$this->status = self::STATUS_ACTIVE;
		return $this;
	}

	public function getPaymentMethods(){
		return CJSON::decode($this->payment_method);
	}

	public function getPaymentMethodObjects(){
		$criteria = new CDbCriteria();
		$criteria->compare('status', ShopPaymentMethod::STATUS_ACTIVE);
		$criteria->addInCondition('id', $this->getPaymentMethods());
		$criteria->order = 't.order';
		return ShopPaymentMethod::model()->findAll($criteria);
	}
}
