<?php
/* @var $this BookController */
/* @var $data Books */
/* @var $itemClass string */
/* @var $buy boolean */
if(!isset($type) || (isset($type) && $type == 'book')){
    ?>
    <div class="thumbnail-container  <?= $data->hasDiscount()?'discount':''; ?>">
        <a href="<?= $this->createUrl('/book/' . $data->id . '/' . urlencode($data->title)) ?>"
           title="<?= CHtml::encode($data->title) ?>">
            <div class="thumbnail <?= (isset($itemClass)?$itemClass:''); ?>">
                <div class="caption">
                    <div class="heading">
                        <h4><?= CHtml::encode($data->title) ?></h4>
                    </div>
                    <div class="stars">
                        <?= Controller::printRateStars($data->rate); ?>
                    </div>
                    <span class="category">دسته بندی: <?php echo $data->category->title ?></span>
                <span class="price"><?php if($data->price == 0):
                        echo 'رایگان';
                    else:?>
                        <?
                        if($data->hasDiscount()):
                            ?>
                            <span
                                class="text-danger text-line-through center-block"><?= Controller::parseNumbers(number_format($data->price, 0)) . ' تومان'; ?></span>
                            <span><?= Controller::parseNumbers(number_format($data->offPrice, 0)) . ' تومان'; ?></span>
                            <?
                        else:
                            ?>
                            <span><?= $data->price?Controller::parseNumbers(number_format($data->price, 0)) . ' تومان':'رایگان'; ?></span>
                            <?
                        endif;
                        ?>
                    <?php endif; ?>
                </span>
                </div>
            </div>
        </a>
    </div>
    <?php
}else if(isset($type) && $type == 'person')
{
    /* @var $data BookPersons */
    ?>
    <div class="thumbnail-container">
        <a href="<?= $this->createUrl('/book/person/' . $data->id . '/' . urlencode($data->name_family)) ?>"
           title="<?= CHtml::encode($data->name_family) ?>">
            <div class="thumbnail">
                <div class="caption">
                    <div class="heading">
                        <h4><?= CHtml::encode($data->name_family) ?></h4>
                    </div>
                </div>
            </div>
        </a>
    </div>
<?
}else if(isset($type) && $type == 'publisher')
{
    /* @var $data Users */
    ?>
    <div class="thumbnail-container">
        <a href="<?= $this->createUrl('/book/publisher/' . $data->id . '/' . urlencode($data->userDetails->getPublisherName())) ?>"
           title="<?= CHtml::encode($data->userDetails->getPublisherName()) ?>">
            <div class="thumbnail">
                <div class="caption">
                    <div class="heading">
                        <h4><?= CHtml::encode($data->userDetails->getPublisherName()) ?></h4>
                    </div>
                </div>
            </div>
        </a>
    </div>
<?
}