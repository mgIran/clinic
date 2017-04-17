<?php
/* @var $this ShopOrderController */
/* @var $model ShopOrder */

Yii::app()->clientScript->registerScript("send-form", '
    var report_type;

    $("body").on("submit", "#filter-form", function(e){
        if(!$("#print").val()){
            e.preventDefault();
            var $filters = $(this).serialize();
            $.fn.yiiGridView.update("report-shop-list",{
                data: $filters
            });
        }
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
    
    $(\'body\').on(\'click\', \'.status-change\', function(){
	    var el = $(this);
	    $(\'.nav li.active\').removeClass(\'active\');
	    el.parent().addClass(\'active\');
        $(\'#ShopOrder_status\').val(el.data(\'status\'));
        $("#filter-form").submit();
	});
	
	$(\'body\').on(\'click\', \'.print\', function(){
	    $("#print").val(true);
	    $("#filter-form").submit();
	});
');
?>

<h1>گزارش فروش نسخه های چاپی کتاب ها</h1>
<?php
echo CHtml::beginForm('','GET',array('class' => 'form-inline', 'id' => 'filter-form'));
echo CHtml::hiddenField('pageSize', (isset($_GET['pageSize']) && in_array($_GET['pageSize'], $this->pageSizes)?$_GET['pageSize']:20));
echo CHtml::hiddenField('print',false);
?>
<div class="filters well">
    <div class="form-group">
        <label for="">شماره رسید</label>
        <?php echo CHtml::textField('ShopOrder[id]', $model->id?$model->id:'',array('class' => 'form-control'));?>
    </div>
    <div class="form-group">
        <label for="">شیوه ارسال</label>
        <?php echo CHtml::dropDownList('ShopOrder[shipping_method]', $model->shipping_method?$model->shipping_method:'',
            CHtml::listData(ShopShippingMethod::model()->findAll(), 'id', 'title'),array(
            'class' => 'selectpicker',
            'data-width' => '100%',
            'prompt' => 'همه ارسال ها'
        ));?>
    </div>
    <div class="form-group">
        <label for="">شیوه پرداخت</label>
        <?php echo CHtml::dropDownList('ShopOrder[payment_method]', $model->payment_method?$model->payment_method:'',
            CHtml::listData(ShopPaymentMethod::model()->findAll(), 'id', 'title'),array(
            'class' => 'selectpicker',
            'data-width' => '100%',
            'prompt' => 'همه پرداخت ها'
        ));?>
    </div>
    <div class="row">
        <div class="form-group"><label style="line-height: 30px;margin-bottom: 0">وضعیت سفارش:</label></div>
        <?php
        echo CHtml::radioButtonList('ShopOrder[status]',$model->status?$model->status:'', CMap::mergeArray(array(''=>'همه'),$model->statusLabels),array(
            'class' => 'form-control',
            'style' => 'width:auto !important; margin-left: 5px;',
            'template' => '<div class="form-group">{input} {label}</div>',
            'separator' => '',
        ));
        ?>
    </div>
    <div class="row">
        <div class="form-group"><label style="line-height: 30px;margin-bottom: 0">وضعیت پرداخت:</label></div>
        <?php
        echo CHtml::radioButtonList('ShopOrder[payment_status]',$model->payment_status?$model->payment_status:'', CMap::mergeArray(array(''=>'همه'),$model->paymentStatusLabels),array(
                'class' => 'form-control',
                'style' => 'width:auto !important; margin-left: 5px;',
                'template' => '<div class="form-group">{input} {label}</div>',
            'separator' => ''
        ));
        ?>
    </div>
    <div class="row">
        <div class="form-group"><label style="line-height: 30px;margin-bottom: 0">نمایش براساس:</label></div>
        <div class="form-group">
            <?php
            echo CHtml::radioButton('ShopOrder[report_type]',(!$model->report_type || ($model->report_type && $model->report_type == '')?true:false), array(
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
            echo CHtml::radioButton('ShopOrder[report_type]',$model->report_type && $model->report_type == 'yearly'?true:false, array(
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
            echo CHtml::radioButton('ShopOrder[report_type]',$model->report_type && $model->report_type == 'monthly'?true:false, array(
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
            echo CHtml::radioButton('ShopOrder[report_type]',$model->report_type && $model->report_type == 'by-date'?true:false, array(
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
        <button type="button" class="btn btn-info print">
            <i class="icon-print"></i>
            چاپ نتایج
        </button>
    </div>
</div>
<div style="width: 100%">
<?php
echo CHtml::dropDownList('', (isset($_GET['pageSize']) && in_array($_GET['pageSize'], $this->pageSizes)?$_GET['pageSize']:20), $this->pageSizes, array('id' => 'page-size', 'class' => 'pull-left'));
?>
</div>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'report-shop-list',
    'dataProvider'=>$model->report(),
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
            'name' => 'id',
            'value' => '$data->getOrderId()',
            'htmlOptions' => array('style' => 'width:60px')
        ),
        array(
            'header' => 'کتاب ها',
            'value' => function($data){
                $html = '';
                foreach($data->items as $item){
                    $html.=CHtml::tag('div',array('class' => 'nested-row text-nowrap'),$item->model->title.'<small>('.Controller::parseNumbers(number_format($item->qty)).'عدد)</small>');
                    $html.=CHtml::closeTag('div');
                }
                return $html;
            },
            'type' => 'raw'
        ),
        array(
            'header' => 'کاربر',
            'value' => function($data){
                return $data->user && $data->user->userDetails?$data->user->userDetails->getShowName():'';
            },
            'footer'=>'<h5 class="text-center"><strong>مجموع</strong></h5>',
        ),
        array(
            'name' => 'ordering_date',
            'value' => function($data){
                return JalaliDate::date('Y/m/d - H:i', $data->ordering_date);
            },
            'filter' => false,
            'htmlOptions' => array('style' => 'width:80px')
        ),
        array(
            'name' => 'status',
            'value' => '$data->statusLabel',
            'filter' => false,
            'htmlOptions' => array('style' => 'width:80px')
        ),
        array(
            'name' => 'payment_method',
            'value' => function($data){
                $p = '';
                if($data->payment_price)
                    $p = '<br><small>('.Controller::parseNumbers(number_format($data->payment_price)).' تومان)</small>';
                return $data->paymentMethod->title.$p;
            },
            'type' => 'raw',
            'htmlOptions' => array('style' => 'width:80px'),
            'filter' => CHtml::listData(ShopPaymentMethod::model()->findAll(),'id', 'title'),
            'footer'=> Controller::parseNumbers(number_format($model->getTotalPaymentPrice())).' تومان',
        ),
        array(
            'name' => 'shipping_method',
            'value' => function($data){
                $p = '<br><small>(رایگان)</small>';
                if($data->shipping_price)
                    $p = '<br><small>('.Controller::parseNumbers(number_format($data->shipping_price)).' تومان)</small>';
                return $data->shippingMethod->title.$p;
            },
            'filter' => CHtml::listData(ShopShippingMethod::model()->findAll(array('order'=>'t.order')),'id', 'title'),
            'footer'=> Controller::parseNumbers(number_format($model->getTotalShippingPrice())).' تومان',
            'type' => 'raw'
        ),
        array(
            'header' => 'مبلغ پایه',
            'value' => function($data){
                $html = '';
                foreach($data->items as $item){
                    $html.=CHtml::tag('div',array('class' => 'nested-row'),Controller::parseNumbers(number_format($item->base_price*$item->qty)).' تومان');
                    $html.=CHtml::closeTag('div');
                }
                return $html;
            },
            'type' => 'raw',
            'footer'=> Controller::parseNumbers(number_format($model->getTotalPrice())).' تومان',
        ),
        array(
            'header' => 'تخفیف',
            'value' => function($data){
                return Controller::parseNumbers(number_format($data->discount_amount)).' تومان';
            },
            'footer'=> Controller::parseNumbers(number_format($model->getTotalDiscount())).' تومان',
        ),
        array(
            'header' => 'جمع پرداختی',
            'value' =>function($data){
                return Controller::parseNumbers(number_format($data->payment_amount)) . ' تومان';
            },
            'footer'=> Controller::parseNumbers(number_format($model->getTotalPayment())).' تومان',
        ),
        array(
            'header' => 'مالیات',
            'value' =>function($data){
                return Controller::parseNumbers(number_format($data->getTax())) . ' تومان';
            },
            'footer'=> Controller::parseNumbers(number_format($model->getTotalTax())).' تومان',
        ),
    )
));
?>
<?php
echo CHtml::endForm();
Yii::app()->clientScript->registerCss('nested-row','
    .nested-row{
        display:block;
        padding:2px 0;
        text-align:center;
        border-bottom: 1px solid #aaa;
    }
    td > .nested-row:last-of-type{
        border:none
    }
');