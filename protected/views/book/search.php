<?php
/* @var $this BookController */
/* @var $bookDataProvider CActiveDataProvider */
/* @var $publisherDataProvider CActiveDataProvider */
/* @var $personsDataProvider CActiveDataProvider */
/* @var $categoryDataProvider CActiveDataProvider */
$flag = false;
?>
<div class="page">
    <div class="page-heading">
        <div class="container">
            <h1>جتسجوی عبارت "<?= CHtml::encode($_GET['term']) ?>"</h1>
            <div class="page-info">
                <span>نتایج یافت شده در کتاب ها<a><?= $bookDataProvider->totalItemCount ?> نتیجه</a></span>
            </div>
        </div>
    </div>
    <div class="container page-content book-list">
        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
            <?php
            if($bookDataProvider->totalItemCount):
                $flag = true;
                ?>
                <div class="row">
                    <h2 class="search-group-title">کتاب ها</h2>
                    <div class="thumbnail-list">
                        <?php
                        $this->widget('zii.widgets.CListView', array(
                            'id' => 'book-list',
                            'dataProvider' => $bookDataProvider,
                            'itemView' => '/site/_book_item',
                            'template' => '{items} {pager}',
                            'viewData' => array('itemClass' => 'simple'),
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
                            'pagerCssClass' => 'thumbnail-container',
                        ));
                        ?>
                    </div>
                </div>
            <?php
            endif;
            ?>
<!--            --><?php
//            if($publisherDataProvider->totalItemCount):
//                $flag = true;
//            ?>
<!--                <div class="row">-->
<!--                    <h2 class="search-group-title">انتشارات</h2>-->
<!--                    <div class="thumbnail-list">-->
<!--                        --><?php
//                        $this->widget('zii.widgets.CListView', array(
//                            'id' => 'publisher-list',
//                            'dataProvider' => $publisherDataProvider,
//                            'itemView' => '/site/_book_item',
//                            'template' => '{items} {pager}',
//                            'viewData' => array('itemClass' => 'simple'),
//                            'ajaxUpdate' => true,
//                            'afterAjaxUpdate' => "function(id, data){
//                                $('html, body').animate({
//                                    scrollTop: ($('#'+id).offset().top-130)
//                                },1000);
//                            }",
//                            'pager' => array(
//                                'header' => '',
//                                'firstPageLabel' => '<<',
//                                'lastPageLabel' => '>>',
//                                'prevPageLabel' => '<',
//                                'nextPageLabel' => '>',
//                                'cssFile' => false,
//                                'htmlOptions' => array(
//                                    'class' => 'pagination pagination-sm',
//                                ),
//                            ),
//                            'pagerCssClass' => 'thumbnail-container',
//                        ));
//                        ?>
<!--                    </div>-->
<!--                </div>-->
<!--            --><?php
//            endif;
//            ?>
<!--            --><?php
//            if($personsDataProvider->totalItemCount):
//                $flag = true;
//            ?>
<!--                <div class="row">-->
<!--                    <h2 class="search-group-title">اشخاص و نویسندگان</h2>-->
<!--                    <div class="thumbnail-list">-->
<!--                        --><?php
//                        $this->widget('zii.widgets.CListView', array(
//                            'id' => 'persons-list',
//                            'dataProvider' => $personsDataProvider,
//                            'itemView' => '/site/_book_item',
//                            'template' => '{items} {pager}',
//                            'viewData' => array('itemClass' => 'simple'),
//                            'ajaxUpdate' => true,
//                            'afterAjaxUpdate' => "function(id, data){
//                                $('html, body').animate({
//                                    scrollTop: ($('#'+id).offset().top-130)
//                                },1000);
//                            }",
//                            'pager' => array(
//                                'header' => '',
//                                'firstPageLabel' => '<<',
//                                'lastPageLabel' => '>>',
//                                'prevPageLabel' => '<',
//                                'nextPageLabel' => '>',
//                                'cssFile' => false,
//                                'htmlOptions' => array(
//                                    'class' => 'pagination pagination-sm',
//                                ),
//                            ),
//                            'pagerCssClass' => 'thumbnail-container',
//                        ));
//                        ?>
<!--                    </div>-->
<!--                </div>-->
<!--            --><?php
//            endif;
//            ?>
            <?php
            if($categoryDataProvider->totalItemCount):
                $flag = true;
            ?>
                <div class="row">
                    <h2 class="search-group-title">دسته بندی ها</h2>
                    <div class="thumbnail-list">
                        <?php
                        $this->widget('zii.widgets.CListView', array(
                            'id' => 'category-book-list',
                            'dataProvider' => $categoryDataProvider,
                            'itemView' => '/site/_book_item',
                            'template' => '{items} {pager}',
                            'viewData' => array('itemClass' => 'simple'),
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
                            'pagerCssClass' => 'thumbnail-container',
                        ));
                        ?>
                    </div>
                </div>
            <?php
            endif;
            ?>
            </div>
            <?php $this->renderPartial('//partial-views/inner-sidebar') ?>
        </div>
    </div>
</div>