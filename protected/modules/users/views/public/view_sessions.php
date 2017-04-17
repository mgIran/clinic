<?php
/* @var $this UsersManageController */
/* @var $model Sessions */
?>
<div class="transparent-form">
<h3>دستگاه های فعال</h3>
<p class="description">لیست دستگاه هایی که به حساب شما متصل است.</p>
<? $this->renderPartial('//partial-views/_flashMessage') ?>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'admins-grid',
    'dataProvider'=>$model->search(),
    'itemsCssClass'=>'table',
    'columns'=>array(
        array(
            'header' => 'دستگاه فعلی',
            'value' => function($data){
                return ($data->id == session_id())?'<i class="icon icon-check"></i>':'';
            },
            'htmlOptions' => array('class' => 'text-center'),
            'type' => 'raw'
        ),
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
                    'url' => function($data){
                        if($data->id == session_id())
                            return Yii::app()->createUrl("/logout");
                        else
                            return Yii::app()->createUrl("users/public/removeSession/",array("id" => $data->id));
                    },
                    'click' =>''
                )
            )
        )
    )
)); ?>