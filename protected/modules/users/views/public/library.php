<?php
/* @var $this UsersPublicController */
/* @var $user Users */
/* @var $boughtBooks Library */
/* @var $downloadBooks Library */
/* @var $myBooks CActiveDataProvider */
?>
<?php
$this->renderPartial('//partial-views/_flashMessage');
?>
<div class="my-library">
    <?php
    if($myBooks){
    ?>
    <div class="white-form">
        <h3>کتاب های خودم</h3>
        <p class="description">کتاب هایی که شما ناشر آنها هستید.</p>
        <div class="is-carousel" data-item-selector="thumbnail-container" data-mouse-drag="1" data-responsive='{"1200":{"items":"4"},"1024":{"items":"4"},"992":{"items":"3"},"768":{"items":"3"},"480":{"items":"2"},"0":{"items":"1"}}'     data-nav="1" data-dots="1">
            <?php $this->widget('zii.widgets.CListView',array(
                'id' => 'my-book-list',
                'dataProvider' => $myBooks,
                'itemView' => '//site/_book_item',
                'template' => '{items}',
                'viewData' => array('itemClass' => 'simple', 'buy'=>false)
            ));?>
        </div>
    </div>
    <?php
    }
    ?>
    <div class="white-form">
        <h3>خریداری شده ها</h3>
        <p class="description">کتاب هایی که خریداری کرده اید و هنوز دانلود نکرده اید.</p>
        <?php
        echo CHtml::beginForm($this->route,'GET',array('class' => 'form-inline form'));
        ?>
            <div class="filters">

                <div class="form-group">
                    <?php echo CHtml::activeTextField($boughtBooks, 'book_id', array('class' => 'form-control ajax-grid-search', 'placeholder' => 'شناسه کتاب را جستجو کنید'));?>
                </div>
                <div class="form-group">
                    <?php echo CHtml::activeTextField($boughtBooks, 'bookNameFilter', array('class' => 'form-control ajax-grid-search', 'placeholder' => 'عنوان کتاب را جستجو کنید'));?>
                </div>
            </div>
            <?php
            $this->widget('zii.widgets.grid.CGridView', array(
                'id' => 'bought-list',
                'dataProvider' => $boughtBooks->search(),
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
                        'name' => 'book.id',
                    ),
                    array(
                        'name' => 'book.title',
                        'value' => function($data){
                            return CHtml::link($data->book->title, array('/book/'.$data->book_id.'/'.urlencode($data->book->title)));
                        },
                        'type' => 'raw'
                    ),
                    array(
                        'header' => 'نسخه کتاب',
                        'value' => '"ویرایش ".Controller::parseNumbers($data->package->version)',
                    ),
                    array(
                        'class' => 'CButtonColumn',
                        'header'=>$this->getPageSizeDropDownTag(),
                        'template' =>'',
//                        'buttons' => array(
//                            'download' => array(
//                                'label' => '<span class="icon icon-download-alt"></span>&nbsp;دانلود',
//                                'url' => 'array(\'/book/download\')',
//                                'options' => array('class' => 'btn btn-info btn-sm'),
//                                'click' => 'function(e){
//                                    e.preventDefault();
////                                    $.ajax({
////                                        url: $(this).attr("href"),
////                                        dataType: "JSON",
////                                        data: {bookId: $(this).parents("tr").data("book-id")},
////                                        type: "POST",
////                                        success: function(){
////                                            $.fn.yiiGridView.update("bookmarked-grid");
////                                        }
////                                    });
//                                }'
//                            )
//                        )
                    )
                )
            ));
            ?>
        <?php
        echo CHtml::endForm();
        ?>
    </div>
    <div class="white-form">
        <h3>دانلود شده ها</h3>
        <p class="description">کتاب هایی که دانلود کرده اید.</p>
        <?php
        echo CHtml::beginForm($this->route,'GET',array('class' => 'form-inline form'));
        ?>
            <div class="filters">

                <div class="form-group">
                    <?php echo CHtml::activeTextField($downloadBooks, 'book_id', array('class' => 'form-control ajax-grid-search', 'placeholder' => 'شناسه کتاب را جستجو کنید'));?>
                </div>
                <div class="form-group">
                    <?php echo CHtml::activeTextField($downloadBooks, 'bookNameFilter', array('class' => 'form-control ajax-grid-search', 'placeholder' => 'عنوان کتاب را جستجو کنید'));?>
                </div>
            </div>
            <?php
            $this->widget('zii.widgets.grid.CGridView', array(
                'id' => 'downloaded-list',
                'dataProvider' => $downloadBooks->search(),
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
                        'name' => 'book.id',
                    ),
                    array(
                        'name' => 'book.title',
                        'value' => function($data){
                            return CHtml::link($data->book->title, array('/book/'.$data->book_id.'/'.urlencode($data->book->title)));
                        },
                        'type' => 'raw'
                    ),
                    array(
                        'header' => 'نسخه کتاب',
                        'value' => '"ویرایش ".Controller::parseNumbers($data->package->version)',
                    ),
                    array(
                        'class' => 'CButtonColumn',
                        'header'=>$this->getPageSizeDropDownTag(),
                        'template' =>'',
                    )
                )
            ));
            ?>
        <?php
        echo CHtml::endForm();
        ?>
    </div>
    <div class="white-form">
        <h3>نشان شده ها</h3>
        <p class="description">کتاب هایی که نشان کرده اید.</p>
        <?php
        echo CHtml::beginForm($this->route,'GET',array('class' => 'form-inline form'));
        ?>
            <?php
            $this->widget('zii.widgets.grid.CGridView', array(
                'id' => 'bookmarked-grid',
                'dataProvider' => new CArrayDataProvider($user->bookmarkedBooks,array(
                    'pagination' => array('pageSize' => isset($_GET['pageSize'])?$_GET['pageSize']:20)
                )),
                'template' => '{pager} {items} {pager}',
                'rowHtmlOptionsExpression'=>'array("data-book-id" => $data->id)',
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
                        'header' => 'عنوان کتاب',
                        'value' => function($data){
                            return CHtml::link($data->title, array('/book/'.$data->id.'/'.urlencode($data->title)));
                        },
                        'type' => 'raw'
                    ),
                    array(
                        'header' => 'نویسنده',
                        'value' => function($data){
                            return $data->getPersonsTags('نویسنده', 'fullName', true, 'a');
                        },
                        'type' => 'raw'
                    ),
                    array(
                        'header' => 'ناشر',
                        'value' => function($data){
                            return CHtml::link($data->getPublisherName(),
                                array('/book/publisher?title='.($data->publisher?urlencode($data->publisher->userDetails->publisher_id).'&id='.$data->publisher_id:urlencode($data->publisher_name).'&t=1'))
                            );
                        },
                        'type' => 'raw'
                    ),
                    array(
                        'header' => 'دسته بندی',
                        'value' => function($data){
                            return CHtml::link($data->category->title,
                                array('/category/'.$data->category_id.'/'.urldecode($data->category->title))
                            );
                        },
                        'type' => 'raw'
                    ),
                    array(
                        'class' => 'CButtonColumn',
                        'header'=>$this->getPageSizeDropDownTag(),
                        'template' =>'{unmark}',
                        'buttons' => array(
                            'unmark' => array(
                                'label' => 'حذف',
                                'url' => 'array(\'/book/bookmark\')',
                                'options' => array('class' => 'btn btn-warning btn-sm'),
                                'click' => 'function(e){
                                    e.preventDefault();
                                    $.ajax({
                                        url: $(this).attr("href"),
                                        dataType: "JSON",
                                        data: {bookId: $(this).parents("tr").data("book-id")},
                                        type: "POST",
                                        success: function(){
                                            $.fn.yiiGridView.update("bookmarked-grid");
                                        }
                                    });
                                }'
                            )
                        )
                    )
                )
            ));
            ?>
        <?php
        echo CHtml::endForm();
        ?>
    </div>
</div>