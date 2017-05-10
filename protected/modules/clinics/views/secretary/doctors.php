<?php
/* @var $this ClinicsDoctorController */
/* @var $model Visits */
/* @var $form CActiveForm */
?>
<h3>لیست پزشکان</h3>
<p class="description">جهت مدیریت نوبت های هر پزشک، لطفا از جدول زیر اقدام فرمایید.</p>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'doctors-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'itemsCssClass'=>'table',
    'template' => '{items} {pager}',
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
    'pagerCssClass' => 'text-center blank',
    'columns'=>array(
        array(
            'name' => 'user_id',
            'value' => '$data->user->userDetails->showName'
        ),
        array(
            'class'=>'CButtonColumn',
            'template'=>'{view}',
            'buttons' => array(
                'view' => array(
                    'label' => 'نمایش نوبت ها',
                    'url' => 'Yii::app()->controller->createUrl("secretary/visits/".$data->user_id)',
                )
            )
        ),
    ),
));