<?php
/* @var $this ShopOrderController */
/* @var $order ShopOrder */
/* @var $message string */
$message = '';
if(Yii::app()->user->hasFlash('message'))
    $message = Yii::app()->user->getFlash('message');
?>
<div class="page">
    <div class="page-heading">
        <div class="container">
            <h1>جزئیات سفارش</h1>
        </div>
    </div>
    <div class="container page-content">
        <div class="white-box cart">
            <div class="payment-message">
                <?php $this->renderPartial("//partial-views/_flashMessage");?>
                <div class="desc"><?php echo $message;?></div>
                <?php
                if($order->payment_status == ShopOrder::PAYMENT_STATUS_UNPAID):
                ?>
                    <div class="overflow-hidden alert-warning alert">
                        <div class="text-center">شما می توانید مجددا مبلغ سفارش را با یکی از روش های زیر پرداخت نمایید تا سفارش شما تکمیل شود.</div>
                        <div class="buttons center-block text-center">
                            <?php
                            foreach(ShopPaymentMethod::model()->findAll(array(
                                'order' => 't.order',
                                'condition' => 'status <> :deactive',
                                'params' => array(':deactive' => ShopPaymentMethod::STATUS_DEACTIVE)
                            )) as $key => $item):
                                if($key%3 == 1)
                                    $class = 'green';
                                if($key%3 == 2)
                                    $class = 'blue';
                                if($key%3 == 0)
                                    $class = 'red';
                            ?>
                                <a href="<?= $this->createUrl('changePayment',array('id' => $order->id, 'method' => $item->name )) ?>" class="btn-<?= $class ?> btn" ><?= $item->title ?></a>
                            <?php
                            endforeach;
                            ?>
                        </div>
                    </div>
                <?php
                endif;
                ?>
            </div>
            <div class="order-details table-responsive">
                <h5>خلاصه وضعیت سفارش</h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th class="green-text">شماره رسید</th>
                            <th>قیمت کل</th>
                            <th>شیوه پرداخت</th>
                            <th<?= $order->payment_status == ShopOrder::PAYMENT_STATUS_UNPAID?' class="red-text"':' class="green-text"' ?>>وضعیت پرداخت</th>
                            <th>وضعیت سفارش</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="green-text"><?= $order->getOrderID() ?></td>
                            <td><span class="price"><?= Controller::parseNumbers(number_format($order->payment_amount)) ?><small> تومان</small></span></td>
                            <td><?= $order->paymentMethod->title ?></td>
                            <td<?= $order->payment_status == ShopOrder::PAYMENT_STATUS_UNPAID?' class="red-text"':' class="green-text"' ?>><?= $order->getPaymentStatusLabel() ?></td>
                            <td><?= $order->getStatusLabel() ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="shipping-details table-responsive">
                <h5>اطلاعات ارسالی سفارش</h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th>تحویل گیرنده</th>
                            <th>آدرس</th>
                            <th>شماره های تماس</th>
                            <th>شیوه ارسال</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= CHtml::encode($order->deliveryAddress->transferee) ?></td>
                            <td><?= CHtml::encode($order->deliveryAddress->postal_address) ?></td>
                            <td><?= CHtml::encode(Controller::parseNumbers($order->deliveryAddress->emergency_tel)) ?> - <?= CHtml::encode(Controller::parseNumbers($order->deliveryAddress->landline_tel)) ?></td>
                            <td><?= CHtml::encode($order->shippingMethod->title)?> <small>( <?php
                                    if($order->shipping_price == 0):
                                        echo 'ارسال رایگان';
                                    else:
                                        ?>
هزینه <?= Controller::parseNumbers(number_format($order->shipping_price)) ?>تومان
                                        <?
                                    endif;
                                    ?> )</small></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php
            if($order->transactions):
            ?>
                <div class="transaction-details table-responsive">
                    <h5>جزییات تراکنش های بانکی شما</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ردیف</th>
                                <th>نوع پرداخت</th>
                                <th>درگاه پرداخت</th>
                                <th>کد رهگیری</th>
                                <th>تاریخ</th>
                                <th>مبلغ</th>
                                <th>وضعیت</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach($order->transactions as $key => $transaction):
                        ?>

                            <tr>
                                <td><?= $key+1 ?></td>
                                <td><?= CHtml::encode($order->paymentMethod->title) ?></td>
                                <td><?= CHtml::encode($transaction->gateway_name) ?></td>
                                <td><?= $transaction->token?CHtml::encode($transaction->token):"-" ?></td>
                                <td><?= CHtml::encode(JalaliDate::date('d F Y',$transaction->date)) ?></td>
                                <td><span class="price"><?= Controller::parseNumbers(number_format($transaction->amount)) ?><small> تومان</small></span></td>
                                <td<?= $transaction->status == UserTransactions::TRANSACTION_STATUS_UNPAID?' class="red-text"':' class="green-text"' ?>><?= $transaction->statusLabels[$transaction->status] ?></td>
                            </tr>
                        <?php
                        endforeach;
                        ?>
                        </tbody>
                    </table>
                </div>
            <?php
            endif;
            ?>
            <?php
            Shop::PrintStatusLine($order->status);
            ?>
        </div>
    </div>
</div>