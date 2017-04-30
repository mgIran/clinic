<?php
/* @var $this ExpertisesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Expertises',
);

$this->menu=array(
	array('label'=>'Create Expertises', 'url'=>array('create')),
	array('label'=>'Manage Expertises', 'url'=>array('admin')),
);
?>

<h1>Expertises</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
