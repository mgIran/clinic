<?php
/* @var $this ShopOrderController */
/* @var $model ShopOrder */
?>
<div class="transparent-form">
    <h3>سفارشات من</h3>
    <p class="description">لیست سفارشات که تا کنون ثبت کرده اید.</p>
    <?php $this->renderPartial('//partial-views/_flashMessage') ?>
    <?php
    echo CHtml::beginForm($this->route,'GET',array('class' => 'form-inline form'));
    ?>
    <div class="filters">

        <div class="form-group">
            <div class="input-group">
                <?php echo CHtml::activeTextField($model, 'id', array('sytle'=> 'border-left-color:#aaa !important;direction: ltr;','aria-describedby'=>'basic-addon1','class' => 'form-control ajax-grid-search', 'placeholder' => 'شماره رسید را جستجو کنید'));?>
                <span class="input-group-addon" style="direction: ltr;border-color:#aaa" id="basic-addon1">KBC-</span>
            </div>
        </div>
        <div class="form-group">
            <?php echo CHtml::activeTextField($model, 'export_code', array('sytle'=> 'direction: ltr;','aria-describedby'=>'basic-addon1','class' => 'form-control ajax-grid-search', 'placeholder' => 'کد مرسوله را جستجو کنید'));?>
        </div>
    </div>
    <?php $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'orders-grid',
        'dataProvider'=>$model->search(),
        'itemsCssClass'=>'table',
        'template'=>'{items}{pager}',
        'enableSorting' => false,
        'columns'=>array(
            array(
                'name'=>'id',
                'value'=>'$data->getOrderID()',
            ),
            array(
                'name'=>'ordering_date',
                'value'=>'JalaliDate::date("d F Y - H:i", $data->ordering_date)',
            ),
            array(
                'name'=>'payment_amount',
                'value'=>'Controller::parseNumbers(number_format($data->payment_amount))." تومان"',
            ),
            array(
                'name'=>'status',
                'value'=>'$data->getStatusLabel()',
            ),
            array(
                'name'=>'payment_method',
                'value'=>'$data->paymentMethod->title',
            ),
            array(
                'class' => 'CButtonColumn',
                'header'=>$this->getPageSizeDropDownTag(),
                'template' => '{details}',
                'buttons' => array(
                    'details' => array(
                        'label'=>'جزئیات',
                        'url'=>'Yii::app()->createUrl("/shop/order/getInfo/".$data->id)',
                        'options' =>array(
                            'data-target'=>'#order-details-modal',
                            'class'=>'load-order',
                        )
                    )
                )
            )
        )
    )); ?>
    <?php
    echo CHtml::endForm();
    ?>
    <?php $this->renderPartial("//partial-views/_loading");?>
</div>
<div id="order-details-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"></button>
                <h4 class="modal-title">جزئیات سفارش</h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">بستن</button>
            </div>
        </div>
    </div>
</div>
<?php Yii::app()->clientScript->registerScript("load-order", '
$("body").on("click", ".load-order", function(e){
    e.preventDefault();
    $(".transparent-form .loading-container").show();
    var target = $(this).data("target");

    $.ajax({
        url: $(this).attr("href"),
        dataType: "JSON",
        success: function(data){
            $("#order-details-modal .modal-body").html("");
            $(".transparent-form .loading-container").hide();
            if(data.status){
                $("#order-details-modal .modal-body").html(data.content);
                $(target).modal("show");
            }else
                alert("در بارگذاری اطلاعات خطایی رخ داده است. لطفا مجددا تلاش کنید!");
        },
        error: function(){
            $(".transparent-form .loading-container").hide();
            alert("در بارگذاری اطلاعات خطایی رخ داده است. لطفا مجددا تلاش کنید!");
        }
    });
});
');?>