<?php
/* @var $this ClassTagsManageController */
/* @var $model ClassTags */

$this->breadcrumbs=array(
	'مدیریت برچسب ها'=>array('index'),
	'افزودن برچسب',
);

$this->menu=array(
	array('label'=>'مدیریت برچسب ها', 'url'=>array('admin')),
);
?>

<h1>افزودن برچسب</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>