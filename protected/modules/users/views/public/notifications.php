<?php
/* @var $this PublicController */
/* @var $model UserNotifications */
?>
<div class="white-form">
    <h3>اطلاعیه ها</h3>

    <ul class="notification-list">
        <?php foreach($model as $notification):?>
            <li class="<?php echo ($notification->seen==0)?'unseen':'';?>">
                <span class="date"><?php echo JalaliDate::date('d F Y - H:i', $notification->date);?></span>
                <?php echo CHtml::encode($notification->message);?>
            </li>
        <?php endforeach;?>
    </ul>
</div>