<?php

/**
 * This is the model class for table "{{sessions}}".
 *
 * The followings are the available columns in table '{{sessions}}':
 * @property string $id
 * @property integer $expire
 * @property string $data
 * @property string $user_id
 * @property string $user_type
 * @property string $device_platform
 * @property string $device_ip
 * @property string $device_type
 * @property string $refresh_token
 *
 * The followings are the available model relations:
 * @property Users $user
 * @property Admins $admin
 */
class Sessions extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{sessions}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'required'),
			array('expire', 'numerical', 'integerOnly'=>true),
			array('id', 'length', 'max'=>32),
			array('user_id', 'length', 'max'=>10),
			array('user_type, device_platform', 'length', 'max'=>20),
			array('device_ip', 'length', 'max'=>15),
			array('device_type, refresh_token', 'length', 'max'=>255),
			array('data', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, expire, data, user_id, user_type, device_platform, device_ip, device_type', 'safe', 'on'=>'search'),
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
			'admin' => array(self::BELONGS_TO, 'Admins', 'user_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'expire' => 'تاریخ انقضا',
			'data' => 'اطلاعات',
			'user_id' => 'شناسه کاربر',
			'user_type' => 'نوع کاربر',
			'device_platform' => 'پلتفرم دستگاه',
			'device_ip' => 'آی پی دستگاه',
			'device_type' => 'نوع دستگاه',
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
		$criteria->compare('expire',$this->expire);
		$criteria->compare('data',$this->data,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('user_type',$this->user_type,true);
		$criteria->compare('device_platform',$this->device_platform,true);
		$criteria->compare('device_ip',$this->device_ip,true);
		$criteria->compare('device_type',$this->device_type,true);
        $session_id=session_id();
        $criteria->order = "case when id = '{$session_id}' then 1 else 2 end";

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Sessions the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
