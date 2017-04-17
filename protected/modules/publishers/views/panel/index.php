<?php
/* @var $this PublishersPanelController */
/* @var $books Books */
?>
<div class="transparent-form">
    <h3>کتاب ها</h3>
    <p class="description">لیست کتاب هایی که منتشر کرده اید.</p>

    <?php $this->renderPartial('//partial-views/_flashMessage', array('prefix'=>'images-'));?>

    <div class="buttons">
        <a class="btn btn-success" href="<?php echo $this->createUrl('/publishers/books/create');?>">افزودن کتاب جدید</a>
    </div>
<!--    <th>قیمت</th>-->
<!--    <th class="hidden-xs">تعداد خرید</th>-->
<!--    <th>عملیات</th>-->
<!--    <th>تاییدیه</th>-->
    <?php $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'books-list',
        'dataProvider' => $books->search(),
        'template' => '{pager} {items} {pager}',
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
            'id',
            array(
                'name' => 'title',
                'value' => function($data){
                    return CHtml::link($data->title, array('/book/'.$data->id.'/'.urlencode($data->title)));
                },
                'type' => 'raw'
            ),
            array(
                'header' => 'قیمت',
                'name' => 'price',
                'value' => function($data){
                    return Controller::parseNumbers(number_format($data->price))." تومان";
                },
            ),
            array(
                'header' => 'قیمت با تخفیف',
                'name' => 'offPrice',
                'value' => function($data){
                    if($data->discount && $data->discount->hasPriceDiscount())
                        return Controller::parseNumbers(number_format($data->offPrice))." تومان";
                    return '-';
                },
                'filter' => false
            ),
            array(
                'name' => 'status',
                'value' => function($data){
                    return $data->statusLabels[$data->status];
                },
                'type' => 'raw',
                'filter' => $books->statusLabels
            ),
            array(
                'header' => 'نسخه کتاب',
                'value' => 'is_null($data->lastPackage)?"-":"ویرایش ".Controller::parseNumbers($data->lastPackage->version)',
            ),
            array(
                'class' => 'CButtonColumn',
                'header'=>$this->getPageSizeDropDownTag(),
                'template' =>'{update} {delete}',
                'buttons'=>array(
                    'update'=>array(
                        'label'=>'',
                        'url'=>'Yii::app()->createUrl("/publishers/books/update", array("id"=>$data->id))',
                        'imageUrl'=>false,
                        'options'=>array('class'=>'icon-pencil text-info')
                    ),
                    'delete'=>array(
                        'label'=>'',
                        'url'=>'Yii::app()->createUrl("/publishers/books/delete", array("id"=>$data->id))',
                        'imageUrl'=>false,
                        'options'=>array('class'=>'icon-trash text-danger ask-sure')
                    ),
                ),
            )
        )
    ));?>
</div>