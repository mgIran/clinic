<?php
/* @var $this SlideShowManageController */
/* @var $model Slideshow */

$this->breadcrumbs=array(
	'مدیریت تصاویر'=>array('admin'),
	$model->title,
);

$this->menu=array(
	array('label'=>'حذف تصویر', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'مدیریت تصاویر', 'url'=>array('admin')),
);
?>

<h1>نمایش تصویر</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name' => 'image',
			'header' => 'تصویر',
			'filter' => '',
			'type' => 'html',
			'value' => CHtml::tag("div",
                                array("style"=>"text-align: right" ) ,
                                CHtml::tag("img",
                                    array("height"=>"50px","width"=>"50px",
                                        "src" => Yii::app()->createAbsoluteUrl('/uploads/slideshow/'.$model->image)	,"alt" => ""
                                        )
                                    )
                                ),
		),
		'title',
		'description',
		'link',
	),
)); ?>
