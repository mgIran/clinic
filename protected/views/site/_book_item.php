<?php
/* @var $this BookController */
/* @var $data Books */
/* @var $itemClass string */
/* @var $buy boolean */
if(!isset($buy))
    $buy=true;
?>
    <div class="thumbnail-container <?=$data->hasDiscount()?'discount':'';?>">
        <div class="thumbnail <?= (isset($itemClass)?$itemClass:''); ?>">
            <div class="thumb">
                <a href="<?= $this->createUrl('/book/'.$data->id.'/'.urlencode($data->title)); ?>" title="<?= CHtml::encode($data->title) ?>">
                    <img src="<?= Yii::app()->baseUrl.'/uploads/books/icons/'.$data->icon ?>" alt="<?= CHtml::encode($data->title) ?>" >
                    <?php
                    if(!isset($itemClass) || (isset($itemClass) && $itemClass != 'small')):
                        ?>
                        <div class="thumbnail-overlay"></div>
                        <div class="thumbnail-overlay-icon">
                            <i class="icon"></i>
                        </div>
                        <?php
                    endif;
                    ?>
                </a>
            </div>
            <div class="caption">
                <a href="<?= $this->createUrl('/category/'.$data->category_id.'/'.urlencode($data->category->title)) ?>" title="<?= CHtml::encode($data->category->title) ?>">
                    <div class="cat-icon" id="book-<?=$data->id?>-category-icon-<?= $data->category_id?>"></div>
                </a>
                <?php
                if(isset($itemClass) && $itemClass == 'small'):
                    ?>
                    <div class="heading">
                        <h4><a href="<?= $this->createUrl('/book/'.$data->id.'/'.urlencode($data->title)) ?>"
                               title="<?= CHtml::encode($data->title) ?>"><?= CHtml::encode($data->title) ?></a></h4>
                    </div>
                    <?php
                endif;
                ?>
                <?php
                if(isset($itemClass) && $itemClass == 'smallest'):
                    ?>
                    <h4><a href="<?= $this->createUrl('/book/'.$data->id.'/'.urlencode($data->title)) ?>"
                           title="<?= CHtml::encode($data->title) ?>"><?= CHtml::encode($data->title) ?></a></h4>
                    <?php
                endif;
                ?>
                <div class="stars" title="امتیاز <?= Controller::parseNumbers(number_format($data->rate,1)) ?>" data-placement="bottom">
                    <?= Controller::printRateStars($data->rate); ?>
                </div>
                <?php
                if(!isset($itemClass) || (isset($itemClass) && ($itemClass != 'small' && $itemClass != 'smallest'))):
                    ?>
                    <h4><a href="<?= $this->createUrl('/book/'.$data->id.'/'.urlencode($data->title)) ?>"
                           title="<?= CHtml::encode($data->title) ?>"><?= CHtml::encode($data->title) ?></a></h4>
                    <?php
                endif;
                ?>
                <span class="price"><?php if($data->price==0):?>
                        <a href="<?php echo Yii::app()->createUrl('/book/free')?>">رایگان</a>
                    <?php else:?>
                        <?
                        if($data->hasDiscount() && $data->discount->hasPriceDiscount()):
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
                <?php
                if(!Yii::app()->user->isGuest && Yii::app()->user->type == 'user'):
                    if($buy):
                        if(!isset($itemClass) || (isset($itemClass) && ($itemClass != 'small' && $itemClass != 'smallest'))):
                            ?>
                            <a href="<?php echo $this->createUrl('/book/buy', array('id'=>$data->id, 'title'=>$data->title));?>" class="btn btn-add-to-library" role="button"><i class="icon"></i>افزودن به کتابخانه</a>
                            <?php
                        endif;
                    endif;
                else:
                    ?>
                    <a href="#" data-target="#login-modal" data-toggle="modal" class="btn btn-add-to-library" role="button" data-return-url="<?= $this->createUrl('/book/buy', array('id'=>$data->id, 'title'=>$data->title)); ?>" ><i class="icon"></i>افزودن به کتابخانه</a>
                    <?
                endif;
                ?>
            </div>
        </div>
    </div>
<?php
Yii::app()->clientScript->registerCss('book-'.$data->id.'-category-icon-'.$data->category_id,
    '#book-'.$data->id.'-category-icon-'.$data->category_id.'{background-color:'.$data->category->icon_color.';
    background-image:url("'.Yii::app()->baseUrl.'/uploads/bookCategories/icons/'.$data->category->icon.'");}');
?>