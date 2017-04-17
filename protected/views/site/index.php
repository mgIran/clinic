<?
/* @var $this SiteController */
/* @var $categoriesDP CActiveDataProvider */
/* @var $latestBooksDP CActiveDataProvider */
/* @var $buyBooksDP CActiveDataProvider */
/* @var $suggestedDP CActiveDataProvider */
/* @var $popularBooksDP CActiveDataProvider */
/* @var $activeRows [] */
/* @var $advertises CActiveDataProvider */
/* @var $news CActiveDataProvider */
/* @var $rows CActiveDataProvider */

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/owl.carousel.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.mousewheel.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/owl.carousel.min.js');
?>
<?php
if($advertises->totalItemCount):
    ?>
    <div class="slider" <?= $advertises->totalItemCount>1?'data-loop="true"':'' ?>>
        <?php
        foreach($advertises->getData() as $advertise):
            $this->renderPartial('_advertise_item',array('data'=>$advertise));
        endforeach;
        ?>
    </div>
<?
endif;
?>
    <div class="categories">
        <div class="container">
            <div class="heading">
                <h2>دسته بندی ها</h2>
            </div>
            <div class="is-carousel" data-item-selector="cat-item" data-margin="10" data-dots="1" data-nav="0" data-mouse-drag="1" data-responsive='{"1920":{"items":"5"},"1200":{"items":"4"},"992":{"items":"3"},"768":{"items":"3"},"480":{"items":"2"},"0":{"items":"1"}}'>
                <?php
                $this->widget('zii.widgets.CListView',array(
                    'id' => 'categories-list',
                    'dataProvider' => $categoriesDP,
                    'itemView' => '_category_item',
                    'template' => '{items}'
                ))
                ?>
            </div>
        </div>
    </div>
<?php if($activeRows['suggested'] && $suggestedDP->totalItemCount):?>
    <div class="offers paralax">
        <div class="content">
            <div class="container">
                <div class="head">
                    <h2>پیشنهاد ما</h2>
                </div>
                <div class="is-carousel" data-item-selector="thumbnail-container" data-mouse-drag="1" data-responsive='{"1200":{"items":"5"},"1024":{"items":"4"},"992":{"items":"3"},"768":{"items":"3"},"480":{"items":"2"},"0":{"items":"1"}}'     data-nav="1" data-dots="1">
                    <?php
                    $this->widget('zii.widgets.CListView',array(
                        'id' => 'suggested-list',
                        'dataProvider' => $suggestedDP,
                        'itemView' => '_book_item',
                        'template' => '{items}',
                        'viewData' => array('itemClass' => 'full')
                    ));
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?
endif;
?>
<?php
if($activeRows['latest'] && $latestBooksDP->totalItemCount):
    ?>
    <div class="newest">
        <div class="container">
            <div class="heading">
                <h2>تازه ترین کتابها</h2>
            </div>
            <div class="thumbnail-list">
                <?php
                $this->widget('zii.widgets.CListView',array(
                    'id' => 'latest-list',
                    'dataProvider' => $latestBooksDP,
                    'itemView' => '_book_item',
                    'template' => '{items}',
                    'viewData' => array('itemClass' => 'simple')
                ));
                ?>
            </div>
            <a href="<?= $this->createUrl('/book/index') ?>" class="more"><i class="icon"></i>کتابهای بیشتر</a>
        </div>
    </div>
<?php
endif;
?>
<?php
if($activeRows['buy'] && $buyBooksDP->totalItemCount):
?>
    <div class="bestselling paralax">
        <div class="content">
            <div class="container">
                <div class="head">
                    <h2>پرفروش ترین ها</h2>
                </div>
                <div class="is-carousel auto-width" data-item-selector="thumbnail-container" data-mouse-drag="1" data-responsive='{"1600":{"items":"5"},"1024":{"items":"4"},"992":{"items":"3"},"768":{"items":"3"},"480":{"items":"2"},"0":{"items":"1"}}' data-dots="1" data-nav="1">
                    <?php
                    $this->widget('zii.widgets.CListView',array(
                        'id' => 'latest-list',
                        'dataProvider' => $buyBooksDP,
                        'itemView' => '_book_item',
                        'template' => '{items}',
                        'viewData' => array('itemClass' => 'small')
                    ));
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php
endif;
?>
<?php
if($activeRows['popular'] && $popularBooksDP->totalItemCount):
?>
    <div class="newest">
        <div class="container">
            <div class="heading">
                <h2>پربازدیدترین ها</h2>
            </div>
            <div class="is-carousel auto-width" data-item-selector="thumbnail-container" data-mouse-drag="1" data-responsive='{"1600":{"items":"5"},"1024":{"items":"4"},"992":{"items":"3"},"768":{"items":"3"},"480":{"items":"2"},"0":{"items":"1"}}' data-dots="1" data-nav="0">
                <?php
                $this->widget('zii.widgets.CListView',array(
                    'id' => 'latest-list',
                    'dataProvider' => $popularBooksDP,
                    'itemView' => '_book_item',
                    'template' => '{items}',
                    'viewData' => array('itemClass' => 'small')
                ));
                ?>
            </div>
        </div>
    </div>
<?php
endif;
?>
<?php
if($rows->totalItemCount):
    $rowData = $rows->getData();
?>
    <div class="tabs">
        <div class="container">
            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                <ul class="nav nav-pills nav-stacked row">
                    <?php
                    foreach ($rowData as $key=>$row):
                        ?>
                        <li role="presentation"<?= $key == 0?' class="active"':'' ?>><a data-toggle="tab" href="#row-<?= $key ?>"><?= $row->title ?></a></li>
                        <?
                    endforeach;
                    ?>
                </ul>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12 tabs-container">
                <div class="tab-content">
                    <?php
                    foreach ($rowData as $key=>$row):
                        ?>
                        <div id="row-<?= $key ?>" class="tab-pane fade<?= $key == 0?' in active':'' ?>">
                            <div class="is-carousel" data-item-selector="thumbnail-container" data-mouse-drag="1" data-responsive='{"992":{"items":"3"},"768":{"items":"2"},"700":{"items":"3"},"480":{"items":"2"},"0":{"items":"1"}}' data-dots="1" data-nav="0">
                                <?php
                                $this->widget('zii.widgets.CListView',array(
                                    'id' => 'row-'.$key.'-carousel-list',
                                    'dataProvider' => new CArrayDataProvider($row->books(Books::model()->getValidBooks(array(),'confirm_date DESC',null,'books'))),
                                    'itemView' => '_book_item',
                                    'template' => '{items}',
                                    'viewData' => array('itemClass' => 'simple')
                                ));
                                ?>
                            </div>
                        </div>
                        <?
                    endforeach;
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php
endif;
?>
<?php
if($news->totalItemCount):
?>
    <div class="news">
        <div class="container">
            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                <div class="is-carousel" data-dots="0" data-nav="1" data-autoplay="1" data-autoplay-hover-pause="1" data-loop="0" data-responsive='{"0":{"items":1}}' data-mouseDrag="0">
                    <?php
                    foreach($news->getData() as $new):
                        $this->renderPartial('_news_item',array('data'=>$new));
                    endforeach;
                    ?>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 controls">
                <i class="arrow-icon next"></i>
                <i class="arrow-icon prev"></i>
            </div>
        </div>
    </div>
    <?php
endif;
?>
