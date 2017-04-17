<?php
/* @var $this RolesController */
/* @var $model AdminRoles */

$this->breadcrumbs=array(
	'نقش کاربران'=>array('index'),
	'مدیریت',
);

$this->menu=array(
	array('label'=>'افزودن', 'url'=>array('create')),
);
?>

<h1>مدیریت نقش کاربران</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'admin-roles-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'name',
        'role',
		array(
			'class'=>'CButtonColumn',
            'template' => '{update}{delete}'
		),
	),
)); ?>
