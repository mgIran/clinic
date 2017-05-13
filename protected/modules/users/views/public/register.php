<?php
/* @var $model Users */
/* @var $form CActiveForm */
?>
<div class="inner-page">
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 col-lg-offset-4 col-md-offset-4 col-sm-offset-4 col-xs-offset-0">
        <h4 class="title"><strong>ایجاد حساب کاربری</strong></h4>
        <div class="login-form signup">
            <?php $this->renderPartial('//partial-views/_flashMessage', array('prefix' => 'register-')); ?>
            <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'register-form',
                'enableAjaxValidation'=>false,
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
            <div class="form-row">
                <?php echo $form->textField($model,'email',array('class'=>'form-control','placeholder'=>'پست الکترونیکی')); ?>
                <?php echo $form->error($model,'email'); ?>
                <span class="transition icon-envelope"></span>
            </div>
            <div class="form-row">
                <?php echo $form->passwordField($model,'password',array('class'=>'form-control','placeholder'=>'کلمه عبور')); ?>
                <?php echo $form->error($model,'password'); ?>
                <span class="transition icon-key"></span>
            </div>
            <div class="form-row register-button">
                <input class="btn btn-green btn-medium" type="submit" value="ثبت نام">
            </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>