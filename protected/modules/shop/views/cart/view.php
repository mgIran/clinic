<?php
/* @var $this ShopCartController */
/* @var $books Books[] */
/* @var $model Books */
?>
<div class="page">
	<div class="page-heading">
		<div class="container">
			<h1>سبد خرید شما</h1>
		</div>
	</div>
	<div class="container page-content relative">
        <?php $this->renderPartial('//partial-views/_loading',array('id' => 'basket-loading')) ?>
		<div class="white-box cart" id="basket-table">
            <?php $this->renderPartial('_basket_table',array('books' => $books)) ?>
		</div>
	</div>
</div>