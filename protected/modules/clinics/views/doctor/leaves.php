<?php
/* @var $this ClinicsDoctorController */
/* @var $model DoctorLeaves */
/* @var $search DoctorLeaves */
/* @var $form CActiveForm */
/* @var $visitsExists string */
?>
<div class="container-fluid" style="min-height: 600px">
    <div class="col-lg-7 col-md-7 col-sm-7 col-ex-12">
        <h3>مدیریت برنامه زمانی مرخصی ها</h3>
        <p class="description">مرخصی های آینده خود را از جدول زیر حذف کرده، یا از فرم بالا مرخصی جدید ثبت کنید.</p>
        <?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'id'=>'leaves-grid',
            'dataProvider'=>$search->search(),
            'itemsCssClass'=>'table',
            'template' => '{items}',
            'columns'=>array(
                array(
                    'name' => 'date',
                    'value' => function($data){
                        return JalaliDate::date('d F Y', $data->date);
                    }
                ),
                array(
                    'class'=>'CButtonColumn',
                    'template'=>'{delete}',
                    'buttons' => array(
                        'delete' => array(
                            'url' => 'Yii::app()->controller->createUrl("doctor/removeLeaves/".$data->id)'
                        )
                    )
                ),
            ),
        ));
        ?>
    </div>
    <div class="col-lg-5 col-md-5 col-sm-5 col-ex-12">
    <h3>افزودن مرخصی جدید</h3>
    <p class="description">جهت تعیین برنامه زمانی مرخص های خود در این کلینیک، لطفا تاریخ موردنظر را به جدول اضافه کنید.</p>
    <?php $this->renderPartial('//partial-views/_flashMessage') ?>
    <div class="form well">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'doctor-schedules',
            'enableAjaxValidation' => false
        ));
        echo CHtml::hiddenField('insert',true);
        ?>
        <div class="form-group col-lg-10 col-md-10 col-sm-10 col-ex-12 relative">
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
        <?php
        if($visitsExists):
            echo CHtml::hiddenField('visitsExists',true);
            echo CHtml::submitButton('لغو نوبت ها و افزودن مرخصی',array('class' => 'btn btn-success', 'style' => 'margin-bottom:10px'));
            echo CHtml::link('نمایش نوبت ها',array('/clinics/doctor/visits/?leaves=true&date='.$model->date),array('class' => 'btn btn-info','style' => 'margin-bottom:10px'));
            echo '<div class="clearfix"></div>';
            echo CHtml::link('انصراف',array('/clinics/doctor/leaves'),array('class' => 'btn btn-danger'));
        else:
            echo CHtml::submitButton('افزودن مرخصی',array('class' => 'btn btn-success'));
        endif;
        ?>
        <?php
        $this->endWidget();
        ?>
    </div>
</div>