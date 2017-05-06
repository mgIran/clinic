<?php
/* @var $this UsersPublicController */
/* @var $model UserDetails */
/* @var $form CActiveForm */
/* @var $avatar array */
?>

<h3>پروفایل</h3>
<p class="description">جهت تغییر اطلاعات حساب کاربری خود فرم زیر را پر کنید.</p>

<?php $this->renderPartial('//partial-views/_flashMessage');?>

<div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'users-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation'=>true,
    )); ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <?php echo $form->textField($model,'first_name',array('placeholder'=>$model->getAttributeLabel('first_name').' *','class'=>'form-control','maxlength'=>50)); ?>
            <?php echo $form->error($model,'first_name'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <?php echo $form->textField($model,'last_name',array('placeholder'=>$model->getAttributeLabel('last_name').' *','class'=>'form-control','maxlength'=>50)); ?>
            <?php echo $form->error($model,'last_name'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <?php echo $form->textField($model,'phone',array('placeholder'=>$model->getAttributeLabel('phone'),'class'=>'form-control','maxlength'=>11)); ?>
            <?php echo $form->error($model,'phone'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <?php echo $form->textField($model,'mobile',array('placeholder'=>$model->getAttributeLabel('mobile').' *','class'=>'form-control','maxlength'=>11)); ?>
            <?php echo $form->error($model,'mobile'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <?php echo $form->textField($model,'zip_code',array('placeholder'=>$model->getAttributeLabel('zip_code'),'class'=>'form-control','maxlength'=>10)); ?>
            <?php echo $form->error($model,'zip_code'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <?php echo $form->textArea($model,'address',array('placeholder'=>$model->getAttributeLabel('address'),'maxlength'=>1000)); ?>
            <?php echo $form->error($model,'address'); ?>
        </div>
    </div>
    <?php
    if($model->user->role_id == 3 || $model->user->role_id == 2):
    ?>
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <?php echo $form->textArea($model,'doctor_resume',array('placeholder'=>$model->getAttributeLabel('doctor_resume'))); ?>
            <?php echo $form->error($model,'doctor_resume'); ?>
        </div>
    </div>
    <?
    endif;
    ?>
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <?php $this->widget('ext.dropZoneUploader.dropZoneUploader', array(
                'id' => 'avatar-uploader',
                'model' => $model,
                'name' => 'avatar',
                'maxFiles' => 1,
                'maxFileSize' => 1, //MB
                'url' => Yii::app()->createUrl('/users/public/upload'),
                'deleteUrl' => Yii::app()->createUrl('/users/public/deleteUpload'),
                'acceptedFiles' => '.jpg, .jpeg, .png',
                'serverFiles' => $avatar,
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
            <div class="uploader-message error"></div>
            <?php echo $form->error($model,'avatar'); ?>
        </div>
    </div>

    <div class="buttons">
        <?php echo CHtml::submitButton('ذخیره',array('class'=>'btn btn-success')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div>