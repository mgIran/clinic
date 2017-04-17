<?php
/* @var $this BookController */
/* @var $model Books */
/* @var $similar CActiveDataProvider */
/* @var $bookmarked boolean */
/* @var $categories array */
/* @var $about Pages */
$filePath = Yii::getPathOfAlias("webroot")."/uploads/books/files/";
$previewPath = Yii::getPathOfAlias("webroot")."/uploads/books/previews/";
$purifier=new CHtmlPurifier();
?>
<svg class="hidden" version="1.1" xmlns="http://www.w3.org/2000/svg">
    <defs>
        <symbol id="add-to-bookmark" viewBox="0 0 419 549">
            <g>
                <path d=" M 69.01 12.86 C 74.95 12.04 80.96 12.13 86.94 12.13 C 170.96 12.15 254.99 12.11 339.01 12.15 C 362.85 11.87 386.41 23.08 401.32 41.67 C 411.84 54.52 417.88 70.72 419.00 87.25 L 419.00 515.59 C 418.22 523.45 414.00 531.00 407.31 535.30 C 399.48 540.71 388.69 541.12 380.35 536.63 C 323.41 507.43 266.46 478.24 209.53 449.04 C 154.31 477.30 99.14 505.63 43.94 533.92 C 40.10 535.82 36.38 538.15 32.14 539.04 C 24.49 540.84 16.08 538.99 9.96 534.05 C 4.25 529.67 0.80 522.81 0.00 515.72 L 0.00 87.52 C 1.10 69.25 8.42 51.37 20.92 37.94 C 33.36 24.30 50.69 15.20 69.01 12.86 M 52.50 91.13 C 52.44 217.61 52.48 344.10 52.48 470.59 C 100.77 445.85 149.04 421.07 197.35 396.35 C 204.80 392.39 214.16 392.39 221.62 396.34 C 269.93 421.06 318.21 445.85 366.52 470.59 C 366.53 344.04 366.56 217.48 366.50 90.93 C 366.51 76.89 353.96 64.44 339.91 64.62 C 252.95 64.59 165.98 64.58 79.02 64.62 C 64.93 64.47 52.38 77.04 52.50 91.13 Z" />
                <path d=" M 205.54 138.31 C 216.65 136.42 228.44 142.69 233.18 152.89 C 235.54 157.55 235.92 162.87 235.77 168.01 C 235.76 180.53 235.77 193.06 235.77 205.58 C 249.83 205.65 263.90 205.55 277.96 205.63 C 289.95 205.73 300.92 215.18 302.95 226.96 C 304.83 236.36 300.95 246.54 293.39 252.39 C 288.76 256.06 282.86 258.06 276.96 258.00 C 263.23 258.01 249.50 257.99 235.77 258.01 C 235.72 272.02 235.83 286.04 235.72 300.05 C 235.59 308.65 230.84 316.96 223.56 321.53 C 215.03 327.09 203.23 326.93 194.85 321.14 C 187.91 316.50 183.38 308.40 183.31 300.02 C 183.26 286.02 183.32 272.01 183.29 258.01 C 168.85 257.93 154.40 258.11 139.96 257.92 C 126.41 257.31 114.92 244.68 115.60 231.11 C 115.66 218.01 126.93 206.27 140.01 205.68 C 154.44 205.48 168.87 205.68 183.29 205.58 C 183.32 191.37 183.23 177.17 183.34 162.96 C 183.64 150.83 193.54 139.93 205.54 138.31 Z" />
            </g>
        </symbol>
        <symbol id="bookmarked" viewBox="0 0 419 549">
            <g>
                <path d=" M 49.15 18.17 C 59.19 13.97 70.11 12.04 80.99 12.14 C 167.32 12.15 253.65 12.09 339.98 12.16 C 362.06 12.17 383.86 22.00 398.56 38.46 C 410.65 51.66 417.68 69.06 419.00 86.85 L 419.00 515.87 C 418.10 523.37 414.22 530.60 407.90 534.89 C 400.05 540.65 388.96 541.25 380.42 536.67 C 323.46 507.45 266.49 478.27 209.55 449.04 C 154.32 477.28 99.15 505.63 43.95 533.91 C 40.11 535.83 36.37 538.15 32.12 539.05 C 24.25 540.90 15.58 538.86 9.41 533.61 C 4.09 529.31 0.94 522.80 0.00 516.10 L 0.00 87.08 C 0.80 78.20 2.56 69.36 6.07 61.13 C 14.03 41.88 29.87 26.08 49.15 18.17 M 52.50 91.10 C 52.44 217.60 52.47 344.09 52.49 470.59 C 100.79 445.84 149.06 421.06 197.37 396.34 C 204.83 392.39 214.18 392.40 221.64 396.34 C 269.94 421.07 318.22 445.85 366.51 470.59 C 366.53 344.03 366.56 217.48 366.50 90.92 C 366.51 76.93 354.03 64.50 340.02 64.63 C 253.02 64.58 166.02 64.58 79.03 64.62 C 64.94 64.47 52.39 77.03 52.50 91.10 Z" />
                <path d=" M 288.40 151.59 C 297.64 150.20 307.40 154.73 312.29 162.70 C 318.16 171.74 316.86 184.62 309.25 192.27 C 273.41 228.10 237.54 263.90 201.66 299.68 C 192.86 308.77 176.93 308.91 167.99 299.96 C 149.93 282.00 131.89 264.01 113.91 245.97 C 105.05 237.01 105.31 221.08 114.45 212.41 C 122.98 203.43 138.52 202.94 147.61 211.35 C 158.60 222.06 169.32 233.04 180.25 243.81 C 181.69 245.30 183.88 246.06 185.93 245.58 C 188.34 245.14 189.84 242.99 191.53 241.42 C 219.19 213.79 246.87 186.17 274.55 158.55 C 278.26 154.82 283.18 152.33 288.40 151.59 Z" />
            </g>
        </symbol>
        <symbol id="remove-bookmark" viewBox="0 0 419 549">
            <g>
                <path d=" M 29.01 30.27 C 43.20 18.48 61.59 12.01 80.02 12.15 C 166.35 12.12 252.68 12.12 339.01 12.15 C 361.42 11.91 383.65 21.74 398.56 38.46 C 410.63 51.64 417.66 69.01 419.00 86.77 L 419.00 516.03 C 418.03 523.46 414.19 530.62 407.92 534.88 C 400.04 540.66 388.91 541.25 380.34 536.63 C 323.42 507.42 266.47 478.28 209.57 449.04 C 154.54 477.14 99.59 505.42 44.59 533.58 C 40.51 535.59 36.58 538.10 32.08 539.06 C 24.22 540.89 15.58 538.85 9.42 533.62 C 4.11 529.34 0.99 522.87 0.00 516.20 L 0.00 86.96 C 1.44 65.14 11.98 44.07 29.01 30.27 M 52.50 91.02 C 52.44 217.55 52.47 344.07 52.49 470.59 C 100.78 445.84 149.05 421.07 197.36 396.35 C 204.81 392.39 214.18 392.39 221.64 396.34 C 269.94 421.07 318.22 445.84 366.51 470.59 C 366.53 344.04 366.56 217.48 366.50 90.92 C 366.51 76.91 353.99 64.47 339.96 64.63 C 252.99 64.58 166.01 64.58 79.03 64.63 C 64.97 64.47 52.44 76.97 52.50 91.02 Z" />
                <path d=" M 138.54 171.57 C 143.13 162.78 153.05 157.15 162.96 157.78 C 169.49 158.05 175.81 160.97 180.39 165.62 C 190.05 175.32 199.80 184.95 209.41 194.70 C 219.79 184.66 229.76 174.20 240.16 164.19 C 250.15 155.11 267.04 155.92 276.18 165.83 C 285.67 175.26 285.94 191.98 276.70 201.68 C 266.74 211.79 256.61 221.75 246.62 231.84 C 256.66 241.97 266.86 251.95 276.83 262.15 C 286.05 271.89 285.68 288.72 276.04 298.04 C 266.78 307.93 249.76 308.47 239.90 299.18 C 229.69 289.19 219.67 279.00 209.55 268.92 C 199.55 278.80 189.69 288.82 179.69 298.70 C 173.07 305.10 162.93 307.42 154.15 304.71 C 144.79 301.98 137.38 293.62 135.86 283.98 C 134.38 275.76 137.20 266.95 143.14 261.09 C 152.87 251.31 162.65 241.57 172.40 231.81 C 163.91 223.24 155.35 214.75 146.84 206.21 C 144.01 203.35 140.92 200.62 138.96 197.05 C 134.46 189.38 134.26 179.38 138.54 171.57 Z" />
            </g>
        </symbol>
    </defs>
</svg>

<div class="page <?= $model->hasDiscount()?'has-discount':'' ?>">
    <div class="page-heading">
        <div class="container">
            <h1><?= CHtml::encode($model->title) ?></h1>
            <div class="page-info"><?php
                if($model->getPerson('نویسنده')):?><div>نویسنده<?= $model->getPersonsTags('نویسنده') ?></div><?php
                endif;
                ?><?php
                if($model->getPerson('مترجم')):?><div>مترجم<?= $model->getPersonsTags('مترجم') ?></div><?php
                endif;
                ?><div>ناشر<a href="<?php echo $this->createUrl('/book/publisher/'.$model->publisher_id.'/'.urlencode($model->getPublisherName()));?>"
                    ><?= CHtml::encode($model->getPublisherName()) ?></a></div>
            </div>
        </div>
    </div>
    <div class="container page-content book-view">
        <?php $this->renderPartial('//partial-views/_flashMessage') ?>
        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                <div class="row">
                    <?php if(!empty($model->icon) and file_exists(Yii::getPathOfAlias("webroot").'/uploads/books/icons/'.$model->icon)):?>
                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 thumb">
                            <img src="<?= Yii::app()->baseUrl.'/uploads/books/icons/'.$model->icon ?>" alt="<?= CHtml::encode($model->title) ?>" >
                        </div>
                    <?php else:?>
                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 thumb no-image">
                            <div class="img-container">
                                <img src="<?= Yii::app()->theme->baseUrl.'/svg/logo-white.svg';?>">
                            </div>
                        </div>
                    <?php endif;?>
                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12 book-info">
                        <div class="info">
                            <h4><?= CHtml::encode($model->title)?><small><span>ویرایش: <?php echo CHtml::encode($this->parseNumbers($model->lastPackage->version));?></span><span>سال چاپ: <?php echo CHtml::encode($this->parseNumbers($model->lastPackage->print_year));?></span></small></h4>
                            <div class="book-meta">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <div class="pull-right"><i class="page-count-icon"></i></div>
                                        <div class="meta-body">تعداد صفحات<div class="meta-heading"><?= CHtml::encode(Controller::parseNumbers(number_format($model->number_of_pages))) ?> صفحه</div></div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <div class="pull-right"><i class="calendar-icon"></i></div>
                                        <div class="meta-body ltr text-right">تاریخ انتشار<div class="meta-heading"><?= CHtml::encode(JalaliDate::date('d F Y',$model->lastPackage->publish_date)) ?></div></div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <div class="pull-right"><i class="file-icon"></i></div>
                                        <div class="meta-body">نوع فایل<div class="meta-heading"><?= $model->lastPackage->getUploadedFilesType(); ?></div></div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <div class="pull-right"><i class="download-icon"></i></div>
                                        <div class="meta-body">حجم فایل<div class="meta-heading file-size"><?= $this->parseNumbers($model->lastPackage->getUploadedFilesSize()); ?></div></div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <div class="pull-right"><i class="earth-icon"></i></div>
                                        <div class="meta-body">زبان<div class="meta-heading"><?= CHtml::encode($model->language) ?></div></div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <div class="pull-right"><i class="isbn-icon"></i></div>
                                        <div class="meta-body">شابک<div class="meta-heading"><?= CHtml::encode($model->lastPackage->isbn) ?></div></div>
                                    </div>
                                </div>
                            </div>
                            <div class="rating-container">
                                <?php if(!Yii::app()->user->isGuest):?>
                                    <span class="pull-left relative bookmark<?php echo ($bookmarked)?' bookmarked':'';?>">
                                            <?= CHtml::ajaxLink('',array('/book/bookmark'),array(
                                                'data' => "js:{bookId:$model->id}",
                                                'type' => 'POST',
                                                'dataType' => 'JSON',
                                                'success' => 'js:function(data){
                                                    if(data.status){
                                                        if($(".bookmark").hasClass("bookmarked")){
                                                            $(".svg-bookmark#bookmark").html("<use xlink:href=\'#add-to-bookmark\'></use>");
                                                            $(".bookmark").removeClass("bookmarked");
                                                            $(".bookmark").find(".title").text("نشان کردن");
                                                        }
                                                        else{
                                                            $(".svg-bookmark#bookmark").html("<use xlink:href=\'#bookmarked\'></use>");
                                                            $(".bookmark").find(".title").text("نشان شده");
                                                            $(".bookmark").addClass("bookmarked");
                                                        }
                                                    }
                                                    else
                                                        alert("در انجام عملیات خطایی رخ داده است لطفا مجددا تلاش کنید.");
                                                    return false;
                                                }'
                                            ),array(
                                                'id' =>"bookmark-book"
                                            )); ?>
                                        <svg id="bookmark" class="svg svg-bookmark gray"><use xlink:href="<?php echo ($bookmarked)?'#bookmarked':'#add-to-bookmark';?>"></use></svg>
                                            <svg id="remove" class="svg svg-bookmark gray"><use xlink:href="#remove-bookmark"></use></svg>
                                            <script>
                                                $(function(){
                                                    $(this).find(".svg-bookmark#remove").hide();
                                                    $('body').on('mouseenter','.bookmark',function(){
                                                        if($(this).hasClass('bookmarked')) {
                                                            $(this).find(".svg-bookmark#bookmark").hide();
                                                            $(this).find(".svg-bookmark#remove").show();
                                                        }
                                                    });
                                                    $('body').on('mouseleave','.bookmark',function(){
                                                        $(this).find(".svg-bookmark#bookmark").show();
                                                        $(this).find(".svg-bookmark#remove").hide();
                                                    });
                                                });
                                            </script>
                                            <span class="gray title" ><?php echo ($bookmarked)?'نشان شده':'نشان کردن';?></span>
                                        </span>
                                <?php endif;
                                ?>
                                <div class="stars">
                                    <?= Controller::printRateStars($model->rate) ?>
                                    <span>(<?= CHtml::encode(Controller::parseNumbers($model->getCountRate())) ?> کاربر)</span>
                                </div>
                                <?php
                                if(!Yii::app()->user->isGuest && Yii::app()->user->type == 'user'):
                                    if(!$model->publisher_id || $model->publisher_id != Yii::app()->user->getId()):
                                        $criteria = new CDbCriteria(array(
                                            'condition' => 'book_id = :book_id And user_id = :user_id',
                                            'params' => array(':book_id'=>$model->id, ':user_id' => Yii::app()->user->getId())
                                        ));
                                        $bought = Library::model()->find($criteria); ?>
                                        <? if($model->hasDiscount()):?>
                                            <h5 class="price text-danger">
                                                <span class="<?= $model->discount->hasPriceDiscount()?'text-line-through':'' ?>">
                                                <?= CHtml::encode(Controller::parseNumbers(number_format($model->price)).' تومان') ?></span>
                                                <small> / <span class="<?= $model->discount->hasPrintedPriceDiscount()?'text-line-through':'' ?>"><?= CHtml::encode('نسخه چاپی '.Controller::parseNumbers(number_format($model->printed_price)).' تومان') ?></span></small>
                                            </h5>
                                            <h5 class="price">
                                                <?= CHtml::encode(Controller::parseNumbers(number_format($model->offPrice)).' تومان') ?>
                                                <?php if($model->lastPackage->sale_printed):?>
                                                    <small> / <?= CHtml::encode('نسخه چاپی '.Controller::parseNumbers(number_format($model->off_printed_price)).' تومان') ?></small>
                                                <?php endif;?>
                                            </h5>
                                        <? else:?>
                                            <h5 class="price">
                                                <?= CHtml::encode(Controller::parseNumbers(number_format($model->price)).' تومان') ?>
                                                <?php if($model->lastPackage->sale_printed):?>
                                                    <small> / <?= CHtml::encode('نسخه چاپی '.Controller::parseNumbers(number_format($model->printed_price)).' تومان') ?></small>
                                                <?php endif;?>
                                            </h5>
                                        <? endif;?>
                                        <?php if(!$bought):?>
                                            <?php if($model->lastPackage->sale_printed):?>
                                                <div class="row buttons">
                                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                                        <a href="<?php echo $this->createUrl('/book/buy', array('id'=>$model->id, 'title'=>$model->title));?>" class="btn-red"><i class="add-to-library-icon"></i>افزودن به کتابخانه</a>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                                        <?php echo CHtml::beginForm(array("/shop/cart/add"));?>
                                                            <?php echo CHtml::hiddenField("book_id", $model->id);?>
                                                            <?php echo CHtml::hiddenField("qty", 1);?>
                                                            <?php echo CHtml::tag("button", array("type"=>"submit", "class"=>"btn-green"), '<i class="cart-icon"></i>خرید نسخه چاپی');?>
                                                        <?php echo CHtml::endForm();?>
                                                    </div>
                                                </div>
                                            <?php else:?>
                                                <a href="<?php echo $this->createUrl('/book/buy', array('id'=>$model->id, 'title'=>$model->title));?>" class="btn-red"><i class="add-to-library-icon"></i>افزودن به کتابخانه</a>
                                            <?php endif;?>
                                        <?php else:?>
                                            <?php if($model->lastPackage->sale_printed):?>
                                                <div class="buttons">
                                                    <?php echo CHtml::beginForm(array("/shop/cart/add"));?>
                                                    <?php echo CHtml::hiddenField("book_id", $model->id);?>
                                                    <?php echo CHtml::hiddenField("qty", 1);?>
                                                    <?php echo CHtml::tag("button", array("type"=>"submit", "class"=>"btn-green"), '<i class="cart-icon"></i>خرید نسخه چاپی');?>
                                                    <?php echo CHtml::endForm();?>
                                                </div>
                                            <?php endif;?>
                                        <?php endif;?>
                                            <?php
                                        /*else:
                                            if($bought->package_id):
                                                if($bought->package_id != $model->lastPackage->id):
                                                    ?>
                                                    <a href="<?php echo $this->createUrl('/book/updateVersion', array('id'=>$model->id, 'title'=>$model->title));?>" class="btn-red"><i class="add-to-library-icon"></i>به روزرسانی کتاب (ویرایش <?= Controller::parseNumbers($model->lastPackage->version) ?>)</a>
                                                    <?php
                                                else:
                                                    ?>
                                                    <a href="<?php echo $this->createUrl('/book/download', array('id'=>$model->id, 'title'=>$model->title));?>" class="btn-red"><i class="add-to-library-icon"></i>دانلود</a>
                                                    <?php
                                                endif;
                                            else:
                                                ?>
                                                <a href="<?php echo $this->createUrl('/book/updateVersion', array('id'=>$model->id, 'title'=>$model->title));?>" class="btn-red"><i class="add-to-library-icon"></i>به روزرسانی کتاب (ویرایش <?= Controller::parseNumbers($model->lastPackage->version) ?>)</a>
                                                <?php
                                            endif;*/
                                        //endif;
                                    else:
                                    ?>
                                        <h6>شما ناشر این کتاب هستید.</h6>
<!--                                        <a href="--><?php //echo $this->createUrl('/book/download', array('id'=>$model->id, 'title'=>$model->title));?><!--" class="btn-red"><i class="add-to-library-icon"></i>دانلود</a>-->
                                    <?
                                    endif;
                                else:
                                ?>
                                    <?
                                    if($model->hasDiscount()):
                                        ?>
                                        <h5 class="price text-danger">
                                        <span class="<?= $model->discount->hasPriceDiscount()?'text-line-through':'' ?>">
                                        <?= CHtml::encode(Controller::parseNumbers(number_format($model->price)).' تومان') ?></span>
                                            <small> / <span class="<?= $model->discount->hasPrintedPriceDiscount()?'text-line-through':'' ?>"><?= CHtml::encode('نسخه چاپی '.Controller::parseNumbers(number_format($model->printed_price)).' تومان') ?></span></small>
                                        </h5>
                                        <h5 class="price">
                                            <?= CHtml::encode(Controller::parseNumbers(number_format($model->offPrice)).' تومان') ?>
                                            <?php if($model->lastPackage->sale_printed):?>
                                                <small> / <?= CHtml::encode('نسخه چاپی '.Controller::parseNumbers(number_format($model->off_printed_price)).' تومان') ?></small>
                                            <?php endif;?>
                                        </h5>
                                        <?
                                    else:
                                        ?>
                                        <h5 class="price">
                                            <?= CHtml::encode(Controller::parseNumbers(number_format($model->price)).' تومان') ?>
                                            <?php if($model->lastPackage->sale_printed):?>
                                                <small> / <?= CHtml::encode('نسخه چاپی '.Controller::parseNumbers(number_format($model->printed_price)).' تومان') ?></small>
                                            <?php endif;?>
                                        </h5>
                                        <?
                                    endif;
                                    ?>
                                    <?php if($model->lastPackage->sale_printed):?>
                                        <div class="row buttons">
                                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                                <a href="#" data-target="#login-modal" data-toggle="modal" class="btn-red"><i class="add-to-library-icon"></i>افزودن به کتابخانه</a>
                                            </div>
                                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                                <?php echo CHtml::beginForm(array("/shop/cart/add"));?>
                                                    <?php echo CHtml::hiddenField("book_id", $model->id);?>
                                                    <?php echo CHtml::hiddenField("qty", 1);?>
                                                    <?php echo CHtml::tag("button", array("type"=>"submit", "class"=>"btn-green"), '<i class="cart-icon"></i>خرید نسخه چاپی');?>
                                                <?php echo CHtml::endForm();?>
                                            </div>
                                        </div>
                                    <?php else:?>
                                        <a href="#" data-target="#login-modal" data-toggle="modal" class="btn-red"><i class="add-to-library-icon"></i>افزودن به کتابخانه</a>
                                    <?php endif;?>
                                <?
                                endif;
                                ?>
                                <?php
                                if($model->preview_file && file_exists($previewPath.$model->preview_file)){
                                    echo '<a href="'.Yii::app()->baseUrl.'/uploads/books/previews/'.$model->preview_file.'" class="btn-blue" style="color: #fff;display: block;margin-top: 4px;overflow: hidden;width: 100%;"><i class="add-to-library-icon"></i>دریافت پیش نمایش</a>';
                                }
                                ?>
                                <div class="small-info">
                                    <p>دسته بندی: <span><a href="<?= $this->createUrl('/category/'.$model->category_id.'/'.urldecode($model->category->title)) ?>" ><?= CHtml::encode($model->category->title) ?></a></span></p>
                                    <?php if($model->showTags): ?><p>بر چسب ها: <span><?php
                                            $links=array();
                                            foreach ($model->showTags as $tag):
                                                if(!empty($tag->title))
                                                    $links[]='<a href="'.$this->createUrl('/book/tag/'.$tag->id.'/'.urldecode($tag->title)).'">'.$tag->title.'</a>';
                                            endforeach;
                                            echo implode(', ', $links);
                                            ?></span></p>
                                    <?php endif;?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 book-tabs">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#summary">توضیحات</a></li>
                            <li><a data-toggle="tab" href="#comments">نظرات (<?= CHtml::encode(Controller::parseNumbers($model->getCountComments())) ?>)</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="summary" class="tab-pane fade in active"><?php echo $purifier->purify($model->description); ?></div>
                            <div id="comments" class="tab-pane fade">
                                <?php $this->widget('comments.widgets.ECommentsListWidget', array(
                                    'model' => $model,
                                )); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <ul class="social-list list-inline">
                            <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?= $this->createAbsoluteUrl('/book/'.$model->id.'/'.urlencode($model->title)) ?>"
                                   class="social-icon"><i class="facebook-icon"></i></a></li>
                            <li><a href="https://twitter.com/home?status=<?= $this->createAbsoluteUrl('/book/'.$model->id.'/'.urlencode($model->title)) ?>"
                                   class="social-icon"><i class="twitter-icon"></i></a></li>
                        </ul>
                    </div>
                    <?php
                    if($similar->totalItemCount):
                    ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="similar-books">
                            <div class="heading">
                                <h4>کتاب های مشابه</h4>
                                <div class="is-carousel" data-item-selector="thumbnail-container"  data-mouse-drag="1" data-responsive='{"1024":{"items":"3"},"992":{"items":"2"},"710":{"items":"3"},"520":{"items":"2"},"0":{"items":"1","dots":true, "nav":false}}' data-dots="1" data-nav="0">
                                    <?php
                                    $this->widget('zii.widgets.CListView',array(
                                        'id' => 'latest-list',
                                        'dataProvider' => $similar,
                                        'itemView' => '//site/_book_item',
                                        'template' => '{items}',
                                        'viewData' => array('itemClass' => 'small')
                                    ));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    endif;
                    ?>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 sidebar-col">
                <div class="boxed">
                    <div class="heading">
                        <h4>درباره <?php echo Yii::app()->name;?></h4>
                    </div>
                    <div class="text-justify"><?php
                        $purifier = new CHtmlPurifier();
                        $purifier->setOptions(array(
                            'HTML.Allowed'=> 'p,a[href|target],b,i,br',
                            'HTML.AllowedAttributes'=> 'style,id,class,src',
                        ));
                        echo $purifier->purify($about->summary);
                        ?></div>
                </div><div class="boxed">
                    <div class="heading">
                        <h4>دسته بندی ها</h4>
                    </div>
                    <ul class="categories">
                        <?php echo $categories; ?>
                    </ul>
                </div><?php if($model->seoTags): ?><div class="boxed">
                    <div class="heading">
                        <h4>برچسب ها</h4>
                    </div>
                    <div class="tags">
                    <?php foreach ($model->seoTags as $tag):
                        if(!empty($tag->title))
                            echo '<a href="'.$this->createUrl('/book/tag/'.$tag->id.'/'.urldecode($tag->title)).'">'.$tag->title.'</a>';
                    endforeach; ?>
                    </div>
                </div>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>