<?php
/* @var $this ShopShippingController */
/* @var $model ShopShippingMethod */

$this->breadcrumbs=array(
	'مدیریت روش های تحویل',
);

?>

<h1>مدیریت روش های تحویل</h1>
<a href="<?= $this->createUrl('create') ?>" class=" btn btn-success pull-right">افزودن روش تحویل جدید</a>
<div class="clearfix"></div>
<?php $this->widget('ext.yiiSortableModel.widgets.SortableCGridView', array(
	'id'=>'shop-shipping-method-grid',
	'orderField' => 'order',
	'idField' => 'id',
	'orderUrl' => 'order',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    'itemsCssClass'=>'table',
	'columns'=>array(
		'title',
		array(
			'name' => 'price',
			'value' => function($data){
				return Controller::parseNumbers(number_format($data->price)).' تومان';
			},
            'filter' => false
		),
		array(
			'name' => 'payment_method',
			'value' => function($data){
				$methods= [];
				foreach($data->paymentMethods as $method){
					$methods[]=ShopPaymentMethod::model()->findByPk($method)->title;
				}
				return implode(' - ',$methods);
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
                            jQuery("#shop-shipping-method-grid").yiiGridView(\'update\', {
                                type: \'POST\',
                                url: jQuery(this).attr(\'href\'),
                                success: function(data) {
                                    jQuery(\'#shop-shipping-method-grid\').yiiGridView(\'update\');
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
                            jQuery("#shop-shipping-method-grid").yiiGridView(\'update\', {
                                type: \'POST\',
                                url: jQuery(this).attr(\'href\'),
                                success: function(data) {
                                    jQuery(\'#shop-shipping-method-grid\').yiiGridView(\'update\');
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
			'class'=>'CButtonColumn',
            'template' => '{update} {delete}'
		),
	),
)); ?>
