<?
/* @var $this ReservationController */
/* @var $user Users */
/* @var $form CActiveForm */
?>

    <div class="inner-page">
        <?php $this->renderPartial('_steps', array('active' => 3)); ?>

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
            <?php $form = $this->beginWidget('CActiveForm', array(
                'id' => 'users-form',
                'enableAjaxValidation' => false,
                'enableClientValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                ),
                'htmlOptions' => array('class' => 'info-form'),
            )); ?>
            <div class="row" style="margin-bottom: 0;">
                <?php $this->renderPartial('//partial-views/_flashMessage'); ?>
                <?php echo $form->errorSummary($user); ?>
            </div>
            <div class="row">
                <?php echo $form->textField($user, 'national_code', array('placeholder' => 'کد ملی *', 'maxlength' => 10)); ?>
                <?php echo $form->error($user, 'national_code'); ?>
                <span class="errorMessage" id="national-code-error"></span>
                <div class="loading-container" id="national-code-loading">
                    <div class="spinner">
                        <div class="bounce3"></div>
                        <div class="bounce2"></div>
                        <div class="bounce1"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php echo $form->textField($user, 'first_name', array('placeholder' => 'نام *')); ?>
                <?php echo $form->error($user, 'first_name'); ?>
            </div>
            <div class="row">
                <?php echo $form->textField($user, 'last_name', array('placeholder' => 'نام خانوادگی *')); ?>
                <?php echo $form->error($user, 'last_name'); ?>
            </div>
            <div class="row">
                <?php echo $form->textField($user, 'mobile', array('placeholder' => 'تلفن همراه *', 'maxlength' => 11)); ?>
                <?php echo $form->error($user, 'mobile'); ?>
            </div>
            <div class="row">
                <?php echo $form->textField($user, 'email', array('placeholder' => 'پست الکترونیکی')); ?>
                <?php echo $form->error($user, 'email'); ?>
            </div>
            <?php if (CCaptcha::checkRequirements()): ?>
                <div class="row">
                    <?php echo $form->labelEx($user, 'verifyCode'); ?>
                    <div>
                        <?php $this->widget('CCaptcha'); ?>
                        <?php echo $form->textField($user, 'verifyCode'); ?>
                    </div>
                    <div class="hint">لطفا امنیتی را وارد کنید
                    </div>
                    <?php echo $form->error($user, 'verifyCode'); ?>
                </div>
            <?php endif; ?>
            <div class="row">
                <small class="desc">در پایان فرایند رزرو نوبت، سیستم برای شما کد رهگیری در نظر می گیرد. لطفا هنگام
                    مراجعه به درمانگاه، کد رهگیری را همراه داشته باشید.</small>
                <?php echo CHtml::submitButton('ثبت', array('class' => 'btn-red pull-left')); ?>
                <?php echo CHtml::link('بازگشت', $this->createUrl('schedule'), array('class' => 'btn-black pull-left')); ?>
            </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>
<?php Yii::app()->clientScript->registerScript('load-user-info', "
    $('#Users_national_code').focusout(function(){
        if($(this).val() != ''){
            if($(this).val().length == 10){
                var pattern = new RegExp(/\\D/);
                if(!pattern.test($(this).val())){
                    $('#national-code-error').text('').addClass('hidden');
                    $('#national-code-loading').show();

                    $.ajax({
                        url: '" . $this->createUrl('/users/public/getUserByCode') . "',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {code: $(this).val()},
                        success:function(data){
                            $('#national-code-loading').hide();
                            if(data.status){
                                if(data.first_name != '' && data.first_name != null){
                                    $('#Users_first_name').val(data.first_name).prop('disabled', true);
                                    $('<input>').attr({
                                        type: 'hidden',
                                        name: 'Users[first_name]',
                                        value: data.first_name
                                    }).appendTo('form#users-form');
                                }

                                if(data.last_name != '' && data.last_name != null){
                                    $('#Users_last_name').val(data.last_name).prop('disabled', true);
                                    $('<input>').attr({
                                        type: 'hidden',
                                        name: 'Users[last_name]',
                                        value: data.last_name
                                    }).appendTo('form#users-form');
                                }

                                if(data.mobile != '' && data.mobile != null){
                                    $('#Users_mobile').val(data.mobile).prop('disabled', true);
                                    $('<input>').attr({
                                        type: 'hidden',
                                        name: 'Users[mobile]',
                                        value: data.mobile
                                    }).appendTo('form#users-form');
                                }

                                if(data.email != '' && data.email != null){
                                    $('#Users_email').val(data.email).prop('disabled', true);
                                    $('<input>').attr({
                                        type: 'hidden',
                                        name: 'Users[email]',
                                        value: data.email
                                    }).appendTo('form#users-form');
                                }
                            }else{
                                $('#Users_first_name').val('').prop('disabled', false);
                                $('#Users_last_name').val('').prop('disabled', false);
                                $('#Users_mobile').val('').prop('disabled', false);
                                $('#Users_email').val('').prop('disabled', false);
                            }
                        },
                        error:function(){
                            $('#national-code-loading').hide();
                            alert('در برقراری ارتباط با سرور خطایی رخ داده است.');
                        }
                    });
                }
            }
        }
    });
"); ?>