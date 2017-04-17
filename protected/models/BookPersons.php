<?php

/**
 * This is the model class for table "{{book_persons}}".
 *
 * The followings are the available columns in table '{{book_persons}}':
 * @property string $id
 * @property string $name_family
 * @property string $alias
 * @property string $birthday
 * @property string $deathday
 * @property string $biography
 *
 * The followings are the available model relations:
 * @property BookPersonRoleRel[] $bookPersonRoleRels
 */
class BookPersons extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{book_persons}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name_family', 'required'),
			array('name_family', 'length', 'max'=>100),
			array('alias', 'length', 'max'=>100),
			array('birthday, deathday', 'length', 'max'=>20),
			array('biography', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, family, alias, birthday, deathday, biography', 'safe', 'on'=>'search'),
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
			'roles' => array(self::MANY_MANY, 'BookPersonRoles', '{{book_person_role_rel}}(book_id, person_id, role_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name_family' => 'نام و نام خانوادگی',
			'alias' => 'نام مستعار',
			'birthday' => 'تاریخ تولد',
			'deathday' => 'تاریخ وفات',
			'biography' => 'شرح حال',
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
		$criteria->compare('name_family',$this->name_family,true);
		$criteria->compare('alias',$this->alias,true);
		$criteria->compare('birthday',$this->birthday,true);
		$criteria->compare('deathday',$this->deathday,true);
		$criteria->compare('biography',$this->biography,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BookPersons the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getFullName(){
		return $this->name_family;
	}
}
