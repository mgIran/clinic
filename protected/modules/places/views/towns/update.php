<?php
/* @var $this TownsManageController */
/* @var $model Towns */

$this->breadcrumbs=array(
	'مدیریت'=>array('admin'),
	$model->name=>array('views','id'=>$model->id),
	'ویرایش',
);

$this->menu=array(
	array('label'=>'افزودن', 'url'=>array('create')),
    array('label'=>'مدیریت', 'url'=>array('admin')),
);
?>

<h1>ویرایش استان <?php echo $model->name; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>