<?php
/* @var $this PlacesManageController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Places',
);

$this->menu=array(
	array('label'=>'افزودن ', 'url'=>array('create')),
	array('label'=>'مدیریت ', 'url'=>array('admin')),
);
?>

<h1>Places</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
