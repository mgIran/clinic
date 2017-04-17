<?php
/* @var $this TownsManageController */
/* @var $model Towns */

$this->breadcrumbs=array(
	'مدیریت استان ها',
);

$this->menu=array(
	array('label'=>'افزودن استان', 'url'=>array('create')),
);
?>

<h1>مدیریت استان ها</h1>
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
	'id'=>'towns-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'name',
        'slug',
		array(
			'class'=>'CButtonColumn',
            'template' => '{update}{delete}'
		),
	),
)); ?>
