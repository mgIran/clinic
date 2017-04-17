<?php

/**
 * This is the model class for table "{{shop_payment_method}}".
 *
 * The followings are the available columns in table '{{shop_payment_method}}':
 * @property string $id
 * @property string $name
 * @property string $title
 * @property string $description
 * @property double $price
 * @property string $status
 * @property string $order
 */
class ShopPaymentMethod extends SortableCActiveRecord
{
	const METHOD_CASH = 'cash';
	const METHOD_GATEWAY = 'gateway';
	const METHOD_CREDIT = 'credit';

	const STATUS_DEACTIVE = 0;
	const STATUS_ACTIVE = 1;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{shop_payment_method}}';
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
			array('title, price', 'required'),
			array('price', 'numerical'),
			array('name', 'length', 'max'=>50),
			array('title', 'length', 'max'=>255),
			array('status', 'length', 'max'=>1),
			array('order', 'length', 'max'=>10),
			array('description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, title, description, price, status, order', 'safe', 'on'=>'search'),
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
			'name' => 'نام روش',
			'title' => 'عنوان',
			'description' => 'توضیحات',
			'price' => 'هزینه اضافی',
			'status' => 'وضعیت',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('status',$this->status,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ShopPaymentMethod the static model class
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
}
