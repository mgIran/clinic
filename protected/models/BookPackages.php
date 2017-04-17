<?php

/**
 * This is the model class for table "{{book_packages}}".
 *
 * The followings are the available columns in table '{{book_packages}}':
 * @property string $id
 * @property string $book_id
 * @property string $version
 * @property string $package_name
 * @property string $isbn
 * @property string $pdf_file_name
 * @property string $epub_file_name
 * @property string $create_date
 * @property string $publish_date
 * @property string $reason
 * @property string $for
 * @property string $price
 * @property integer $sale_printed
 * @property string $printed_price
 * @property string $print_year
 *
 * The followings are the available model relations:
 * @property Books $book
 */
class BookPackages extends CActiveRecord
{
    const FOR_NEW_BOOK = 'new_book';
    const FOR_OLD_BOOK = 'old_book';
    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REFUSED = 'refused';
    const STATUS_CHANGE_REQUIRED = 'change_required';

    public $forLabels = array(
        'new_book' => '<span class="label label-success">کتاب جدید</span>',
        'old_book' => '<span class="label label-warning">کتاب تغییر داده شده</span>',
    );

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{book_packages}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('book_id, isbn, print_year', 'required'),
            array('pdf_file_name', 'orRequired', 'other' => 'epub_file_name'),
            array('book_id, price, printed_price', 'length', 'max' => 10),
            array('print_year', 'length', 'max' => 8),
            array('version, isbn, create_date, publish_date', 'length', 'max' => 20),
            array('sale_printed, price, printed_price, print_year, version', 'numerical', 'integerOnly' => true),
            array('isbn, create_date, publish_date, reason, print_year', 'filter', 'filter' => 'strip_tags'),
            array('package_name', 'length', 'max' => 100),
            array('pdf_file_name, epub_file_name', 'length', 'max' => 255),
            array('for', 'length', 'max' => 8),
            array('create_date', 'default', 'value' => time()),
            array('isbn', 'isbnChecker'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, book_id, version, package_name, isbn, pdf_file_name, epub_file_name, create_date, publish_date, reason, for, price, sale_printed, printed_price', 'safe', 'on' => 'search'),
        );
    }

    public function orRequired($attribute, $params)
    {
        if (is_null($this->$attribute) and is_null($this->$params['other']))
            $this->addError($attribute, '"' . $this->getAttributeLabel($attribute) . '" و "' . $this->getAttributeLabel($params['other']) . '" نمی توانند خالی باشند. لطفا یکی از آنها را پر کنید.');
    }

    public function isbnChecker($attribute, $params)
    {
        $isbn = str_ireplace('-', '', $this->$attribute);
        if (strlen($isbn) !== 10 && strlen($isbn) !== 13)
            $this->addError($attribute, 'طول رشته شابک اشتباه است.');
        $numbers = str_split($isbn);
        $sum = 0;
        if (strlen($isbn) === 13) {
            foreach ($numbers as $key => $number) {
                $z = 1;
                if ($key % 2)
                    $z = 3;
                $sum += $number * $z;
            }
            if ($sum % 10 !== 0)
                $this->addError($attribute, 'شابک نامعتبر است.');
        } elseif (strlen($isbn) === 10) {
            $z = 10;
            foreach ($numbers as $key => $number) {
                $sum += $number * $z;
                $z--;
            }
            if ($sum % 11 !== 0)
                $this->addError($attribute, 'شابک نامعتبر است.');
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'book' => array(self::BELONGS_TO, 'Books', 'book_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'شناسه',
            'book_id' => 'کتاب',
            'version' => 'نسخه',
            'package_name' => 'نام نوبت چاپ',
            'pdf_file_name' => 'فایل PDF',
            'epub_file_name' => 'فایل EPUB',
            'create_date' => 'تاریخ بارگذاری',
            'publish_date' => 'تاریخ انتشار',
            'reason' => 'دلیل',
            'for' => 'نوع نوبت چاپ',
            'isbn' => 'شابک',
            'price' => 'قیمت نسخه الکترونیک',
            'sale_printed' => 'فروش نسخه چاپی',
            'printed_price' => 'قیمت نسخه چاپی',
            'print_year' => 'سال چاپ',
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

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('book_id', $this->book_id, true);
        $criteria->compare('version', $this->version, true);
        $criteria->compare('package_name', $this->package_name, true);
        $criteria->compare('pdf_file_name', $this->pdf_file_name, true);
        $criteria->compare('epub_file_name', $this->epub_file_name, true);
        $criteria->compare('isbn', $this->isbn, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return BookPackages the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getOffPrice()
    {
        if ($this->book->discount)
            return $this->price - $this->price * $this->book->discount->percent / 100;
        else
            return $this->price;
    }
    

    public function getUploadedFilesType()
    {
        $types = array();
        if (!is_null($this->pdf_file_name))
            $types[] = 'PDF';
        if (!is_null($this->epub_file_name))
            $types[] = 'EPUB';
        return implode(', ', $types);
    }

    public function getUploadedFilesSize()
    {
        $types = array();
        $filePath = Yii::getPathOfAlias("webroot")."/uploads/books/files/";
        if (!is_null($this->pdf_file_name))
            $types[] = 'PDF: '.Controller::fileSize($filePath.$this->pdf_file_name);
        if (!is_null($this->epub_file_name))
            $types[] = 'EPUB: '.Controller::fileSize($filePath.$this->epub_file_name);
        return implode('<br>', $types);
    }
}