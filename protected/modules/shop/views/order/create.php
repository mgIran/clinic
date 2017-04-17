<?php
/* @var $this ShopOrderController */
/* @var $deliveryAddress ShopAddresses */
/* @var $shippingMethod ShopShippingMethod */
?>
<div class="page">
	<div class="page-heading">
		<div class="container">
			<h1>بازبینی سفارش</h1>
		</div>
	</div>
	<div class="container page-content relative">
		<?php $this->renderPartial('shop.views.shipping._loading', array('id' => 'basket-loading')) ?>
		<div class="white-box cart">
			<?php $this->renderPartial('/order/_steps', array('point' => 3));?>
			<?php $this->renderPartial('//partial-views/_flashMessage');?>
			<?php $this->renderPartial('_basket_table', array('books' => Shop::getCartContent()));?>
			<div class="bill">
				<?php $cartStatistics=Shop::getPriceTotal(); ?>
				<h5>خلاصه صورتحساب شما</h5>
				<ul class="list-group">
					<li class="list-group-item">
						<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">جمع کل خرید شما</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 price text-center"><?php echo Controller::parseNumbers(number_format($cartStatistics["totalPrice"]))?><small> تومان</small></div>
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
					<li class="list-group-item">
						<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">شیوه پرداخت</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 price text-center"><?php
							$pay_m = Shop::getPaymentMethod();
							echo $pay_m?$pay_m->title:'';
						?></div>
					</li>
					<li class="list-group-item red-item">
						<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">جمع کل تخفیف</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 price text-center"><?php echo Controller::parseNumbers(number_format($cartStatistics["totalDiscount"] + $cartStatistics["discountCodeAmount"]))?><small> تومان</small></div>
					</li>
					<li class="list-group-item green-item">
						<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">جمع کل قابل پرداخت</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 price text-center"><?php echo Controller::parseNumbers(number_format($cartStatistics["totalPayment"]))?><small> تومان</small></div>
					</li>
				</ul>
			</div>
			<div class="address-info">
				<h5>اطلاعات ارسال سفارش</h5>
				<ul class="list-group">
					<li class="list-group-item">این سفارش به <span class="green-label"><?php echo CHtml::encode($deliveryAddress->transferee);?></span> به آدرس <span class="green-label"><?php echo CHtml::encode($deliveryAddress->postal_address);?></span> و شماره تماس <span class="green-label"><?php echo CHtml::encode($deliveryAddress->emergency_tel);?></span> تحویل می گردد.</li>
					<li class="list-group-item">این سفارش از طریق <span class="green-label"><?php echo CHtml::encode($shippingMethod->title);?></span> با هزینه <span class="green-label"><?php echo Controller::parseNumbers(number_format($shippingMethod->price));?></span> تومان به شما تحویل داده خواهد شد.</li>
				</ul>
            </div>
            <small class="note">با انتخاب دکمه (پرداخت و ثبت سفارش) موافقت خود را با <a href="#">شرایط و قوانین</a> مربوط به ثبت سفارش کتابیک، اعلام نموده‌اید. </small>
            <div class="buttons">
                <a href="<?= $this->createUrl('/shop/order/back') ?>" class="btn-black pull-right">بازگشت</a>
                <a href="<?= $this->createUrl('/shop/order/confirm') ?>" class="btn-green pull-left">پرداخت و ثبت سفارش</a>
			</div>
		</div>
	</div>
</div>