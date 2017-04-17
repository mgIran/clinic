<?php
/* @var $this PanelController */
/* @var $model UserDetails */
/* @var $form CActiveForm */
/* @var $nationalCardImage array */
/* @var $registrationCertificateImage array */
?>
<h5>لطفا بر اساس نوع اطلاعات فرم های زیر را پر کنید.</h5>
<?php
if(!$model->type):
?>
<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#real">شخص حقیقی</a></li>
    <li><a data-toggle="tab" href="#legal">شخص حقوقی</a></li>
</ul>
<?
endif;
?>
<div class="tab-content">
    <?php
    if(!$model->type || $model->type == 'real'):
    ?>
    <div id="real" class="tab-pane fade in active">
        <div class="form">

            <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'update-real-profile-form',
                // Please note: When you enable ajax validation, make sure the corresponding
                // controller action is handling ajax validation correctly.
                // There is a call to performAjaxValidation() commented in generated controller code.
                // See class documentation of CActiveForm for details on this.
                'enableAjaxValidation'=>true,
                'enableClientValidation'=>true,
                'clientOptions'=>array(
                    'validateOnSubmit'=>true,
                )
            )); ?>

            <?php echo $form->hiddenField($model, 'type', array('value'=>'real'));?>

            <?php echo $form->errorSummary($model); ?>

            <small class="description">لطفا فرم زیر را با دقت تکمیل نمایید. موارد ستاره دار الزامی هستند.</small>

            <div class="form-group">
                <?php echo $form->textField($model,'fa_name',array('placeholder'=>$model->getAttributeLabel('fa_name').' *','maxlength'=>50,'class'=>'form-control')); ?>
                <small class="description">نام باید عبارتی با حداکثر 50 حرف باشد که از حروف و اعداد فارسی و انگلیسی، فاصله و نیم‌فاصله تشکیل شده باشد.</small>
                <?php echo $form->error($model,'fa_name'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->textField($model,'publication_name',array('placeholder'=>$model->getAttributeLabel('publication_name').' *','maxlength'=>100,'class'=>'form-control')); ?>
                <?php echo $form->error($model,'publication_name'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->textField($model,'fa_web_url',array('placeholder'=>$model->getAttributeLabel('fa_web_url'),'maxlength'=>255,'class'=>'form-control')); ?>
                <?php echo $form->error($model,'fa_web_url'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->textField($model,'nickname',array('placeholder'=>$model->getAttributeLabel('nickname').' *','maxlength'=>20,'class'=>'form-control')); ?>
                <?php echo $form->error($model,'nickname'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->textField($model,'national_code',array('placeholder'=>$model->getAttributeLabel('national_code').' *','maxlength'=>10,'class'=>'form-control')); ?>
                <?php echo $form->error($model,'national_code'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->textField($model,'phone',array('placeholder'=>$model->getAttributeLabel('phone').' *','maxlength'=>11,'class'=>'form-control')); ?>
                <?php echo $form->error($model,'phone'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->textField($model,'zip_code',array('placeholder'=>$model->getAttributeLabel('zip_code').' *','maxlength'=>10,'class'=>'form-control')); ?>
                <?php echo $form->error($model,'zip_code'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->textArea($model,'address',array('placeholder'=>$model->getAttributeLabel('address').' *','maxlength'=>1000,'class'=>'form-control')); ?>
                <?php echo $form->error($model,'address'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->textField($model,'publisher_id',array('placeholder'=>$model->getAttributeLabel('publisher_id').' *','class'=>'form-control')); ?>
                <?php echo $form->error($model,'publisher_id'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model,'credit'); ?>
                <?php echo $form->textField($model,'credit',array('placeholder'=>$model->getAttributeLabel('credit').' *','class'=>'form-control')); ?>تومان
                <?php echo $form->error($model,'credit'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model,'commission'); ?>
                <?php echo $form->textField($model,'commission',array('placeholder'=>$model->getAttributeLabel('commission').' (درصد)','class'=>'form-control','disabled'=>is_null($model->commission)?true:false)); ?>
                <div>
                    <?php echo CHtml::checkBox('default_commission', (is_null($model->commission)?true:false), array('style'=>'margin-top:15px;'));?>
                    <?php echo CHtml::label('کمیسیون پیش فرض در نظر گرفته شود.', 'default_commission');?>
                </div>
                <?php echo $form->error($model,'commission'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model,'tax_exempt'); ?>
                <div><?php echo $form->radioButtonList($model, 'tax_exempt', array(UserDetails::TAX_EXEMPT=>'بله', UserDetails::TAX_EXEMPT_NOT=>'خیر')); ?></div>
                <?php echo $form->error($model,'tax_exempt'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model,'details_status'); ?>
                <?php echo $form->dropDownList($model,'details_status',$model->detailsStatusLabels,array('options'=>array('accepted'=>array('selected'=>true)),'class'=>'form-control')); ?>
                <?php echo $form->error($model,'details_status'); ?>
            </div>

            <div class="form-group">
                <?php $this->widget('ext.dropZoneUploader.dropZoneUploader', array(
                    'id' => 'national-card-uploader',
                    'model' => $model,
                    'name' => 'national_card_image',
                    'dictDefaultMessage'=>$model->getAttributeLabel('national_card_image').' را به اینجا بکشید',
                    'maxFiles' => 1,
                    'maxFileSize' => 0.5, //MB
                    'data'=>array('user_id'=>$model->user_id),
                    'url' => $this->createUrl('/publishers/panel/uploadNationalCardImage'),
                    'acceptedFiles' => 'image/jpeg , image/png',
                    'serverFiles' => $nationalCardImage,
                    'onSuccess' => '
                        var responseObj = JSON.parse(res);
                        if(responseObj.status){
                            {serverName} = responseObj.fileName;
                            $(".uploader-message#national_card_image_error").html("");
                        }
                        else{
                            $(".uploader-message#national_card_image_error").html(responseObj.message);
                            this.removeFile(file);
                        }
                    ',
                ));?>
                <div class="uploader-message error" id="national_card_image_error"></div>
            </div>
            <h3>اطلاعات مالی ناشر</h3>


            <div class="form-group">
                <?php echo $form->labelEx($model, 'account_type');?>
                <?php echo $form->dropDownList($model, 'account_type', $model->typeLabels);?>
                <?php echo $form->error($model, 'account_type');?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'account_owner_name');?>
                <?php echo $form->textField($model, 'account_owner_name', array(
                    'class'=>'form-control',
                    'maxLength' => 50,
                ));?>
                <?php echo $form->error($model, 'account_owner_name');?>
            </div>
            <div class="form-group" id="account_owner_family">
                <?php echo $form->labelEx($model, 'account_owner_family');?>
                <?php echo $form->textField($model, 'account_owner_family', array(
                    'class'=>'form-control',
                    'maxLength' => 50,
                ));?>
                <?php echo $form->error($model, 'account_owner_family');?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'bank_name'); ?>
                <?php echo $form->textField($model,'bank_name',array('maxlength'=>100,'class'=>'form-control')); ?>
                <?php echo $form->error($model,'bank_name'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'iban'); ?>
                <div class="input-group">
                    <?php echo $form->textField($model,'iban',array('maxlength'=>24,'class'=>'form-control')); ?>
                    <span class="input-group-addon">IR</span>
                </div>
                <small>شماره شبا بدون IR و هیچ گونه فاصله و خط تیره باید ثبت شود</small>
                <?php echo $form->error($model,'iban'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'account_number'); ?>
                <?php echo $form->textField($model,'account_number',array('maxlength'=>50,'class'=>'form-control')); ?>
                <?php echo $form->error($model,'account_number'); ?>
            </div>

            <div class="buttons">
                <?php echo CHtml::submitButton('ذخیره',array('class'=>'btn btn-success')); ?>
            </div>

            <?php $this->endWidget(); ?>

        </div><!-- form -->
    </div>
    <?php
    endif;
    if(!$model->type || $model->type == 'legal'):
        ?>
    <div id="legal" class="tab-pane fade <?= $model->type == 'legal'?'in active':'' ?>">
        <div class="form">

            <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'update-legal-profile-form',
                // Please note: When you enable ajax validation, make sure the corresponding
                // controller action is handling ajax validation correctly.
                // There is a call to performAjaxValidation() commented in generated controller code.
                // See class documentation of CActiveForm for details on this.
                'enableAjaxValidation'=>true,
                'enableClientValidation'=>true,
                'clientOptions'=>array(
                    'validateOnSubmit'=>true,
                )
            )); ?>

            <?php echo $form->hiddenField($model, 'type', array('value'=>'legal'));?>

            <?php echo $form->errorSummary($model); ?>

            <small class="description">لطفا فرم زیر را با دقت تکمیل نمایید. موارد ستاره دار الزامی هستند.</small>

            <div class="form-group">
                <?php echo $form->textField($model,'fa_name',array('placeholder'=>'نام و نام خانوادگی *','maxlength'=>50,'class'=>'form-control')); ?>
                <small class="description">باید عبارتی با حداکثر 50 حرف باشد که از حروف و اعداد فارسی و انگلیسی، فاصله و نیم‌فاصله تشکیل شده باشد.</small>
                <?php echo $form->error($model,'fa_name'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->textField($model,'publication_name',array('placeholder'=>$model->getAttributeLabel('publication_name').' *','maxlength'=>100,'class'=>'form-control')); ?>
                <?php echo $form->error($model,'publication_name'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->textField($model,'nickname',array('placeholder'=>$model->getAttributeLabel('nickname').' *','maxlength'=>50,'class'=>'form-control')); ?>
                <?php echo $form->error($model,'nickname'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->textField($model,'fa_web_url',array('placeholder'=>'آدرس سایت','maxlength'=>255,'class'=>'form-control')); ?>
                <?php echo $form->error($model,'fa_web_url'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->dropDownList($model,'post',$model->postLabels,array('class'=>'form-control', 'prompt'=>$model->getAttributeLabel('post'))); ?>
                <?php echo $form->error($model,'post'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->textField($model,'company_name',array('placeholder'=>$model->getAttributeLabel('company_name').' *','maxlength'=>50,'class'=>'form-control')); ?>
                <?php echo $form->error($model,'company_name'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->textField($model,'registration_number',array('placeholder'=>$model->getAttributeLabel('registration_number').' *','maxlength'=>50,'class'=>'form-control')); ?>
                <?php echo $form->error($model,'registration_number'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->textField($model,'phone',array('placeholder'=>$model->getAttributeLabel('phone').' *','maxlength'=>11,'class'=>'form-control')); ?>
                <?php echo $form->error($model,'phone'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->textField($model,'zip_code',array('placeholder'=>$model->getAttributeLabel('zip_code').' *','maxlength'=>10,'class'=>'form-control')); ?>
                <?php echo $form->error($model,'zip_code'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->textArea($model,'address',array('placeholder'=>$model->getAttributeLabel('address').' *','maxlength'=>1000,'class'=>'form-control')); ?>
                <?php echo $form->error($model,'address'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->textField($model,'publisher_id',array('placeholder'=>$model->getAttributeLabel('publisher_id').' *','class'=>'form-control')); ?>
                <?php echo $form->error($model,'publisher_id'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model,'credit'); ?>
                <?php echo $form->textField($model,'credit',array('placeholder'=>$model->getAttributeLabel('credit').' *','class'=>'form-control')); ?>تومان
                <?php echo $form->error($model,'credit'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model,'commission'); ?>
                <?php echo $form->textField($model,'commission',array('placeholder'=>$model->getAttributeLabel('commission').' (درصد)','class'=>'form-control')); ?>
                <?php echo $form->error($model,'commission'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model,'tax_exempt'); ?>
                <div><?php echo $form->radioButtonList($model, 'tax_exempt', array(UserDetails::TAX_EXEMPT=>'بله', UserDetails::TAX_EXEMPT_NOT=>'خیر')); ?></div>
                <?php echo $form->error($model,'tax_exempt'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model,'details_status'); ?>
                <?php echo $form->dropDownList($model,'details_status',$model->detailsStatusLabels,array('options'=>array('accepted'=>array('selected'=>true)),'class'=>'form-control')); ?>
                <?php echo $form->error($model,'details_status'); ?>
            </div>

            <div class="form-group">
                <?php $this->widget('ext.dropZoneUploader.dropZoneUploader', array(
                    'id' => 'registration-certificate-uploader',
                    'model' => $model,
                    'name' => 'registration_certificate_image',
                    'dictDefaultMessage'=>$model->getAttributeLabel('registration_certificate_image').' را به اینجا بکشید',
                    'maxFiles' => 1,
                    'maxFileSize' => 0.5, //MB
                    'data'=>array('user_id'=>$model->user_id),
                    'url' => $this->createUrl('/publishers/panel/uploadRegistrationCertificateImage'),
                    'acceptedFiles' => 'image/jpeg , image/png',
                    'serverFiles' => $registrationCertificateImage,
                    'onSuccess' => '
                        var responseObj = JSON.parse(res);
                        if(responseObj.status){
                            {serverName} = responseObj.fileName;
                            $(".uploader-message#registration_certificate_image_error").html("");
                        }
                        else{
                            $(".uploader-message#registration_certificate_image_error").html(responseObj.message);
                            this.removeFile(file);
                        }
                    ',
                ));?>
                <div class="uploader-message error" id="registration_certificate_image_error"></div>
            </div>

            <h3>اطلاعات مالی ناشر</h3>

            <div class="form-group">
                <?php echo $form->labelEx($model, 'account_type');?>
                <?php echo $form->dropDownList($model, 'account_type', $model->typeLabels);?>
                <?php echo $form->error($model, 'account_type');?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'account_owner_name');?>
                <?php echo $form->textField($model, 'account_owner_name', array(
                    'class'=>'form-control',
                    'maxLength' => 50,
                ));?>
                <?php echo $form->error($model, 'account_owner_name');?>
            </div>
            <div class="form-group" id="account_owner_family">
                <?php echo $form->labelEx($model, 'account_owner_family');?>
                <?php echo $form->textField($model, 'account_owner_family', array(
                    'class'=>'form-control',
                    'maxLength' => 50,
                ));?>
                <?php echo $form->error($model, 'account_owner_family');?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'bank_name'); ?>
                <?php echo $form->textField($model,'bank_name',array('maxlength'=>100,'class'=>'form-control')); ?>
                <?php echo $form->error($model,'bank_name'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'iban'); ?>
                <?php echo $form->textField($model,'iban',array('maxlength'=>24,'class'=>'form-control')); ?>
                <small class="description">شماره شبا بدون IR وارد شود.</small>
                <?php echo $form->error($model,'iban'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'account_number'); ?>
                <?php echo $form->textField($model,'account_number',array('maxlength'=>50,'class'=>'form-control')); ?>
                <?php echo $form->error($model,'account_number'); ?>
            </div>

            <div class="buttons">
                <?php echo CHtml::submitButton('ذخیره',array('class'=>'btn btn-success')); ?>
            </div>

            <?php $this->endWidget(); ?>

        </div><!-- form -->
    </div>
    <?php
    endif;
    ?>
</div>
<?php
Yii::app()->clientScript->registerScript('account-type','
    if($(\'#UserDetails_account_type\').val() == \'legal\')
        $("#account_owner_family").fadeOut();
    else
        $("#account_owner_family").fadeIn();
    $(\'body\').on(\'change\', \'#UserDetails_account_type\', function () {
        if($(this).val() == \'legal\')
            $("#account_owner_family").fadeOut();
        else
            $("#account_owner_family").fadeIn();
    });
', CClientScript::POS_READY);

Yii::app()->clientScript->registerScript('inline-script', "
$('body').on('change', '#default_commission', function(){
	$('#UserDetails_commission').prop('disabled', function(i, v) { return !v; }).val('');
});
");?>