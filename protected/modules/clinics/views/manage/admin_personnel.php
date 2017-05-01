<?php
/* @var $this ClinicsManageController */
/* @var $model ClinicPersonnels */

$this->breadcrumbs=array(
    'مطب ها' => array('admin'),
    'مدیریت پرسنل',
);

$this->menu=array(
    array('label'=>'افزودن پرسنل', 'url'=>array('manage/addPersonnel/'.$model->clinic_id)),
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
            'class'=>'CButtonColumn',
            'template'=>'{update} {delete}',
            'buttons' => array(
                'update' => array(
                    'url' => 'Yii::app()->controller->createUrl("manage/updatePersonnel/".$data->clinic_id."/".$data->user_id)'
                )
            )
        ),
    ),
)); ?>
