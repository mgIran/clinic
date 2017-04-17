<?php
/* @var $this TagsManageController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Tags',
);

$this->menu=array(
	array('label'=>'افزودن ', 'url'=>array('create')),
	array('label'=>'مدیریت ', 'url'=>array('admin')),
);
?>

<h1>Tags</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
