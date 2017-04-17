<?php
/* @var $this AdminsManageController */
/* @var $model Admins */

$this->breadcrumbs=array(
	'مدیران'=>array('index'),
	'مدیریت',
);

$this->menu=array(
	array('label'=>'افزودن', 'url'=>array('create')),
);
?>

<h1>مدیریت مدیران</h1>
<? $this->renderPartial('//layouts/_flashMessage'); ?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'admins-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'itemsCssClass'=>'table',
	'columns'=>array(
		'name_family',
		'username',
        'email',
        array(
            'header' => 'نقش',
            'name' => 'role.name',
            'filter' => CHtml::activeDropDownList($model , 'roleId' ,
                CHtml::listData(AdminRoles::model()->findAll('role <> "superAdmin"') , 'id' , 'name'))
        ),
		array(
			'class'=>'CButtonColumn',
            'template' => '{update}{delete}',
		),
	),
)); ?>
