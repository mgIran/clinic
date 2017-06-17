<?
/* @var $this ReservationController */
/* @var $days array */
/* @var $doctor Users */
/* @var $clinic Clinics */
?>

<div class="inner-page">
    <div class="page-help">
        <div class="container">
            <h4>پیگیری نوبت</h4>
            <ul>
                <li>لطفا کد رهگیری خود را وارد کنید.</li>
            </ul>
        </div>
    </div>

    <div class="container" style="position:relative;">
        <?php echo CHtml::beginForm('', 'get', array('id' => 'tracking-form','class'=>'select-date-form row'));?>
            <div id="flash-message"><?php $this->renderPartial('//partial-views/_flashMessage');?></div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <?= CHtml::label('کد رهگیری', 'tracking_code') ?>
                <?= CHtml::telField('id','',array('class' => 'form-control')) ?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <?php echo CHtml::submitButton('جستجو', array('class'=>'btn-black'));?>
            </div>
        <?php echo CHtml::endForm();?>
        <?php $this->renderPartial('//partial-views/_loading');?>
    </div>
    <div class="patient-info">
        <div class="container" id="view-visit"></div>
    </div>
</div>

<?php
Yii::app()->clientScript->registerScript("load-visit", '
    $("body").on("submit", "#tracking-form", function(e){
        e.preventDefault();
        var elView = $("#view-visit");
        var id = $("#tracking-form [name=\'id\']").val();
        if(id){
            $.ajax({
                url: "'.$this->createUrl('follow').'/"+id,
                date:$("#tracking-form").serialize(),
                type: "GET",
                dataType: "JSON",
                beforeSend: function(){
                    $("#flash-message").html("");
                    $(".loading-container").show();
                },
                success: function(data){
                    $(".loading-container").hide();
                    if(data.status){
                        $("#view-visit").html(data.html);
                    }else
                        $("#flash-message").html("<div class=\'alert alert-danger\'>"+data.message+"</div>");
                },
            });
        }else
            alert("لطفا کدرهگیری را وارد کنید.");
    });
',CClientScript::POS_READY);