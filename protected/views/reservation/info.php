<?
/* @var $this ReservationController */
?>

<div class="inner-page">
    <?php $this->renderPartial('_steps', array('active'=>2));?>

    <div class="page-help">
        <div class="container">
            <h4>ثبت اطلاعات بیمار</h4>
            <ul>
                <li>اطلاعات خود را بصورت صحیح و کامل وارد نمایید.</li>
                <li>وارد کردن شماره همراه الزامی می باشد. با لغو یا تغییر نوبت برای شما پیامی ارسال خواهد شد.</li>
            </ul>
        </div>
    </div>

    <div class="form-container">
        <?php echo CHtml::beginForm('', 'post', array('class'=>'info-form'));?>
            <div class="row">
                <?php echo CHtml::textField('PatientInfo[national_code]', '', array('placeholder'=>'کد ملی *', 'maxlength'=>10));?>
                <span class="errorMessage" id="national-code-error"></span>
            </div>
            <div class="row">
                <?php echo CHtml::textField('PatientInfo[name]', '', array('placeholder'=>'نام و نام خانوادگی'));?>
            </div>
            <div class="row">
                <?php echo CHtml::textField('PatientInfo[mobile]', '', array('placeholder'=>'تلفن همراه *', 'maxlength'=>11));?>
            </div>
            <div class="row">
                <?php echo CHtml::textField('PatientInfo[email]', '', array('placeholder'=>'پست الکترونیکی'));?>
            </div>
            <div class="row">
                <input type="text" placeholder="کد امنیتی گوگل">
            </div>
            <div class="row">
                <small class="desc">در پایان فرایند رزرو نوبت، سیستم برای شما کد رهگیری در نظر می گیرد. لطفا هنگام مراجعه به درمانگاه، کد رهگیری را همراه داشته باشید.</small>
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