<?php
/* @var $this UsersPublicController */
/* @var $model UserTransactions */

$this->menu=array(
    array('label'=>'مدیرت کاربران', 'url'=>array($model->user->role_id == 2?'adminPublishers':'admin')),
);

?>

<div class="transparent-form">
    <h3>تراکنش ها</h3>
    <p class="description">لیست تراکنش های <?= $model->user->userDetails->getShowName() ?></p>
    <?php
    echo CHtml::beginForm($this->route,'GET',array('class' => 'form-inline form'));
    ?>
    <div class="filters">

        <div class="form-group">
            <?php echo CHtml::activeTextField($model, 'id', array('class' => 'form-control ajax-grid-search', 'placeholder' => 'شناسه تراکنش را جستجو کنید'));?>
        </div>
        <div class="form-group">
            <?php echo CHtml::activeTextField($model, 'token', array('class' => 'form-control ajax-grid-search', 'placeholder' => 'کدرهگیری را جستجو کنید'));?>
        </div>
    </div>
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'bookmarked-grid',
        'dataProvider' => $model->search(),
        'template' => '{items} {pager}',
        'rowHtmlOptionsExpression'=>'array("data-book-id" => $data->id)',
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
                'header' => 'تاریخ تراکنش',
                'value' => 'JalaliDate::date(\'d F Y - H:i\', $data->date)',
            ),
            array(
                'header' => 'مبلغ',
                'value' => 'Controller::parseNumbers(number_format($data->amount)).\' تومان\'',
            ),
            'description',
            'gateway_name',
            array(
                'name' => 'token',
                'value' => '$data->token',
                'htmlOptions' => array('style' => 'font-weight:bold;letter-spacing:4px')
            ),
            array(
                'class' => 'CButtonColumn',
                'header'=>$this->getPageSizeDropDownTag(),
                'template' =>'',
            )
        )
    ));
    ?>
    <?php
    echo CHtml::endForm();
    ?>
</div>