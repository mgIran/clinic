<?php
/* @var $this PublicController */
/* @var $bookmarked[] UserBookBookmark */
/* @var $book Books */
?>

<div class="transparent-form">
    <h3>نشان شده ها</h3>
    <p class="description">لیست کتاب هایی که نشان کرده اید.</p>

    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'bookmarked-grid',
        'dataProvider' => new CArrayDataProvider($bookmarked,array('pagination' => array('pageSize' => 15))),
        'template' => '{items} {pager}',
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
</div>