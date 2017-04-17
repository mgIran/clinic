<?php
/* @var $this PublishersBooksController */
/* @var $model BookPackages */
/* @var $pdfPackage [] */
/* @var $epubPackage [] */
$this->breadcrumbs = array(
    'مدیریت کتاب ها' => array('admin'),
    'نوبت های چاپ کتاب '.$model->book->title => array('update?id='.$model->book_id.'&step=2'),
    'نوبت چاپ '.$model->version,
    'ویرایش'
);
$this->menu = array(
    array('label' => 'بازگشت','url' => array('update?id='.$model->book_id.'&step=2'))
);
Yii::app()->clientScript->registerCss('inline',"
.dropzone.single{width:100%;}
");
?>
<div class="white-form">
    <h3>ویرایش نوبت چاپ <?= $model->version ?> کتاب <?= $model->book->title ?></h3>
    <p class="description">جهت ثبت کتاب لطفا فرم زیر را پر کنید.</p>
    <?php $this->renderPartial("//partial-views/_flashMessage") ?>
    <div class="container-fluid">
        <div class="form">
        <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'book-packages-form',
            'enableClientValidation'=>true,
            'clientOptions' => array(
                'validateOnSubmit' => true
            )
        )); ?>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-group">
                <?php $this->widget('ext.dropZoneUploader.dropZoneUploader', array(
                    'id' => 'uploaderPdfFile',
                    'model' => $model,
                    'name' => 'pdf_file_name',
                    'maxFileSize' => 1024,
                    'maxFiles' => 1,
                    'url' => Yii::app()->createUrl('/publishers/books/uploadPdfFile'),
                    'deleteUrl' => Yii::app()->createUrl('/publishers/books/deletePdfFile'),
                    'acceptedFiles' => '.pdf',
                    'serverFiles' => $pdfPackage,
                    'onSuccess' => '
                        var responseObj = JSON.parse(res);
                        if(responseObj.status){
                            {serverName} = responseObj.fileName;
                            $(".pdf-uploader-message").html("");
                        }
                        else{
                            $(".pdf-uploader-message").html(responseObj.message).addClass("error");
                            this.removeFile(file);
                        }
                    ',
                ));?>
                <?php echo $form->error($model , 'file_name'); ?>
                <div class="pdf-uploader-message"></div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-group">
                <?php $this->widget('ext.dropZoneUploader.dropZoneUploader', array(
                    'id' => 'uploaderEpubFile',
                    'model' => $model,
                    'name' => 'epub_file_name',
                    'maxFileSize' => 1024,
                    'maxFiles' => 1,
                    'url' => Yii::app()->createUrl('/publishers/books/uploadEpubFile'),
                    'deleteUrl' => Yii::app()->createUrl('/publishers/books/deleteEpubFile'),
                    'acceptedFiles' => '.epub',
                    'serverFiles' => $epubPackage,
                    'onSuccess' => '
                        var responseObj = JSON.parse(res);
                        if(responseObj.status){
                            {serverName} = responseObj.fileName;
                            $(".epub-uploader-message").html("");
                        }
                        else{
                            $(".epub-uploader-message").html(responseObj.message).addClass("error");
                            this.removeFile(file);
                        }
                    ',
                ));?>
                <?php echo $form->error($model , 'file_name'); ?>
                <div class="epub-uploader-message"></div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                <?php echo $form->labelEx($model , 'version'); ?>
                <?php echo $form->textField($model,'version', array('class'=>'form-control' , 'size'=>60));?>
                <?php echo $form->error($model , 'version'); ?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                <?php echo $form->labelEx($model , 'isbn'); ?>
                <?php echo $form->textField($model , 'isbn', array('class'=>'form-control' , 'size'=>60));?>
                <?php echo $form->error($model , 'isbn'); ?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                <?php echo $form->labelEx($model , 'print_year'); ?>
                <?php echo $form->textField($model , 'print_year', array('class'=>'form-control' , 'size'=>60));?>
                <?php echo $form->error($model , 'print_year'); ?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
                <?php echo $form->labelEx($model , 'price'); ?>
                <?php echo $form->textField($model , 'price', array('class'=>'form-control'.(($model->price==0)?' hidden':'') , 'size'=>60));?>
                <div style="margin-top: 10px">
                    <?php echo CHtml::checkBox('free', ($model->price==0)?true:false);?>
                    <?php echo CHtml::label('رایگان', 'free');?>
                </div>
                <?php echo $form->error($model , 'price'); ?>
            </div>
<!--            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group clearfix" style="margin-top: 15px;">-->
<!--                --><?php //echo $form->checkBox($model,'sale_printed', array('data-toggle'=>'collapse', 'data-target'=>'#printed-price'));?>
<!--                --><?php //echo CHtml::label('میخواهم نسخه چاپی این کتاب را هم بفروشم.', 'sale_printed');?>
<!--            </div>-->
<!--            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group --><?//= $model->sale_printed?'collapsed':'collapse' ?><!-- clearfix" id="printed-price">-->
<!--                --><?php //echo $form->labelEx($model , 'printed_price'); ?>
<!--                --><?php //echo $form->textField($model , 'printed_price', array('class'=>'form-control' , 'size'=>60));?>
<!--                --><?php //echo $form->error($model , 'printed_price'); ?>
<!--            </div>-->
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group buttons">
                <?php echo $form->hiddenField($model ,'book_id');?>
                <?php echo CHtml::submitButton('ویرایش',array('class' => 'btn btn-success')); ?>
            </div>
            <?php $this->endWidget();?>
        </div>
    </div>
</div>

<?php Yii::app()->clientScript->registerScript('inline-script',"
var price = null;
$('#free').on('change', function(){
    if($(this).is(':checked')){
        price = $('#BookPackages_price').val();
        $('#BookPackages_price').addClass('hidden').val(0);
    }else{
        if(price==0)
            $('#BookPackages_price').val('').removeClass('hidden');
        else
            $('#BookPackages_price').val(price).removeClass('hidden');
    }
});
");?>