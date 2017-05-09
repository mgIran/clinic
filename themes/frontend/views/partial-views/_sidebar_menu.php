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
        <a title="لیست نوبت ها" href="<?php echo Yii::app()->createUrl('/clinics/doctor/reserves');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='clinics/doctor/reserves')?' active':'';?>">لیست نوبت ها</a>
    <?php elseif(Yii::app()->user->roles == 'secretary'):?>
        <h5>منوی منشی</h5>
        <a title="ثبت نوبت جدید" href="<?php echo Yii::app()->createUrl('/clinics/panel');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='clinics/panسel')?' active':'';?>">ثبت نوبت جدید</a>
        <a title="سیستم حضور بیمار" href="<?php echo Yii::app()->createUrl('/clinics/panel');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='clinics/panسel')?' active':'';?>">سیستم حضور بیمار</a>
    <?php endif;?>
<?php endif;?>
<h5>منوی کاربری</h5>
<?php if(!isset(Yii::app()->user->clinic)):?>
    <a title="داشبورد" href="<?php echo Yii::app()->createUrl('/dashboard');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='dashboard')?' active':'';?>">داشبورد</a>
<?php endif;?>
<a title="پروفایل" href="<?php echo Yii::app()->createUrl('/users/public/profile');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='users/public/profile')?' active':'';?>">پروفایل</a>
<a title="تنظیمات" href="<?php echo Yii::app()->createUrl('/users/public/setting');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='users/public/setting')?' active':'';?>">تنظیمات</a>
