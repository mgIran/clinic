<?php
/* @var $this ClinicsManageController */
/* @var $model Clinics */

$this->breadcrumbs=array(
	'مطب ها'=>array('admin'),
	$model->clinic_name => array('view','id' => $model->id),
	'ویرایش',
);

$this->menu=array(
	array('label'=>'افزودن مطب', 'url'=>array('create')),
	array('label'=>'لیست مطب ها', 'url'=>array('admin')),
	array('label'=>'لیست پرسنل این مطب', 'url'=>array('manage/adminPersonnel/'.$model->id)),
);
?>

<h3>ویرایش اطلاعات درمانگاه "<?php echo $model->clinic_name;?>"</h3>
<p class="description">برای اعمال تغییرات در اطلاعات این درمانگاه لطفا فرم زیر را ویرایش کنید.</p>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>