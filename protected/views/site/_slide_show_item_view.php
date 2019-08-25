<?php
/* @var $data Slideshow*/
$path = Yii::getPathOfAlias('webroot').'/uploads/slideshow/';

if (($data->image && is_file($path . $data->image)) || ($data->mobile_image && is_file($path . $data->mobile_image))):
    if ($data->link != ''):
        ?>
        <a href="<?= $data->link ?>" target="_blank" class="ls-slide"
           data-ls="slidedelay:4000;transition2d:21,105;timeshift:-1000;">

            <?php if ($data->image && is_file($path . $data->image)): ?>
                <img class="ls-bg hidden-xs"
                     src="<?= Yii::app()->baseUrl . '/uploads/slideshow/' . $data->image ?>"
                     alt="<?= $data->title ?>"/>
            <?php endif; ?>

            <?php if ($data->mobile_image && is_file($path . $data->mobile_image)): ?>
                <img class="ls-bg hidden-lg hidden-md hidden-sm"
                     src="<?= Yii::app()->baseUrl . '/uploads/slideshow/' . $data->mobile_image ?>"
                     alt="<?= $data->title ?>"/>
            <?php endif; ?>
        </a>
    <?php
    else:?>
        <div class="ls-slide" data-ls="slidedelay:4000;transition2d:21,105;timeshift:-1000;">
            <?php if ($data->image && is_file($path . $data->image)): ?>
                <img class="ls-bg hidden-xs"
                     src="<?= Yii::app()->baseUrl . '/uploads/slideshow/' . $data->image ?>"
                     alt="<?= $data->title ?>"/>
            <?php endif; ?>

            <?php if ($data->mobile_image && is_file($path . $data->mobile_image)): ?>
                <img class="ls-bg hidden-lg hidden-md hidden-sm"
                     src="<?= Yii::app()->baseUrl . '/uploads/slideshow/' . $data->mobile_image ?>"
                     alt="<?= $data->title ?>"/>
            <?php endif; ?>
        </div>
    <?php
    endif;
endif;