<?php
/* @var $this UsersPublicController */
/* @var $clinics CArrayDataProvider */
/* @var $user Users */
?>
<?php
if($user->useGeneratedPassword()):
?>
    <div class="alert alert-warning">
        بنا به دلایل امنیتی کلمه عبور فعلی شما نیاز به تغییر دارد، لطفا برای تغییر از
        <a href="<?= Yii::app()->createUrl('/users/public/setting') ?>">اینجا</a>
        اقدام فرمایید.
    </div>
<?php
endif;
?>
<?php $this->renderPartial('//partial-views/_flashMessage'); ?>
<?php
if($clinics):
?>
<div class="transparent-form">
    <h3>لیست بیمارستان ها / درمانگاه ها / مطب ها</h3>
    <p class="description">لیست بیمارستان، درمانگاه یا مطب هایی که در آنها عضو هستید.</p>

    <?php $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'expertises-grid',
        'dataProvider'=>$clinics,
        'itemsCssClass'=>'table',
        'template'=>'{items} {pager}',
        'columns'=>array(
            array(
                'name'=>'clinic_name',
                'header'=>Clinics::model()->getAttributeLabel('clinic_name')
            ),
            array(
                'name'=>'town_id',
                'value'=>'$data->town->name',
                'header'=>Clinics::model()->getAttributeLabel('town_id')
            ),
            array(
                'name'=>'place_id',
                'value'=>'$data->place->name',
                'header'=>Clinics::model()->getAttributeLabel('place_id')
            ),
            array(
                'name'=>'address',
                'header'=>Clinics::model()->getAttributeLabel('address')
            ),
            array(
                'header' => 'سمت',
                'value'=>function($data){
                    return UserRoles::model()->findByPk($data->post)->name;
                },
            ),
            array(
                'class'=>'CButtonColumn',
                'template'=>'{enter}',
                'buttons'=>array(
                    'enter'=>array(
                        'label'=>'ورود به بیمارستان / درمانگاه / مطب',
                        'url'=>'CHtml::normalizeUrl(array("/clinics/panel/enter", "id"=>$data->id))',
                        'options'=>array(
                            'class'=>'btn btn-info btn-sm'
                        ),
                    )
                ),
            ),
        ),
    )); ?>
</div>
<?
endif;