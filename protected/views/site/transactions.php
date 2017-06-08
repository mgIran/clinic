<?php
/* @var $this SiteController */
/* @var $model UserTransactions */
?>

<h1>تراکنش ها</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'transactions-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'itemsCssClass'=>'table',
    'columns'=>array(
        array(
            'name'=>'user_name',
            'header'=>'کاربر',
            'value'=>'$data->user && $data->user->userDetails?$data->user->userDetails->getShowName():"کاربر حذف شده"'
        ),
        array(
            'name'=>'amount',
            'value'=>'number_format($data->amount, 0)." تومان"',
        ),
        array(
            'name'=>'date',
            'value'=>'$data->date?JalaliDate::date("d F Y - H:i", $data->date):"-"',
            'filter'=>false
        ),
        'token',
        'gateway_name',
        array(
            'name'=>'status',
            'value'=>function($data){
                return '<span class="label label-'.(($data->status=='paid')?'success':'danger').'">'.$data->statusLabels[$data->status].'</span>';
            },
            'type'=>'raw',
            'filter'=>CHtml::activeDropDownList($model,'status',$model->statusLabels,array('prompt' => 'همه'))
        ),
        'description',
    ),
)); ?>