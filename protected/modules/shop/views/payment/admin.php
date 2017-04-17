<?php
/* @var $this ShopPaymentController */
/* @var $model ShopPaymentMethod */

$this->breadcrumbs=array(
	'مدیریت روش های پرداخت',
);

?>

<h1>مدیریت روش های پرداخت</h1>

<?php $this->widget('ext.yiiSortableModel.widgets.SortableCGridView', array(
	'id'=>'shop-payment-method-grid',
	'orderField' => 'order',
	'idField' => 'id',
	'orderUrl' => 'order',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    'itemsCssClass'=>'table',
	'columns'=>array(
		'title',
		'description',
		array(
			'name' => 'price',
			'value' => function($data){
				return Controller::parseNumbers(number_format($data->price)).' تومان';
			},
			'filter' => false
		),
		array(
			'name' => 'status',
			'value' => '$data->statusLabel',
			'filter' => false
		),
		array(
            'header' => 'تغییر وضعیت',
			'class'=>'CButtonColumn',
			'template' => '{active} {deactive}',
			'buttons' => array(
				'active' => array(
					'label'=>'فعال',
					'url'=>'Yii::app()->controller->createUrl("changeStatus",array("id"=>$data->primaryKey))',
					'options'=>array('class' => 'btn btn-success btn-sm'),
					'click' => new CJavaScriptExpression('function() {
                        if(confirm("آیا از انجام این عمل اطمینان دارید؟")){
                            var th = this;
                            jQuery("#shop-payment-method-grid").yiiGridView(\'update\', {
                                type: \'POST\',
                                url: jQuery(this).attr(\'href\'),
                                success: function(data) {
                                    jQuery(\'#shop-payment-method-grid\').yiiGridView(\'update\');
                                }
                            });
                        }
                        return false;
                    }'),
					'visible'=>'!$data->status',
				),
				'deactive' => array(
					'label'=>'غیر فعال',
                    'url'=>'Yii::app()->controller->createUrl("changeStatus",array("id"=>$data->primaryKey))',
					'options'=>array('class' => 'btn btn-danger btn-sm'),
                    'click' => new CJavaScriptExpression('function() {
                        if(confirm("آیا از انجام این عمل اطمینان دارید؟")){
                            var th = this;
                            jQuery("#shop-payment-method-grid").yiiGridView(\'update\', {
                                type: \'POST\',
                                url: jQuery(this).attr(\'href\'),
                                success: function(data) {
                                    jQuery(\'#shop-payment-method-grid\').yiiGridView(\'update\');
                                }
                            });
                        }
                        return false;
                    }'),
					'visible'=>'$data->status',
				)
			)
		),
		array(
            'header' => 'تغییر وضعیت',
			'class'=>'CButtonColumn',
			'template' => '{update}',
		),
	),
)); ?>
