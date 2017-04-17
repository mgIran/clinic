<?php
/* @var $this PanelController */
/* @var $model UserDetails */
?>

<div class="alert alert-info" role="alert" style="margin: 50px 0;"><h4 class="text-center" style="margin: 0">به جمع ناشران <?= Yii::app()->name ?> خوش آمدید.</h4></div>
<p style="margin-bottom: 30px;">از این پس می توانید کتاب های خود را در <?= Yii::app()->name ?> ارائه کنید. جهت ورود به پنل روی دکمه زیر کلیک کنید.</p>
<form method="post" action="<?php echo $this->createUrl('/publishers/panel/signup/step/finish');?>">
    <?php echo CHtml::submitButton('ورود به پنل ناشران', array(
        'class'=>'btn btn-danger btn-lg center-block',
        'name'=>'goto_publisher_panel'
    )); ?>
</form>