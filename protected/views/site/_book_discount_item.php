<?php
/* @var $data BookDiscounts */
$book = $data->book;
if($book && $book->hasDiscount()) {
    ?>

    <div class="book-item <?=$book->hasDiscount()?'discount':''?>">
        <div class="book-item-content">
            <div class="pic">
                <div>
                    <a href="<?php echo Yii::app()->createUrl('/book/'.$book->id.'/'.urlencode($book->lastPackage->package_name)); ?>">
                        <img src="<?php echo Yii::app()->baseUrl.'/uploads/books/icons/'.CHtml::encode($book->icon); ?>">
                    </a>
                </div>
            </div>
            <div class="detail">
                <div class="book-title">
                    <a href="<?php echo Yii::app()->createUrl('/book/'.$book->id.'/'.urlencode($book->lastPackage->package_name)); ?>">
                        <?php echo CHtml::encode($book->title); ?>
                        <span class="paragraph-end"></span>
                    </a>
                </div>
                <div class="book-any">
                    <span class="book-price">
                        <?
                        if($data->hasDiscount() && $data->discount->hasPriceDiscount()):
                            ?>
                            <span class="text-danger text-line-through center-block"><?= Controller::parseNumbers(number_format($book->price, 0)).' تومان'; ?></span>
                            <span ><?= Controller::parseNumbers(number_format($book->offPrice, 0)).' تومان' ; ?></span>
                            <?
                        else:
                            ?>
                            <span ><?= $book->price?Controller::parseNumbers(number_format($book->price, 0)).' تومان':'رایگان'; ?></span>
                            <?
                        endif;
                        ?>
                    </span>
                    <span class="book-rate" title="امتیاز <?= Controller::parseNumbers(number_format($data->rate,1)) ?>" data-placement="bottom">
                        <?= Controller::printRateStars($book->rate); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <?
}