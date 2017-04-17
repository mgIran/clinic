<?php
/* @var $this PanelController */
/* @var $booksDataProvider CActiveDataProvider */
/* @var $books [] */
?>
<div class="transparent-form">
    <h3>تخفیفات</h3>
    <p class="description">لیست تخفیفاتی که در نظر گرفته اید.</p>
    <?php $this->renderPartial('//partial-views/_flashMessage', array('prefix'=>'discount-'));?>
    <div class="buttons">
        <a class="btn btn-success" data-toggle="modal" href="#discount-modal"> افزودن تخفیف جدید</a>
    </div>
    <table class="table">
        <thead>
        <tr>
            <th>عنوان کتاب</th>
            <th>وضعیت</th>
            <th class="hidden-xs">قیمت آخرین نسخه</th>
            <th>درصد یا مبلغ</th>
            <th class="hidden-xs">قیمت با تخفیف</th>
            <th>مدت تخفیف</th>
        </tr>
        </thead>
        <?php if(!$booksDataProvider->totalItemCount):?>
            <tbody>
            <tr>
                <td colspan="6" class="text-center">نتیجه ای یافت نشد.</td>
            </tr>
            </tbody>
        <?php else:?>
            <?php $this->widget('zii.widgets.CListView', array(
                'dataProvider'=>$booksDataProvider,
                'itemView'=>'_book_discount_list',
                'itemsTagName'=>'tbody',
                'template'=>'{items}'
            ));?>
        <?php endif;?>
    </table>
</div>
<div id="discount-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal" >&times;</button>
                <h5>افزودن تخیف</h5>
            </div>
            <div class="modal-body">
                <? $this->renderPartial('_discount_form',array('model' => new BookDiscounts(),'books' => $books)); ?>
            </div>
        </div>
    </div>
</div>