<?php
/* @var $data BookCategories */
?>
<div class="cat-item">
    <a href="<?= Yii::app()->createUrl('/category/'.$data->id.'/'.urldecode($data->title)) ?>">
        <img src="<?= Yii::app()->baseUrl.'/uploads/bookCategories/images/'.$data->image ?>" alt="<?= $data->title ?>">
        <div class="caption">
            <div class="icon" id="category-icon-<?= $data->id?>"></div>
            <div class="heading"><h4><?= $data->title ?></h4></div>
            <span class="additional"><?= Controller::parseNumbers($data->getBooksCount()) ?> کتاب</span>
        </div>
    </a>
</div>
<?php
Yii::app()->clientScript->registerCss('category-icon-'.$data->id,'#category-icon-'.$data->id.'{background-color:'.$data->icon_color.';background-image:url("'.Yii::app()->baseUrl.'/uploads/bookCategories/icons/'.$data->icon.'");}');