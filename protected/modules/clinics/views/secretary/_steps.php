<?php
/* @var $this ClinicsSecretaryController */
/* @var $active string */
$reservation = [];
if(Yii::app()->user->hasState("reservation"))
    $reservation = Yii::app()->user->reservation;
?>

<div class="reservation-steps">
    <div class="container-fluid">
        <ul class="steps">
            <li class="<?php echo ($active==1)?' active':'';?>">
                <a href="<?= $this->createUrl('secretary/addNewVisit')?>">
                    <div class="num">۱</div>
                    <div class="title"><small>مرحله اول</small><small>انتخاب تخصص</small></div>
                </a>
            </li>
            <li class="<?php echo ($active==2)?' active':'';?>">
                <a href="<?php
                if(isset($reservation['expertiseID']))
                    echo $this->createUrl('secretary/search/'.$reservation['expertiseID']);
                else
                    echo "#"; ?>">
                    <div class="num">۲</div>
                    <div class="title"><small>مرحله دوم</small><small>انتخاب پزشک</small></div>
                </a>
            </li>
            <li class="<?php echo ($active==3)?' active':'';?>">
                <a href="<?php
                if(isset($reservation['doctorID']))
                    echo $this->createUrl('secretary/selectSchedule');
                else
                    echo "#"; ?>">
                    <div class="num">۳</div>
                    <div class="title"><small>مرحله سوم</small><small>تاریخ و زمان</small></div>
                </a>
            </li>
            <li class="<?php echo ($active==4)?' active':'';?>">
                <a href="<?php
                if(isset($reservation['date']) && isset($reservation['time']))
                    echo $this->createUrl('secretary/info');
                else
                    echo "#";  ?>">
                    <div class="num">۴</div>
                    <div class="title"><small>مرحله چهارم</small><small>ورود اطلاعات</small></div>
                </a>
            </li>
            <li class="<?php echo ($active==5)?' active':'';?>">
                <a href="#">
                    <div class="num">۵</div>
                    <div class="title"><small>مرحله پنجم</small><small>دریافت نوبت</small></div>
                </a>
            </li>
        </ul>
    </div>
</div>