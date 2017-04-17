<?php
/* @var $this BookCategoriesController */
/* @var $model BookCategories */
/* @var $form CActiveForm */
/* @var $image [] */
/* @var $icon [] */
?>

<div class="form">
	<?php if($model->parent_id==null):?>
		<div class="row">
			<?php echo CHtml::activeLabelEx($model,'image')?>
			<span class="clearfix"></span>
			<span class="description">حداقل اندازه مجاز تصویر 500 در 280 پیکسل است.</span>
			<span class="clearfix"></span>
			<?php $this->widget('ext.dropZoneUploader.dropZoneUploader', array(
				'id' => 'image-uploader',
				'model' => $model,
				'name' => 'image',
				'maxFiles' => 1,
				'maxFileSize' => 0.2, //MB
				'data'=>array('model_id'=>$model->id),
				'url' => $this->createUrl('/category/upload'),
				'deleteUrl' => $this->createUrl('/category/deleteUpload'),
				'acceptedFiles' => 'image/jpeg , image/png',
				'serverFiles' => $image,
				'onSuccess' => '
					var responseObj = JSON.parse(res);
					if(responseObj.status){
						{serverName} = responseObj.fileName;
						$(".uploader-message#image_error").html("");
					}
					else{
						$(".uploader-message#image_error").html(responseObj.message);
						this.removeFile(file);
					}
				',
			));?>
			<div class="uploader-message error" id="image_error"></div>
		</div>
	<?php endif;?>
	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'icon')?>
        <span class="clearfix"></span>
        <span class="description">فرمت فایل آیکون باید svg باشد.</span>
        <span class="clearfix"></span>
		<?php $this->widget('ext.dropZoneUploader.dropZoneUploader', array(
			'id' => 'icon-uploader',
			'model' => $model,
			'name' => 'icon',
			'maxFiles' => 1,
			'maxFileSize' => 0.2, //MB
			'data'=>array('model_id'=>$model->id),
			'url' => $this->createUrl('/category/uploadIcon'),
			'deleteUrl' => $this->createUrl('/category/deleteUploadIcon'),
			'acceptedFiles' => '.svg',
			'serverFiles' => $icon,
			'onSuccess' => '
				var responseObj = JSON.parse(res);
				if(responseObj.status){
					{serverName} = responseObj.fileName;
					$(".uploader-message#icon_error").html("");
					$(".show-icon").css({"background-color":$("#BookCategories_icon_color").val(),
					    "background-image": "url(\'"+baseUrl+"/uploads/bookCategories/icons/"+responseObj.fileName+"\')"
					});
				}
				else{
					$(".uploader-message#icon_error").html(responseObj.message);
					this.removeFile(file);
				}
			',
		));?>
		<div class="uploader-message error" id="icon_error"></div>
	</div>
    <span class="clearfix"></span>
    <span class="description">پیش نمایش آیکون</span>
    <span class="clearfix"></span>
    <div class="show-icon"></div>
</div><!-- form -->
<script>
    $(function () {
        $(window).load(function () {
            $(".show-icon").css({"background-color":$("#BookCategories_icon_color").val(),
                "background-image": "url('"+baseUrl+"/uploads/bookCategories/icons/"+$("#iconUploader input").val()+"')"
            });
        });
    })
</script>
<style>
    .show-icon{
        background: rgba(0, 0, 0, 0) none no-repeat scroll center center / 40px auto;
        border-radius: 4px;
        float: right;
        height: 60px;
        margin-top: 15px;
        width: 60px;
    }
</style>