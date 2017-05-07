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
        <?php echo CHtml::beginForm('', 'post', array('class'=>'select-date-form'));?>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <?php echo CHtml::textField('PatientInfo[national_code]', '', array('placeholder'=>'کد ملی *'));?>
                <?php $this->widget('ext.PDatePicker.PDatePicker', array(
                    'id'=>'year',
                    'attribute' => 'from',
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
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <?php echo CHtml::textField('PatientInfo[name]', '', array('placeholder'=>'نام و نام خانوادگی'));?>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <?php echo CHtml::submitButton('ثبت', array('class'=>'btn-red pull-left'));?>
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