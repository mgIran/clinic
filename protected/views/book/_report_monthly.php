<?php
/* @var $labels array */
/* @var $values array */
?>
<?php echo CHtml::beginForm();?>
<div class="row">
    <div class="col-md-4">
        <?php echo CHtml::label('ماه مورد نظر را انتخاب کنید:', 'month');?>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <?php $this->widget('application.extensions.PDatePicker.PDatePicker', array(
            'id'=>'month',
            'value' => isset($_POST['month_altField'])?$_POST['month_altField']:false,
            'options'=>array(
                'format'=>'MMMM YYYY',
                'monthPicker'=>'js:{enabled:true}',
                'dayPicker'=>'js:{enabled:false}',
                'yearPicker'=>'js:{enabled:false}',
            ),
            'htmlOptions'=>array(
                'class'=>'form-control'
            ),
        ));?>
    </div>
    <div class="col-md-4">
        <?php echo CHtml::submitButton('جستجو', array(
            'class'=>'btn btn-info',
            'name'=>'show-chart-monthly',
            'id'=>'show-chart-monthly',
        ));?>
    </div>
</div>
<?php echo CHtml::endForm();?>