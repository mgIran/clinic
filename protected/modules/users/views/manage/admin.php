<?php
/* @var $this UsersManageController */
/* @var $model Users */
/* @var $role string */

$this->breadcrumbs=array(
    'کاربران'=>array('admin'),
    'مدیریت',
);

$this->menu=array(
    array('label'=>'افزودن', 'url'=>array('create')),
);
?>
<? $this->renderPartial('//partial-views/_flashMessage'); ?>
<h1>مدیریت <?= $role==1?'کاربران':'ناشران' ?></h1>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'admins-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'itemsCssClass'=>'table',
    'columns'=>array(
        array(
            'header' => 'نام کامل',
            'value' => '$data->userDetails->getShowName()',
            'filter' => CHtml::activeTextField($model,'first_name')
        ),
        array(
            'header' => 'وضعیت',
            'value' => '$data->statusLabels[$data->status]',
            'filter' => CHtml::activeDropDownList($model,'statusFilter',$model->statusLabels,array('prompt' => 'همه'))
        ),array(
            'header' => 'کلمه عبور',
            'value' => function($data){
                return $data->useGeneratedPassword()?$data->generatePassword():"کلمه عبور توسط کاربر تغییر یافته";
            }
        ),
        array(
            'class'=>'CButtonColumn',
            'buttons' => array(
                'view' => array(
                    'url' => 'Yii::app()->createUrl("/users/manage/view",array("id" => $data->id))'
                )
            )
        )
    )
)); ?>
