<div class="page-loading">
    <div class="logo">
        <img src="<?php echo Yii::app()->theme->baseUrl.'/svg/logo.svg';?>" alt="<?php echo Yii::app()->name;?>"><h1>کــــــــتابیــــــــــک<small>نزدیکترین کتابفروشی شهر</small></h1>
    </div>
    <div class="spinners">
        <div class="spinner"></div>
        <div class="spinner-2"></div>
        <div class="spinner-3"></div>
    </div>
</div>
<?php
Yii::app()->clientScript->registerScript('page-loading',"
    $('.page-loading').fadeOut(function(){
        $('body').removeClass('overflow-hidden');
    });
",CClientScript::POS_LOAD);