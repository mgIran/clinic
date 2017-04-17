<?php
/* @var $this RowsManageController */
/* @var $model RowsHomepage */

$this->breadcrumbs=array(
	'مدیریت'=>array('admin'),
	'افزودن',
);

$this->menu=array(
	array('label'=>'مدیریت', 'url'=>array('admin')),
);
?>

<h1>افزودن ردیف</h1>
	<ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#general">عمومی</a></li>
		<li class="disabled"><a href="#">لیست کتاب ها</a></li>
	</ul>
	<div class="tab-content">
		<div id="general" class="tab-pane fade in active">
			<?php $this->renderPartial('_form', array('model'=>$model)); ?>
		</div>
	</div>