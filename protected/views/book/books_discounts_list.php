<?php
/* @var $this BooksController */
/* @var $dataProvider CActiveDataProvider */
/* @var $title String */
/* @var $pageTitle String */
?>

<div class="book-box">
    <div class="top-box">
        <div class="title pull-right">
            <h2>تخفیفات</h2>
        </div>
    </div>
    <?php $this->widget('zii.widgets.CListView', array(
        'dataProvider'=>$dataProvider,
        'id'=>'newest-programs',
        'itemView'=>'//site/_book_discount_item',
        'template'=>'{items}',
        'itemsCssClass'=>'book-carousel'
    ));?>
</div>