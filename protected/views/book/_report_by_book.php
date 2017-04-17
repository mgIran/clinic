<?php
/* @var $labels array */
/* @var $values array */
$books = CHtml::listData(Books::model()->findAll(Books::model()->publisherBooks()),'id', 'titleAndId');
?>
<?php echo CHtml::beginForm();?>
<div class="row">
    <div class="col-lg-4 col-md-4">
        <?php echo CHtml::label('کتاب', 'book_id');?>
    </div>
    <div class="col-lg-4 col-md-4">
        <?php echo CHtml::label('از تاریخ', 'from_date');?>
    </div>
    <div class="col-lg-4  col-md-4">
        <?php echo CHtml::label('تا تاریخ', 'to_date');?>
    </div>
</div>
<div class="row">
    <div class="col-lg-4 col-md-4">
        <?php echo CHtml::dropDownList('book_id', isset($_POST['book_id'])?$_POST['book_id']:'', $books,array(
            'class' => 'selectpicker',
            'data-live-search' => true,
            'data-width' => '100%',
        ));?>
    </div>
    <div class="col-lg-4 col-md-4">
        <?php $this->widget('application.extensions.PDatePicker.PDatePicker', array(
            'id'=>'from_date',
            'value' => isset($_POST['from_date_altField'])?$_POST['from_date_altField']:false,
            'options'=>array(
                'format'=>'DD MMMM YYYY'
            ),
            'htmlOptions'=>array(
                'class'=>'form-control'
            ),
        ));?>
    </div>
    <div class="col-lg-4 col-md-4">
        <?php $this->widget('application.extensions.PDatePicker.PDatePicker', array(
            'id'=>'to_date',
            'value' => isset($_POST['to_date_altField'])?$_POST['to_date_altField']:false,
            'options'=>array(
                'format'=>'DD MMMM YYYY'
            ),
            'htmlOptions'=>array(
                'class'=>'form-control'
            ),
        ));?>
    </div>
    <div class="col-lg-4 col-md-4">
        <?php echo CHtml::submitButton('جستجو', array(
            'class'=>'btn btn-info',
            'name'=>'show-chart-by-book',
            'id'=>'show-chart-by-book',
        ));?>
    </div>
</div>
<?php echo CHtml::endForm();?>