<?php
/* @var $this ClinicsPanelController */
/* @var $clinic Clinics */
/* @var $personnel ClinicPersonnels */
?>
<div class="transparent-form">
    <h3>لیست پرسنل</h3>
    <p class="description">کاربرانی که در درمانگاه شما ثبت شده اند.</p>
    <?php $this->renderPartial('//partial-views/_flashMessage');?>
    <div class="buttons">
        <a class="btn btn-success" href="<?= $this->createUrl('manage/addPersonnel/'.$clinic->id) ?>"> افزودن شخص جدید</a>
    </div>
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'clinics-grid',
        'dataProvider'=>$personnel->search(),
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
    ));
    ?>
</div>