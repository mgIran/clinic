<?php
/* @var $this ClinicsManageController */
/* @var $model DoctorSchedules[] */
/* @var $form CActiveForm */
/* @var $errors [] */
?>
<!--    <a href="--><?//= $this->createUrl('secretary/doctors/?action=leaves') ?><!--" class="btn btn-info pull-left">بازگشت به لیست پزشکان</a>-->
<!--    <div class="clearfix"></div>-->
    <h3>مدیریت برنامه زمانی نوبت دهی</h3>
    <p class="description">جهت تعیین برنامه زمانی نوبت گیری در این کلینیک، لطفا جدول هفتگی زیر را پر کنید.</p>
<?php $this->renderPartial('//partial-views/_flashMessage') ?>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'doctor-schedules',
    'enableAjaxValidation' => false
));
?>

<?php echo DoctorSchedules::errorSummary($errors); ?>
<?php echo CHtml::submitButton('ثبت / ویرایش',array('class' => 'btn btn-success')) ?>
    <table class="table table-bordered table-hover table-striped table-schedules">
        <thead>
        <tr>
            <th>روز هفته</th>
            <th colspan="3">نوبت صبح</th>
            <th colspan="3">نوبت بعد از ظهر</th>
        </tr>
        <tr>
            <th></th>
            <th>ساعت ورود</th>
            <th>ساعت خروج</th>
            <th>تعداد ویزیت (نفر)</th>
            <th>ساعت ورود</th>
            <th>ساعت خروج</th>
            <th>تعداد ویزیت (نفر)</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach(DoctorSchedules::$weekDays as $dayNum => $day):
            $row = isset($model[$dayNum])?$model[$dayNum]:false;
            ?>
            <tr<?= !$row?' class="disable"':''; ?>>
                <td><div class="row-overlay"></div><?php
                    echo CHtml::checkBox("DoctorSchedules[$dayNum][week_day]",$row?true:false,array('value' =>$dayNum));
                    echo CHtml::label($day, "week_day_{$dayNum}"); ?></td>
                <td><?php echo CHtml::dropDownList("DoctorSchedules[$dayNum][entry_time_am]",$row?$row->entry_time_am:null,Controller::parseNumbers(DoctorSchedules::$AM),array('class' => 'selectpicker', 'prompt' => 'انتخاب کنید'));?></td>
                <td><?php echo CHtml::dropDownList("DoctorSchedules[$dayNum][exit_time_am]",$row?$row->exit_time_am:null,Controller::parseNumbers(DoctorSchedules::$AM),array('class' => 'selectpicker', 'prompt' => 'انتخاب کنید'));?></td>
                <td><?php echo CHtml::textField("DoctorSchedules[$dayNum][visit_count_am]",$row?$row->visit_count_am:null);?></td>
                <td><?php echo CHtml::dropDownList("DoctorSchedules[$dayNum][entry_time_pm]",$row?$row->entry_time_pm:null,Controller::parseNumbers(DoctorSchedules::$PM),array('class' => 'selectpicker', 'prompt' => 'انتخاب کنید'));?></td>
                <td><?php echo CHtml::dropDownList("DoctorSchedules[$dayNum][exit_time_pm]",$row?$row->exit_time_pm:null,Controller::parseNumbers(DoctorSchedules::$PM),array('class' => 'selectpicker', 'prompt' => 'انتخاب کنید'));?></td>
                <td><?php echo CHtml::textField("DoctorSchedules[$dayNum][visit_count_pm]",$row?$row->visit_count_pm:null);?></td>
            </tr>
            <?php
        endforeach;
        ?>
        </tbody>
    </table>
<?php echo CHtml::submitButton('ثبت / ویرایش',array('class' => 'btn btn-success')) ?>
<?php
$this->endWidget();

Yii::app()->clientScript->registerScript('row-disable', '
    $("body").on("change", "tr input[type=checkbox]", function(){
        if($(this).is(":checked"))
            $(this).parents("tr").removeClass("disable");
        else
            $(this).parents("tr").addClass("disable");
    });
');