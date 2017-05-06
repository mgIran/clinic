<?php
/* @var $this ClinicsManageController */
/* @var $model DoctorSchedules[] */
/* @var $form CActiveForm */
?>

<h3>مدیریت برنامه زمانی نوبت</h3>
<p class="description">جهت تعیین برنامه زمانی نوبت گیری خود در این کلینیک، لطفا جدول هفتگی زیر را پر کنید.</p>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'doctor-schedules',
    'enableAjaxValidation' => false
));
?>
<?php //echo $form->errorSummary($model); ?>
<div class="table-responsive">
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
            $row = $model[$dayNum-1];
            ?>
            <tr>
                <td><?= $day ?></td>
                <td><?php echo CHtml::dropDownList("entry_time_am[$dayNum]",$row->entry_time_am,Controller::parseNumbers(DoctorSchedules::$AM),array('class' => 'selectpicker', 'prompt' => 'انتخاب کنید'));?></td>
                <td><?php echo CHtml::dropDownList("exit_time_am[$dayNum]",$row->exit_time_am,Controller::parseNumbers(DoctorSchedules::$AM),array('class' => 'selectpicker', 'prompt' => 'انتخاب کنید'));?></td>
                <td><?php echo CHtml::textField("visit_count_am[$dayNum]",$row->visit_count_am);?></td>
                <td><?php echo CHtml::dropDownList("entry_time_pm[$dayNum]",$row->entry_time_pm,Controller::parseNumbers(DoctorSchedules::$PM),array('class' => 'selectpicker', 'prompt' => 'انتخاب کنید'));?></td>
                <td><?php echo CHtml::dropDownList("exit_time_pm[$dayNum]",$row->exit_time_pm,Controller::parseNumbers(DoctorSchedules::$PM),array('class' => 'selectpicker', 'prompt' => 'انتخاب کنید'));?></td>
                <td><?php echo CHtml::textField("visit_count_pm[$dayNum]",$row->visit_count_pm);?></td>
            </tr>
            <?php
        endforeach;
        ?>
        </tbody>
    </table>
</div>
<?php
$this->endWidget();
?>
<?//= JalaliDate::date('l', time()) ?>