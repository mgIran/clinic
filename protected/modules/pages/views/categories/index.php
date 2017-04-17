<?php
/* @var $this PageCategoriesManageController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Page Categories',
);

$this->menu=array(
	array('label'=>'افزودن ', 'url'=>array('create')),
	array('label'=>'مدیریت ', 'url'=>array('admin')),
);
?>

<h1>Page Categories</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
