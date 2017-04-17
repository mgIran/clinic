<?php

/**
 * This is the model class for table "ym_user_settlement".
 *
 * The followings are the available columns in table 'ym_user_settlement':
 * @property string $id
 * @property string $user_id
 * @property string $amount
 * @property string $date
 * @property string $account_type
 * @property string $account_owner_name
 * @property string $account_owner_family
 * @property string $account_number
 * @property string $bank_name
 * @property string $iban
 * @property string $token
 *
 * The followings are the available model relations:
 * @property Users $user
 */
class UserSettlement extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ym_user_settlement';
	}


	public $typeLabels = array(
		'real' => 'حقیقی',
		'legal' => 'حقوقی',
	);
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id', 'length', 'max'=>10),
			array('amount', 'length', 'max'=>15),
			array('date', 'length', 'max'=>20),
			array('iban', 'length', 'max'=>24),
			array('token', 'length', 'max'=>255),
			array('account_owner_name, account_owner_family', 'length', 'max' => 50),
			array('account_type', 'safe'),
			array('bank_name, account_number', 'length', 'max' => 50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, amount, date, iban, token', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'شناسه',
			'user_id' => 'کاربر',
			'amount' => 'مبلغ',
			'date' => 'تاریخ',
			'iban' => 'شماره شبا',
			'account_owner_name' => 'نام صاحب حساب',
			'account_owner_family' => 'نام خانوادگی صاحب حساب',
			'account_type' => 'نوع حساب',
			'account_number' => 'شماره حساب',
			'bank_name' => 'نام بانک',
			'token' => 'کد رهگیری',
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
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('iban',$this->iban,true);
		$criteria->compare('token',$this->token,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserSettlement the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
