<?php
/* @var $this UsersManageController */
/* @var $model Users */
/* @var $role string */

$this->breadcrumbs=array(
    $role==1?'کاربران':'ناشران'=>array($role==1?'admin':'adminPublishers'),
    'مدیریت',
);

$this->menu=array(
    array('label'=>'افزودن', 'url'=>array($role==1?'create':'/publishers/panel/create')),
);
$buttons = array(
    'view' => array(
        'url' => 'Yii::app()->createUrl("/users/manage/view",array("id" => $data->id))'
    ),
    'session' => array(
        'label' => 'دستگاه های متصل',
        'options' => array(
            'class' => 'btn btn-sm btn-warning',
            'style' => 'margin-bottom:5px'
        ),
        'url' => 'Yii::app()->createUrl("/users/manage/sessions/".$data->id)'
    )
);
$columns = array(
    'email',
    array(
        'header' => 'نام کامل',
        'value' => '$data->userDetails->getShowName()',
        'filter' => CHtml::activeTextField($model,'first_name')
    ),
    array(
        'header' => 'وضعیت',
        'value' => '$data->statusLabels[$data->status]',
        'filter' => CHtml::activeDropDownList($model,'statusFilter',$model->statusLabels,array('prompt' => 'همه'))
    ),
    array(
        'class'=>'CButtonColumn',
        'buttons' => $buttons
    ),
);
if($role == 2){

    $buttons = array(
        'update' => array(
            'url' => 'Yii::app()->createUrl("/publishers/panel/update",array("id" => $data->id))'
        ),
        'view' => array(
            'url' => 'Yii::app()->createUrl("/users/manage/view",array("id" => $data->id))'
        ),
    );
    $columns = array(
        'email',
        array(
            'header' => 'نام کامل',
            'value' => '$data->userDetails->fa_name',
            'filter' => CHtml::activeTextField($model, 'fa_name')
        ),
        array(
            'header' => 'وضعیت',
            'value' => '$data->statusLabels[$data->status]',
            'filter' => CHtml::activeDropDownList($model, 'statusFilter', $model->statusLabels, array('prompt' => 'همه'))
        ),
        array(
            'class' => 'CButtonColumn',
            'buttons' => $buttons
        ),
    );
}
?>
<? $this->renderPartial('//partial-views/_flashMessage'); ?>
<h1>مدیریت <?= $role==1?'کاربران':'ناشران' ?></h1>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'admins-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'itemsCssClass'=>'table',
    'columns'=>$columns
)); ?>
