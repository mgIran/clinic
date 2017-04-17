<?php
/* @var $this ShopCartController */
/* @var $books Books[] */
/* @var $model Books */
if($books):?>
    <table class="table">
        <thead>
        <tr>
            <th>شرح محصول</th>
            <th class="text-center">تعداد</th>
            <th class="text-center hidden-xs">قیمت واحد</th>
            <th class="text-center hidden-xs">قیمت کل</th>
            <th class="hidden-xs"></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($books as $position => $book):?>
            <?php if(@$model = Books::model()->findByPk($book['book_id'])):?>
                <tr>
                    <td>
                        <a href="<?php echo $this->createUrl("/book/".$model->id."/".urlencode($model->title));?>"><img src="<?php echo Yii::app()->baseUrl."/uploads/books/icons/".$model->icon;?>" alt="<?php echo CHtml::encode($model->title);?>" class="hidden-xs hidden-sm"></a>
                        <div class="info">
                            <h4><a href="<?php echo $this->createUrl("/book/".$model->id."/".urlencode($model->title));?>"><?php echo CHtml::encode($model->title);?></a></h4>
                            <span class="item hidden-xs">نویسنده: <span class="value"><?php echo $model->getPersonsTags("نویسنده", "fullName", true, "span");?></span></span>
                            <span class="item hidden-xs">ناشر: <span class="value"><?php echo CHtml::encode($model->getPublisherName());?></span></span>
                            <span class="item hidden-xs">سال چاپ: <span class="value"><?php echo Controller::parseNumbers($model->lastPackage->print_year);?></span></span>
                            <span class="item hidden-xs">تعداد صفحات: <span class="value"><?php echo Controller::parseNumbers($model->number_of_pages);?> صفحه</span></span>
                        </div>
                    </td>
                    <td class="vertical-middle text-center">
                        <?php echo CHtml::dropDownList('qty_'.$position, $book["qty"], Shop::$qtyList, array("class"=>"quantity", "data-id"=>$position));?>
                        <?php echo CHtml::link("حذف", array('//shop/cart/remove'), array("class"=>"remove hidden-lg hidden-md hidden-sm", 'confirm' => 'آیا از حذف این کتاب مطمئن هستید؟', 'data-id' => $position));?>
                    </td>
                    <td class="vertical-middle text-center hidden-xs">
                        <?php $price = $model->getPrinted_price();?>
                        <?php if($model->hasDiscount() && $model->discount->hasPrintedPriceDiscount()):?>
                            <?php $price = $model->off_printed_price;?>
                            <span class="price text-danger text-line-through"><?= Controller::parseNumbers(number_format($model->printed_price, 0)); ?><small> تومان</small></span>
                            <span class="price center-block"><?= Controller::parseNumbers(number_format($model->off_printed_price, 0)); ?><small> تومان</small></span>
                        <?php else:?>
                            <span class="price"><?php echo Controller::parseNumbers(number_format($model->getPrinted_price()))?><small> تومان</small></span>
                        <?php endif;?>
                    </td>
                    <td class="vertical-middle text-center hidden-xs">
                        <span class="price"><?php echo Controller::parseNumbers(number_format((double)($book["qty"]*$price)))?><small> تومان</small></span>
                    </td>
                    <td class="vertical-middle text-center hidden-xs">
                        <?php echo CHtml::link("حذف", array('//shop/cart/remove'), array("class"=>"remove", 'confirm' => 'آیا از حذف این کتاب مطمئن هستید؟', 'data-id' => $position));?>
                    </td>
                </tr>
            <?php endif;?>
        <?php endforeach;?>
        </tbody>
    </table>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 pull-left total-container">
        <div class="row">
            <?php $cartStatistics=Shop::getPriceTotal(); ?>
            <ul class="list-group green-list">
                <li class="list-group-item">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">جمع کل خرید شما</div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 price text-center"><?php echo Controller::parseNumbers(number_format($cartStatistics["totalPrice"]));?><small> تومان</small></div>
                </li>
                <li class="list-group-item">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 tax-container">تخفیف</div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 price text-center"><?php echo Controller::parseNumbers(number_format($cartStatistics["totalDiscount"]));?><small> تومان</small></div>
                </li>
                <li class="list-group-item">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 total">مبلغ قابل پرداخت</div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 price text-center total-value"><?php echo Controller::parseNumbers(number_format($cartStatistics["cartPrice"]));?><small> تومان</small></div>
                </li>
            </ul>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="buttons">
        <a href="<?php echo $this->createUrl("/site");?>" class="btn-black pull-right">بازگشت به صفحه اصلی</a>
        <a href="<?php echo $this->createUrl("/shop/order/create");?>" class="btn-blue pull-left">انتخاب شیوه ارسال</a>
    </div>
<?php else:?>
    <div class="empty-message">
        <h4>سبد خرید شما خالی می باشد<small>جهت خرید کتاب می توانید به لیست کتاب ها مراجعه کنید.</small></h4>
        <a class="btn-blue" href="<?php echo $this->createUrl("/book/index");?>">لیست کتاب ها</a>
    </div>
<?php endif;?>