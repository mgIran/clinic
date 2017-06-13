<?php
/* @var $this ClinicsDoctorController */
/* @var $model Visits */
/* @var $form CActiveForm */
/* @var $action string */
if($action == 'visits'){
    $desc = 'جهت مدیریت نوبت های هر پزشک، لطفا از جدول زیر اقدام فرمایید.';
    $viewBtn = array(
        'label' => 'نمایش نوبت ها',
        'url' => isset($_POST['date_altField'])?
            'Yii::app()->controller->createUrl("secretary/visits/".$data->user_id."/?date='.$_POST['date_altField'].'")':
            'Yii::app()->controller->createUrl("secretary/visits/".$data->user_id."/?Visits[time]=".$data->getNowTime())',
    );
}elseif($action == 'leaves'){
    $desc = 'جهت مدیریت مرخصی های هر پزشک، لطفا از جدول زیر اقدام فرمایید.';
    $viewBtn = array(
        'label' => 'مدیریت مرخصی ها',
        'url' => 'Yii::app()->controller->createUrl("secretary/leaves/".$data->user_id)',
    );
}elseif($action == 'schedules'){
    $desc = 'جهت مدیریت برنامه زمانی هر پزشک، لطفا از جدول زیر اقدام فرمایید.';
    $viewBtn = array(
        'label' => 'مدیریت برنامه زمانی',
        'url' => 'Yii::app()->controller->createUrl("secretary/schedules/".$data->user_id)',
    );
}elseif($action == 'expertises'){
    $desc = 'جهت مدیریت تخصص های هر پزشک، لطفا از جدول زیر اقدام فرمایید.';
    $viewBtn = array(
        'label' => 'مدیریت تخصص ها',
        'url' => 'Yii::app()->controller->createUrl("secretary/expertises/".$data->user_id)',
    );
}
?>
<h3>لیست پزشکان</h3>
    <p class="description"><?= $desc ?></p>
<?php
if($action == -1):
?>
    <div class="form well">
        <?php
        echo CHtml::form();
        ?>
        <div class="form-group col-lg-4 col-md-4 col-sm-4 col-ex-12 relative">
            <label>انتخاب تاریخ نمایش</label>
            <?php $this->widget('ext.PDatePicker.PDatePicker', array(
                'id' => 'date',
                'value' => isset($_POST['date_altField'])?$_POST['date_altField']:null,
                'htmlOptions' => array(
                    'autocomplete' => 'off'
                ),
                'options' => array(
                    'format' => 'YYYY/MM/DD',
                )
            )); ?>
        </div>
        <?php echo CHtml::submitButton('نمایش',array('class' => 'btn btn-success')) ?>
        <?php
        echo CHtml::endForm();
        ?>
    </div>
<?php
endif;
?>
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
                'view' => $viewBtn
            )
        ),
    ),
));