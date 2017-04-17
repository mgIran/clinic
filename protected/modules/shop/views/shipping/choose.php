<?php
/* @var $this ShopOrderController */
/* @var $form CActiveForm */
/* @var $user Users */
/* @var $shippingMethods ShopShippingMethod[] */
$cartStatistics = Shop::getPriceTotal();
?>
<div class="page">
    <div class="page-heading">
        <div class="container">
            <h1>اطلاعات ارسال سفارش</h1>
        </div>
    </div>
    <div class="container page-content relative">
        <?php $this->renderPartial('shop.views.shipping._loading', array('id' => 'basket-loading')) ?>
        <div class="white-box cart">
            <?php $this->renderPartial('/order/_steps', array('point' => 1));?>
            <?php $this->renderPartial('//partial-views/_flashMessage');?>
            <div class="select-address">
                <?php echo CHtml::beginForm(array("/shop/order/create"));?>
                    <?php echo CHtml::hiddenField("form", "shipping-form");?>
                    <h5 class="pull-right">انتخاب آدرس</h5>
                    <a href="#" data-toggle="modal" data-target="#add-address-modal" id="add-address-modal-trigger" class="btn-green pull-left" data-url="<?= Yii::app()->createUrl("/shop/addresses/add") ?>">افزودن آدرس جدید</a>
                    <div class="clearfix"></div>
                    <div id="addresses-list-container">
                        <?php $this->renderPartial("/shipping/_addresses_list", array("addresses"=>$user->addresses));?>
                    </div>
                    <div class="shipping-method">
                        <h5>شیوه ارسال</h5>
                        <?php $this->widget('zii.widgets.CListView', array(
                            'id' => 'shipping-list',
                            'dataProvider' => new CArrayDataProvider($shippingMethods,array(
                                'pagination' => false
                            )),
                            'itemView' => 'shop.views.shipping._shipping_item',
                            'template' => '{items}',
                            'viewData' => array('payment_total' => $cartStatistics['totalPayment']),
                            'itemsCssClass' => 'shipping-methods-list'
                        )); ?>
                    </div>
                    <div class="buttons">
                        <a href="<?= $this->createUrl('/shop/order/back') ?>" class="btn-black pull-right">بازگشت به سبد خرید</a>
                        <?php echo CHtml::submitButton("انتخاب شیوه پرداخت", array("class"=>"btn-blue pull-left"));?>
                    </div>
                <?php echo CHtml::endForm();?>
            </div>
        </div>
    </div>
</div>
<?php $this->renderPartial("/shipping/_add_address_modal");?>