<?php
/* @var $this TicketDepartmentsController */
/* @var $model TicketDepartments */

$this->breadcrumbs=array(
	'Ticket Departments'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List TicketDepartments', 'url'=>array('index')),
	array('label'=>'Manage TicketDepartments', 'url'=>array('admin')),
);
?>

<h1>Create TicketDepartments</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>