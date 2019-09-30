<?php
/* @var $this ClinicsDoctorController */
/* @var $model DoctorSchedules */
/* @var $search DoctorSchedules */
/* @var $form CActiveForm */
?>
    <div class="container-fluid" style="min-height: 600px">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3>افزودن روز جدید</h3>
        <!--        <p class="description">جهت تعیین برنامه زمانی مرخصی های خود در این کلینیک، لطفا تاریخ موردنظر را به جدول اضافه کنید.</p>-->
        <?php $this->renderPartial('//partial-views/_flashMessage') ?>
        <div class="form well">
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'doctor-schedules',
                'htmlOptions' => array('class' => 'form-inline'),
                'enableAjaxValidation' => false,
                'enableClientValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                ),
            ));
            echo CHtml::hiddenField('insert', true);
            ?>
            <?= $form->errorSummary($model) ?>

            <div class="row">
                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12 relative">
                    <?php echo $form->labelEx($model, 'date'); ?>
                    <?php $this->widget('ext.PDatePicker.PDatePicker', array(
                        'id' => 'date-picker',
                        'model' => $model,
                        'attribute' => 'date',
                        'htmlOptions' => array(
                            'autocomplete' => 'off'
                        ),
                        'options' => array(
                            'format' => 'YYYY/MM/DD',
                        )
                    )); ?>
                    <?php echo $form->error($model, 'date'); ?>
                </div>
                <br>
                <br>
                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <?php echo $form->labelEx($model, 'entry_time_am'); ?>
                    <?php echo $form->dropDownList($model, 'entry_time_am', Controller::parseNumbers(DoctorSchedules::$AM), array('class' => 'selectpicker', 'prompt' => 'انتخاب کنید')); ?>
                    <?php echo $form->error($model, 'entry_time_am'); ?>
                </div>
                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <?php echo $form->labelEx($model, 'exit_time_am'); ?>
                    <?php echo $form->dropDownList($model, 'exit_time_am', Controller::parseNumbers(DoctorSchedules::$AM), array('class' => 'selectpicker', 'prompt' => 'انتخاب کنید')); ?>
                    <?php echo $form->error($model, 'exit_time_am'); ?>
                </div>
                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <?php echo $form->labelEx($model, 'visit_count_am'); ?>
                    <?php echo $form->textField($model, 'visit_count_am', array('style' => 'padding-top:0')); ?>
                    <?php echo $form->error($model, 'visit_count_am'); ?>
                </div>
                <br>
                <br>
                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <?php echo $form->labelEx($model, 'entry_time_pm'); ?>
                    <?php echo $form->dropDownList($model, 'entry_time_pm', Controller::parseNumbers(DoctorSchedules::$PM), array('class' => 'selectpicker', 'prompt' => 'انتخاب کنید')); ?>
                    <?php echo $form->error($model, 'entry_time_pm'); ?>
                </div>
                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <?php echo $form->labelEx($model, 'exit_time_pm'); ?>
                    <?php echo $form->dropDownList($model, 'exit_time_pm', Controller::parseNumbers(DoctorSchedules::$PM), array('class' => 'selectpicker', 'prompt' => 'انتخاب کنید')); ?>
                    <?php echo $form->error($model, 'exit_time_pm'); ?>
                </div>
                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <?php echo $form->labelEx($model, 'visit_count_pm'); ?>
                    <?php echo $form->textField($model, 'visit_count_pm', array('style' => 'padding-top:0')); ?>
                    <?php echo $form->error($model, 'visit_count_pm'); ?>
                </div>
                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 15px">
                    <?php echo CHtml::submitButton('افزودن ', array('class' => 'btn btn-success')); ?>
                </div>
            </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3>مدیریت برنامه زمانی نوبت دهی</h3>
        <!--        <p class="description">مرخصی های آینده خود را از جدول زیر حذف کرده، یا از فرم بالا مرخصی جدید ثبت کنید.</p>-->
        <?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'visits-grid',
            'dataProvider' => $search->search(),
            'itemsCssClass' => 'table',
            'template' => '{items}',
            'columns' => array(
                array(
                    'name' => 'date',
                    'value' => function ($data) {
                        return JalaliDate::date('d F Y', $data->date);
                    }
                ),
                'entry_time_am',
                'exit_time_am',
                'visit_count_am',
                'entry_time_pm',
                'exit_time_pm',
                'visit_count_pm',
//                array(
//                    'class'=>'CButtonColumn',
//                    'template'=>'{delete}',
//                    'buttons' => array(
//                        'delete' => array(
//                            'url' => 'Yii::app()->controller->createUrl("doctor/removeLeaves/".$data->id)'
//                        )
//                    )
//                ),
            ),
        ));
        ?>
    </div>
<?php
Yii::app()->clientScript->registerScript('confirm-form', '
    $("body").on("submit", "#doctor-schedules", function(e){
        e.preventDefault();
        if(confirm("آیا از ثبت این روز اطمینان دارید؟"))
            document.getElementById("doctor-schedules").submit();  
    });
', CClientScript::POS_READY);