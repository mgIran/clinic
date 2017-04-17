<?php
/* @var $this BooksController */
/* @var $model Books */
/* @var $form CActiveForm */
/* @var $icon [] */
/* @var $tax string */
/* @var $commission string */
?>

<div class="container-fluid">
    <div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'books-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
        'clientOptions' => array(
            'validateOnSubmit' => true
        )
    ));
    ?>
        <?= $this->renderPartial('//partial-views/_flashMessage'); ?>

        <div class="row">

            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <?php echo $form->textField($model,'title',array('placeholder'=>$model->getAttributeLabel('title').' *','maxlength'=>50,'class'=>'form-control')); ?>
                <?php echo $form->error($model,'title'); ?>
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <?php echo $form->textField($model,'language',array('placeholder'=>$model->getAttributeLabel('language').' *','maxlength'=>20,'class'=>'form-control')); ?>
                <?php echo $form->error($model,'language'); ?>
            </div>

            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?php
                $this->widget("ext.tagIt.tagIt",array(
                    'model' => $model,
                    'attribute' => 'formAuthor',
                    'suggestType' => 'json',
                    'placeholder' => 'نام نویسنده را وارد کرده و Enter بزنید...',
                    'suggestUrl' => Yii::app()->createUrl('/bookPersons/list'),
                    'data' => $model->formAuthor
                ));
                ?>
                <?php echo $form->error($model,'formAuthor'); ?>
            </div>
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?php
                $this->widget("ext.tagIt.tagIt",array(
                    'model' => $model,
                    'attribute' => 'formTranslator',
                    'suggestType' => 'json',
                    'placeholder' => 'نام مترجم را وارد کرده و Enter بزنید...',
                    'suggestUrl' => Yii::app()->createUrl('/bookPersons/list'),
                    'data' => $model->formTranslator
                ));
                ?>
                <?php echo $form->error($model,'formTranslator'); ?>
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <?php echo $form->textField($model,'number_of_pages',array('placeholder'=>$model->getAttributeLabel('number_of_pages').' *','maxlength'=>5,'class'=>'form-control')); ?>
                <?php echo $form->error($model,'number_of_pages'); ?>
            </div>


            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <?php echo $form->dropDownList($model,'category_id',BookCategories::model()->adminSortList(null,false),array('prompt'=>'لطفا دسته مورد نظر را انتخاب کنید *','class'=>'form-control')); ?>
                <?php echo $form->error($model,'category_id'); ?>
            </div>

            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?php echo $form->dropDownList($model,'status',array('enable'=>'فعال','disable'=>'غیرفعال',),array('class'=>'form-control')); ?>
                <?php echo $form->error($model,'status'); ?>
            </div>

            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <?= $form->labelEx($model,'description');?>
                <?php
                $this->widget('ext.ckeditor.CKEditor',array(
                    'model' => $model,
                    'attribute' => 'description',
                    'config' =>'basic'
                ));
                ?>
                <?php echo $form->error($model,'description'); ?>
            </div>

            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <?= $form->labelEx($model,'change_log');?>
                <?php
                $this->widget('ext.ckeditor.CKEditor',array(
                    'model' => $model,
                    'attribute' => 'change_log',
                    'config' =>'basic'
                ));
                ?>
                <?php echo $form->error($model,'change_log'); ?>
                <small class="description">این فیلد برای زمانی است که کتاب در نوبت چاپ جدید تغییراتی داشته باشد، پر کردن آن الزامی نیست.</small>
            </div>

            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 large-tagit">
                <?php echo $form->labelEx($model,'formTags'); ?>
                <?php
                $this->widget("ext.tagIt.tagIt",array(
                    'model' => $model,
                    'attribute' => 'formTags',
                    'suggestType' => 'json',
                    'suggestUrl' => Yii::app()->createUrl('/tags/list'),
                    'data' => $model->formTags
                ));
                ?>
                <?php echo $form->error($model,'formTags'); ?>
            </div>

            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 large-tagit">
                <?php echo $form->labelEx($model,'formSeoTags'); ?>
                <?php
                $this->widget("ext.tagIt.tagIt",array(
                    'model' => $model,
                    'attribute' => 'formSeoTags',
                    'suggestType' => 'json',
                    'suggestUrl' => Yii::app()->createUrl('/tags/list'),
                    'data' => $model->formSeoTags
                ));
                ?>
                <?php echo $form->error($model,'formSeoTags'); ?>
            </div>

            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <?php echo $form->labelEx($model,'icon',array('class'=> 'block')); ?>
                <?php $this->widget('ext.dropZoneUploader.dropZoneUploader', array(
                    'id' => 'uploaderIcon',
                    'model' => $model,
                    'name' => 'icon',
                    'maxFiles' => 1,
                    'maxFileSize' => 0.5, //MB
                    'url' => Yii::app()->createUrl('/publishers/books/upload'),
                    'deleteUrl' => Yii::app()->createUrl('/publishers/books/deleteUpload'),
                    'acceptedFiles' => '.jpg, .jpeg, .png',
                    'serverFiles' => $icon,
                    'onSuccess' => '
                        var responseObj = JSON.parse(res);
                        if(responseObj.status){
                            {serverName} = responseObj.fileName;
                            $(".uploader-message").html("");
                        }
                        else{
                            $(".uploader-message").html(responseObj.message);
                            this.removeFile(file);
                        }
                    ',
                )); ?>
                <?php echo $form->error($model,'icon'); ?>
                <div class="uploader-message error"></div>
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <?php echo $form->labelEx($model,'preview_file',array('class'=> 'block')); ?>
                <?php $this->widget('ext.dropZoneUploader.dropZoneUploader', array(
                    'id' => 'uploaderPreview',
                    'model' => $model,
                    'name' => 'preview_file',
                    'maxFiles' => 1,
                    'maxFileSize' => 5, //MB
                    'url' => Yii::app()->createUrl('/publishers/books/uploadPreview'),
                    'deleteUrl' => Yii::app()->createUrl('/publishers/books/deleteUploadPreview'),
                    'acceptedFiles' => '.pdf, .epub',
                    'serverFiles' => $previewFile,
                    'onSuccess' => '
                        var responseObj = JSON.parse(res);
                        if(responseObj.status){
                            {serverName} = responseObj.fileName;
                            $(".uploader-preview-message").html("");
                        }
                        else{
                            $(".uploader-preview-message").html(responseObj.message);
                            this.removeFile(file);
                        }
                    ',
                )); ?>
                <?php echo $form->error($model,'preview_file'); ?>
                <div class="uploader-preview-message error"></div>
            </div>

        </div>

        <div class="buttons">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'ثبت' : 'ذخیره تغییرات',array('class'=>'btn btn-default')); ?>
        </div>

    <?php $this->endWidget(); ?>

    </div><!-- form -->
</div>