<?php
/* @var $this CreditController */
/* @var $amount string */
/* @var $model Users */
?>

<div class="white-form">
    <h3>پیش فاکتور</h3>
    <p class="description">اطلاعات سفارش شما به شرح زیر می باشد:</p>
    <p>
        <?php echo CHtml::label('اعتبار فعلی شما: ','');?>
        <?php echo number_format($model->userDetails->credit, 0).' تومان';?>
    </p>
    <p>
        <?php echo CHtml::label('اعتبار درخواستی: ','');?>
        <?php echo number_format($amount, 0).' تومان';?>
    </p>
    <p>
        <label>روش پرداخت: </label>
        <span>درگاه زرین پال</span>
    </p>
    <div class="buttons" style="margin-top: 35px;">
        <?php echo CHtml::beginForm($this->createUrl('/users/credit/bill'));?>
            <?php echo CHtml::hiddenField('amount', CHtml::encode($_POST['amount']));?>
            <?php echo CHtml::submitButton('پرداخت', array(
                'class'=>'btn btn-default',
                'name'=>'pay',
            ));?>
            <?php echo CHtml::link('بازگشت',$this->createUrl('buy'), array('class'=>'btn btn-info'));?>
        <?php echo CHtml::endForm();?>
    </div>
</div>