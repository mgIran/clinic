<div class="inner-page">
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 col-lg-offset-4 col-md-offset-4 col-sm-offset-4 col-xs-offset-0">
        <h4 class="title">بازیابی کلمه عبور</h4>
        <div class="login-form">

            <?php echo CHtml::beginForm(Yii::app()->createUrl('/users/public/forgetPassword'), 'post', array(
                'id'=>'forget-password-form',
            ));?>

            <?php $this->renderPartial('//partial-views/_flashMessage');?>

            <div class="form-row">
                <?php echo CHtml::textField('email', '',array('placeholder'=>'پست الکترونیکی')); ?>
            </div>
            <div class="form-row">
                <?php echo CHtml::submitButton('ارسال', array('class'=>'btn btn-green btn-medium'));?>
            </div>
            <?php CHtml::endForm(); ?>

            <p><a href="<?php echo $this->createUrl('/login');?>">ورود به حساب کاربری</a></p>

        </div>
    </div>
</div>