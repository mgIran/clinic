<?php
/* @var $labels array */
/* @var $values array */
?>
<?php echo CHtml::beginForm();?>
<div class="row">
    <div class="col-md-4">
        <?php echo CHtml::label('سال مورد نظر را انتخاب کنید:', 'month');?>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <?php $this->widget('application.extensions.PDatePicker.PDatePicker', array(
            'id'=>'year',
            'value' => isset($_POST['year_altField'])?$_POST['year_altField']:false,
            'options'=>array(
                'format'=>'YYYY',
                'monthPicker'=>'js:{enabled:false}',
                'dayPicker'=>'js:{enabled:false}',
                'yearPicker'=>'js:{enabled:true}',
            ),
            'htmlOptions'=>array(
                'class'=>'form-control'
            ),
        ));?>
    </div>
    <div class="col-md-4">
        <?php echo CHtml::submitButton('جستجو', array(
            'class'=>'btn btn-info',
            'name'=>'show-chart-yearly',
            'id'=>'show-chart-yearly',
        ));?>
    </div>
</div>
<?php echo CHtml::endForm();?>