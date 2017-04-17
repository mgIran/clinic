<?php

/**
 * This is the model class for table "ym_user_dev_id_requests".
 *
 * The followings are the available columns in table 'ym_user_dev_id_requests':
 * @property string $user_id
 * @property string $requested_id
 *
 * The followings are the available model relations:
 * @property Users $user
 */
class UserDevIdRequests extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ym_user_dev_id_requests';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('requested_id', 'required'),
            array('requested_id', 'unique'),
			array('requested_id', 'unique', 'caseSensitive'=>false,
				  'criteria'=>array(
						  'join'=>'LEFT JOIN ym_user_details as `UserDetails` ON UserDetails.publisher_id=t.requested_id',
						  'condition'=>'UserDetails.publisher_id=t.requested_id',
				  )),
            array('requested_id', 'uniqueInUserDetails'),
            //array('requested_id', 'uniqueInUserDetails'),
			array('user_id', 'length', 'max'=>10),
			array('requested_id', 'length', 'max'=>20, 'min'=>5),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_id, requested_id', 'safe', 'on'=>'search'),
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
			'user_id' => 'کاربر',
			'requested_id' => 'شناسه درخواستی',
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

		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('requested_id',$this->requested_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserDevIdRequests the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * Check the publisherID is unique in userDetails
     */
    public function uniqueInUserDetails($attribute,$params)
    {
        if(!$this->hasErrors())
        {
            $record = UserDetails::model()->findByAttributes( array( 'publisher_id' => $this->requested_id ) );
            if($record)
                $this->addError($attribute, "شناسه درخواستی \"{$this->requested_id}\" در حال حاضر گرفته شده است.");
        }
    }
}
