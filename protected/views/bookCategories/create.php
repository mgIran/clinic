<?php
/* @var $this BookCategoriesController */
/* @var $model BookCategories */

$this->breadcrumbs=array(
	'دسته بندی های کتاب',
	'افزودن',
);

$this->menu=array(
	array('label'=>'مدیریت دسته بندی ها', 'url'=>array('admin')),
);
?>

	<h1>افزودن دسته بندی</h1>
	<ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#info">عمومی</a></li>
		<li class="disabled"><a>آپلود تصویر و آیکون</a></li>
	</ul>

	<div class="tab-content">
		<div id="info" class="tab-pane fade in active">
			<?php $this->renderPartial('_form', array('model'=>$model)); ?>
		</div>
	</div>

