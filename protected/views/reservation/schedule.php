<?
/* @var $this ReservationController */
?>

<div class="inner-page">
    <?php $this->renderPartial('_steps', array('active'=>2));?>

    <div class="page-help">
        <div class="container">
            <h4>انتخاب تاریخ و زمان</h4>
            <ul>
                <li>لطفا یک بازه زمانی مشخص کنید و سپس تاریخ و زمان مد نظر خود را انتخاب کنید.</li>
            </ul>
        </div>
    </div>

    <div class="container">
        <?php echo CHtml::beginForm('', 'post', array('class'=>'select-date-form row'));?>
            <?php $this->renderPartial('//partial-views/_flashMessage');?>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <?php echo CHtml::label('از', 'from');?>
                <?php $this->widget('ext.PDatePicker.PDatePicker', array(
                    'id'=>'from',
                    'value' => isset($_POST['from_altField'])?$_POST['from_altField']:false,
                    'options'=>array(
                        'format'=>'DD MMMM YYYY',
                        'onShow'=>"js:function(){ $('.datepicker-plot-area').width($('#from').parent().width()) }"
                    ),
                ));?>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <?php echo CHtml::label('تا', 'to');?>
                <?php $this->widget('ext.PDatePicker.PDatePicker', array(
                    'id'=>'to',
                    'value' => isset($_POST['to_altField'])?$_POST['to_altField']:false,
                    'options'=>array(
                        'format'=>'DD MMMM YYYY',
                        'onShow'=>"js:function(){ $('.datepicker-plot-area').width($('#to').parent().width()) }"
                    ),
                ));?>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <?php echo CHtml::submitButton('جستجو', array('class'=>'btn-red'));?>
            </div>
        <?php echo CHtml::endForm();?>
    </div>
</div>
<?php Yii::app()->clientScript->registerScript('load-user-info', "
    $('#PatientInfo_national_code').focusout(function(){
        if($(this).val() != ''){
            if($(this).val().length < 10)
                $('#national-code-error').text('کد ملی باید 10 رقم باشد.').removeClass('hidden');
            else{
                var pattern = new RegExp(/\\D/);
                if(pattern.test($(this).val()))
                    $('#national-code-error').text('کد ملی باید عددی باشد.').removeClass('hidden');
                else{
                    $('#national-code-error').text('').addClass('hidden');
                    $.ajax({
                        url: '".$this->createUrl('/users/public/getUserByCode')."',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {code: $(this).val()},
                        success:function(data){
                            if(data.status){
                                $('#PatientInfo_name').val(data.name).prop('disabled', true);
                                $('#PatientInfo_mobile').val(data.mobile).prop('disabled', true);
                                $('#PatientInfo_email').val(data.email).prop('disabled', true);
                            }
                        },
                        error:function(){
                            alert('در برقراری ارتباط با سرور خطایی رخ داده است.');
                        }
                    });
                }
            }
        }else
            $('#national-code-error').text('کد ملی نمی تواند خالی باشد.').removeClass('hidden');
    });
");?>