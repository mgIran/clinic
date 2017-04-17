<?php
/* @var $this PageCategoriesManageController */
/* @var $model PageCategories */

$this->breadcrumbs=array(
	'مدیریت'=>array('admin'),
	'افزودن',
);

$this->menu=array(
	array('label'=>'مدیریت', 'url'=>array('admin')),
);
?>

<h1>افزودن PageCategories</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>