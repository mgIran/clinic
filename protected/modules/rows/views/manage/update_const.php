<?php
/* @var $this RowsManageController */
/* @var $model RowsHomepage */

$this->breadcrumbs=array(
	'مدیریت ردیف های ثابت'=>array('const'),
	$model->title,
	'ویرایش',
);

$this->menu=array(
    array('label'=>'مدیریت', 'url'=>array('const')),
);
?>

<h1>ویرایش ردیف <?php echo $model->title; ?></h1>
<?php $this->renderPartial('_const_form', array('model'=>$model)); ?>