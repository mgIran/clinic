<?php
/* @var $this UsersPublicController */
/* @var $model Users */
/* @var $suggestedDataProvider CActiveDataProvider */
/* @var $bookBuys CActiveDataProvider */
/* @var $transactions CActiveDataProvider */
/* @var $messages CArrayDataProvider */
?>
<?php if($messages->totalItemCount > 0):?>
    <?php $this->widget('zii.widgets.CListView', array(
        'id' => 'messages-list',
        'dataProvider' => $messages,
        'itemView' => '_message',
        'template' => '{items}'
    )); ?>
<?php endif;?>
<div class="statistics">
    <div>
        <div class="green">
            <h4>اعتبار</h4>
            <span>میزان اعتبار شما در کتابیک</span>
            <h3><?php echo number_format($model->userDetails->credit, 0, ',', '.');?> تومان</h3>
            <a href="<?php echo $this->createUrl('/users/credit/buy');?>">خرید اعتبار<i class="arrow-icon"></i></a>
        </div>
    </div><div>
        <div class="red">
            <h4>نشان شده ها</h4>
            <span>کتاب هایی که مایلید مطالعه کنید</span>
            <h3><?php echo Controller::parseNumbers(number_format($model->getCountBookmarkedBooks()));?> کتاب</h3>
            <a href="<?php echo Yii::app()->createUrl('users/public/bookmarked');?>">نشان شده ها<i class="arrow-icon"></i></a>
        </div>
    </div><div>
        <div class="blue">
            <h4>کتابخانه من</h4>
            <span>کتابخانه مجازی خود را بسازید</span>
            <h3><?php echo Controller::parseNumbers(number_format(($model->getCountBookmarkedBooks() + $model->getCountLibraryBooks())));?> کتاب</h3>
            <a href="<?php echo Yii::app()->createUrl('users/public/library');?>">کتابخانه من<i class="arrow-icon"></i></a>
        </div>
    </div>
</div>
<div class="tabs tables">
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#downloaded"><h5>دانلود شده ها<small> / <?php echo number_format(count($model->bookBuys), 0, ',', '.');?> مورد</small></h5></a></li>
        <li><a data-toggle="tab" href="#transactions"><h5>تراکنش ها<small> / <?php echo number_format(count($model->transactions), 0, ',', '.');?> تراکنش</small></h5></a></li>
    </ul>
    <div class="tab-content">
        <div id="downloaded" class="tab-pane fade in active">
            <?php
            $this->widget('zii.widgets.grid.CGridView', array(
                'id' => 'book-buys-list',
                'dataProvider' => $bookBuys->search(),
                'template' => '{pager} {items} {pager}',
                'ajaxUpdate' => true,
                'afterAjaxUpdate' => "function(id, data){
                    $('html, body').animate({
                        scrollTop: ($('#'+id).offset().top-130)
                    },1000);
                }",
                'pager' => array(
                    'header' => '',
                    'firstPageLabel' => '<<',
                    'lastPageLabel' => '>>',
                    'prevPageLabel' => '<',
                    'nextPageLabel' => '>',
                    'cssFile' => false,
                    'htmlOptions' => array(
                        'class' => 'pagination pagination-sm',
                    ),
                ),
                'pagerCssClass' => 'blank',
                'itemsCssClass' => 'table',
                'columns' => array(
                    array(
                        'name' => 'date',
                        'value' => 'JalaliDate::date("d F Y - H:i", $data->date)'
                    ),
                    array(
                        'name' => 'book.title',
                        'value' => function($data){
                            return CHtml::link($data->book->title, array('/book/'.$data->book_id.'/'.urlencode($data->book->title)));
                        },
                        'type' => 'raw'
                    ),
                    array(
                        'header' => 'مبلغ',
                        'value' => 'Controller::parseNumbers(number_format($data->price))." تومان"',
                    ),
                    array(
                        'class' => 'CButtonColumn',
                        'header'=>$this->getPageSizeDropDownTag(),
                        'template' =>'',
                    )
                )
            ));
            ?>
        </div>
        <div id="transactions" class="tab-pane fade">
            <?php
            $this->widget('zii.widgets.grid.CGridView', array(
                'id' => 'transactions-list',
                'dataProvider' => $transactions->search(),
                'template' => '{pager} {items} {pager}',
                'ajaxUpdate' => true,
                'afterAjaxUpdate' => "function(id, data){
                    $('html, body').animate({
                        scrollTop: ($('#'+id).offset().top-130)
                    },1000);
                }",
                'pager' => array(
                    'header' => '',
                    'firstPageLabel' => '<<',
                    'lastPageLabel' => '>>',
                    'prevPageLabel' => '<',
                    'nextPageLabel' => '>',
                    'cssFile' => false,
                    'htmlOptions' => array(
                        'class' => 'pagination pagination-sm',
                    ),
                ),
                'pagerCssClass' => 'blank',
                'itemsCssClass' => 'table',
                'columns' => array(
                    array(
                        'name' => 'date',
                        'value' => 'JalaliDate::date("d F Y - H:i", $data->date)'
                    ),
                    array(
                        'header' => 'مبلغ',
                        'value' => 'Controller::parseNumbers(number_format($data->amount))." تومان"',
                    ),
                    array(
                        'header' => 'توضیحات',
                        'value' => 'CHtml::encode($data->description)',
                    ),
                    array(
                        'name' => 'token',
                        'value' => function($data){
                            return CHtml::encode($data->token);
                        },
                        'htmlOptions' => array('style' => 'letter-spacing:2px;font-weight:bold'),
                        'type' => 'raw'
                    ),
                    array(
                        'class' => 'CButtonColumn',
                        'header'=>$this->getPageSizeDropDownTag(),
                        'template' =>'',
                    )
                )
            ));
            ?>
        </div>
    </div>
</div>
<div class="offers">
    <div class="head">
        <h4>پیشنهاد ما به شما</h4>
    </div>
    <div class="is-carousel" data-item-selector="thumbnail-container" data-mouse-drag="1" data-responsive='{"1600":{"items":"5"},"1400":{"items":"4"},"1024":{"items":"3"},"992":{"items":"3"},"768":{"items":"2"},"700":{"items":"3"},"480":{"items":"2"},"0":{"items":"1"}}' data-dots="1" data-nav="1">
        <?php $this->widget('zii.widgets.CListView',array(
            'id' => 'suggested-list',
            'dataProvider' => $suggestedDataProvider,
            'itemView' => '//site/_book_item',
            'template' => '{items}',
            'viewData' => array('itemClass' => 'simple')
        )); ?>
    </div>
</div>