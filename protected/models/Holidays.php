<?php

/**
 * This is the model class for table "{{holidays}}".
 *
 * The followings are the available columns in table '{{holidays}}':
 * @property string $id
 * @property string $title
 * @property string $date
 */
class Holidays extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{holidays}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date', 'required'),
			array('title', 'length', 'max'=>20),
			array('date', 'length', 'max'=>20),
			array('date', 'compare', 'operator' => '>=', 'compareValue'=>strtotime(date('Y/m/d 00:00', time()))),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, date', 'safe', 'on'=>'search'),
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
			'date' => 'تاریخ',
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

		$criteria->compare('title',$this->title,true);
		$criteria->order = 'date DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Holidays the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
