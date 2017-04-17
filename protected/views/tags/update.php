<?php
/* @var $this ClassTagsManageController */
/* @var $model ClassTags */

$this->breadcrumbs=array(
	'مدیریت برچسب ها'=>array('index'),
	$model->title,
	'ویرایش',
);

$this->menu=array(
	array('label'=>'مدیریت برچسب ها', 'url'=>array('admin')),
	array('label'=>'افزودن برچسب', 'url'=>array('create')),
);
?>

<h1>ویرایش برچسب <?php echo $model->title; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>