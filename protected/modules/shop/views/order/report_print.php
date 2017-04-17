<?php
/* @var $this ShopOrderController */
/* @var $model ShopOrder */
Yii::app()->clientScript->registerCss('nested-row','
    .nested-row{
        display:block;
        padding:2px 0;
        text-align:center;
        border-bottom: 1px solid #aaa;
    }
    td > .nested-row:last-of-type{
        border:none
    }
');
Yii::app()->clientScript->registerScript('print','
    window.print();
');
?>

<h1>گزارش فروش نسخه های چاپی کتاب ها</h1>
<br>
<br>
<br>
<br>
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>شناسه رسید</th>
            <th>کتاب ها</th>
<!--            <th>کاربر</th>-->
            <th><?=$model->getAttributeLabel('ordering_date')?></th>
            <th><?=$model->getAttributeLabel('status')?></th>
            <th><?=$model->getAttributeLabel('payment_method')?></th>
            <th><?=$model->getAttributeLabel('shipping_method')?></th>
            <th>مبلغ پایه</th>
            <th>تخفیف</th>
            <th>جمع پرداختی</th>
            <th>مالیات</th>
        </tr>
    </thead>
    <tbody>
    <?php
    foreach($model->report(false)->getData() as $data):
    ?>
        <tr>
            <td><?= $data->getOrderId() ?></td>
            <td><?php
                    $html = '';
                    foreach($data->items as $item){
                        $html.=CHtml::tag('div',array('class' => 'nested-row text-nowrap'),$item->model->title.'<small>('.Controller::parseNumbers(number_format($item->qty)).'عدد)</small>');
                        $html.=CHtml::closeTag('div');
                    }
                    echo $html;
                    ?></td>

<!--            <td>--><?//= $data->user && $data->user->userDetails?$data->user->userDetails->getShowName():''; ?><!--</td>-->
            <td><?= JalaliDate::date('Y/m/d - H:i', $data->ordering_date); ?></td>
            <td><?=$data->statusLabel?></td>
            <td>
                    <?php
                    $p = '';
                    if($data->payment_price)
                        $p = '<br><small>('.Controller::parseNumbers(number_format($data->payment_price)).' تومان)</small>';
                    echo $data->paymentMethod->title.$p;
                    ?></td>

            <td><?php
                    $p = '<br><small>(رایگان)</small>';
                    if($data->shipping_price)
                        $p = '<br><small>('.Controller::parseNumbers(number_format($data->shipping_price)).' تومان)</small>';
                    echo $data->shippingMethod->title.$p;
                    ?></td>
            <td><?php
                    $html = '';
                    foreach($data->items as $item){
                        $html.=CHtml::tag('div',array('class' => 'nested-row'),Controller::parseNumbers(number_format($item->base_price*$item->qty)).' تومان');
                        $html.=CHtml::closeTag('div');
                    }
                    echo $html;
                    ?></td>
            <td><?= Controller::parseNumbers(number_format($data->discount_amount)).' تومان' ?></td>
            <td><?= Controller::parseNumbers(number_format($data->payment_amount)) . ' تومان' ?></td>
            <td><?= Controller::parseNumbers(number_format($data->getTax())) . ' تومان' ?></td>
        </tr>
    <?php
    endforeach;
    ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4"><h5 class="text-center"><strong>مجموع</strong></h5></td>
            <td><?= Controller::parseNumbers(number_format($model->getTotalPaymentPrice())).' تومان' ?></td>
            <td><?= Controller::parseNumbers(number_format($model->getTotalShippingPrice())).' تومان' ?></td>
            <td><?= Controller::parseNumbers(number_format($model->getTotalPrice())).' تومان' ?></td>
            <td><?= Controller::parseNumbers(number_format($model->getTotalDiscount())).' تومان' ?></td>
            <td><?= Controller::parseNumbers(number_format($model->getTotalPayment())).' تومان' ?></td>
            <td><?= Controller::parseNumbers(number_format($model->getTotalTax())).' تومان' ?></td>
        </tr>
    </tfoot>
</table>
