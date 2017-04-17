<?php
/* @var $this ShopOrderController */
/* @var $form CActiveForm */
/* @var $user Users */
/* @var $paymentMethods ShopPaymentMethod[] */
/* @var $discountObj DiscountCodes */

$discountCodesInSession = Users::model()->getDiscountCodes();
$cartStatistics = Shop::getPriceTotal();
?>
<div class="page">
    <div class="page-heading">
        <div class="container">
            <h1>اطلاعات پرداخت</h1>
        </div>
    </div>
    <div class="container page-content">
        <div class="white-box cart">
            <?php $this->renderPartial('/order/_steps', array('point' => 2));?>
            <?php $this->renderPartial("//partial-views/_flashMessage");?>
            <?php if($discountCodesInSession):
                $discountObj = DiscountCodes::model()->findByAttributes(['code' => $discountCodesInSession]);
                ?>
                <div class="used-discount-code">
                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12"><?php echo CHtml::encode($discountObj->title);?><a href="<?php echo $this->createUrl("/shop/order/removeDiscount", array("code"=>$discountObj->code));?>" class="remove-discount">حذف</a></div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 price text-center"><?php echo Controller::parseNumbers(number_format($cartStatistics['discountCodeAmount']))?><small> تومان</small></div>
                </div>
            <?php else:?>
                <div class="discount-code">
                    <div class="checkbox-container">
                        <input type="checkbox" id="has-discount" data-toggle="collapse" data-target="#discount-code-form">
                        <label for="has-discount">کد تخفیف کتابیک دارم</label>
                    </div>
                    <div id="discount-code-form" class="collapse">
                        <div class="pull-right">لطفا کد تخفیف خود را وارد کنید.</div>
                        <div class="pull-left">
                            <?php echo CHtml::beginForm(array("/shop/order/addDiscount"));?>
                                <?php echo CHtml::textField("DiscountCodes[code]", "", array("class"=>"text-field sm pull-right", "placeholder"=>"کد تخفیف"));?>
                                <?php echo CHtml::submitButton("ثبت", array("class"=>"btn-blue btn-sm pull-left"));?>
                            <?php echo CHtml::endForm();?>
                        </div>
                    </div>
                </div>
            <?php endif;?>
            <div class="bill">
                <ul class="list-group">
                    <li class="list-group-item">
                        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">جمع کل خرید</div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 price text-center"><?php echo Controller::parseNumbers(number_format($cartStatistics['totalPrice'])) ?><small> تومان</small></div>
                    </li>
                    <li class="list-group-item">
                        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">هزینه ارسال</div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 price text-center"><?php
                            if(!$cartStatistics['shippingPrice'] || $cartStatistics['shippingPrice'] == 0):
                                echo 'رایگان';
                            else:
                                ?>
                                <?= Controller::parseNumbers(number_format($cartStatistics['shippingPrice'])) ?><small> تومان</small>
                                <?
                            endif;
                            ?></div>
                    </li>
                    <li class="list-group-item red-item">
                        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">تخفیف کتاب<?php echo ($discountCodesInSession)?" + ".CHtml::encode($discountObj->title):"";?></div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 price text-center"><?php echo Controller::parseNumbers(number_format($cartStatistics['totalDiscount'] + $cartStatistics['discountCodeAmount'])) ?><small> تومان</small></div>
                    </li>
                    <li class="list-group-item green-item">
                        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">مبلغ قابل پرداخت</div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 price text-center"><?php echo Controller::parseNumbers(number_format($cartStatistics['totalPayment'])) ?><small> تومان</small></div>
                    </li>
                </ul>
            </div>
            <?php echo CHtml::beginForm(array("/shop/order/create"));?>
                <div class="payment-method">
                    <?php echo CHtml::hiddenField("form", "payment-form");?>
                    <h5>شیوه پرداخت</h5>
                    <?php $this->widget('zii.widgets.CListView', array(
                        'id' => 'payment-list',
                        'dataProvider' => new CArrayDataProvider($paymentMethods,array(
                            'pagination' => false
                        )),
                        'itemView' => 'shop.views.payment._payment_item',
                        'template' => '{items}',
                        'viewData' => array('credit' => $user->userDetails->credit),
                        'itemsCssClass' => 'payment-methods-list'
                    )); ?>
                </div>
                <div class="buttons">
                    <a href="<?= $this->createUrl('/shop/order/back') ?>" class="btn-black pull-right">بازگشت</a>
                    <?php echo CHtml::submitButton("بازبینی سفارش", array("class"=>"btn-blue pull-left"));?>
                </div>
            <?php echo CHtml::endForm();?>
        </div>
    </div>
</div>