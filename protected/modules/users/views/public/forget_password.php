<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12 col-lg-offset-3 col-md-offset-3 col-sm-offset-2">
    <h4 class="welcome-text">بازیابی کلمه عبور<small> ، لطفا پست الکترونیکی خود را وارد کنید.</small></h4>
    <div class="login-form">

        <?php echo CHtml::beginForm(Yii::app()->createUrl('/users/public/forgetPassword'), 'post', array(
            'id'=>'forget-password-form',
        ));?>

        <div class="alert alert-success hidden" id="message"></div>

        <div class="form-row">
            <?php echo CHtml::textField('email', '',array('class'=>'form-control','placeholder'=>'پست الکترونیکی')); ?>
        </div>
        <div class="form-row">
            <?php echo CHtml::ajaxSubmitButton('ارسال', Yii::app()->createUrl('/users/public/forgetPassword'), array(
                'type'=>'POST',
                'dataType'=>'JSON',
                'data'=>"js:$('#forget-password-form').serialize()",
                'beforeSend'=>"js:function(){
                    $('#message').addClass('hidden');
                    $('.loading-container').fadeIn();
                }",
                'success'=>"js:function(data){
                    if(data.hasError)
                        $('#message').removeClass('alert-success').addClass('alert-danger').text(data.message).removeClass('hidden');
                    else
                        $('#message').removeClass('alert-danger').addClass('alert-success').text(data.message).removeClass('hidden');
                    $('.loading-container').fadeOut();
                }"
            ), array('class'=>'btn btn-info'));?>
        </div>
        <?php CHtml::endForm(); ?>

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