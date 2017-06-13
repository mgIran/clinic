<?php
/* @var $this SiteController */
/* @var $model Clinics */

$this->breadcrumbs=array(
    'مطب ها'=>array('admin'),
    $model->clinic_name
);

$this->menu = array(
    array('label' => 'لیست مطب ها', 'url' => array('admin')),
    array('label' => 'لیست پرسنل این مطب', 'url' => array('manage/adminPersonnel/'.$model->id)),
    array('label' => 'ویرایش اطلاعات این مطب', 'url' => array('manage/update','id' => $model->id)),
);

?>

<h3>نمایش اطلاعات "<?php echo $model->clinic_name; ?>"</h3>
<?php $this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        array(
            'label' => 'استان',
            'name' => 'town.name',
        ),
        array(
            'label' => 'شهرستان',
            'name' => 'place.name',
        ),
        'description',
        array(
            'name' => 'contracts',
            'value' => implode(' - ', CJSON::decode($model->contracts))
        ),
        'zip_code',
        'phone',
        'fax',
        'address',
    ),
)); ?>