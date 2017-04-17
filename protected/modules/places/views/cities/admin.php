<?php
/* @var $this PlacesManageController */
/* @var $model Places */

$this->breadcrumbs=array(
	'مدیریت',
);

$this->menu=array(
	array('label'=>'افزودن شهر', 'url'=>array('create')),
);
?>

<h1>مدیریت شهر ها</h1>
<?php
if(Yii::app()->user->hasFlash('success'))
    echo '<div class=\'alert alert-success fade in\'>
            <button class=\'close close-sm\' type=\'button\' data-dismiss=\'alert\'><i class=\'icon-remove\'></i></button>
            '.Yii::app()->user->getFlash('success').'
        </div>';
else if(Yii::app()->user->hasFlash('failed'))
    echo '<div class=\'alert alert-danger fade in\'>
            <button class=\'close close-sm\' type=\'button\' data-dismiss=\'alert\'><i class=\'icon-remove\'></i></button>
            '.Yii::app()->user->getFlash('failed').'
        </div>';
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'places-grid',
	'dataProvider'=>$model->search(40),
	'filter'=>$model,
	'columns'=>array(
		'name',
		'slug',
        array(
            'header' => 'استان',
            'name' => 'town.name',
            'filter' => CHtml::activeTextField($model,'townName')
        ),
		array(
			'class'=>'CButtonColumn',
            'template' => '{update}{delete}'
		),
	),
)); ?>
