<?php
/* @var $data Books*/
?>

<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 book-item">
    <?php echo CHtml::radioButton('book_id', false, array(
        'value'=>$data->id,
    ));?>
    <img src="<?php echo Yii::app()->baseUrl.'/uploads/books/icons/'.CHtml::encode($data->icon);?>">
    <h5><?php echo CHtml::encode($data->title);?></h5>
</div>
