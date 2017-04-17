<?php
/* @var $this NewsManageController */
/* @var $data News */
/* @var $type string */
?>
<?php
if(!isset($type) || $type == 'carousel'):
    ?>
<div class="news-item">
    <div class="thumb"><img alt="<?= CHtml::encode($data->title) ?>" src="<?= Yii::app()->baseUrl.'/uploads/news/200x200/'.$data->image ?>"></div>
    <div class="text">
        <h2><a href="<?= $this->createUrl('/news/'.$data->id.'/'.urlencode($data->title)) ?>"><?= CHtml::encode($data->title) ?></a></h2>
        <div class="info">
            <span class="date"><?= JalaliDate::date('Y F d - H:i',$data->publish_date) ?></span>
        </div>
        <div class="summary"><?= $data->summary ?></div>
    </div>
</div>
<?php
elseif(isset($type) && $type == 'list-view'):
$thumbPath = Yii::getPathOfAlias("webroot").'/uploads/news/200x200/';
$date = JalaliDate::date("Y F d - H:i",$data->publish_date);
?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="news-list-item">
        <div class="pic">
            <?php
            if($data->image && file_exists($thumbPath.$data->image)):
                ?>
                <img src="<?= Yii::app()->baseUrl.'/uploads/news/200x200/'.$data->image ?>" alt="<?= CHtml::encode($data->title) ?>">
                <?php
            else:
                ?>
                <div class="default-pic"></div>
                <?
            endif;
            ?>
        </div>
        <div class="news-detail">
            <a href="<?= $this->createUrl('/news/'.$data->id.'/'.urlencode($data->title)) ?>">
                <h3><?= CHtml::encode($data->title) ?></h3>
            </a>
            <span class="date"><?= $date ?></span>
            <span class="category"><strong>دسته بندی: </strong><a href="<?= $this->createUrl('/news/category/'.$data->category->id.'/'.urlencode($data->category->title)) ?>" ><?= $data->category->title ?></a></span>
            <a href="<?= $this->createUrl('/news/'.$data->id.'/'.urlencode($data->title)) ?>">
                <p><?= strip_tags($data->summary) ?><span class="paragraph-end" ></span></p>
            </a>
        </div>
    </div>
</div>
<?php
elseif(isset($type) && $type == 'side-view'):
    $thumbPath = Yii::getPathOfAlias("webroot").'/uploads/news/200x200/';
    $date = JalaliDate::date("Y F d - H:i",$data->publish_date);
?>
    <div class="news-list-item side-view">
        <div class="pic">
            <?php
            if($data->image && file_exists($thumbPath.$data->image)):
                ?>
                <img src="<?= Yii::app()->baseUrl.'/uploads/news/200x200/'.$data->image ?>" alt="<?= CHtml::encode($data->title) ?>">
                <?php
            else:
                ?>
                <div class="default-pic"></div>
                <?
            endif;
            ?>
        </div>
        <div class="news-detail">
            <a href="<?= $this->createUrl('/news/'.$data->id.'/'.urlencode($data->title)) ?>">
                <h3><?= CHtml::encode($data->title) ?></h3>
            </a>
            <span class="date"><?= $date ?></span>
            <span class="category"><strong>دسته بندی: </strong><a href="<?= $this->createUrl('/news/category/'.$data->category->id.'/'.urlencode($data->category->title)) ?>" ><?= $data->category->title ?></a></span>
        </div>
    </div>
<?php
endif;