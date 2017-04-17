<?php
/* @var $this PublishersBooksController */
/* @var $model Books */
/* @var $imageModel BookImages */
/* @var $images [] */
/* @var $step integer */
/* @var $packagesDataProvider CActiveDataProvider */
?>
<div class="white-form">
    <h3>اطلاعات کتاب <?= CHtml::encode($model->title); ?></h3>
    <p class="description">جهت ثبت اطلاعات کتاب لطفا فرم زیر را پر کنید.</p>

    <?php $this->renderPartial("//partial-views/_flashMessage") ?>

    <ul class="nav nav-tabs">
        <li <?= !isset($step) || $step == 1 ?'class="active"':''; ?>>
            <a data-toggle="tab" href="#info">اطلاعات کتاب</a>
        </li>
        <li <?= isset($step) && $step == 2 ?'class="active"':''; ?>>
            <a data-toggle="tab" href="#packages">نوبت چاپ کتاب</a>
        </li>
<!--        <li class="--><?// if($step == 3)echo 'active';elseif($step<3)echo 'disabled';?><!--">-->
<!--            <a data-toggle="--><?//= ($step == 3)?'tab':''?><!--" href="#images">تصاویر کتاب</a>-->
<!--        </li>-->
    </ul>

    <div class="tab-content">
        <div id="info" class="tab-pane fade <?= $step == 1?'in active':''; ?>">
            <?php if($step>= 1):?>
                <?php $this->renderPartial('_form', array(
                    'model'=>$model,
                    'icon' => $icon,
                    'previewFile' => $previewFile,
                    'tax'=>$tax,
                    'commission'=>$commission,
                ));?>
            <?php endif;?>
        </div>
        <div id="packages" class="tab-pane fade <?= !isset($step) || $step == 2?'in active':''; ?>">
            <?php $this->renderPartial('_package', array(
                'model'=>$model,
                'dataProvider'=>$packagesDataProvider,
                'for'=>(Yii::app()->request->getParam('new')=='1')?'new_book':'old_book'
            ));?>
        </div>
<!--        <div id="images" class="tab-pane fade --><?//= $step == 3?'in active':''; ?><!--">-->
<!--            --><?php //if($step>=3):?>
<!--                --><?php //$this->renderPartial('_images_form', array(
//                    'model'=>$model,
//                    'imageModel'=>$imageModel,
//                    'images' => $images
//                ));?>
<!--            --><?php //endif;?>
<!--        </div>-->
    </div>
</div>