<?php
/* @var $this RolesController */
/* @var $model AdminRoles */
/* @var $actions array */

$this->breadcrumbs=array(
    'پیشخوان'=> array('/admins'),
    'کاربران'=> array('/admins/manage'),
    'نقش کاربران'=>array('admin'),
    'ویرایش',
);

$this->menu=array(
    array('label'=>'مدیریت نقش کاربران', 'url'=>array('admin')),
    array('label'=>'افزودن نقش', 'url'=>array('create')),
);
?>

<h1>ویرایش نقش <?php echo $model->name; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model,'actions'=>$actions)); ?>