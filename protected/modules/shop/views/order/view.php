<?php
/* @var $this ShopOrderController */
/* @var $model ShopOrder */
/* @var $form CActiveForm */

$this->breadcrumbs=array(
	'مدیریت سفارشات'=>array('admin'),
	'نمایش اطلاعات سفارش '.$model->getOrderID(),
);

$this->menu=array(
	array('label'=>'لیست سفارش', 'url'=>array('index')),
	array('label'=>'افزودن سفارش', 'url'=>array('create')),
	array('label'=>'ویرایش سفارش', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'حذف سفارش', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'مدیریت سفارش', 'url'=>array('admin')),
);
?>

<h1>نمایش اطلاعات سفارش #<?php echo $model->getOrderID(); ?></h1>
<br>
<br>
<div class="order-details">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h4>وضعیت سفارش</h4>
        <span class="description">برای تغییر وضعیت سفارش روی وضعیت موردنظر کلیک کنید</span>
        <?php
        Shop::PrintStatusLine($model->status,true,$model->id);
        ?>
    </div>
    <br>
    <br>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
        <h4>جزییات اقلام سفارش</h4>
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>شرح محصول</th>
                <th class="text-center">تعداد</th>
                <th class="text-center">قیمت پایه واحد</th>
                <th class="text-center">قیمت واحد<small>(همراه با تخفیف)</small></th>
                <th class="text-center">قیمت کل</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($model->items as $item):?>
                <tr>
                    <td>
                        <h5><a href="<?php echo $this->createUrl("/book/".$item->model->id."/".urlencode($item->model->title));?>"><?php echo CHtml::encode($item->model->title);?></a></h5>
                        <span class="item hidden-xs">نویسنده: <span class="value"><?php echo $item->model->getPersonsTags("نویسنده", "fullName", true, "span");?></span></span>
                        <span class="item hidden-xs">ناشر: <span class="value"><?php echo $item->model->getPublisherName();?></span></span>
                    </td>
                    <td class="text-center">
                        <?php echo CHtml::encode(Controller::parseNumbers($item->qty));?> عدد
                    </td>
                    <td class="text-center">
                        <?php echo CHtml::encode(Controller::parseNumbers(number_format($item->base_price)));?> تومان
                    </td>
                    <td class="text-center">
                        <?php echo CHtml::encode(Controller::parseNumbers(number_format($item->payment)));?> تومان
                    </td>
                    <td class="text-center">
                        <?php echo CHtml::encode(Controller::parseNumbers(number_format($item->payment * $item->qty)));?>تومان
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>
            <tfoot>
                <tr>
                    <td>
                        
                    </td>
                    <td class="text-center">
                        <?php echo CHtml::encode(Controller::parseNumbers($item->qty));?> عدد
                    </td>
                    <td class="text-center">
                        <?php echo CHtml::encode(Controller::parseNumbers(number_format($item->base_price)));?> تومان
                    </td>
                    <td class="text-center">
                        <?php echo CHtml::encode(Controller::parseNumbers(number_format($item->payment)));?> تومان
                    </td>
                    <td class="text-center">
                        <?php echo CHtml::encode(Controller::parseNumbers(number_format($item->payment * $item->qty)));?>تومان
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <br>
    <br>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <h4>اطلاعات سفارش</h4>
        <?php $this->widget('zii.widgets.CDetailView', array(
            'data'=>$model,
            'htmlOptions' => array('class'=>'detail-view table table-striped','style' => 'margin-top:30px'),
            'attributes'=>array(
                array(
                    'label' => $model->getAttributeLabel('id'),
                    'name' => 'orderID',
                ),
                array(
                    'label' => 'کاربر',
                    'value' => function($data){
                        return $data->user && $data->user->userDetails?$data->user->userDetails->getShowName():'';
                    }
                ),
                array(
                    'name' => 'ordering_date',
                    'value' => JalaliDate::date('Y/m/d - H:i', $model->ordering_date)
                ),
                array(
                    'name' => 'update_date',
                    'value' => JalaliDate::date('Y/m/d - H:i', $model->update_date)
                ),
                array(
                    'name' => 'status',
                    'value' => $model->getStatusLabel()
                ),
            ),
        )); ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <h4>اطلاعات مالی سفارش</h4>
        <?php $this->widget('zii.widgets.CDetailView', array(
            'data'=>$model,
            'htmlOptions' => array('class'=>'detail-view table table-striped','style' => 'margin-top:30px'),
            'attributes'=>array(
                array(
                    'name' => 'payment_method',
                    'value' => $model->paymentMethod->title
                ),
                array(
                    'name' => 'payment_status',
                    'value' => $model->getPaymentStatusLabel()
                ),
                array(
                    'label' => 'کد رهگیری تراکنش',
                    'value' => $model->transaction?$model->transaction->token:'',
                    'cssClass' => 'token-text text-lg'
                ),
                array(
                    'name' => 'price_amount',
                    'value' => function($data){
                        return Controller::parseNumbers(number_format($data->price_amount)).' تومان';
                    }
                ),
                array(
                    'name' => 'discount_amount',
                    'value' => function($data){
                        return Controller::parseNumbers(number_format($data->discount_amount)).' تومان';
                    }
                ),
                array(
                    'name' => 'payment_amount',
                    'value' => function($data){
                        return Controller::parseNumbers(number_format($data->payment_amount)).' تومان';
                    },
                    'cssClass' => 'green-text text-lg'
                ),
            ),
        )); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h4>اطلاعات ارسال سفارش</h4>
        <div class="row">
            <h3>ثبت کد مرسوله</h3>
            <div class="form">
                <?php
                $model->scenario='export-code';
                $form = $this->beginWidget('CActiveForm',array(
                    'id' =>'export-code-form',
                    'action' => array('exportCode','id' => $model->id),
                    'enableAjaxValidation' => false,
                    'enableClientValidation' => true,
                    'clientOptions' => array(
                        'validateOnSubmit' => true,
                        'afterValidate' => 'js: function(form, data, hasError){
                            if(!hasError)
                                return true;
                            return false;
                        }'
                )));
                ?>
                <div class="form-group" style='width:200px'>
                    <?php
                    echo $form->labelEx($model, 'export_code',array('class' => 'control-label'));
                    echo $form->textField($model, 'export_code',array('maxLength'=>100, 'class' => 'form-control'));
                    echo $form->error($model, 'export_code');
                    ?>
                </div>
                <div class="buttons">
                    <?php
                    echo CHtml::submitButton($model->export_code?'ویرایش کد مرسوله':'ثبت کد مرسوله',array('class' => 'btn btn-success'));
                    ?>
                </div>
                <?php
                $this->endWidget();
                ?>
            </div>
        </div>
        <br>
        <?php $this->widget('zii.widgets.CDetailView', array(
            'data'=>$model,
            'htmlOptions' => array('class'=>'detail-view table table-striped','style' => 'margin-top:30px'),
            'attributes'=>array(
                array(
                    'name' => 'shipping_method',
                    'value' => $model->shippingMethod->title.'<small> (هزینه ارسال '.Controller::parseNumbers(number_format($model->shippingMethod->price)).' تومان)</small>',
                    'type' => 'raw'
                ),
                array(
                    'name' => 'export_code',
                    'value' => $model->export_code,
                    'cssClass' => 'token-text text-lg',
                ),
                array(
                    'name' => 'deliveryAddress.transferee',
                    'value' => $model->deliveryAddress->transferee,
                ),
                array(
                    'label' => 'استان',
                    'value' => $model->deliveryAddress->town->name,
                ),
                array(
                    'label' => 'شهرستان',
                    'value' => $model->deliveryAddress->place->name,
                ),
                array(
                    'name' => 'deliveryAddress.district',
                    'value' => $model->deliveryAddress->district,
                ),
                array(
                    'name' => 'deliveryAddress.landline_tel',
                    'value' => $model->deliveryAddress->landline_tel,
                ),
                array(
                    'name' => 'deliveryAddress.emergency_tel',
                    'value' => $model->deliveryAddress->emergency_tel,
                ),
                array(
                    'name' => 'deliveryAddress.postal_address',
                    'value' => $model->deliveryAddress->postal_address,
                ),
                array(
                    'name' => 'deliveryAddress.postal_code',
                    'value' => $model->deliveryAddress->postal_code,
                    'cssClass' => 'token-text text-lg',
                ),
            ),
        )); ?>
    </div>
    <div class="clearfix"></div>
        <br>
        <br>
    <?php
    if($model->transactions):
        ?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h4>جزییات پرداخت های این سفارش</h4>
            <table style="margin-top:30px" class="table table-striped">
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
        <br>
        <br>
        <?
    endif;
    ?>
</div>
