<?php
/* @var $this CreditController */
/* @var $model Users */
/* @var $voucherForm VoucherForm */
/* @var $amounts Array */
?>


<div class="white-form">
    <?php
    $form = $this->beginWidget('CActiveForm',array(
        'id' => 'voucher-form',
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
        'htmlOptions' =>array('class'=>'form form-inline')
    ));
    echo $form->hiddenField($voucherForm,'user_id');
    ?>
    <h3>استفاده از بن خرید</h3>
    <?php
    $this->renderPartial('//partial-views/_flashMessage',array('prefix' => 'voucher-'))
    ?>
    <p class="description">لطفا کد بن خرید را وارد کنید:</p>
    <div class="form-group">
        <?php echo $form->textField($voucherForm, 'code',array('placeholder' => 'مثال: Cj8xl2','class' => 'form-control'));?>
        <?php echo $form->error($voucherForm, 'code');?>
    </div>
    <?php if ($voucherForm->scenario == 'withCaptcha' && CCaptcha::checkRequirements()): ?>
        <div class="form-group">
            <?php echo $form->textField($voucherForm, 'verifyCode',array('placeholder' => 'کد امنیتی را وارد کنید','class' => 'form-control')); ?>
            <?php echo $form->error($voucherForm, 'verifyCode');?>
        </div>
        <div class="form-group">
            <?php $this->widget('CCaptcha',array(
                'buttonLabel'   => '<span class="glyphicon glyphicon-refresh"></span>',
                'buttonOptions' => array(
                    'class' => 'btn-refresh-captcha',
                    'style' => 'text-decoration: none; text-weight: bold;',
                )
            )); ?>
        </div>
        <?php
        Yii::app()->clientScript->registerScript( 'refresh-captcha',
            '$(".btn-refresh-captcha").click();', CClientScript::POS_READY
        );
        ?>
    <?php endif; ?>
    <div class="form-group">
        <?php echo CHtml::submitButton('اعمال', array('class'=>'btn btn-default'));?>
    </div>

    <?php $this->endWidget(); ?>

</div>
<div class="white-form">

<?php echo CHtml::beginForm($this->createUrl('/users/credit/bill'));?>

    <?php if(Yii::app()->user->hasFlash('min_credit_fail')):?>
    <div class="alert alert-danger fade in">
        <p>اعتبار شما کافی نیست!</p>
        <?php echo Yii::app()->user->getFlash('min_credit_fail');?>
    </div>
    <?php endif;?>

    <h3>خرید اعتبار</h3>
    <p class="description">میزان اعتبار مورد نظر را انتخاب کنید:</p>
    <div class="form-group">
        <?php echo CHtml::radioButtonList('amount', '5000', $amounts);?>
    </div>
    <div class="buttons">
        <?php echo CHtml::submitButton('خرید', array('class'=>'btn btn-default'));?>
    </div>

<?php echo CHtml::endForm(); ?>

</div>
