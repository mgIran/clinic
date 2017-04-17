<?php
/* @var $this Controller */
?>
<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 sidebar-col">
    <div class="boxed">
        <div class="heading">
            <h4>دسته بندی ها</h4>
        </div>
        <ul class="categories">
            <?php
            foreach($this->categories as $category):
                ?>
                <li class="<?= (isset($activeId) && $category->id===$activeId)?'active':'' ?>"><a href="<?= $this->createUrl('/category/'.$category->id.'/'.urlencode($category->title)) ?>"><?= $category->title ?>
                        <small>(<?= $category->getBooksCount() ?>)</small></a></li>
                <?php
            endforeach;
            ?>
        </ul>
    </div>
    <div class="boxed">
        <div class="heading">
            <h4>تازه ها</h4>
        </div>
        <div class="sidebar-book-list">
            <?php
            $this->widget('zii.widgets.CListView',array(
                'id' => 'latest-list',
                'dataProvider' => $this->getConstBooks('latest'),
                'itemView' => '//site/_book_item',
                'template' => '{items}',
                'viewData' => array('itemClass' => 'smallest')
            ));
            ?>
        </div>
    </div>
    <div class="boxed">
        <div class="heading">
            <h4>محبوب ترین ها</h4>
        </div>
        <div class="sidebar-book-list">
            <?php
            $this->widget('zii.widgets.CListView',array(
                'id' => 'latest-list',
                'dataProvider' => $this->getConstBooks('popular'),
                'itemView' => '//site/_book_item',
                'template' => '{items}',
                'viewData' => array('itemClass' => 'smallest')
            ));
            ?>
        </div>
    </div>
</div>