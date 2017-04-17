<?php
/* @var $data Advertises */
$book = $data->book;
?>
<div class="slider-item">
    <div class="slider-thumbnail"><img src="<?= Yii::app()->baseUrl.'/uploads/advertisesCover/'.$data->cover ?>" alt="<?= $book->title ?>"></div>
    <div class="slider-overlay">
        <a href="#" class="slider-overlay-nav slider-next"><i class="arrow-icon"></i></a>
        <a href="#" class="slider-overlay-nav slider-prev"><i class="arrow-icon"></i></a>
        <a href="<?= $this->createUrl('/book/'.$book->id.'/'.urlencode($book->title)) ?>" class="slider-overlay-link">
            <img src="<?= Yii::app()->baseUrl.'/uploads/books/icons/'.$book->icon ?>" alt="<?= $book->title ?>">
            <h3><?= $book->title ?><small><??></small></h3>
        </a>
    </div>
</div>
