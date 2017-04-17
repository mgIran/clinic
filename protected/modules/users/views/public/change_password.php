<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12 col-lg-offset-3 col-md-offset-3 col-sm-offset-2">
    <h4 class="welcome-text">تغییر کلمه عبور<small> ، لطفا کلمه عبور جدید را وارد کنید.</small></h4>
    <div class="login-form">

        <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'users-form',
            'enableAjaxValidation'=>true,
            'enableClientValidation'=>true,
            'clientOptions'=>array(
                'validateOnSubmit'=>true,
                'beforeValidate' => "js:function(form) {
                $('.loading-container').fadeIn();
                return true;
            }",
                'afterValidate' => "js:function(form) {
                $('.loading-container').stop().hide();
                return true;
            }",
            ),
        )); ?>

        <div class="alert alert-success hidden" id="message"></div>

        <div class="form-row">
            <?php echo $form->passwordField($model,'password',array('class'=>'form-control','placeholder'=>'کلمه عبور')); ?>
            <?php echo $form->error($model,'password'); ?>
        </div>
        <div class="form-row">
            <?php echo $form->passwordField($model,'repeatPassword',array('class'=>'form-control','placeholder'=>'تکرار کلمه عبور')); ?>
            <?php echo $form->error($model,'repeatPassword'); ?>
        </div>
        <div class="form-row">
            <?php echo CHtml::SubmitButton('ارسال', array('class'=>'btn btn-info'));?>
        </div>
        <?php $this->endWidget(); ?>

        <p><a href="<?php echo $this->createUrl('/login');?>">ورود به حساب کاربری</a></p>

        <div class="loading-container">
            <div class="overly"></div>
            <div class="spinner">
                <div class="bounce1"></div>
                <div class="bounce2"></div>
                <div class="bounce3"></div>
            </div>
        </div>
    </div>
</div>