<div class="login-form">
    <?php
    $form=$this->beginWidget('CActiveForm', array(
        'id'=>'login-form',
        'enableAjaxValidation'=>false,
        'enableClientValidation'=>true,
    )); ?>
    <div class="row">
        <?php echo $form->textField($model,'username',array('class'=>'form-control','placeholder'=>'نام کاربری')); ?>
        <?php echo $form->error($model,'username'); ?>
    </div>
    <div class="row">
        <?php echo $form->passwordField($model,'password',array('class'=>'form-control','placeholder'=>'کلمه عبور')); ?>
        <?php echo $form->error($model,'password'); ?>
    </div>
    <?php if ($model->scenario == 'withCaptcha' && CCaptcha::checkRequirements()): ?>
        <div class="row form-group">
            <?php echo $form->labelEx($model, 'verifyCode'); ?>
            <div>
                <?php $this->widget('CCaptcha'); ?>
                <?php echo $form->textField($model, 'verifyCode'); ?>
            </div>
            <?php echo $form->error($model, 'verifyCode'); ?>
        </div>
    <?php endif; ?>
    <div class="row">
        <input class="btn btn-success form-control" type="submit" value="ورود">
    </div>
    <?php $this->endWidget(); ?>
    <p>
        <a href="<?php echo $this->createAbsoluteUrl('//');?>" class="forget-link">صفحه اصلی سایت</a>
    </p>

    <div class="loading-container">
        <div class="overly"></div>
        <div class="spinner">
            <div class="bounce1"></div>
            <div class="bounce2"></div>
            <div class="bounce3"></div>
        </div>
    </div>
</div>
<?php
Yii::app()->clientScript->registerScript('click-on-captcha', '$("#yw0_button").click();',CClientScript::POS_READY);