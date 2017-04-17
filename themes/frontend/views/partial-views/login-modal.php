<?php
/* @var $this Controller*/
?>
<div id="login-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="close-icon"></i></button>
                <h4 class="modal-title">ورود به پنل کاربری</small></h4>
            </div>
            <div class="modal-body">
                <?php $this->renderPartial('//partial-views/_loading')?>
                <div class="tab-content">
                    <div class="tab-pane fade active in" id="login-modal-login-tab">
                        <?php
                        /* @var $formL CActiveForm */
                        Yii::import('users.models.UserLoginForm');
                        $loginModel = new UserLoginForm();
                        $formL=$this->beginWidget('CActiveForm', array(
                            'id'=>'login-form',
                            'enableAjaxValidation'=>false,
                            'enableClientValidation'=>true,
                            'clientOptions'=>array(
                                'validateOnSubmit'=>true,
                                'afterValidate' => 'js:function(form ,data ,hasError){
                                    if(!hasError)
                                    {
                                        var form = $("#login-form");
                                        var loading = $(".modal .loading-container");
                                        var url = \''.Yii::app()->createUrl('/login').'\';
                                        submitAjaxForm(form ,url ,loading ,"if(html.status){ $(\'#UserLoginForm_authenticate_field_em_\').text(html.msg); if(typeof html.url !== \'undefined\') window.location = html.url; else location.reload();}else{ if(typeof html === \'object\') $(\'#UserLoginForm_authenticate_field_em_\').html(html.errors); else $(\'#UserLoginForm_authenticate_field_em_\').text( html ); } ");
                                    }
                                }'
                            )
                        ));
                        echo CHtml::hiddenField('ajax','login-form');
                        if($this->route != 'site/index')
                            echo CHtml::hiddenField('returnUrl',$this->route);
                        ?>
                        <div class="form-group"><p id="UserLoginForm_authenticate_field_em_" class="text-center"></p></div>
                        <div class="form-group">
                            <?php echo $formL->emailField($loginModel,'email' ,array(
                                'placeholder' => 'پست الکترونیکی',
                                'class' => 'text-field ltr text-right'
                            ));
                            echo $formL->error($loginModel,'email'); ?>
                        </div>
                        <div class="form-group">
                            <?php echo $formL->passwordField($loginModel,'password',array(
                                'placeholder' => 'کلمه عبور',
                                'class' => 'text-field password'
                            ));
                            echo $formL->error($loginModel,'password');
                            ?>
                        </div>
                        <div class="form-group">
                            <?= $formL->checkBox($loginModel,'rememberMe',array('id'=>'remember-me')); ?>
                            <?= CHtml::label('مرا به خاطر بسپار','remember-me') ?>
                            <div class="pull-left"><?php echo CHtml::link('کلمه عبور خود را فراموش کرده اید؟',
                                    $this->createUrl('/users/public/forgetPassword')) ?></div>
                        </div>
                        <div class="form-group">
                            <?= CHtml::submitButton('ورود',array('class'=>"btn-blue")); ?>
                        </div>
                        <div class="form-group">
                            <span style="color: #818181;">حساب کاربری ندارید؟ <a href="#" data-target="#login-modal-register-tab" data-toggle="tab" id="register-tab-trigger">ثبت نام کنید</a></span>
                        </div>
                        <? $this->endWidget(); ?>
                    </div>
                    <div class="tab-pane fade" id="login-modal-register-tab">
                    <?php
                    /* @var $formR CActiveForm */
                    Yii::import('users.models.Users');
                    $registerModal = new Users();
                    $formR=$this->beginWidget('CActiveForm', array(
                        'id'=>'register-form',
                        'enableAjaxValidation'=>false,
                        'enableClientValidation'=>true,
                        'clientOptions'=>array(
                            'validateOnSubmit'=>true,
                            'afterValidate' => 'js:function(form ,data ,hasError){
                                if(!hasError)
                                {
                                    var form = $("#register-form");
                                    var loading = $(".modal .loading-container");
                                    var url = \''.Yii::app()->createUrl('/register').'\';
                                    submitAjaxForm(form ,url ,loading ,"console.log(html); if(html.status){ if(typeof html.url !== \'undefined\') window.location = html.url; else location.reload(); }else $(\'#Users_authenticate_field_em_\').html(html.errors);");
                                }
                            }'
                        )
                    ));
                    echo CHtml::hiddenField('ajax','register-form'); ?>
                    <div class="form-group"><p id="Users_authenticate_field_em_" class="text-center"></p></div>
                    <div class="form-group">
                        <?php echo $formR->emailField($registerModal,'email' ,array(
                            'placeholder' => 'پست الکترونیکی',
                            'class' => 'text-field ltr text-right'
                        ));
                        echo $formR->error($registerModal,'email'); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $formR->passwordField($registerModal,'password',array(
                            'placeholder' => 'کلمه عبور',
                            'class' => 'text-field password'
                        ));
                        echo $formR->error($registerModal,'password');
                        ?>
                    </div>
                    <div class="form-group">
                        <?= CHtml::submitButton('ثبت نام',array('class'=>"btn-blue")); ?>
                    </div>
                    <div class="form-group">
                        <a href="#" data-target="#login-modal-login-tab" data-toggle="tab" id="login-tab-trigger">ورود به حساب کاربری</a>
                    </div>
                    <? $this->endWidget(); ?>
                </div>
                </div>
                <div class="divider"></div>
                <p class="text-center">می توانید با حساب کاربری گوگل وارد شوید یا ثبت نام کنید...</p>
                <a href="<?= $this->createUrl('/googleLogin') ?>" class="btn-red" id="google-login-btn"><i class="google-icon"></i>ورود یا ثبت نام با گوگل</a>
            </div>
        </div>
    </div>
</div>