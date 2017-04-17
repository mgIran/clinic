<?php
/* @var $this AdminsManageController */
/* @var $model Admins */

$this->breadcrumbs=array(
    'پیشخوان'=> array('/admins'),
    'مدیران'=> array('/admins/manage'),
    'مدیریت'=>array('admin'),
    'ویرایش',
);

$this->menu=array(
    array('label'=>'مدیریت مدیران', 'url'=>array('index')),
    array('label'=>'افزودن مدیر', 'url'=>array('create')),
);
?>

<h1>ویرایش مدیر  <?php echo $model->username; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>