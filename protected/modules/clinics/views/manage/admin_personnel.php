<?php
/* @var $this ClinicsManageController */
/* @var $model ClinicPersonnels */

$this->breadcrumbs=array(
    'مطب ها' => array('admin'),
    'مدیریت پرسنل',
);

$this->menu=array(
    array('label'=>'لیست مطب ها', 'url'=>array('admin')),
    array('label'=>'افزودن پرسنل از بین کاربران', 'url'=>array('manage/addPersonnel/'.$model->clinic_id)),
    array('label'=>'ایجاد پرسنل جدید', 'url'=>array('manage/addNewPersonnel/'.$model->clinic_id)),
);
?>

<h1>مدیریت پرسنل مطب</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'clinics-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'itemsCssClass'=>'table',
    'columns'=>array(
        'user.userDetails.first_name',
        'user.userDetails.last_name',
        'post_rel.name',
        array(
            'header' => 'کلمه عبور',
            'value' => function($data){
                return $data->user->useGeneratedPassword()?$data->user->generatePassword():"کلمه عبور توسط کاربر تغییر یافته";
            }
        ),
        array(
            'class'=>'CButtonColumn',
            'template'=>'{update} {delete}',
            'buttons' => array(
                'update' => array(
                    'url' => 'Yii::app()->controller->createUrl("manage/updatePersonnel/".$data->clinic_id."/".$data->user_id)'
                ),
                'delete' => array(
                    'url' => 'Yii::app()->controller->createUrl("manage/removePersonnel/".$data->clinic_id."/".$data->user_id)'
                )
            )
        ),
    ),
)); ?>
