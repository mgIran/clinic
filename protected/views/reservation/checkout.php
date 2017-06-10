<?
/* @var $this ReservationController */
/* @var $model Visits */
/* @var $doctorSchedule DoctorSchedules */
/* @var $commission SiteSetting */
/* @var $transaction UserTransactions */
?>

<div class="inner-page">
    <?php $this->renderPartial('_steps', array('active'=>4));?>
    <?php
    if($model->status == Visits::STATUS_PENDING):
    ?>
    <div class="page-help">
        <div class="container">
            <h4>اطلاعات نوبت ویزیت</h4>
            <ul>
                <li>لطفا همه اطلاعات را بررسی بفرمایید.</li>
                <li>در صورت تایید، اطلاعات دیگر قابل ویرایش نخواهند بود.</li>
            </ul>
        </div>
    </div>
    <?php
    endif;
    ?>
    <div class="patient-info">
        <div class="container">
            <div class="row">
                <?php $this->renderPartial('//partial-views/_flashMessage');?>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 first">
                    <h4>اطلاعات بیمار</h4>
                    <div class="items">
                        <div class="row">
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">نام و نام خانوادگی</div>
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><?php echo $model->user->userDetails->getShowName();?></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">کد ملی</div>
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><?php echo $model->user->national_code;?></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">تلفن همراه</div>
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><?php echo $model->user->userDetails->mobile;?></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">پست الکترونیکی</div>
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><?php echo $model->user->email;?></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <h4>اطلاعات نوبت</h4>
                    <div class="items">
                        <div class="row">
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">مطب</div>
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><?php echo $model->clinic->clinic_name;?></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">پزشک</div>
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><a href="<?php echo $this->createUrl('/users/' . $model->doctor_id . '/clinic/' . $model->clinic_id)?>" target="_blank"><?php echo $model->doctor->userDetails->getShowName();?></a></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">تخصص</div>
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><?php echo $model->expertise->title;?></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">تاریخ مراجعه</div>
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><?php echo JalaliDate::date(' d F Y', $model->date, false);?></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">ساعت حضور پزشک</div>
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><?php echo (($model->time == Visits::TIME_AM)?$doctorSchedule->entry_time_am:$doctorSchedule->entry_time_pm).':00';?> الی <?php echo ($model->time == Visits::TIME_AM)?$doctorSchedule->exit_time_am.':00 صبح':$doctorSchedule->exit_time_pm.':00 بعد از ظهر';?></div>
                        </div>
                    </div>
                </div>
                <?php
                if($model->status == Visits::STATUS_ACCEPTED && isset($transaction) && $transaction):
                    ?>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <h4>اطلاعات تراکنش</h4>
                        <div class="items">
                            <div class="row">
                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">مبلغ پرداخت</div>
                                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><?php echo Controller::parseNumbers(number_format($transaction->amount))?> تومان</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">تاریخ پرداخت</div>
                                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 ltr text-right"><?php echo JalaliDate::date('Y/m/d - H:i', $transaction->date)?></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">وضعیت پرداخت</div>
                                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><?php echo $transaction->statusLabels[$transaction->status];?></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">کد رهگیری تراکنش</div>
                                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 ltr text-right" style="font-weight: 600; font-size: 18px; letter-spacing: 2px;"><?php echo CHtml::encode($transaction->token); ?></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">درگاه پرداخت</div>
                                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><?php echo $transaction->gateway_name ?></div>
                            </div>
                        </div>
                    </div>
                <?php
                endif;
                ?>
            </div>
            <div class="price-info">
                <?php echo CHtml::beginForm();?>
                    <?php if($model->status == Visits::STATUS_PENDING):?>
                        <?php if($commission->value != 0):?>
                            <h4>کمیسیون سایت: <span><?= Controller::parseNumbers(number_format($commission->value)) ?> <small>تومان</small></span></h4>
                            <?php echo CHtml::submitButton('پرداخت', array('class'=>'btn-red', 'name'=>'Payment'));?>
                        <?php else:?>
                            <span class="pull-right">جهت دریافت کد رهگیری لطفا اطلاعات فوق را تایید کنید.</span>
                                <?php echo CHtml::submitButton('تایید نهایی', array('class'=>'btn-red', 'name'=>'Confirm'));?>
                                <?php echo CHtml::link('بازگشت', $this->createUrl('info'), array('class'=>'btn-black'));?>
                        <?php endif;?>
                    <?php else:?>
                        <h4>کد رهگیری: <span><?php echo $model->tracking_code;?></span></h4>
                    <?php endif;?>
                <?php echo  CHtml::endForm();?>
            </div>
        </div>
    </div>
</div>