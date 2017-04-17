<?php
/* @var $this PagesManageController */
/* @var $model Pages */
$this->breadcrumbs=array(
	'مدیریت'=>array('admin'),
	'ویرایش',
);
$template = '{view}{update}{delete}';

if($this->categorySlug == 'document' || $this->categorySlug == 'free')
{
    $this->menu=array(
        array('label'=>'افزودن', 'url'=>array('manage/create/slug/'.$this->categorySlug)),
        array('label'=>'مدیریت', 'url'=>array('manage/admin/slug/'.$this->categorySlug)),
    );
}
?>

<h1>ویرایش <?php echo $model->title; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>