<?php
/* @var $this UsersPublicController */
/* @var $model UserTransactions */
?>

<div class="transparent-form">
    <h3>لیست تمام نوبت ها</h3>
    <p class="description">لیست تمام نوبت هایی که تا کنون در سایت رزرو کرده اید.</p>
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'visits-grid',
        'dataProvider' => $model->search(),
        'filter' => $model,
        'template' => '{items} {pager}',
        'ajaxUpdate' => true,
        'afterAjaxUpdate' => "function(id, data){
            $('html, body').animate({
                scrollTop: ($('#'+id).offset().top-130)
            },1000);
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
        'itemsCssClass' => 'table',
        'columns' => array(
            array(
                'name' => 'clinic_id',
                'value' => '$data->clinic->clinic_name',
            ),
            array(
                'name' => 'doctor_id',
                'value' => '$data->doctor->userDetails->showName',
            ),
            array(
                'header' => 'وضعیت نوبت',
                'name' => 'status',
                'value' => '$data->statusLabel',
                'filter' => $model->statusLabels
            ),
            array(
                'header' => 'تاریخ رزرو',
                'value' => 'JalaliDate::date("Y/m/d - H:i", $data->date)',
            ),
            array(
                'header' => 'زمان مراجعه',
                'value' => 'JalaliDate::date("Y/m/d - H:i", $data->date)',
            ),
            array(
                'header' => 'تاریخ ویزیت',
                'value' => '$data->check_date?JalaliDate::date("Y/m/d - H:i", $data->check_date):"-"',
            ),
            array(
                'name' => 'tracking_code',
                'value' => '$data->tracking_code',
                'htmlOptions' => array('style' => 'font-weight:bold;letter-spacing:4px')
            ),
            array(
                'class' => 'CButtonColumn',
                'header'=>$this->getPageSizeDropDownTag(),
                'template' =>'{delete}',
                'buttons' => array(
                    'delete' => array(
                        'label' => 'حذف',
                        'imageUrl' => '',
                        'options' => array(
                            'class' => 'btn btn-danger btn-sm'
                        ),
                        'url' => 'Yii::app()->createUrl("/clinics/panel/removeVisit/".$data->id)',
//                        'visible' => '$data->status < 3'
                    )
                )
            )
        )
    ));
    ?>
</div>