<?php

/**
 * This is the model class for table "{{books}}".
 *
 * The followings are the available columns in table '{{books}}':
 * @property string $id
 * @property string $title
 * @property string $preview_file
 * @property string $icon
 * @property string $description
 * @property integer $number_of_pages
 * @property string $change_log
 * @property string $language
 * @property string $status
 * @property string $category_id
 * @property string $publisher_name
 * @property string $publisher_id
 * @property string $publisher_commission
 * @property string $confirm
 * @property string $confirm_date
 * @property integer $seen
 * @property string $download
 * @property integer $deleted
 * @property integer $size
 * @property integer $price
 * @property integer $printed_price
 * @property integer $off_printed_price
 * @property integer $offPrice
 * @property integer $rate
 *
 *
 * The followings are the available model relations:
 * @property BookPackages $lastPackage
 * @property BookBuys[] $bookBuys
 * @property BookImages[] $images
 * @property Users $publisher
 * @property BookCategories $category
 * @property Users[] $bookmarker
 * @property BookPackages[] $packages
 * @property BookDiscounts $discount
 * @property Advertises $bookAdvertises
 * @property BookPersons[] $persons
 * @property Comment[] $comments
 * @property Tags[] $showTags
 * @property Tags[] $seoTags
 */
class Books extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{books}}';
	}

	private $_purifier;
	public $confirmLabels = array(
		'pending' => 'در حال بررسی',
		'refused' => 'رد شده',
		'accepted' => 'تایید شده',
		'change_required' => 'نیاز به تغییر',
	);
	public $statusLabels = array(
		'enable' => 'فعال',
		'disable' => 'غیر فعال'
	);

	/**
	 * @var string publisher name filter
	 */
	public $devFilter;

	public $formTags = [];
	public $formSeoTags = [];
	public $formAuthor = [];
	public $formTranslator = [];

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{

		$this->_purifier = new CHtmlPurifier();
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, category_id, icon', 'required'),
			array('number_of_pages, seen, deleted, confirm_date', 'numerical', 'integerOnly' => true),
			array('description, change_log, formTags, formSeoTags, formAuthor, formTranslator', 'filter', 'filter' => array($this->_purifier, 'purify')),
			array('title, icon, publisher_name', 'length', 'max' => 50),
			array('number_of_pages', 'length', 'max' => 5),
			array('publisher_id, category_id', 'length', 'max' => 10),
			array('publisher_commission', 'length', 'max' => 3),
			array('language, confirm_date', 'length', 'max' => 20),
			array('language, preview_file', 'filter', 'filter' => 'strip_tags'),
			array('status', 'length', 'max' => 7),
			array('download', 'length', 'max' => 12),
			array('preview_file', 'length', 'max' => 255),
			array('description, change_log, publisher_name, _purifier, formAuthor, formTranslator', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, confirm_date, icon, description, change_log, number_of_pages, language, status, category_id, publisher_name, publisher_id, publisher_commission, confirm, seen, download, deleted ,devFilter', 'safe', 'on' => 'search'),
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
			'bookBuys' => array(self::HAS_MANY, 'BookBuys', 'book_id'),
			'images' => array(self::HAS_MANY, 'BookImages', 'book_id'),
			'publisher' => array(self::BELONGS_TO, 'Users', 'publisher_id'),
			'category' => array(self::BELONGS_TO, 'BookCategories', 'category_id'),
			'discount' => array(self::HAS_ONE, 'BookDiscounts', 'book_id',
				'on' => 'discount.start_date < :time AND discount.end_date > :time',
				'params' => array(':time' => time()),
				'order' => 'discount.id DESC'
			),
			'bookmarker' => array(self::MANY_MANY, 'Users', '{{user_book_bookmark}}(user_id, book_id)'),
			'lastPackage' => array(self::HAS_ONE, 'BookPackages', 'book_id', 'order' => 'lastPackage.publish_date DESC'),
			'packages' => array(self::HAS_MANY, 'BookPackages', 'book_id'),
			'ratings' => array(self::HAS_MANY, 'BookRatings', 'book_id'),
			'advertise' => array(self::BELONGS_TO, 'Advertises', 'id'),
			'showTags' => array(self::MANY_MANY, 'Tags', '{{book_tag_rel}}(book_id,tag_id)', 'on' => 'for_seo = 0'),
			'seoTags' => array(self::MANY_MANY, 'Tags', '{{book_tag_rel}}(book_id,tag_id)', 'on' => 'for_seo = 1'),
			'persons' => array(self::MANY_MANY, 'BookPersons', '{{book_person_role_rel}}(book_id, person_id)'),
			'roles' => array(self::MANY_MANY, 'BookPersonRoles', '{{book_person_role_rel}}(book_id, role_id)'),
			'tagsRel' => array(self::HAS_MANY, 'BookTagRel', 'book_id'),
			'rowRel' => array(self::HAS_MANY, 'RowBookRel', 'book_id'),
			'personRel' => array(self::HAS_MANY, 'BookPersonRoleRel', 'book_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'شناسه',
			'title' => 'عنوان',
			'preview_file' => 'پیش نمایش کتاب',
			'icon' => 'تصویر جلد',
			'description' => 'توضیحات',
			'number_of_pages' => 'تعداد صفحات',
			'language' => 'زبان کتاب',
			'publisher_id' => 'ناشر',
			'publisher_commission' => 'کمیسیون ناشر',
			'category_id' => 'دسته',
			'status' => 'وضعیت',
			'change_log' => 'لیست تغییرات',
			'confirm' => 'وضعیت انتشار',
			'confirm_date' => 'تاریخ انتشار',
			'publisher_name' => 'عنوان ناشر',
			'seen' => 'دیده شده',
			'download' => 'تعداد دریافت',
			'deleted' => 'حذف شده',
			'size' => 'حجم فایل',
			'formTags' => 'برچسب های نمایشی',
			'formSeoTags' => 'برچسب های سئو',
			'formAuthor' => 'نویسندگان',
			'formTranslator' => 'مترجمان',
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
		$criteria->compare('publisher_id', $this->publisher_id);
		$criteria->compare('t.id', $this->id, true);
		$criteria->compare('t.title', $this->title, true);
		$criteria->compare('category_id', $this->category_id);
		$criteria->compare('t.status', $this->status);

		if($this->devFilter){
			$criteria->with = array('publisher', 'publisher.userDetails');
			$criteria->addCondition('publisher_name Like :dev_filter OR  userDetails.fa_name Like :dev_filter OR userDetails.en_name Like :dev_filter OR userDetails.publisher_id Like :dev_filter');
			$criteria->params[':dev_filter'] = '%' . $this->devFilter . '%';
		}

		$criteria->addCondition('deleted=0');
		$criteria->addCondition('t.title != ""');
		$criteria->order = 't.id DESC';

		return new CActiveDataProvider($this ,array(
			'criteria' => $criteria ,
			'pagination' => array('pageSize' => isset($_GET['pageSize'])?$_GET['pageSize']:20)
		));
	}

	public function publisherBooks($publisher_id=false)
	{
		$criteria = new CDbCriteria;
		if($publisher_id)
			$criteria->compare('publisher_id', $publisher_id);
		$criteria->addCondition('deleted=0');
		$criteria->order = 't.id DESC';
		return $criteria;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Books the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Return developer portion
	 *
	 * @param $price float without discount code
	 * @param bool|BookBuys $buy
	 * @return float|string
	 */
	public function getPublisherPortion($price, &$buy = false)
	{
		Yii::app()->getModule('setting');

		// Get commission from book
		$commission = $this->publisher_commission;

		// Get commission from publisher
		if(is_null($commission) or $commission == '')
			$commission = $this->publisher->userDetails->commission;

		// Get commission from setting
		if(is_null($commission) or $commission == '')
			$commission = SiteSetting::model()->findByAttributes(array('name' => 'commission'))->value;
		if(!$this->publisher->userDetails->tax_exempt){
			$tax = SiteSetting::model()->findByAttributes(array('name' => 'tax'))->value;
            $tax = ($price * $tax) / 100;
            $price = $price - $tax;
		}
		$commission_amount = ($price * $commission) / 100;

		// save publisher commission percent and amount in db
		if($buy)
		{
			$buy->publisher_commission = $commission;
			$buy->publisher_commission_amount = $commission_amount;
		}

		return $commission_amount;
	}

    /**
     * Return site portion
     *
     * @param $price float with discount code
     * @param BookBuys $buy
     * @return float
     */
	public function getSitePortion($price, &$buy)
    {
        Yii::app()->getModule('setting');
        $p_a = $buy->publisher_commission_amount?$buy->publisher_commission_amount:0;
        if(!$this->publisher || !$this->publisher->userDetails->tax_exempt){
            $tax = SiteSetting::model()->findByAttributes(array('name' => 'tax'))->value;
            $tax = ($price * $tax) / 100;
            $price = $price - $tax;
            $buy->tax_amount = $tax;
        }
        $site_commission_amount = $price - $p_a;
        return $site_commission_amount;
    }

	protected function afterSave()
	{
		if ($this->formTags && !empty($this->formTags)) {
			if (!$this->IsNewRecord)
				BookTagRel::model()->deleteAll('for_seo = 0 AND book_id=' . $this->id);
			foreach ($this->formTags as $tag) {
				if (!empty($tag)) {
					$tagModel = Tags::model()->findByAttributes(array('title' => $tag));
					if ($tagModel) {
						$tag_rel = new BookTagRel('not_seo');
						$tag_rel->book_id = $this->id;
						$tag_rel->tag_id = $tagModel->id;
						$tag_rel->save();
					} else {
						$tagModel = new Tags;
						$tagModel->title = $tag;
						if ($tagModel->save()) {
							$tag_rel = new BookTagRel('not_seo');
							$tag_rel->book_id = $this->id;
							$tag_rel->tag_id = $tagModel->id;
							$tag_rel->save();
						}
					}
				}
			}
		}
		if ($this->formSeoTags && !empty($this->formSeoTags)) {
			if (!$this->IsNewRecord)
				BookTagRel::model()->deleteAll('for_seo = 1 AND book_id=' . $this->id);
			foreach ($this->formSeoTags as $tag) {
				if (!empty($tag)) {
					$tagModel = Tags::model()->findByAttributes(array('title' => $tag));
					if ($tagModel) {
						$tag_rel = new BookTagRel('for_seo');
						$tag_rel->book_id = $this->id;
						$tag_rel->tag_id = $tagModel->id;
						$tag_rel->save();
					} else {
						$tagModel = new Tags;
						$tagModel->title = $tag;
						if ($tagModel->save()) {
							$tag_rel = new BookTagRel('for_seo');
							$tag_rel->book_id = $this->id;
							$tag_rel->tag_id = $tagModel->id;
							$tag_rel->save();
						}
					}
				}
			}
		}
		if ($this->formAuthor && !empty($this->formAuthor)) {
			$roleId = BookPersonRoles::model()->find('title = :title', array(':title' => 'نویسنده'))->id;
			if (!$this->IsNewRecord)
				BookPersonRoleRel::model()->deleteAll('role_id = :role_id AND book_id= :book_id', array(':book_id' => $this->id, ':role_id' => $roleId));
			foreach ($this->formAuthor as $person) {
				if (!empty($person)) {
					$personModel = BookPersons::model()->findByAttributes(array('name_family' => $person));
					if ($personModel) {
						$person_rel = new BookPersonRoleRel();
						$person_rel->book_id = $this->id;
						$person_rel->person_id = $personModel->id;
						$person_rel->role_id = $roleId;
						$person_rel->save();
					} else {
						$personModel = new BookPersons();
						$personModel->name_family = $person;
						if ($personModel->save()) {
							$person_rel = new BookPersonRoleRel();
							$person_rel->book_id = $this->id;
							$person_rel->person_id = $personModel->id;
							$person_rel->role_id = $roleId;
							$person_rel->save();
						}
					}
				}
			}
		}
		if ($this->formTranslator && !empty($this->formTranslator)) {
			$roleId = BookPersonRoles::model()->find('title = :title', array(':title' => 'مترجم'))->id;
			if (!$this->IsNewRecord)
				BookPersonRoleRel::model()->deleteAll('role_id = :role_id AND book_id= :book_id', array(':book_id' => $this->id, ':role_id' => $roleId));
			foreach ($this->formTranslator as $person) {
				if (!empty($person)) {
					$personModel = BookPersons::model()->findByAttributes(array('name_family' => $person));
					if ($personModel) {
						$person_rel = new BookPersonRoleRel();
						$person_rel->book_id = $this->id;
						$person_rel->person_id = $personModel->id;
						$person_rel->role_id = $roleId;
						$person_rel->save();
					} else {
						$personModel = new BookPersons();
						$personModel->name_family = $person;
						if ($personModel->save()) {
							$person_rel = new BookPersonRoleRel();
							$person_rel->book_id = $this->id;
							$person_rel->person_id = $personModel->id;
							$person_rel->role_id = $roleId;
							$person_rel->save();
						}
					}
				}
			}
		}
		parent::afterSave();
	}

	/**
	 * Return url of book file
	 *
	 * @param string $type is type of file
	 * @return string
	 */
	public function getBookFileUrl($type = 'pdf')
	{
		if (!empty($this->packages))
			return Yii::app()->createUrl("/uploads/books/files/" . $this->lastPackage->{$type . '_file_name'});
		return '';
	}

	public function getPerson($role = NUlL)
	{
		$criteria = new CDbCriteria();
		if ($role) {
			$role = BookPersonRoles::model()->findByAttributes(array('title' => $role));
			if (!$role)
				return null;
			$criteria->compare('role_id', $role->id);
		}
		return $this->persons($criteria);
	}

	public function getPersonsTags($role = NULL, $personProperty = 'fullName', $implode = true, $tag = 'a', $htmlOptions = array())
	{
		$html = array();
		foreach ($this->getPerson($role) as $person) {
			if ($tag == 'a') {
				$link = array('/book/person/' . $person->id . '/' . urldecode($person->fullName));
				$htmlTag = CHtml::link($person->{$personProperty}, $link, $htmlOptions);
			} else
				$htmlTag = CHtml::tag($tag, $htmlOptions, $person->{$personProperty});
			array_push($html, $htmlTag);
		}
		return $implode ? implode(',', $html) : $html;
	}

	public function getPublisherName()
	{
		if ($this->publisher_id)
			return $this->publisher->userDetails->nickname ? $this->publisher->userDetails->nickname : $this->publisher->userDetails->fa_name;
		else
			return $this->publisher_name;
	}

	public function calculateRating()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('book_id', $this->id);
		$result['totalCount'] = BookRatings::model()->count($criteria);
		$criteria->select = array('rate', 'avg(rate) as avgRate');
		$result['totalAvg'] = BookRatings::model()->find($criteria)->avgRate;

		$criteria->addCondition('rate = :rate');
		$criteria->params[':rate'] = 1;
		$result['oneCount'] = BookRatings::model()->count($criteria);
		$result['onePercent'] = $result['totalCount'] ? $result['oneCount'] / $result['totalCount'] * 100 : 0;
		$criteria->params[':rate'] = 2;
		$result['twoCount'] = BookRatings::model()->count($criteria);
		$result['twoPercent'] = $result['totalCount'] ? $result['twoCount'] / $result['totalCount'] * 100 : 0;
		$criteria->params[':rate'] = 3;
		$result['threeCount'] = BookRatings::model()->count($criteria);
		$result['threePercent'] = $result['totalCount'] ? $result['threeCount'] / $result['totalCount'] * 100 : 0;
		$criteria->params[':rate'] = 4;
		$result['fourCount'] = BookRatings::model()->count($criteria);
		$result['fourPercent'] = $result['totalCount'] ? $result['fourCount'] / $result['totalCount'] * 100 : 0;
		$criteria->params[':rate'] = 5;
		$result['fiveCount'] = BookRatings::model()->count($criteria);
		$result['fivePercent'] = $result['totalCount'] ? $result['fiveCount'] / $result['totalCount'] * 100 : 0;
		return $result;
	}

	public function getRate()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('book_id', $this->id);
		$criteria->select = array('rate', 'avg(rate) as avgRate');
		return BookRatings::model()->find($criteria)->avgRate;
	}

	public function getCountRate()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('book_id', $this->id);
		return BookRatings::model()->count($criteria);
	}

	public function userRated($user_id)
	{
		$criteria = new CDbCriteria;
		$criteria->compare('book_id', $this->id);
		$criteria->compare('user_id', $user_id);
		$result = BookRatings::model()->find($criteria);
		return $result ? $result->rate : false;
	}

	/**
	 * Get criteria for valid books
	 *
	 * @param array $categoryIds
	 * @param string $order
	 * @param string $limit
	 * @return CDbCriteria
	 */
	public function getValidBooks($categoryIds = array(), $order = 'confirm_date DESC', $limit = null, $alias = 't')
	{
		$criteria = new CDbCriteria();
		$criteria->addCondition($alias . '.' . 'status=:status');
		$criteria->addCondition('confirm=:confirm');
		$criteria->addCondition('deleted=:deleted');
		$criteria->addCondition('(SELECT COUNT(book_packages.id) FROM ym_book_packages book_packages WHERE book_packages.book_id=' . $alias . '.id) != 0');
		$criteria->params[':status'] = 'enable';
		$criteria->params[':confirm'] = 'accepted';
		$criteria->params[':deleted'] = 0;
		if ($categoryIds)
			$criteria->addInCondition('category_id', $categoryIds);
		$criteria->order = $order;
		if ($limit)
			$criteria->limit = $limit;
		return $criteria;
	}


	public function hasDiscount()
	{
		if ($this->discount && ($this->discount->hasPriceDiscount() || $this->discount->hasPrintedPriceDiscount()))
			return true;
		else
			return false;
	}

	public function getPrice()
	{
		if ($this->lastPackage)
			return $this->lastPackage->price;
		return false;
	}

	public function getPrinted_price()
	{
		if ($this->lastPackage && $this->lastPackage->sale_printed)
			return $this->lastPackage->printed_price;
		return false;
	}

	public function getOffPrice()
	{
		if ($this->lastPackage && $this->discount)
			return $this->discount->getOffPrice();
		else
			return $this->getPrice();
	}

	public function getOff_printed_price()
	{
		if ($this->lastPackage && $this->discount)
			return $this->discount->getOff_printed_price();
		else
			return $this->getPrinted_price();
	}

	public function getComments()
	{
		$criteria = new CDbCriteria();
		$criteria->addCondition('owner_name = :model AND owner_id = :id AND status = 1');
		$criteria->params = array(':model' => get_class($this), ':id' => $this->id);
		return Comment::model()->findAll($criteria);
	}

	public function getCountComments()
	{
		Yii::app()->getModule('comments');
		$criteria = new CDbCriteria();
		$criteria->addCondition('owner_name = :model AND owner_id = :id AND status = 1');
		$criteria->params = array(':model' => get_class($this), ':id' => $this->id);
		return Comment::model()->count($criteria);
	}

	public function getKeywords()
	{
		if ($this->seoTags) {
			$tags = CHtml::listData($this->seoTags, 'title', 'title');
			return implode(',', $tags);
		}
		return false;
	}

	public function getTitleAndId(){
		return $this->id.' - '.$this->title;
	}
}