<?php

/**
 * This is the model class for table "{{book_tag_rel}}".
 *
 * The followings are the available columns in table '{{book_tag_rel}}':
 * @property string $book_id
 * @property string $tag_id
 * @property integer $for_seo
 */
class BookTagRel extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{book_tag_rel}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('book_id, tag_id', 'required'),
			array('for_seo', 'numerical', 'integerOnly'=>true),
			array('book_id, tag_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('book_id, tag_id, for_seo', 'safe', 'on'=>'search'),
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
			'book_id' => 'Book',
			'tag_id' => 'Tag',
			'for_seo' => 'For Seo',
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

		$criteria->compare('book_id',$this->book_id,true);
		$criteria->compare('tag_id',$this->tag_id,true);
		$criteria->compare('for_seo',$this->for_seo);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BookTagRel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	protected function beforeSave()
	{
		if ($this->scenario == 'for_seo')
			$this->for_seo = 1;
		if ($this->scenario == 'not_seo')
			$this->for_seo = 0;
		return parent::beforeSave();
	}
}
