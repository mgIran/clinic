<?php

/**
 * This is the model class for table "ym_places".
 *
 * The followings are the available columns in table 'ym_places':
 * @property string $id
 * @property string $name
 * @property string $town_id
 * @property string $tags
 * @property string $slug
 *
 * The followings are the available model relations:
 * @property Towns $town
 */
class Places extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ym_places';
	}

    public $townName;
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, town_id,slug', 'required'),
			array('name', 'length', 'max'=>200),
			array('town_id', 'length', 'max'=>12),
			array('slug', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, townName,tags,slug', 'safe', 'on'=>'search'),
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
			'town' => array(self::BELONGS_TO, 'Towns', 'town_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'شناسه',
			'name' => 'عنوان',
			'town_id' => 'والد',
            'slug' => 'آدرس',
            'tags' => 'برچسب ها'
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
	public function search($pageSize = 20)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('t.name',$this->name,true);
		$criteria->compare('t.slug',$this->slug,true);
        $criteria->with = array('town');
		$criteria->addSearchCondition('town.name',$this->townName);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'Pagination' => array (
                    'PageSize' => $pageSize
            ),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Places the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getKeywords()
    {

        $model = $this;
        if($model->tags == null || $model->tags == "")
            return $model->town->getKeywords();
        else
            return implode(',' ,CJSON::decode($model->tags));
    }

    protected function beforeSave(){
//        if($this->tags){
//            foreach($this->tags as $tag){
//                $model = Tags::model()->findByAttributes(array('title' => $tag));
//                if(!$model){
//                    $model = new Tags();
//                    $model->title = $tag;
//                    $model->save();
//                }
//            }
//            $this->tags = !empty($this->tags) && is_array($this->tags) ? CJSON::encode($this->tags) : null;
//        }
        return true;
    }
}
