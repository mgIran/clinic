<?php
/* @var $this RowsManageController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Rows Homepages',
);

$this->menu=array(
	array('label'=>'افزودن ', 'url'=>array('create')),
	array('label'=>'مدیریت ', 'url'=>array('admin')),
);
?>

<h1>Rows Homepages</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
