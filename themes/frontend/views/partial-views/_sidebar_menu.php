<?php if(isset(Yii::app()->user->clinic)):?>
    <?php if(Yii::app()->user->roles == 'clinicAdmin'):?>
        <h5>منوی مدیر درمانگاه و پزشک</h5>
        <a title="پرسنل" href="<?php echo Yii::app()->createUrl('/clinics/panel');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='clinics/panel')?' active':'';?>">پرسنل</a>
        <a title="برنامه زمانی نوبت دهی" href="<?php echo Yii::app()->createUrl('/clinics/doctor/schedules');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='clinics/doctor/schedules')?' active':'';?>">برنامه زمانی نوبت دهی</a>
        <a title="برنامه زمانی مرخصی ها" href="<?php echo Yii::app()->createUrl('/clinics/doctor/leaves');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='clinics/doctor/leaves')?' active':'';?>">برنامه زمانی مرخصی ها</a>
    <?php elseif(Yii::app()->user->roles == 'doctor'):?>
        <h5>منوی پزشک</h5>
        <a title="پرسنل" href="<?php echo Yii::app()->createUrl('/clinics/panel');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='clinics/panel')?' active':'';?>">پرسنل</a>
        <a title="برنامه زمانی نوبت دهی" href="<?php echo Yii::app()->createUrl('/clinics/doctor/schedules');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='clinics/doctor/schedules')?' active':'';?>">برنامه زمانی نوبت دهی</a>
        <a title="برنامه زمانی مرخصی ها" href="<?php echo Yii::app()->createUrl('/clinics/doctor/leaves');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='clinics/doctor/leaves')?' active':'';?>">برنامه زمانی مرخصی ها</a>
        <a title="لیست نوبت ها" href="<?php echo Yii::app()->createUrl('/clinics/doctor/visits');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='clinics/doctor/visits')?' active':'';?>">لیست نوبت ها</a>
    <?php elseif(Yii::app()->user->roles == 'secretary'):?>
        <h5>منوی منشی</h5>
        <a title="مدیریت نوبت های پزشکان" href="<?php echo Yii::app()->createUrl('/clinics/secretary/doctors');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='clinics/secretary/doctors')?' active':'';?>">مدیریت نوبت های پزشکان</a>
        <a title="مانیتورینگ نوبت ها" href="<?php echo Yii::app()->createUrl('/clinics/secretary/monitoring');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='clinics/secretary/monitoring')?' active':'';?>">مانیتورینگ نوبت ها</a>
    <?php endif;?>
<?php endif;?>
<h5>منوی کاربری</h5>
<?php if(!isset(Yii::app()->user->clinic)):?>
    <a title="داشبورد" href="<?php echo Yii::app()->createUrl('/dashboard');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='dashboard')?' active':'';?>">داشبورد</a>
<?php endif;?>
<a title="پروفایل" href="<?php echo Yii::app()->createUrl('/users/public/profile');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='users/public/profile')?' active':'';?>">پروفایل</a>
<a title="تنظیمات" href="<?php echo Yii::app()->createUrl('/users/public/setting');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='users/public/setting')?' active':'';?>">تنظیمات</a>
