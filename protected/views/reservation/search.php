<?
/* @var $this ReservationController */
/* @var $doctors ClinicPersonnels */
/* @var $form CActiveForm */
?>

<div class="inner-page">
    <?php $this->renderPartial('_steps', array('active'=>1));?>

    <div class="filters">
        <div class="container">
            <h4>فیلترها</h4>
            <?php $form = $this->beginWidget('CActiveForm', array(
                'id'=>'filter-form',
            )); ?>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <?php echo $form->textField($doctors,'doctor_name',array('placeholder'=>'پزشک')); ?>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <?php echo $form->textField($doctors,'clinic_name',array('placeholder'=>'بیمارستان / درمانگاه / مطب')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <?php echo CHtml::submitButton('جستجو', array('class'=>'btn-red', 'id'=>'filter-button')); ?>
                </div>
            <?php $this->endWidget();?>
        </div>
    </div>

    <div class="table-container">
        <div class="container table-responsive">
            <?php $this->widget('zii.widgets.grid.CGridView', array(
                'id'=>'doctors-list',
                'dataProvider'=>$doctors->getDoctorsByExp(),
                'itemsCssClass'=>'table table-hover',
                'template'=>'{items}{pager}',
                'columns'=>array(
                    array(
                        'header'=>'پزشک',
                        'value'=>function($data) {
                            /* @var $data ClinicPersonnels */
                            return '<img src="' . $data->user->userDetails->getAvatar() . '" class="img-circle">' . $data->user->userDetails->getShowName();
                        },
                        'type'=>'raw'
                    ),
                    array(
                        'header'=>'تخصص',
                        'value'=>'$data->user->expertises[0]->title'
                    ),
                    array(
                        'header'=>'بیمارستان / درمانگاه / مطب',
                        'value'=>'$data->clinic->clinic_name'
                    ),
                    array(
                        'header'=>'عملیات',
                        'htmlOptions'=>array('class'=>'text-center'),
                        'headerHtmlOptions'=>array('class'=>'text-center'),
                        'value'=>function($data) {
                            /* @var $data ClinicPersonnels */
                            $html = CHtml::beginForm(array('/reservation/selectDoctor'));
                            $html .= CHtml::submitButton('رزرو نوبت', array('class' => 'btn-green btn-sm'));
                            $html .= CHtml::hiddenField('Reservation[doctor_id]', $data->user_id);
                            $html .= CHtml::hiddenField('Reservation[clinic_id]', $data->clinic_id);
                            $html .= CHtml::endForm();
                            return $html;
                        },
                        'type'=>'raw'
                    ),
                ),
            ));?>
        </div>
    </div>
</div>
<?php Yii::app()->clientScript->registerScript('filter', '
    $("body").on("click", "#filter-button", function(){
        $.fn.yiiGridView.update("doctors-list", {data:$("#filter-form").serialize()});
        return false;
    });
');?>