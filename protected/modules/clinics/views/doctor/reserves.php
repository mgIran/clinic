<?php
/* @var $this ClinicsDoctorController */
/* @var $model Visits */
/* @var $form CActiveForm */
?>
<h3>لیست نوبت های امروز</h3>
<p class="description">لیست افرادی که امروز نوبت گرفته اند.</p>
<div class="container-fluid">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <b>مجموع نوبت های امروز:</b> <span id="all"><?= Controller::parseNumbers(Visits::getAllVisits($model->date)) ?></span>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <b>نوبت های تایید شده امروز:</b> <span id="accepted"><?= Controller::parseNumbers(Visits::getAllVisits($model->date, Visits::STATUS_ACCEPTED)) ?></span>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <b>نوبت های حضور یافته در مطب:</b> <span id="checked"><?= Controller::parseNumbers(Visits::getAllVisits($model->date, Visits::STATUS_CLINIC_CHECKED)) ?></span>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <b>نوبت های ویزیت شده:</b> <span id="visited"><?= Controller::parseNumbers(Visits::getAllVisits($model->date, Visits::STATUS_CLINIC_VISITED)) ?></span>
    </div>
</div>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'reserves-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'itemsCssClass'=>'table',
    'template' => '{items} {pager}',
    'ajaxUpdate' => true,
    'afterAjaxUpdate' => "function(id, data){
        $('html, body').animate({
            scrollTop: ($('#'+id).offset().top-130)
        },1000,'easeOutCubic');
    }",
    'pager' => array(
        'header' => '',
        'firstPageLabel' => '<<',
        'lastPageLabel' => '>>',
        'prevPageLabel' => '<',
        'nextPageLabel' => '>',
        'cssFile' => false,
        'htmlOptions' => array(
            'class' => 'pagination pagination-sm',
        ),
    ),
    'pagerCssClass' => 'text-center blank',
    'columns'=>array(
        array(
            'name' => 'user_id',
            'value' => '$data->user->userDetails->showName'
        ),
        array(
            'name' => 'time',
            'value' => '$data->timeLabel'
        ),
        array(
            'name' => 'status',
            'value' => '$data->statusLabel',
            'filter' => $model->statusLabels
        ),
        'tracking_code',
        array(
            'name' => 'clinic_checked_number',
            'value' => function($data){
                return Controller::parseNumbers($data->clinic_checked_number);
            }
        ),
        array(
            'class'=>'CButtonColumn',
            'template'=>'{visited} {check} {delete}',
            'buttons' => array(
                'delete' => array(
                    'label' => 'حذف نوبت',
                    'imageUrl' => '',
                    'options' => array(
                        'class' => 'btn btn-danger btn-sm',
                        'style' => 'margin-top: 5px'
                    ),
                    'url' => 'Yii::app()->controller->createUrl("doctor/removeReserve/".$data->id)',
                    'visible' => '$data->status < 3'
                ),
                'check' => array(
                    'label' => 'حضور در کلینیک',
                    'options' => array(
                        'class' => 'btn btn-info btn-sm checked-clinic',
                        'style' => 'margin-top: 5px'
                    ),
                    'url' => 'Yii::app()->controller->createUrl("doctor/clinicChecked/".$data->id)',
                    'visible' => '$data->status == 2'
                ),
                'visited' => array(
                    'label' => 'ویزیت شده',
                    'options' => array(
                        'class' => 'btn btn-success btn-sm visited-clinic',
                    ),
                    'url' => 'Yii::app()->controller->createUrl("doctor/clinicVisited/".$data->id)',
                    'visible' => '$data->status == 3'
                )
            )
        ),
    ),
));

Yii::app()->clientScript->registerScript('checked-clinic','
    $("body").on("click", ".checked-clinic, .visited-clinic", function(e){
        var $this = $(this);
        e.preventDefault();
        $.ajax({
            url: $this.attr("href"),
            dataType: "JSON",
            type: "POST",
            beforeSend: function(){
                
            },
            success: function(data){
                if(data.status)
                    $.fn.yiiGridView.update("reserves-grid");
                else
                    alert(data.msg);
            }
        });
    });
    
    setInterval(function () {
        var $this = $(this);
        $.ajax({
            url: window.location,
            data: {numbers: true},
            dataType: "JSON",
            type: "GET",
            beforeSend: function(){},
            success: function(data){
                $.fn.yiiGridView.update("reserves-grid");
                if(data.status){
                    $("#all").text(data.all);
                    $("#accepted").text(data.accepted);
                    $("#checked").text(data.checked);
                    $("#visited").text(data.visited);
                }
            }
        });
    }, 10000);
');