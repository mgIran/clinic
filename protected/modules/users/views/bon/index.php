<?php
/* @var $this UserBonsManageController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'User Bons',
);

$this->menu=array(
	array('label'=>'افزودن ', 'url'=>array('create')),
	array('label'=>'مدیریت ', 'url'=>array('admin')),
);
?>

<h1>User Bons</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
