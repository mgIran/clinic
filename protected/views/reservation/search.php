<?
/* @var $this ReservationController */
/* @var $doctors ClinicPersonnels */
/* @var $form CActiveForm */
$ceil_reservation_time = SiteSetting::get('ceil_reservation_time');
?>
    <div class="inner-page">
        <?php $this->renderPartial('_steps', array('active' => 1)); ?>

        <div class="filters">
            <div class="container">
                <h4>فیلترها</h4>
                <?php $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'filter-form',
                )); ?>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <?php echo $form->textField($doctors, 'doctor_name', array('placeholder' => 'پزشک')); ?>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <?php echo $form->textField($doctors, 'clinic_name', array('placeholder' => 'مطب')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <?php echo CHtml::submitButton('جستجو', array('class' => 'btn-red', 'id' => 'filter-button')); ?>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
        <?php if (empty($ceil_reservation_time) || date('H', time()) <= $ceil_reservation_time): ?>
        <div class="table-container">
            <div class="container table-responsive">
                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id' => 'doctors-list',
                    'dataProvider' => $doctors->getDoctorsByExp(),
                    'itemsCssClass' => 'table table-hover',
                    'template' => '{items}{pager}',
                    'columns' => array(
                        array(
                            'header' => 'پزشک',
                            'value' => function ($data) {
                                /* @var $data ClinicPersonnels */
                                return '<img src="' . $data->user->userDetails->getAvatar() . '" class="img-circle">' . $data->user->userDetails->getShowName();
                            },
                            'type' => 'raw'
                        ),
                        array(
                            'header' => 'تخصص',
                            'value' => '$data->user->expertises[0]->title'
                        ),
//                        array(
//                            'header' => 'روزهای حضور در مطب',
//                            'value' => function ($data) {
//                                /* @var $data ClinicPersonnels */
//                                $html = [];
//                                foreach ($data->user->doctorSchedules as $schedule)
//                                    $html[] = DoctorSchedules::$weekDays[$schedule->week_day];
//                                return implode('، ', $html);
//                            }
//                        ),
                        array(
                            'header' => 'عملیات',
                            'htmlOptions' => array('class' => 'text-center'),
                            'headerHtmlOptions' => array('class' => 'text-center'),
                            'value' => function ($data) {
                                /* @var $data ClinicPersonnels */
                                $html = CHtml::beginForm(array('/reservation/selectDoctor'));
                                $html .= CHtml::submitButton('رزرو نوبت', array('class' => 'btn-green btn-sm'));
                                $html .= CHtml::hiddenField('Reservation[doctor_id]', $data->user_id);
                                $html .= CHtml::hiddenField('Reservation[clinic_id]', $data->clinic_id);
                                $html .= CHtml::hiddenField('Reservation[expertise_id]', $data->user->expertises[0]->id);
                                $html .= CHtml::link('پروفایل دکتر و اطلاعات مطب', Yii::app()->createUrl('/users/' . $data->user_id . '/clinic/' . $data->clinic_id), array('class' => 'btn btn-danger btn-sm', 'style' => 'width:auto', 'target' => '_blank'));
                                $html .= CHtml::endForm();
                                return $html;
                            },
                            'type' => 'raw'
                        ),
                    ),
                )); ?>
            </div>
            <?php else: ?>
                <div style="display: flex;height: 400px;align-items: center;vertical-align: middle;text-align: center">
                    <div style="display: inline-block;flex: 1">
                        <h3 style="color: #888888;line-height: 42px">مدت زمان نوبت دهی امروز به پایان رسیده است، لطفا بعدا مراجعه
                            فرمایید. <br><br>
                            زمان پایان نوبت دهی<br>
                            <?= strlen($ceil_reservation_time) == 2?$ceil_reservation_time.":00":$ceil_reservation_time ?>
                        </h3>
                    </div>
                </div>
            <?php endif; ?>
        </div>

    </div>
<?php Yii::app()->clientScript->registerScript('filter', '
    $("body").on("click", "#filter-button", function(){
        $.fn.yiiGridView.update("doctors-list", {data:$("#filter-form").serialize()});
        return false;
    });
'); ?>