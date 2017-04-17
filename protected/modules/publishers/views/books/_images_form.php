<?php
/* @var $this BooksController */
/* @var $model Books */
/* @var $imageModel BookImages */
/* @var $images [] */
/* @var $form CActiveForm */
?>
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'book-images-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>false,
    'action' => array('/publishers/books/images?id='.$model->id),
    'enableClientValidation'=>true,
    'clientOptions' => array(
        'validateOnSubmit' => true
    )
));
?>
<?= $this->renderPartial('//partial-views/_flashMessage' ,array('prefix' => 'images-')); ?>
<div class="form-group">
    <?php
    $this->widget('ext.dropZoneUploader.dropZoneUploader', array(
        'id' => 'uploader',
        'model' => $imageModel,
        'name' => 'image',
        'maxFiles' => 10,
        'maxFileSize' => 1, //MB
        'data'=>array('book_id'=>$model->id),
        'url' => $this->createUrl('/publishers/books/uploadImage'),
        'deleteUrl' => $this->createUrl('/publishers/books/deleteImage'),
        'acceptedFiles' => 'image/jpeg , image/png',
        'serverFiles' => $images,
        'onSuccess' => '
            var responseObj = JSON.parse(res);
            if(responseObj.status){
                {serverName} = responseObj.fileName;
                $(".uploader-message").html("");
            }
            else{
                $(".uploader-message").html(responseObj.message).addClass("error");
                this.removeFile(file);
            }
        ',
    ));
    ?>
    <?php echo $form->error($model,'image'); ?>

    <h5 class="uploader-message error pull-right"></h5>
</div>
<div class="form-group">
    <div class="input-group buttons">
        <?php echo CHtml::submitButton('تایید نهایی',array('class'=>'btn btn-success')); ?>
    </div>
</div>
<?
$this->endWidget();
?>