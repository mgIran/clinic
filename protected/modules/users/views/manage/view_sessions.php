<?php
/* @var $this UsersManageController */
/* @var $model Sessions */

$this->breadcrumbs=array(
    'کاربران'=>array('index'),
    'دستگاه های متصل',
);

$this->menu = array(
    array('label' => 'مدیرت کاربران', 'url' => array($model->user->role_id == 2?'adminPublishers':'admin')),
    array('label' => 'نمایش اطلاعات کاربر', 'url' => array("view", 'id' => $model->user->id)),
);
?>

<h3>نمایش دستگاه های متصل
    "<?php echo $model->user->userDetails->fa_name && !empty($model->user->userDetails->fa_name)?$model->user->userDetails->fa_name:$model->user->email; ?>
    "</h3>
<? $this->renderPartial('//partial-views/_flashMessage') ?>

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
                    'url' => 'Yii::app()->createUrl("users/manage/removeSession/",array("id" => $data->id))',
                )
            )
        )
    )
)); ?>