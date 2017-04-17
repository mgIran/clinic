<?php
/* @var $data ShopPaymentMethod*/
/* @var $credit integer */
?>
<div class="payment-method-item<?= Yii::app()->user->getState('payment_method') == $data->id?" selected":'' ?>">
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 radio-container">
        <div class="radio-control">
            <?php echo CHtml::radioButton("PaymentMethod", Yii::app()->user->getState('payment_method') == $data->id?true:false, array("id"=>"payment-method-".$data->id, "value"=>$data->id));?>
            <?php echo CHtml::label("", "payment-method-".$data->id);?>
        </div>
    </div>
    <div class="col-lg-11 col-md-11 col-sm-11 col-xs-12 info-container">
        <h5 class="name"><?= CHtml::encode($data->title) ?><?php
            if($data->name == ShopPaymentMethod::METHOD_CREDIT):
            ?>&nbsp;&nbsp;&nbsp;<small> اعتبار فعلی: <?= Controller::parseNumbers(number_format($credit)).' تومان' ?></small><?php
            endif;
            ?>
        </h5>
        <div class="desc"><?= strip_tags($data->description) ?></div>
    </div>
</div>