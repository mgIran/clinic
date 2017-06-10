<?php if(isset(Yii::app()->user->clinic)):?>
    <?php if(Yii::app()->user->roles == 'clinicAdmin'):?>
        <h5>منوی مدیر درمانگاه و پزشک</h5>
        <a title="اطلاعات درمانگاه" href="<?php echo Yii::app()->createUrl('/clinics/manage/update');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='clinics/manage/update')?' active':'';?>">اطلاعات درمانگاه</a>
        <a title="لیست نوبت های امروز" href="<?php echo Yii::app()->createUrl('/clinics/doctor/visits');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='clinics/doctor/visits')?' active':'';?>">لیست نوبت های امروز</a>
        <a title="لیست تاریخی نوبت ها" href="<?php echo Yii::app()->createUrl('/clinics/doctor/visits/?date='.time());?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='clinics/doctor/visits/?date='.time())?' active':'';?>">لیست تاریخی نوبت ها</a>
        <a title="پرسنل" href="<?php echo Yii::app()->createUrl('/clinics/panel/personnel');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='clinics/panel/personnel')?' active':'';?>">پرسنل</a>
        <a title="برنامه زمانی نوبت دهی" href="<?php echo Yii::app()->createUrl('/clinics/doctor/schedules');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='clinics/doctor/schedules')?' active':'';?>">برنامه زمانی نوبت دهی</a>
        <a title="برنامه زمانی مرخصی ها" href="<?php echo Yii::app()->createUrl('/clinics/doctor/leaves');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='clinics/doctor/leaves')?' active':'';?>">برنامه زمانی مرخصی ها</a>
        <a title="تعریف تخصص های پزشکی" href="<?php echo Yii::app()->createUrl('/clinics/doctor/expertises');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='clinics/doctor/expertises')?' active':'';?>">تعریف تخصص های پزشکی</a>
    <?php elseif(Yii::app()->user->roles == 'doctor'):?>
        <h5>منوی پزشک</h5>
        <a title="لیست نوبت های امروز" href="<?php echo Yii::app()->createUrl('/clinics/doctor/visits');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='clinics/doctor/visits')?' active':'';?>">لیست نوبت های امروز</a>
        <a title="لیست تاریخی نوبت ها" href="<?php echo Yii::app()->createUrl('/clinics/doctor/visits/?date='.time());?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='clinics/doctor/visits/?date='.time())?' active':'';?>">لیست تاریخی نوبت ها</a>
<!--        <a title="پرسنل" href="--><?php //echo Yii::app()->createUrl('/clinics/panel/personnel');?><!--" class="list-group-item--><?php //echo (Yii::app()->request->pathInfo=='clinics/panel/personnel')?' active':'';?><!--">پرسنل</a>-->
        <a title="برنامه زمانی نوبت دهی" href="<?php echo Yii::app()->createUrl('/clinics/doctor/schedules');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='clinics/doctor/schedules')?' active':'';?>">برنامه زمانی نوبت دهی</a>
        <a title="برنامه زمانی مرخصی ها" href="<?php echo Yii::app()->createUrl('/clinics/doctor/leaves');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='clinics/doctor/leaves')?' active':'';?>">برنامه زمانی مرخصی ها</a>
        <a title="تعریف تخصص های پزشکی" href="<?php echo Yii::app()->createUrl('/clinics/doctor/expertises');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='clinics/doctor/expertises')?' active':'';?>">تعریف تخصص های پزشکی</a>
    <?php elseif(Yii::app()->user->roles == 'secretary'):?>
        <h5>منوی منشی</h5>
        <a title="داشبورد منشی" href="<?php echo Yii::app()->createUrl('/clinics/panel');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='clinics/panel')?' active':'';?>">داشبورد منشی</a>
        <a title="مدیریت برنامه زمانی پزشکان" href="<?php echo Yii::app()->createUrl('/clinics/secretary/doctors?action=schedules');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='clinics/secretary/doctors?action=schedules')?' active':'';?>">مدیریت برنامه زمانی پزشکان</a>
        <a title="مدیریت مرخصی های پزشکان" href="<?php echo Yii::app()->createUrl('/clinics/secretary/doctors?action=leaves');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='clinics/secretary/doctors?action=leaves')?' active':'';?>">مدیریت مرخصی های پزشکان</a>
        <a title="مدیریت نوبت های پزشکان" href="<?php echo Yii::app()->createUrl('/clinics/secretary/doctors');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='clinics/secretary/doctors')?' active':'';?>">مدیریت نوبت های پزشکان</a>
        <a title="مدیریت تخصص های پزشکان" href="<?php echo Yii::app()->createUrl('/clinics/secretary/doctors?action=expertises');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='clinics/secretary/doctors?action=expertises')?' active':'';?>">مدیریت تخصص های پزشکان</a>
        <!--        <a title="مانیتورینگ نوبت ها" href="--><?php //echo Yii::app()->createUrl('/clinics/secretary/monitoring');?><!--" class="list-group-item--><?php //echo (Yii::app()->request->pathInfo=='clinics/secretary/monitoring')?' active':'';?><!--">مانیتورینگ نوبت ها</a>-->
    <?php endif;?>
<?php endif;?>
<h5>منوی کاربری</h5>
<?php if(!isset(Yii::app()->user->clinic)):?>
    <a title="داشبورد" href="<?php echo Yii::app()->createUrl('/dashboard');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='dashboard')?' active':'';?>">داشبورد</a>
<?php endif;?>
<?php if(Yii::app()->user->roles == 'user'):
    ?>
    <a title="لیست نوبت های گرفته شده" href="<?php echo Yii::app()->createUrl('/users/public/visits');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='users/public/visits')?' active':'';?>">لیست نوبت های گرفته شده</a>
    <a title="تراکنش ها" href="<?php echo Yii::app()->createUrl('/users/public/transactions');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='users/public/transactions')?' active':'';?>">تراکنش ها</a>
    <?php
endif;
?>
<a title="پروفایل" href="<?php echo Yii::app()->createUrl('/users/public/profile');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='users/public/profile')?' active':'';?>">پروفایل</a>
<a title="تنظیمات" href="<?php echo Yii::app()->createUrl('/users/public/setting');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='users/public/setting')?' active':'';?>">تنظیمات</a>