<?php
/* @var $this SlideShowManageController */
/* @var $model Slideshow */

$this->breadcrumbs=array(
	'مدیریت',
);

$this->menu=array(
	array('label'=>'افزودن تصویر', 'url'=>array('create')),
);
?>

<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">مدیریت تصاویر</h3>
		<a href="<?= $this->createUrl('create') ?>" class="btn btn-default btn-sm">افزودن تصویر جدید</a>
	</div>
	<div class="box-body">
		<?php $this->renderPartial("//partial-views/_flashMessage"); ?>
		<div class="table-responsive">
			<?php $this->widget('zii.widgets.grid.CGridView', array(
				'id'=>'slideshow-grid',
				'dataProvider'=>$model->search(),
				'filter'=>$model,
				'itemsCssClass'=>'table table-striped',
				'template' => '{pager} {items} {pager}',
				'ajaxUpdate' => true,
				'afterAjaxUpdate' => "function(id, data){
                    $('html, body').animate({
                    scrollTop: ($('#'+id).offset().top-130)
                    },1000,'easeOutCubic');
                }",
				'pager' => array(
					'header' => '',
					'firstPageLabel' => '<<',
					'lastPageLabel' => '>>',
					'prevPageLabel' => '<',
					'nextPageLabel' => '>',
					'cssFile' => false,
					'htmlOptions' => array(
						'class' => 'pagination pagination-sm',
					),
				),
				'pagerCssClass' => 'blank',
				'columns'=>array(
					array(
						'name' => 'image',
						'header' => 'تصویر',
						'filter' => '',
						'type' => 'html',
						'value' => 'CHtml::tag("div",
							array("style"=>"text-align: center" ) ,
							CHtml::tag("img",
								array("height"=>"50px","width"=>"50px",
									"src" => "' . Yii::app()->createAbsoluteUrl('/uploads/slideshow/$data->image') . '" ,"alt" => ""
									)
								)
							)',
					),
					'title',
					'link',
					array(
						'class'=>'CButtonColumn',
						'template' => '{update} {delete}',
					),
				),
			)); ?>
		</div>
	</div>
</div>
