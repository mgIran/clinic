<?php
/* @var $this BookController */
/* @var $transaction UserTransactions */
/* @var $book Books */
/* @var $user Users */
/* @var $price string */
/* @var $transactionResult boolean */
?>
<div class="white-form">
    <h3>خرید کتاب</h3>
    <p class="description">جزئیات خرید شما به شرح ذیل می باشد:</p>

    <?php $this->renderPartial('//partial-views/_flashMessage');?>

    <?php if($transactionResult):?>
        <p><label>کتاب:</label><span><a href="<?php echo $this->createUrl('/book/view', array('id'=>$book->id, 'title'=>$book->title));?>" title="<?php echo CHtml::encode($book->title);?>"><?php echo CHtml::encode($book->title);?></a></span></p>
        <p><label>مبلغ:</label><span><?php echo CHtml::encode(number_format($price, 0));?> تومان</span></p>
        <p><label>کد رهگیری تراکنش:</label><span><?php echo CHtml::encode($transaction->token);?></span></p>
        <a href="<?php echo $this->createUrl('/users/public/library');?>" class="btn btn-info">کتابخانه من</a>
    <?php else:?>
        <a href="<?php echo $this->createUrl('/book/buy', array('id'=>$book->id, 'title'=>$book->title));?>" class="btn btn-danger">بازگشت به صفحه خرید</a>
    <?php endif;?>
</div>