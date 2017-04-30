<?php
/* @var $this UsersExpertisesController */
/* @var $model Expertises */
/* @var $icon array */

$this->breadcrumbs=array(
	'تخصص ها'=>array('admin'),
	'افزودن',
);

$this->menu=array(
	array('label'=>'لیست تخصص ها', 'url'=>array('admin')),
);
?>

<h1>افزودن تخصص</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'icon' => $icon,)); ?>