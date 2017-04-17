<?php
/* @var $this ShopOrderController */
/* @var $data ShopOrder */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::encode($data->user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('delivery_address_id')); ?>:</b>
	<?php echo CHtml::encode($data->delivery_address_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('billing_address_id')); ?>:</b>
	<?php echo CHtml::encode($data->billing_address_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ordering_date')); ?>:</b>
	<?php echo CHtml::encode($data->ordering_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('update_date')); ?>:</b>
	<?php echo CHtml::encode($data->update_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('payment_method')); ?>:</b>
	<?php echo CHtml::encode($data->payment_method); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('shipping_method')); ?>:</b>
	<?php echo CHtml::encode($data->shipping_method); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('comment')); ?>:</b>
	<?php echo CHtml::encode($data->comment); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('amount')); ?>:</b>
	<?php echo CHtml::encode($data->amount); ?>
	<br />

	*/ ?>

</div>