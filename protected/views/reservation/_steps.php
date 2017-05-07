<?php
/* @var $active string */
?>

<div class="reservation-steps">
    <div class="container">
        <ul class="steps">
            <li class="col-lg-3 col-md-3 col-sm-3 col-xs-12<?php echo ($active==1)?' active':'';?>">
                <div class="num">۱</div>
                <div class="title"><small>مرحله اول</small>یافتن پزشک</div>
            </li>
            <li class="col-lg-3 col-md-3 col-sm-3 col-xs-12<?php echo ($active==2)?' active':'';?>">
                <div class="num">۲</div>
                <div class="title"><small>مرحله دوم</small>تاریخ و زمان</div>
            </li>
            <li class="col-lg-3 col-md-3 col-sm-3 col-xs-12<?php echo ($active==3)?' active':'';?>">
                <div class="num">۳</div>
                <div class="title"><small>مرحله سوم</small>ورود اطلاعات</div>
            </li>
            <li class="col-lg-3 col-md-3 col-sm-3 col-xs-12<?php echo ($active==4)?' active':'';?>">
                <div class="num">۴</div>
                <div class="title"><small>مرحله چهارم</small>دریافت نوبت</div>
            </li>
        </ul>
    </div>
</div>