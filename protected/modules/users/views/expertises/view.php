<?php
/* @var $this ExpertisesController */
/* @var $model Expertises */

$this->breadcrumbs=array(
	'Expertises'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List Expertises', 'url'=>array('index')),
	array('label'=>'Create Expertises', 'url'=>array('create')),
	array('label'=>'Update Expertises', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Expertises', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Expertises', 'url'=>array('admin')),
);
?>

<h1>View Expertises #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'description',
		'icon',
	),
)); ?>
