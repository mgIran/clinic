<?php
/* @var $this ClinicsDoctorController */
/* @var $model Visits */
/* @var $form CActiveForm */
/* @var $today boolean */
?>
<div class="form-group">
    <button class="btn btn-default" data-toggle="collapse" data-target="#collapse">انتخاب تاریخ</button>
</div>
<div class="collapse" id="collapse">
    <div class="form well">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'doctor-schedules',
            'enableAjaxValidation' => false
        ));
        ?>
        <div class="form-group col-lg-4 col-md-4 col-sm-4 col-ex-12 relative">
            <?php echo $form->labelEx($model, 'date'); ?>
            <?php $this->widget('ext.PDatePicker.PDatePicker', array(
                'id' => 'date-picker',
                'model' => $model,
                'attribute' => 'date',
                'htmlOptions' => array(
                    'autocomplete' => 'off'
                ),
                'options' => array(
                    'format' => 'YYYY/MM/DD',
                )
            )); ?>
            <?php echo $form->error($model, 'date'); ?>
        </div>
        <?php echo CHtml::submitButton('نمایش',array('class' => 'btn btn-success')) ?>
        <?php
        $this->endWidget();
        ?>
    </div>
</div>
<?php
if($today):
    ?>
    <h3>لیست نوبت های امروز<?= (isset($_GET['Visits']['time']) && $_GET['Visits']['time'] == 1?'&nbsp;<small>(نوبت صبح)</small>':'&nbsp;<small>(نوبت بعدازظهر)</small>') ?></h3>
    <p class="description">
        <a href="<?= $this->createUrl('secretary/visits/'.$doctorID.'/?Visits[time]=1') ?>" class="btn btn-default btn-sm">
            <?php
            if(isset($_GET['Visits']['time']) && $_GET['Visits']['time'] == 1)
                echo '<i class="icon-check"></i>';
            ?>
            نوبت صبح
        </a>
        <a href="<?= $this->createUrl('secretary/visits/'.$doctorID.'/?Visits[time]=2') ?>" class="btn btn-default btn-sm">
            <?php
            if(isset($_GET['Visits']['time']) && $_GET['Visits']['time'] == 2)
                echo '<i class="icon-check"></i>';
            ?>
            نوبت بعدازظهر
        </a>
    </p>
<?php
elseif(!isset($_GET['leaves'])):
    ?>
    <h3>لیست نوبت های <?= JalaliDate::date('Y/m/d',$model->date) ?></h3>
    <p class="description">لیست افرادی که در این تاریخ نوبت گرفته اند.</p>
    <?
endif;
?>
<div class="container-fluid well">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <b>مجموع نوبت های امروز:</b> <span id="all"><?= Controller::parseNumbers(Visits::getAllVisits(Yii::app()->user->clinic->id, $doctorID,$model->date, $model->time)) ?></span> نوبت
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <b>نوبت های تایید شده امروز:</b> <span id="accepted"><?= Controller::parseNumbers(Visits::getAllVisits(Yii::app()->user->clinic->id, $doctorID,$model->date, $model->time, Visits::STATUS_ACCEPTED)) ?></span> نوبت
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <b>نوبت های حضور یافته در مطب:</b> <span id="checked"><?= Controller::parseNumbers(Visits::getAllVisits(Yii::app()->user->clinic->id, $doctorID,$model->date, $model->time, Visits::STATUS_CLINIC_CHECKED)) ?></span> نوبت
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <b>نوبت های ویزیت شده:</b> <span id="visited"><?= Controller::parseNumbers(Visits::getAllVisits(Yii::app()->user->clinic->id, $doctorID,$model->date, $model->time, Visits::STATUS_CLINIC_VISITED)) ?></span> نوبت
    </div>
    <?php
    if($today):
    ?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h3 class="text-danger"><b>شماره نوبت مراجعه به پزشک:</b> <span id="visiting"><?= Controller::parseNumbers(Visits::getNowVisit(Yii::app()->user->clinic->id, $doctorID,$model->date, $model->time)) ?></span></h3>
        </div>
    <?php
    endif;
    ?>
</div>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'visits-grid',
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
            'name' => 'userNameFilter',
            'value' => '$data->user && $data->user->userDetails?$data->user->userDetails->showName:"حذف شده"'
        ),
        array(
            'name' => 'time',
            'value' => '$data->timeLabel',
            'filter' => CHtml::activeDropDownList($model, 'time', $model->timeLabels, array('id' => 'Visits_time', 'prompt' => ''))
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
                return $data->clinic_checked_number?Controller::parseNumbers($data->clinic_checked_number):'';
            }
        ),
        array(
            'class'=>'CButtonColumn',
            'template'=>'{visited} {check} {delete}',
            'buttons' => array(
                'delete' => array(
                    'label' => 'لغو نوبت',
                    'imageUrl' => '',
                    'options' => array(
                        'class' => 'btn btn-danger btn-sm',
                        'style' => 'margin-top: 5px'
                    ),
                    'url' => 'Yii::app()->controller->createUrl("secretary/removeReserve/".$data->id)',
                    'visible' => '$data->status < 3 && $data->status > 0'
                ),
                'check' => array(
                    'label' => 'حضور در کلینیک',
                    'options' => array(
                        'class' => 'btn btn-info btn-sm checked-clinic',
                        'style' => 'margin-top: 5px'
                    ),
                    'url' => 'Yii::app()->controller->createUrl("secretary/clinicChecked/".$data->id)',
                    'visible' => '$data->status == 2'
                ),
                'visited' => array(
                    'label' => 'ویزیت شده',
                    'options' => array(
                        'class' => 'btn btn-success btn-sm visited-clinic',
                    ),
                    'url' => 'Yii::app()->controller->createUrl("secretary/clinicVisited/".$data->id)',
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
                    reloadStatistics();
                else
                    alert(data.msg);
            }
        });
    });
    $("body").on("change", "#Visits_time", function(e){
        reloadStatistics();
    });
    setInterval(function () {
        reloadStatistics();
    }, 15000);
    
    function reloadStatistics(){
        $.ajax({
            url: window.location,
            data: {Visits: {time: $("#Visits_time").val()}},
            dataType: "JSON",
            type: "GET",
            beforeSend: function(){},
            success: function(data){
                $.fn.yiiGridView.update("visits-grid");
                if(data.status){
                    $("#all").text(data.all);
                    $("#accepted").text(data.accepted);
                    $("#checked").text(data.checked);
                    $("#visited").text(data.visited);
                    $("#visiting").text(data.visiting);
                }
            }
        });
    }
');