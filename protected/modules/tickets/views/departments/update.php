<?php
/* @var $this TicketDepartmentsController */
/* @var $model TicketDepartments */

$this->breadcrumbs=array(
	'Ticket Departments'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List TicketDepartments', 'url'=>array('index')),
	array('label'=>'Create TicketDepartments', 'url'=>array('create')),
	array('label'=>'View TicketDepartments', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage TicketDepartments', 'url'=>array('admin')),
);
?>

<h1>Update TicketDepartments <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>