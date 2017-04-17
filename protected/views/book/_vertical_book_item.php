<?php
/* @var $this BooksController */
/* @var $data Books */
?>

<div class="book-details">
    <div class="pic">
        <img src="<?= Yii::app()->baseUrl.'/uploads/books/icons/'.CHtml::encode($data->icon); ?>">
    </div>
    <div class="book-content">
        <div class="title">
            <a href="<?php echo $this->createUrl('/book/'.CHtml::encode($data->id).'/'.CHtml::encode($data->lastPackage->package_name));?>"><?php echo CHtml::encode($data->title);?></a>
        </div>
        <div class="title" >
            <span class="text-right green col-lg-6 col-md-6 col-sm-6 col-xs-6" >
                <?php if($data->price==0):?>
                    <a href="<?php echo Yii::app()->createUrl('/book/free')?>">رایگان</a>
                <?php else:?>
                    <?
                    if($data->hasDiscount()):
                        ?>
                        <span class="text-danger text-line-through center-block"><?= Controller::parseNumbers(number_format($data->price, 0)).' تومان'; ?></span>
                        <span ><?= Controller::parseNumbers(number_format($data->offPrice, 0)).' تومان' ; ?></span>
                        <?
                    else:
                        ?>
                        <span ><?= $data->price?Controller::parseNumbers(number_format($data->price, 0)).' تومان':'رایگان'; ?></span>
                        <?
                    endif;
                    ?>
                <?php endif;?>
            </span>
            <span class="ltr text-left book-rate col-lg-6 col-md-6 col-sm-6 col-xs-6 pull-left" >
                <?= Controller::printRateStars($data->rate); ?>
            </span>
        </div>
        <div class="book-desc">
            <?php
                echo strip_tags(nl2br($data->description));
            ?>
            <span class="paragraph-end"></span>
        </div>
    </div>
    <div class="book-footer">
        <span class="col-lg-4 col-md-4 col-sm-4 hidden-xs"><?php echo Controller::parseNumbers(number_format($data->download, 0)).' دانلود';?></span>
        <span class="col-lg-4 col-md-4 col-sm-4 hidden-xs"><?php echo Controller::parseNumbers(round($data->size/1024,1)).' کیلوبایت';?></span>
        <span class="col-lg-4 col-md-4 col-sm-4 hidden-xs green"><?php echo (is_null($data->publisher_id) or empty($data->publisher_id))?CHtml::encode($data->publisher_name):CHtml::encode($data->publisher->userDetails->fa_name);?></span>
    </div>
</div>