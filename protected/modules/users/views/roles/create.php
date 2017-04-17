<?php
/* @var $this AdminsManageController */
/* @var $model Admins */
/* @var $actions array */

$this->breadcrumbs=array(
    'پیشخوان'=> array('/admins'),
    'کاربران'=> array('/admins/manage'),
	'نقش کاربران'=>array('admin'),
	'افزودن',
);

$this->menu=array(
	array('label'=>'مدیریت نقش کاربران', 'url'=>array('admin')),
);
?>

<h1>افزودن نقش</h1>

<?php $this->renderPartial('_form', array('model'=>$model,'actions'=>$actions)); ?>