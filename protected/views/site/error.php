<?php
/* @var $this SiteController */
/* @var $error array */
?>
<div class="page-error">
    <div class="code"><?php echo $code; ?></div>
    <div class="title"><?php echo CHtml::encode($message);?></div>

    <div class="buttons">
        <div class="row">
            <div class="col-md-6">
                <a href="<?php echo $this->createUrl('/site');?>" class="btn btn-danger btn-block">صفحه اصلی <i class="arrow-icon right"></i></a>
            </div>
            <div class="col-md-6">
                <button onclick="history.back();" class="btn btn-info btn-block"><i class="arrow-icon left"></i> بازگشت</button>
            </div>
        </div>
    </div>

    <!-- Copyright -->
    <div class="copyright">
        <div class="ltr">
            <?php $this->renderPartial('//partial-views/_copyright');?>
        </div>
        <div>
            <a href="<?php echo $this->createUrl('/')?>">راهنما</a> / <a href="#">تماس با ما</a>
        </div>
    </div>
    <!-- ./Copyright -->

</div>