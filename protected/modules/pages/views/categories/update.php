<?php
/* @var $this PageCategoriesManageController */
/* @var $model PageCategories */

$this->breadcrumbs=array(
	'مدیریت'=>array('admin'),
	$model->name=>array('view','id'=>$model->id),
	'ویرایش',
);

$this->menu=array(
	array('label'=>'افزودن', 'url'=>array('create')),
    array('label'=>'مدیریت', 'url'=>array('admin')),
);
?>

<h1>ویرایش PageCategories <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>