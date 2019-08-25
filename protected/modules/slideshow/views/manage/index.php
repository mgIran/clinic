<?php
/* @var $this SlideShowManageController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Slideshows',
);

$this->menu=array(
	array('label'=>'افزودن تصویر', 'url'=>array('create')),
	array('label'=>'مدیریت', 'url'=>array('admin')),
);
?>

<h1>Slideshows</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
