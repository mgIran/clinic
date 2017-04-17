<?php
/* @var $this PublishersBooksController */
/* @var $model BookDiscounts */
/* @var $books [] */
/* @var $form CActiveForm */
?>

<div class="container-fluid" id="books-discount-form-parent">
    <? $this->renderPartial('//partial-views/_loading'); ?>
    <div class="form">
        <?php if($books){?>
        <span class="form-group description text-danger">
            توجه: در صورتی که کتابی را در لیست تخفیفات قرار دهید تا زمانی که مدت آن به اتمام نرسد، امکان ویرایش یا حذف آن وجود ندارد.
        </span>
        <?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'books-discount-form',
            // Please note: When you enable ajax validation, make sure the corresponding
            // controller action is handling ajax validation correctly.
            // There is a call to performAjaxValidation() commented in generated controller code.
            // See class documentation of CActiveForm for details on this.
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
                'afterValidate' => 'js:function(form ,data ,hasError){
                    if(!hasError){
                        var loading = $("#books-discount-form-parent .loading-container");
                        var url = \''.Yii::app()->createUrl('/publishers/panel/discount/?ajax=books-discount-form').'\';
                        submitAjaxForm(form ,url ,loading ,"if(html.status) location.reload();");
                    }
                }'
            )
        ));
        ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'book_id', array('class' => 'control-label')); ?>
            <?php echo $form->dropDownList($model, 'book_id', $books, array('class' => 'form-control')); ?>
            <?php echo $form->error($model, 'book_id'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'start_date', array('class' => 'control-label')); ?>
            <?php echo CHtml::textField('', '', array('id' => 'start_date', 'class' => 'form-control')); ?>
            <?php echo $form->hiddenField($model, 'start_date', array('id' => 'start_date_alt')); ?>
            <?php echo $form->error($model, 'start_date'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'end_date', array('class' => 'control-label')); ?>
            <?php echo CHtml::textField('', '', array('id' => 'end_date', 'class' => 'form-control')); ?>
            <?php echo $form->hiddenField($model, 'end_date', array('id' => 'end_date_alt')); ?>
            <?php echo $form->error($model, 'end_date'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'discount_type', array('class' => 'control-label')); ?>
            <?php echo $form->dropDownList($model, 'discount_type', $model->discountTypeLabels, array('class' => 'form-control', 'maxLength' => 2)); ?>
            <?php echo $form->error($model, 'discount_type'); ?>
        </div>
        <div class="form-group fade hidden" id="percent-field">
            <?php echo $form->labelEx($model, 'percent', array('class' => 'control-label')); ?>
            <?php echo $form->textField($model, 'percent', array('class' => 'form-control', 'maxLength' => 2)); ?>
            <?php echo $form->error($model, 'percent'); ?>
        </div>
        <div class="form-group fade hidden" id="amount-field">
            <?php echo $form->labelEx($model, 'amount', array('class' => 'control-label')); ?>
            <?php echo $form->textField($model, 'amount', array('class' => 'form-control', 'maxLength' => 12)); ?>تومان
            <?php echo $form->error($model, 'amount'); ?>
        </div>

        <div class="form-group buttons">
            <?php echo CHtml::submitButton('ثبت', array('class' => 'btn btn-success')); ?>
        </div>

        <?php $this->endWidget(); ?>

    </div><!-- form -->
    <?
    Yii::app()->clientScript->registerScript('change-discount-type', '
        var $type = $("#BookDiscounts_discount_type").val();
        if($type == 1){
            $("#percent-field").removeClass("hidden").addClass("in");
            $("#amount-field").removeClass("in").addClass("hidden");
        }else{
            $("#amount-field").removeClass("hidden").addClass("in");
            $("#percent-field").removeClass("in").addClass("hidden");
        }
        $("body").on("change", "#BookDiscounts_discount_type", function(){
            var $type = $("#BookDiscounts_discount_type").val();
            if($type == 1){
                $("#percent-field").removeClass("hidden").addClass("in");
                $("#amount-field").removeClass("in").addClass("hidden");
            }else{
                $("#amount-field").removeClass("hidden").addClass("in");
                $("#percent-field").removeClass("in").addClass("hidden");
            }
        });
    ');
    Yii::app()->clientScript->registerScript('datesScript', '
        $(\'#start_date\').persianDatepicker({
            altField: \'#start_date_alt\',
            maxDate:'.(time()*1000).',
            altFormat: \'X\',
            observer: true,
            format: \'DD MMMM YYYY  -  h:mm a\',
            autoClose:false,
            persianDigit: true,
            timePicker:{
                enabled:true
            }
        });


        $(\'#end_date\').persianDatepicker({
            altField: \'#end_date_alt\',
            altFormat: \'X\',
            observer: true,
            format: \'DD MMMM YYYY  -  h:mm a\',
            autoClose:false,
            persianDigit: true,
            timePicker:{
                enabled:true
            }
        });
    ');

    $ss = explode('/', JalaliDate::date("Y/m/d/H/i/s", time(), false));
    $es = explode('/', JalaliDate::date("Y/m/d/H/i/s", time(), false));
    Yii::app()->clientScript->registerScript('dateSets', '
        $("#start_date").persianDatepicker("setDate",['.$ss[0].','.$ss[1].','.$ss[2].','.$ss[3].','.$ss[4].','.$ss[5].']);
        $("#end_date").persianDatepicker("setDate",['.$es[0].','.$es[1].','.$es[2].','.$es[3].',00,00]);
    ');

    }else
        echo 'کتابی برای اعمال تخفیف موجود نیست.';
    ?>
</div>