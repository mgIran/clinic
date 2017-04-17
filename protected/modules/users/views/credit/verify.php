<?php
/* @var $this CreditController */
/* @var $model UserTransactions */
/* @var $userDetails UserDetails */
/* @var $gift string */
?>

<div class="white-form">
    <h3>جزئیات پرداخت</h3>
    <p class="description">اطلاعات پرداخت شما به شرح زیر می باشد:</p>
    <?php if(Yii::app()->user->hasFlash('success')):?>
        <div class="alert alert-success fade in">
            <?php echo Yii::app()->user->getFlash('success');?>
        </div>
        <h4>اطلاعات تراکنش</h4>
        <div class="panel-body">
            <p>
                <?php echo CHtml::label('مبلغ پرداخت شده:','');?>
                <?php echo Controller::parseNumbers(number_format($model->amount)).' تومان';?>
            </p>
            <?php
            if($gift):
            ?>
            <p>
                <?php echo CHtml::label('هدیه دریافتی:','');?>
                <?php echo Controller::parseNumbers(number_format($gift)).' تومان';?>
            </p>
            <?php
            endif;
            ?>
            <p>
                <?php echo CHtml::label('اعتبار فعلی شما:','');?>
                <?php echo Controller::parseNumbers(number_format($userDetails->credit)).' تومان';?>
            </p>
            <p>
                <?php echo CHtml::label('کد رهگیری تراکنش:','');?>
                <?php echo CHtml::encode($model->token);?>
            </p>
        </div>
    <?php elseif(Yii::app()->user->hasFlash('failed')):?>
        <div class="alert alert-danger fade in">
            <?php echo Yii::app()->user->getFlash('failed');?>
            <?php if(Yii::app()->user->hasFlash('transactionFailed')) echo '<br>'.Yii::app()->user->getFlash('transactionFailed');?>
        </div>
        <div class="buttons">
            <a href="<?php echo $this->createUrl('/users/credit/buy')?>" class="btn btn-danger">خرید اعتبار</a>
        </div>
    <?php endif;?>
</div>