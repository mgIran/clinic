<?php
/* @var $this ShopOrderController */
/* @var $model ShopOrder */
/* @var $item ShopOrderItems */
?>

<div class="order-info">
    <table class="table">
        <thead>
            <tr>
                <th>شرح محصول</th>
                <th class="text-center">تعداد</th>
                <th class="text-center hidden-xs">قیمت واحد<small>(همراه با تخفیف)</small></th>
                <th class="text-center hidden-xs">قیمت کل</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($model->items as $item):?>
                <tr>
                    <td>
                        <h5><a href="<?php echo $this->createUrl("/book/".$item->model->id."/".urlencode($item->model->title));?>"><?php echo CHtml::encode($item->model->title);?></a></h5>
                        <span class="item hidden-xs">نویسنده: <span class="value"><?php echo $item->model->getPersonsTags("نویسنده", "fullName", true, "span");?></span></span>
                    </td>
                    <td class="text-center">
                        <?php echo CHtml::encode(Controller::parseNumbers($item->qty));?> عدد
                    </td>
                    <td class="text-center hidden-xs">
                        <?php echo CHtml::encode(Controller::parseNumbers(number_format($item->payment)));?>تومان
                    </td>
                    <td class="text-center hidden-xs">
                        <?php echo CHtml::encode(Controller::parseNumbers(number_format($item->payment)));?>تومان
                    </td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
    <div class="order-status">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4>اطلاعات مالی سفارش</h4>
                <div class="rows">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">مبلغ کل</div>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><?php echo CHtml::encode(Controller::parseNumbers(number_format($model->price_amount)));?> تومان</div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">تخفیفات</div>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><?php echo CHtml::encode(Controller::parseNumbers(number_format($model->discount_amount)));?> تومان</div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">روش ارسال</div>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><?php echo CHtml::encode($model->shippingMethod->title)?><?php
                            if($model->shipping_price == 0):
                                echo '<small>(ارسال رایگان)</small>';
                            else:
                                ?>
                                <small> (هزینه ارسال <?php echo Controller::parseNumbers(number_format($model->shipping_price));?> تومان)</small>
                                <?
                            endif;
                            ?></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">مبلغ قابل پرداخت</div>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><?php echo CHtml::encode(Controller::parseNumbers(number_format($model->payment_amount)));?> تومان</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4>اطلاعات ارسالی سفارش</h4>
                <div class="rows">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">تحویل گیرنده</div>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><?php echo CHtml::encode($model->deliveryAddress->transferee);?></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">آدرس تحویل</div>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><?php echo CHtml::encode($model->deliveryAddress->postal_address);?></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">شماره تماس</div>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><?php echo CHtml::encode($model->deliveryAddress->emergency_tel." - ".$model->deliveryAddress->landline_tel);?></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">کد مرسوله</div>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"><strong><?php echo $model->export_code?CHtml::encode($model->export_code):'-';?></strong></div>
                    </div>
                </div>
            </div>


            <?php
            if($model->transactions):
                ?>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 transaction-details table-responsive">
                    <h4>جزییات تراکنش های بانکی شما</h4>
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
                        foreach($model->transactions as $key => $transaction):
                            ?>

                            <tr>
                                <td><?= $key+1 ?></td>
                                <td><?= CHtml::encode($model->paymentMethod->title) ?></td>
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

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h4>وضعیت سفارش</h4>
                <?php
                Shop::PrintStatusLine($model->status);
                ?>
            </div>
            <?php
            if($model->payment_status == ShopOrder::PAYMENT_STATUS_UNPAID):
                ?>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <ul class="nav nav-justified">
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
                        <li>
                            <a href="<?= $this->createUrl('changePayment',array('id' => $model->id, 'method' => $item->name )) ?>" class="btn-<?= $class ?> btn btn-sm" ><?= $item->title ?></a>
                        </li>
                        <?php
                    endforeach;
                    ?>
                    </ul>
                </div>
                <?php
            endif;
            ?>
        </div>
    </div>
</div>
