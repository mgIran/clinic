<?php
/* @var $this AdminsDashboardController*/
/* @var $clinicCount int*/
/* @var $doctorCount int*/
/* @var $secCount int*/
/* @var $personnelCount int*/
/* @var $allVisitsCount int*/
/* @var $deletedVisitsCount int*/
/* @var $pendingVisitsCount int*/
/* @var $acceptedVisitsCount int*/
/* @var $visitedVisitsCount int*/
?>
<?php if(Yii::app()->user->hasFlash('success')):?>
    <div class="alert alert-success fade in">
        <button class="close close-sm" type="button" data-dismiss="alert"><i class="icon-remove"></i></button>
        <?php echo Yii::app()->user->getFlash('success');?>
    </div>
<?php elseif(Yii::app()->user->hasFlash('failed')):?>
    <div class="alert alert-danger fade in">
        <button class="close close-sm" type="button" data-dismiss="alert"><i class="icon-remove"></i></button>
        <?php echo Yii::app()->user->getFlash('failed');?>
    </div>
<?php endif;?>

<p>
    <?= Yii::app()->user->name; ?>
    خوش آمدید
</p>
<div class="panel panel-success col-lg-4 col-md-4 col-sm-6 col-xs-12">
    <div class="panel-heading">
        آمار مطب ها و پرسنل
    </div>
    <div class="panel-body">
        <p>
            <b>تعداد مطب ها: </b><?= Controller::parseNumbers(number_format($clinicCount)) ?> نفر
        </p>
        <p>
            <b>تعداد مدیران و پزشکان مطب: </b><?= Controller::parseNumbers(number_format($doctorCount))?> نفر
        </p>
        <p>
            <b>تعداد منشی ها: </b><?= Controller::parseNumbers(number_format($secCount))?> نفر
        </p>
    </div>
    <div class="panel-footer">
        <p>
            <b>تعداد کل پرسنل: </b><?= Controller::parseNumbers(number_format($personnelCount)) ?> نفر
        </p>
    </div>
</div>
<div class="panel panel-warning col-lg-4 col-md-4 col-sm-6 col-xs-12">
    <div class="panel-heading">
        آمار نوبت ها
    </div>
    <div class="panel-body">
        <p>
            <b>تعداد نوبت های در انتظار تایید: </b><?= Controller::parseNumbers(number_format($pendingVisitsCount)) ?> نوبت
        </p>
        <p>
            <b>تعداد نوبت های تایید شده: </b><?= Controller::parseNumbers(number_format($acceptedVisitsCount)) ?> نوبت
        </p>
        <p>
            <b>تعداد نوبت های ویزیت شده: </b><?= Controller::parseNumbers(number_format($visitedVisitsCount)) ?> نوبت
        </p>
        <p>
            <b>تعداد نوبت های حذف/ لغو شده: </b><?= Controller::parseNumbers(number_format($deletedVisitsCount)) ?> نوبت
        </p>
    </div>
    <div class="panel-footer">
        <p>
            <b>تعداد کل نوبت های ثبت شده: </b><?= Controller::parseNumbers(number_format($allVisitsCount)) ?> نوبت
        </p>
    </div>
</div>

<div class="panel panel-info col-lg-4 col-md-4 col-sm-6 col-xs-12">
    <div class="panel-heading">
        آمار بازدیدکنندگان
    </div>
    <div class="panel-body">
        <p>
            <b>افراد آنلاین: </b><?php echo Controller::parseNumbers(number_format(Yii::app()->userCounter->getOnline())); ?>
        </p>
        <p>
            <b>بازدید امروز: </b><?php echo Controller::parseNumbers(number_format(Yii::app()->userCounter->getToday())); ?>
        </p>
        <p>
            <b>بازدید دیروز: </b><?php echo Controller::parseNumbers(number_format(Yii::app()->userCounter->getYesterday())); ?>
        </p>
        <p>
            <b>بیشترین بازدید: </b><?php echo Controller::parseNumbers(number_format(Yii::app()->userCounter->getMaximal())); ?>
        </p>
    </div>
    <div class="panel-footer">
        <p><b>تعداد کل بازدید ها: </b><?php echo Controller::parseNumbers(number_format(Yii::app()->userCounter->getTotal())); ?> بازدید</p>
    </div>
</div>