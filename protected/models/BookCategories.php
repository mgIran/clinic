<?php

/**
 * This is the model class for table "{{book_categories}}".
 *
 * The followings are the available columns in table '{{book_categories}}':
 * @property string $id
 * @property string $title
 * @property string $parent_id
 * @property string $path
 * @property string $image
 * @property string $icon
 * @property string $icon_color
 *
 *
 * The followings are the available model relations:
 * @property BookCategories $parent
 * @property BookCategories[] $childs
 * @property Books[] $books
 */
class BookCategories extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{book_categories}}';
    }


    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title, icon_color', 'required'),
            array('title', 'unique'),
            array('title', 'length', 'max' => 50),
            array('title', 'compareWithParent'),
            array('parent_id', 'length', 'max' => 10),
            array('parent_id', 'checkParent'),
            array('path', 'length', 'max' => 500),
            array('image, icon', 'length', 'max' => 500),
            array('icon_color', 'length', 'max' => 7),
            array('image, icon, icon_color', 'filter', 'filter' => 'strip_tags'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, title, parent_id', 'safe', 'on' => 'search'),
        );
    }


    public function compareWithParent($attribute, $params)
    {
        if (!empty($this->title) && $this->parent_id) {
            $record = BookCategories::model()->findByAttributes(array('id' => $this->parent_id, 'title' => $this->title));
            if ($record)
                $this->addError($attribute, 'عنوان دسته بندی با عنوان والد یکسان است.');
        }
    }

    public function checkParent($attribute, $params)
    {
        if (!empty($this->parent_id) && $this->id) {
            if ($this->parent_id == $this->id)
                $this->addError($attribute, 'دسته بندی نمی تواند زیرمجموعه خودش باشد.');
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
            'parent' => array(self::BELONGS_TO, 'BookCategories', 'parent_id'),
            'childs' => array(self::HAS_MANY, 'BookCategories', 'parent_id'),
            'books' => array(self::HAS_MANY, 'Books', 'category_id'),
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
            'parent_id' => 'والد',
            'path' => 'مسیر',
            'icon_color' => 'رنگ زمینه آیکون',
            'icon' => 'آیکون',
            'image' => 'تصویر'
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
        $criteria->compare('title', $this->title, true);
        $criteria->compare('parent_id', $this->parent_id);
        $criteria->order = 't.id DESC';
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 100
            )
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return BookCategories the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function sortList($withParent = false)
    {
        $parents = $this->findAll('parent_id IS NULL order by title');
        $list = array();
        foreach ($parents as $parent) {
            if ($withParent)
                array_push($list, $parent);
            $childs = $this->findAll($this->getCategoryChilds($parent->id, false, 'criteria'));
            foreach ($childs as $child)
                array_push($list, $child);
        }
        return CHtml::listData($list, 'id', 'fullTitle');
    }

    public function adminSortList($excludeId = NULL, $withPrompt = true)
    {
        $parents = $this->findAll('parent_id IS NULL order by title');
        $list = array();
        foreach ($parents as $parent) {
            if ($parent->id != $excludeId) {
                array_push($list, $parent);
                $childs = $this->findAll($this->getCategoryChilds($parent->id, false, 'criteria'));
                foreach ($childs as $child) {
                    if ($child->id != $excludeId && $child->parent_id != $excludeId)
                        array_push($list, $child);
                }
            }
        }
        return $withPrompt ? CMap::mergeArray(array('' => '-'), CHtml::listData($list, 'id', 'fullTitle')) : CHtml::listData($list, 'id', 'fullTitle');
    }

    public function getParents($id = NULL)
    {
        if ($id)
            $parents = $this->findAll('parent_id = :id order by title', array(':id' => $id));
        else
            $parents = $this->findAll('parent_id IS NULL order by title');
        $list = array();
        foreach ($parents as $parent) {
            array_push($list, $parent);
        }
        return CHtml::listData($list, 'id', 'fullTitle');
    }

    public function getFullTitle()
    {
        $fullTitle = $this->title;
        $model = $this;
        while ($model->parent) {
            $model = $model->parent;
            if ($model->parent)
                $fullTitle = $model->title . ' - ' . $fullTitle;
            else
                $fullTitle = $fullTitle . ' (' . $model->title . ')';
        }
        return $fullTitle;
    }

    public function beforeSave()
    {
        if (empty($this->parent_id))
            $this->parent_id = NULL;
        $this->path = null;
        return parent::beforeSave();
    }

    protected function afterSave()
    {
        $this->updatePath($this->id);
        return true;
    }

    public function getCategoryChilds($id = null, $withSelf = true, $returnType = 'array')
    {
        if ($id)
            $this->id = $id;
        $criteria = new CDbCriteria();
        $criteria->addCondition('path LIKE :regex1', 'OR');
        $criteria->addCondition('path LIKE :regex2', 'OR');
        $criteria->params[':regex1'] = $this->id . '-%';
        $criteria->params[':regex2'] = '%-' . $this->id . '-%';
        if ($withSelf) {
            $criteria->addCondition('id  = :id', 'OR');
            $criteria->params[':id'] = $this->id;
        }
        if ($returnType === 'array')
            return CHtml::listData($this->findAll($criteria), 'id', 'id');
        elseif ($returnType === 'criteria')
            return $criteria;
    }

    /**
     * Update Path field when model parent_id is changed
     * @param $id
     */
    private function updatePath($id)
    {
        /* @var $model BookCategories */
        $model = BookCategories::model()->findByPk($id);
        if ($model->parent) {
            $path = $model->parent->path ? $model->parent->path . $model->parent_id . '-' : $model->parent_id . '-';
            BookCategories::model()->updateByPk($model->id, array('path' => $path));
        }
        foreach ($model->childs as $child)
            $this->updatePath($child->id);
    }

    public function getValidCategories()
    {
        $criteria = new CDbCriteria();
        $criteria->addCondition('t.image IS NOT NULL');
        $criteria->addCondition('t.icon IS NOT NULL');
        $criteria->addCondition('t.parent_id IS NULL');
        return $criteria;
    }

    public function getBooksCount()
    {
        $catIds = $this->getCategoryChilds();
        $criteria = Books::model()->getValidBooks($catIds);
        return Books::model()->count($criteria);
    }

    public function getPublishersCount()
    {
        return 1;
    }

    public function getCategoriesAsHtml($parentId = false, $tag, $parentTag)
    {
        if (!is_array($parentTag) or !is_array($tag))
            throw new CException('Wrong input parameters in the function getCategoriesAsHtml', 500);

        if (!isset($tag['content']))
            $tag['content'] = '{title}';

        $html = '';
        if (!$parentId) {
            $childs = $this->findAll('parent_id IS NULL');
            $html = CHtml::openTag($parentTag['tagName'], (isset($parentTag['htmlOptions']) ? $parentTag['htmlOptions'] : array()));
        } else
            $childs = $this->findAll('parent_id = :id', array(':id' => $parentId));
        foreach ($childs as $child) {
            $html .= CHtml::openTag($tag['tagName'], (isset($tag['htmlOptions']) ? $tag['htmlOptions'] : array()));
            $content = $tag['content'];
            $content = str_replace('{title}', CHtml::encode($child->title), $content);
            $content = str_replace('{url}', Yii::app()->createUrl('/category/' . $child->id . '/' . urlencode($child->title)), $content);
            $content = str_replace('{booksCount}', $child->getBooksCount(), $content);
            $html .= $content;
            if ($child->childs) {
                $html .= CHtml::openTag('div', array('class' => 'whole-row'));
                $html .= CHtml::closeTag('div');
                $html .= CHtml::openTag($parentTag['tagName'], (isset($parentTag['htmlOptions']) ? $parentTag['htmlOptions'] : array()));
                $html .= $this->getCategoriesAsHtml($child->id, $tag, $parentTag);
                $html .= CHtml::closeTag($parentTag['tagName']);
            }
            $html .= CHtml::closeTag($tag['tagName']);
        }
        if (!$parentId) {
            $html .= CHtml::closeTag($parentTag['tagName']);
        }
        return $html;
    }
}