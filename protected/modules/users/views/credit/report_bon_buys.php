<?php
/* @var $this BookController */
/* @var $model UserTransactions */

Yii::app()->clientScript->registerScript("send-bon-form", '
    var report_type;

    $("body").on("submit", "#filter-form", function(e){
        e.preventDefault();
        var $filters = $(this).serialize();
        $.fn.yiiGridView.update("report-credit-buys-list",{
            data: $filters
        });
    });
    
    $("body").on("change", "#page-size", function(){
        $("#pageSize").val($(this).val());
        $("#filter-form").submit();
    });
    
    $("body").on("change", ".fader", function(){
        var $target = $(this).data("target");
        $(".collapse").not($target).removeClass("in");
        $($target).addClass("in");
        report_type = $(this).data("report");
    });
');
?>

<h1>گزارش استفاده از بن خرید</h1>

<?php
echo CHtml::beginForm('','GET',array('class' => 'form-inline', 'id' => 'filter-form'));
echo CHtml::hiddenField('pageSize', (isset($_GET['pageSize']) && in_array($_GET['pageSize'], $this->pageSizes)?$_GET['pageSize']:20));
?>
<div class="filters well">
<!--    <div class="form-group">-->
<!--        <label for="">نوع پرداخت</label>-->
<!--        --><?php //echo CHtml::dropDownList('UserBonRel[method]', $model->method?$model->method:'', $model->methodLabels,array(
//            'class' => 'selectpicker',
//            'data-width' => '100%',
//            'prompt' => 'همه پرداخت ها'
//        ));?>
<!--    </div>-->
    <div class="row">
        <div class="form-group"><label style="line-height: 30px;margin-bottom: 0">نمایش براساس:</label></div>
        <div class="form-group">
            <?php
            echo CHtml::radioButton('UserBonRel[report_type]',(!$model->report_type || ($model->report_type && $model->report_type == '')?true:false), array(
                'data-target' => '#collapse',
                'class' => 'form-control fader',
                'style' => 'width: auto !important; margin-left: 5px;',
                'data-report' => '',
                'value' => ''
            ));
            echo CHtml::label('همه', '', array('class' => 'control-label'));
            ?>
        </div>
        <div class="form-group">
            <?php
            echo CHtml::radioButton('UserBonRel[report_type]',$model->report_type && $model->report_type == 'yearly'?true:false, array(
                'data-target' => '#yearly-collapse',
                'class' => 'form-control fader',
                'style' => 'width: auto !important; margin-left: 5px;',
                'data-report' => 'yearly',
                'value' => 'yearly'
            ));
            echo CHtml::label('سالانه', '', array('class' => 'control-label'));
            ?>
        </div>
        <div class="form-group">
            <?php
            echo CHtml::radioButton('UserBonRel[report_type]',$model->report_type && $model->report_type == 'monthly'?true:false, array(
                'data-target' => '#monthly-collapse',
                'class' => 'form-control fader',
                'style' => 'width: auto !important; margin-left: 5px;',
                'data-report' => 'monthly',
                'value' => 'monthly'
            ));
            echo CHtml::label('ماهانه', '', array('class' => 'control-label'));
            ?>
        </div>
        <div class="form-group">
            <?php
            echo CHtml::radioButton('UserBonRel[report_type]',$model->report_type && $model->report_type == 'by-date'?true:false, array(
                'data-target' => '#by-date-collapse',
                'class' => 'form-control fader',
                'style' => 'width: auto !important; margin-left: 5px;',
                'data-report' => 'by-date',
                'value' => 'by-date'
            ));
            echo CHtml::label('بر اساس تاریخ', '', array('class' => 'control-label'));
            ?>
        </div>
    </div>
    <div id="yearly-collapse" class="row collapse">
        <div class="form-group">
            <label for="">سال موردنظر خود را انتخاب کنید:</label>
            <?php $this->widget('ext.PDatePicker.PDatePicker', array(
                'id'=>'year',
                'model' => $model,
                'attribute' => 'year_altField',
                'options'=>array(
                    'format'=>'YYYY',
                    'monthPicker'=>'js:{enabled:false}',
                    'dayPicker'=>'js:{enabled:false}',
                    'yearPicker'=>'js:{enabled:true}',
                ),
                'htmlOptions'=>array(
                    'class'=>'form-control'
                ),
            ));?>
        </div>
    </div>
    <div id="monthly-collapse" class="row collapse">
        <div class="form-group">
            <label for="">ماه مورد نظر را انتخاب کنید:</label>
            <?php $this->widget('ext.PDatePicker.PDatePicker', array(
                'id'=>'month',
                'model' => $model,
                'attribute' => 'month_altField',
                'options'=>array(
                    'format'=>'MMMM YYYY',
                    'monthPicker'=>'js:{enabled:true}',
                    'dayPicker'=>'js:{enabled:false}',
                    'yearPicker'=>'js:{enabled:false}',
                ),
                'htmlOptions'=>array(
                    'class'=>'form-control'
                ),
            ));?>
        </div>
    </div>
    <div id="by-date-collapse" class="row collapse">
        <div class="form-group">
            <label for="">از تاریخ</label>
            <?php $this->widget('ext.PDatePicker.PDatePicker', array(
                'id'=>'from_date',
                'model' => $model,
                'attribute' => 'from_date_altField',
                'options'=>array(
                    'format'=>'DD MMMM YYYY'
                ),
                'htmlOptions'=>array(
                    'class'=>'form-control'
                ),
            ));?>
        </div>
        <div class="form-group">
            <label for="">تا تاریخ</label>
            <?php $this->widget('ext.PDatePicker.PDatePicker', array(
                'id'=>'to_date',
                'model' => $model,
                'attribute' => 'to_date_altField',
                'options'=>array(
                    'format'=>'DD MMMM YYYY'
                ),
                'htmlOptions'=>array(
                    'class'=>'form-control'
                ),
            ));?>
        </div>
    </div>
    <div class="buttons">
        <button type="submit" class="btn btn-success">نمایش</button>
    </div>
</div>
<div style="width: 100%">
<?php
echo CHtml::dropDownList('', (isset($_GET['pageSize']) && in_array($_GET['pageSize'], $this->pageSizes)?$_GET['pageSize']:20), $this->pageSizes, array('id' => 'page-size', 'class' => 'pull-left'));
?>
</div>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'report-credit-buys-list',
    'dataProvider' => $model->search(),
    'template' => '{items} {pager}',
    'ajaxUpdate' => true,
    'afterAjaxUpdate' => "function(id, data){
        $('html, body').animate({
            scrollTop: ($('#'+id).offset().top-130)
        },1000);
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
    'pagerCssClass' => 'blank',
    'itemsCssClass' => 'table',
    'columns' => array(
        array(
            'header' => 'کاربر',
            'value' => function($data){
                return $data->user->userDetails->fa_name?$data->user->userDetails->fa_name . ' (' . $data->user->email . ')':$data->user->email;
            },
            'footer'=>'<h5 class="text-center"><strong>مجموع</strong></h5>',
            'htmlOptions' => array('style' => 'width:50px')
        ),
        array(
            'header' => 'عنوان بن',
            'name' => 'bon.title',
            'htmlOptions' => array('style' => 'width:50px')
        ),
        array(
            'header' => 'کد بن',
            'name' => 'bon.code',
            'htmlOptions' => array('style' => 'width:50px')
        ),
        array(
            'header' => 'تاریخ و زمان',
            'value' =>function($data){
                return JalaliDate::date("Y/m/d H:i", $data->date);
            },
            'htmlOptions' => array('style' => 'width:50px')
        ),
        array(
            'header' => 'مبلغ بن',
            'value' =>function($data){
                return Controller::parseNumbers(number_format($data->amount)) . ' تومان';
            },
            'htmlOptions' => array('style' => 'width:50px'),
            'footer'=> Controller::parseNumbers(number_format($model->getTotalAmount())).' تومان'
        )
    )
));
?>
<h4><strong>مجموع اعتبار کاربران:</strong>&nbsp;<?= Controller::parseNumbers(number_format(Users::getTotalUserCredits())).' تومان' ?></h4>
<?php
echo CHtml::endForm();
?>
