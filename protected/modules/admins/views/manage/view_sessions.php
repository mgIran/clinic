<?php
/* @var $this UsersManageController */
/* @var $model Sessions */

$this->breadcrumbs=array(
    'جلسات کاری فعال',
);
?>

<h3>نمایش دستگاه های متصل</h3>
<? $this->renderPartial('//layouts/_flashMessage') ?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'admins-grid',
    'dataProvider'=>$model->search(),
    'itemsCssClass'=>'table',
    'columns'=>array(
        array(
            'name' => 'expire',
            'value' => function($data){
                return JalaliDate::date('Y/m/d - H:i', $data->expire);
            }
        ),
        'device_platform',
        'device_ip',
        'device_type',
        array(
            'class' => 'CButtonColumn',
            'template' => '{delete}',
            'buttons' => array(
                'delete' => array(
                    'url' => 'Yii::app()->createUrl("admins/manage/removeSession/".$data->id)',
                )
            )
        )
    )
)); ?>