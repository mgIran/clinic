<?php
/* @var $this BookCategoriesController */
/* @var $model BookCategories */
/* @var $image [] */
/* @var $icon [] */

$this->breadcrumbs=array(
		'دسته بندی های کتاب',
	$model->title,
	'ویرایش',
);

$this->menu=array(
	array('label'=>'افزودن دسته بندی', 'url'=>array('create')),
	array('label'=>'مدیریت دسته بندی ها', 'url'=>array('admin')),
);
?>

<h1>ویرایش دسته بندی <?php echo $model->title; ?></h1>
<ul class="nav nav-tabs">
	<li class="<?= ($step == 1?'active':''); ?>"><a data-toggle="tab" href="#general">عمومی</a></li>
	<li class="<?= $model->getIsNewRecord()?'disabled':''; ?> <?= ($step == 2?'active':''); ?>"><a data-toggle="tab" href="#details">آپلود تصویر و آیکون</a></li>
</ul>

<div class="tab-content">
	<div id="general" class="tab-pane fade <?= ($step == 1?'in active':''); ?>">
		<?php $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>
	<? if(!$model->getIsNewRecord()):?>
		<div id="details" class="tab-pane fade <?= ($step == 2?'in active':''); ?>">
			<?php $this->renderPartial('_details', array('model'=>$model, 'image'=>$image, 'icon'=>$icon)); ?>
		</div>
	<? endif;?>
</div>