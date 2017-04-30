<div class="inner-page">
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 col-lg-offset-4 col-md-offset-4 col-sm-offset-4 col-xs-offset-0">
        <h4 class="title">بازیابی کلمه عبور</h4>
        <div class="login-form">

            <?php echo CHtml::beginForm(Yii::app()->createUrl('/users/public/forgetPassword'), 'post', array(
                'id'=>'forget-password-form',
            ));?>

            <div class="alert alert-success hidden" id="message"></div>

            <div class="form-row">
                <?php echo CHtml::textField('email', '',array('placeholder'=>'پست الکترونیکی')); ?>
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
                ), array('class'=>'btn btn-green btn-medium'));?>
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
</div>