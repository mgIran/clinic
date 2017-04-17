<?php
/* @var $this AdminsManageController */
/* @var $model Admins */
/* @var $actions array */

$this->breadcrumbs=array(
    'پیشخوان'=> array('/admins'),
    'مدیران'=> array('/admins/manage'),
	'نقش مدیران'=>array('admin'),
	'افزودن',
);

$this->menu=array(
	array('label'=>'مدیریت نقش مدیران', 'url'=>array('admin')),
);
?>

<h1>افزودن نقش</h1>

<?php $this->renderPartial('_form', array('model'=>$model,'actions'=>$actions)); ?>