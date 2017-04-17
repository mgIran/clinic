<?php
/* @var $labels array */
/* @var $values array */
?>
<?php echo CHtml::beginForm();?>
<div class="row">
    <div class="col-md-3">
        <?php echo CHtml::label('ناشر', 'publisher');?>
    </div>
    <div class="col-md-3">
        <?php echo CHtml::label('از تاریخ', 'from_date');?>
    </div>
    <div class="col-md-3">
        <?php echo CHtml::label('تا تاریخ', 'to_date');?>
    </div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-3">
        <?php echo CHtml::dropDownList('publisher', isset($_POST['publisher'])?$_POST['publisher']:'', CHtml::listData(Users::model()->getPublishers()->getData(),'id','userDetails.fa_name'),array(
            'class' => 'selectpicker',
            'data-live-search' => true,
            'data-width' => '100%',
        ));?>
    </div>
    <div class="col-lg-3 col-md-3">
        <?php $this->widget('application.extensions.PDatePicker.PDatePicker', array(
            'id'=>'from_date_publisher',
            'value' => isset($_POST['from_date_publisher_altField'])?$_POST['from_date_publisher_altField']:false,
            'options'=>array(
                'format'=>'DD MMMM YYYY'
            ),
            'htmlOptions'=>array(
                'class'=>'form-control'
            ),
        ));?>
    </div>
    <div class="col-lg-3 col-md-3">
        <?php $this->widget('application.extensions.PDatePicker.PDatePicker', array(
            'id'=>'to_date_publisher',
            'value' => isset($_POST['to_date_publisher_altField'])?$_POST['to_date_publisher_altField']:false,
            'options'=>array(
                'format'=>'DD MMMM YYYY'
            ),
            'htmlOptions'=>array(
                'class'=>'form-control'
            ),
        ));?>
    </div>
    <div class="col-lg-3 col-md-3">
        <?php echo CHtml::submitButton('جستجو', array(
            'class'=>'btn btn-info',
            'name'=>'show-chart-by-publisher',
            'id'=>'show-chart-by-publisher',
        ));?>
    </div>
</div>
<?php echo CHtml::endForm();?>