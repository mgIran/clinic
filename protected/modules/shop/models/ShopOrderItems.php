<?php

/**
 * This is the model class for table "{{shop_order_items}}".
 *
 * The followings are the available columns in table '{{shop_order_items}}':
 * @property integer $id
 * @property string $order_id
 * @property string $model_name
 * @property string $model_id
 * @property double $payment
 * @property double $base_price
 * @property string $qty
 *
 * The followings are the available model relations:
 * @property ShopOrder $order
 * @property Books $model
 */
class ShopOrderItems extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{shop_order_items}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('order_id, model_id, payment, base_price', 'required'),
			array('payment, base_price', 'numerical'),
			array('order_id, model_id, qty', 'length', 'max'=>10),
			array('model_name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, order_id, model_name, model_id, payment, qty, base_price', 'safe', 'on'=>'search'),
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
			'order' => array(self::BELONGS_TO, 'ShopOrder', 'order_id'),
			'model' => array(self::BELONGS_TO, 'Books', 'model_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'order_id' => 'شناسه سفارش',
			'model_name' => 'نام مدل',
			'model_id' => 'شناسه مدل',
			'base_price' => 'مبلغ پایه',
			'payment' => 'مبلغ پرداختی',
			'qty' => 'تعداد',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('order_id',$this->order_id,true);
		$criteria->compare('model_name',$this->model_name,true);
		$criteria->compare('model_id',$this->model_id,true);
		$criteria->compare('payment',$this->payment);
		$criteria->compare('base_price',$this->base_price);
		$criteria->compare('qty',$this->qty,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ShopOrderItems the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
