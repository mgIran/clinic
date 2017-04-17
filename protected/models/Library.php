<?php

/**
 * This is the model class for table "{{library}}".
 *
 * The followings are the available columns in table '{{library}}':
 * @property string $book_id
 * @property string $package_id
 * @property string $user_id
 * @property string $download_status
 * @property string $create_date
 *
 * The followings are the available model relations:
 * @property Books $book
 * @property BookPackages $package
 * @property Users $user
 */
class Library extends CActiveRecord
{
    const STATUS_DOWNLOADED_NOT = 0;
    const STATUS_DOWNLOADED = 1;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{library}}';
    }

    public $bookNameFilter;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('book_id' ,'required') ,
            array('book_id, package_id, user_id' ,'length' ,'max' => 10) ,
            array('download_status' ,'length' ,'max' => 1) ,
            array('create_date' ,'length' ,'max' => 20) ,
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('bookNameFilter, book_id, package_id, user_id, download_status, create_date' ,'safe' ,'on' => 'search') ,
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
            'book' => array(self::BELONGS_TO ,'Books' ,'book_id') ,
            'package' => array(self::BELONGS_TO ,'BookPackages' ,'package_id') ,
            'user' => array(self::BELONGS_TO ,'Users' ,'user_id') ,
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'book_id' => 'کتاب' ,
            'package_id' => 'نسخه کتاب' ,
            'user_id' => 'کاربر' ,
            'download_status' => 'وضعیت دانلود' ,
            'create_date' => 'تاریخ ثبت' ,
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
     * @param bool $downloaded
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->with = array('book');
        $criteria->compare('book.title' ,$this->bookNameFilter ,true);
        $criteria->compare('book_id' ,$this->book_id ,true);
        $criteria->compare('package_id' ,$this->package_id ,true);
        $criteria->compare('user_id' ,$this->user_id ,true);
        if($this->download_status == self::STATUS_DOWNLOADED || $this->download_status == self::STATUS_DOWNLOADED_NOT)
            $criteria->compare('download_status' ,$this->download_status);
        $criteria->order = 'create_date DESC';
        return new CActiveDataProvider($this ,array(
            'criteria' => $criteria ,
            'pagination' => array('pageSize' => isset($_GET['pageSize'])?$_GET['pageSize']:20)
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Library the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Add book to user library
     * @param $book_id
     * @param $package_id
     * @param $user_id
     * @param bool $setFlash
     * @return bool
     */
    public static function AddToLib($book_id ,$package_id ,$user_id ,$setFlash = true)
    {
        $model = self::BookExistsInLib($book_id, $package_id, $user_id);
        $flag = false;
        if(!$model){
            $model = new Library();
            $model->book_id = $book_id;
            $model->package_id = $package_id;
            $model->user_id = $user_id;
            $model->download_status = self::STATUS_DOWNLOADED_NOT;
            $model->create_date = time();
            $flag = $model->save()?true:false;

            if($setFlash){
                if($flag)
                    Yii::app()->user->setFlash('success', 'کتاب موردنظر به کتابخانه شما افزوده شد.');
                else
                    Yii::app()->user->setFlash('failed', 'با عرض پوزش! در افزودن کتاب کتاب مشکلی رخ داده است. لطفا مجددا اقدام فرمایید.');
            }
        }
        return $flag;
    }

    /**
     * Check specified book and package exists in library
     * @param $book_id
     * @param $package_id
     * @param $user_id
     * @return bool
     */
    public static function BookExistsInLib($book_id ,$package_id ,$user_id)
    {
        $model = Library::model()->findByAttributes(array(
            'book_id' => $book_id ,
            'package_id' => $package_id ,
            'user_id' => $user_id
        ));
        if($model === null)
            return false;
        else
            return $model;
    }
}