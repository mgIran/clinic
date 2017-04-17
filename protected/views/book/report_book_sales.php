<?php
/* @var $this BookController */
/* @var $model BookBuys */

Yii::app()->clientScript->registerScript("send-form", '
    var report_type;

    $("body").on("submit", "#filter-form", function(e){
        e.preventDefault();
        var $filters = $(this).serialize();
        $.fn.yiiGridView.update("report-book-sales-list",{
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

$books = CHtml::listData(Books::model()->findAll(),'id', 'titleAndId');
$publishers = CHtml::listData(Users::model()->getPublishers()->getData(),'id', 'userDetails.fa_name');
?>

<h1>گزارش فروش کتاب ها</h1>

<?php
echo CHtml::beginForm('','GET',array('class' => 'form-inline', 'id' => 'filter-form'));
echo CHtml::hiddenField('pageSize', (isset($_GET['pageSize']) && in_array($_GET['pageSize'], $this->pageSizes)?$_GET['pageSize']:20));
?>
<div class="filters well">
    <div class="form-group">
        <label for="">انتخاب کتاب</label>
        <?php echo CHtml::dropDownList('BookBuys[book_id]', $model->book_id?$model->book_id:'', $books,array(
            'class' => 'selectpicker',
            'data-live-search' => true,
            'data-width' => '100%',
            'prompt' => 'همه کتاب ها'
        ));?>
    </div>
    <div class="form-group">
        <label for="">انتخاب ناشر</label>
        <?php echo CHtml::dropDownList('BookBuys[publisher_id]', $model->publisher_id?$model->publisher_id:'', $publishers,array(
            'class' => 'selectpicker',
            'data-live-search' => true,
            'data-width' => '100%',
            'prompt' => 'همه ناشران'
        ));?>
    </div>
    <div class="form-group">
        <label for="">نوع پرداخت</label>
        <?php echo CHtml::dropDownList('BookBuys[method]', $model->method?$model->method:'', $model->methodLabels,array(
            'class' => 'selectpicker',
            'data-width' => '100%',
            'prompt' => 'همه پرداخت ها'
        ));?>
    </div>
    <div class="row">
        <div class="form-group"><label style="line-height: 30px;margin-bottom: 0">نمایش براساس:</label></div>
        <div class="form-group">
            <?php
            echo CHtml::radioButton('BookBuys[report_type]',(!$model->report_type || ($model->report_type && $model->report_type == '')?true:false), array(
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
            echo CHtml::radioButton('BookBuys[report_type]',$model->report_type && $model->report_type == 'yearly'?true:false, array(
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
            echo CHtml::radioButton('BookBuys[report_type]',$model->report_type && $model->report_type == 'monthly'?true:false, array(
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
            echo CHtml::radioButton('BookBuys[report_type]',$model->report_type && $model->report_type == 'by-date'?true:false, array(
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
    'id' => 'report-book-sales-list',
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
        ),
        array(
            'header' => 'شناسه کتاب',
            'name' => 'book_id',
            'htmlOptions' => array('style' => 'width:50px')
        ),
        array(
            'header' => 'ناشر',
            'value' => function($data){
                return $data->book->publisher && $data->book->publisher->userDetails->fa_name?$data->book->publisher->userDetails->fa_name:$data->book->publisher_name;
            }
        ),
        array(
            'header' => 'نام کتاب',
            'name' => 'book.title',
        ),
        array(
            'header' => 'تاریخ و زمان',
            'value' =>function($data){
                return JalaliDate::date("Y/m/d H:i", $data->date);
            }
        ),
        array(
            'header' => 'نوع خرید',
            'value' =>function($data){
                if($data->method == 'credit')
                    return $data->getMethodLabel();
                elseif($data->method == 'gateway')
                    return $data->getMethodLabel() . ' ' . $data->transaction->gateway_name;
            }
        ),
        array(
            'header' => 'کد رهگیری',
            'value' =>function($data){
                if($data->method == 'credit')
                    return '-';
                elseif($data->method == 'gateway')
                    return $data->transaction->token;
            }
        ),
        array(
            'header' => 'مبلغ',
            'value' =>function($data){
                return Controller::parseNumbers(number_format($data->base_price)) . ' تومان';
            },
            'footer'=> Controller::parseNumbers(number_format($model->getTotalBasePrice())).' تومان',
        ),
        array(
            'header' => 'کد تخفیف',
            'value' =>function($data){
                if($data->discount_code_type && $data->discount_code_type == BookBuys::DISCOUNT_CODE_TYPE_PERCENT){
                    return Controller::parseNumbers(number_format($data->discount_code_amount)) . '%';
                }else if($data->discount_code_type && $data->discount_code_type == BookBuys::DISCOUNT_CODE_TYPE_AMOUNT){
                    return Controller::parseNumbers(number_format($data->discount_code_amount)) . ' تومان';
                }
                return '-';
            }
        ),
        array(
            'header' => 'مبلغ با تخفیف',
            'value' =>function($data){
                if($data->base_price == $data->price)
                    return '-';
                return Controller::parseNumbers(number_format($data->price)) . ' تومان';
            },
            'footer'=> Controller::parseNumbers(number_format($model->getTotalPrice())).' تومان',
        ),
        array(
            'header' => 'درصد ناشر',
            'value' =>function($data){
                return Controller::parseNumbers(number_format($data->publisher_commission)) . '%';
            }
        ),
        array(
            'header' => 'سهم ناشر',
            'value' =>function($data){
                if((int)$data->publisher_commission_amount != $data->publisher_commission_amount)
                    return Controller::parseNumbers(number_format($data->publisher_commission_amount, 1)) . ' تومان';
                return Controller::parseNumbers(number_format($data->publisher_commission_amount)) . ' تومان';
            },
            'footer'=> Controller::parseNumbers(number_format($model->getTotalPublisherCommission())).' تومان',
        ),
        array(
            'header' => 'سهم سایت',
            'value' =>function($data){
                if((int)$data->site_amount != $data->site_amount)
                    return Controller::parseNumbers(number_format($data->site_amount, 1)) . ' تومان';
                return Controller::parseNumbers(number_format($data->site_amount)) . ' تومان';
            },
            'footer'=> Controller::parseNumbers(number_format($model->getTotalSiteCommission())).' تومان',
        ),
        array(
            'header' => 'مالیات',
            'value' =>function($data){
                if($data->tax_amount){
                    if((int)$data->tax_amount != $data->tax_amount)
                        return Controller::parseNumbers(number_format($data->tax_amount, 1)) . ' تومان';
                    return Controller::parseNumbers(number_format($data->tax_amount)) . ' تومان';
                }else
                    return 'معاف';
            },
            'footer'=> Controller::parseNumbers(number_format($model->getTotalTax())).' تومان',
        )
    )
));
?>
<?php
echo CHtml::endForm();
?>
