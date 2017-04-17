<?php
/* @var $this ShopOrderController */
/* @var $model ShopOrder */

$this->breadcrumbs=array(
	'مدیریت',
);

Yii::app()->clientScript->registerScript('changeNav', "
    $(\"body\").on(\"submit\", \"#filter-form\", function(e){
        e.preventDefault();
        var filters = $(this).serialize();
        $.fn.yiiGridView.update(\"shop-order-grid\",{
            data: filters
        });
    });

	$('body').on('click', '.status-change', function(){
	    var el = $(this);
	    $('.nav li.active').removeClass('active');
	    el.parent().addClass('active');
        $('#ShopOrder_status').val(el.data('status'));
        $(\"#filter-form\").submit();
	});
");

?>

<h1>مدیریت سفارشات</h1>
<ul class="nav nav-tabs nav-justified">
	<?php
	foreach(ShopOrder::model()->statusLabels as $key => $status):
	?>
	<li>
		<a href="#" class="status-change" style="outline: 0 none !important;" data-status="<?= $key ?>"><?= $status ?></a>
	</li>
	<?php
	endforeach;
	?>
</ul>
<?php
echo CHtml::beginForm('','GET',array('id' => 'filter-form'));
echo CHtml::hiddenField('ShopOrder[status]', (isset($_GET['ShopOrder']['status'])?$_GET['ShopOrder']['status']:1));
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'shop-order-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'itemsCssClass'=>'table',
	'columns'=>array(
        array(
            'name' => 'id',
            'value' => '$data->getOrderId()',
            'htmlOptions' => array('style' => 'width:80px')
        ),
		array(
			'header' => 'کاربر',
			'value' => function($data){
				return $data->user && $data->user->userDetails?$data->user->userDetails->getShowName():'';
			},
            'htmlOptions' => array('style' => 'width:200px')
		),
		array(
			'name' => 'ordering_date',
			'value' => function($data){
				return JalaliDate::date('Y/m/d - H:i', $data->ordering_date);
			},
            'filter' => false,
            'htmlOptions' => array('style' => 'width:130px')
		),
		array(
            'name' => 'update_date',
			'value' => function($data){
				return JalaliDate::date('Y/m/d - H:i', $data->update_date);
			},
            'filter' => false,
            'htmlOptions' => array('style' => 'width:130px')
		),
		array(
            'name' => 'payment_method',
            'value' => '$data->paymentMethod->title',
            'filter' => CHtml::listData(ShopPaymentMethod::model()->findAll(),'id', 'title'),
            'htmlOptions' => array('style' => 'width:100px')
		),
		array(
            'name' => 'shipping_method',
            'value' => '$data->shippingMethod->title',
            'filter' => CHtml::listData(ShopShippingMethod::model()->findAll(array('order'=>'t.order')),'id', 'title'),
            'htmlOptions' => array('style' => 'width:100px')
		),
		array(
			'name' => 'status',
			'value' => '$data->statusLabel',
			'filter' => false
		),
		array(
			'header'=>'تغییر وضعیت',
			'value'=>function($data){
				$form=CHtml::dropDownList("stauts", $data->status, $data->statusLabels, array("class"=>"change-order-status", "data-id"=>$data->id));
				$form.=CHtml::button("ثبت", array("class"=>"btn btn-success order-change-status", 'style'=>'margin-right:5px;'));
				return $form;
			},
			'htmlOptions' => array('style' => 'width:210px'),
			'type'=>'raw'
		),
		array(
			'class'=>'CButtonColumn',
            'template' => '{view} {delete}',
            'header'=>$this->getPageSizeDropDownTag(),
		),
	),
));

echo CHtml::endForm();

Yii::app()->clientScript->registerScript('changeOrderStatus', "
	$('body').on('click', '.order-change-status', function(){
	    var el = $(this),
            tr = el.parents('tr');
		$.ajax({
			url:'".$this->createUrl('/shop/order/changeStatus')."',
			type:'POST',
			dataType:'JSON',
			data:{id:tr.find('.change-order-status').data('id'), value:tr.find('.change-order-status').val()},
			success:function(data){
				if(data.status)
					$.fn.yiiGridView.update('shop-order-grid');
				else
					alert(data.msg);
			}
		});
	});
");
