<?php
/* @var $this UsersExpertisesController */
/* @var $model Expertises */
/* @var $icon array */

$this->breadcrumbs=array(
	'تخصص ها'=>array('admin'),
	$model->title,
	'ویرایش',
);

$this->menu=array(
	array('label'=>'افزودن تخصص', 'url'=>array('create')),
	array('label'=>'لیست تخصص ها', 'url'=>array('admin')),
);
?>

<h1>ویرایش <?php echo $model->title; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'icon'=>$icon)); ?>