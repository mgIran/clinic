<?php
/* @var $this PublishersBooksController */
/* @var $model Books */
/* @var $dataProvider CActiveDataProvider */
/* @var $for string */
?>

<div class="packages-list-container">
    <a class="btn btn-success add-package<?php echo ($dataProvider->totalItemCount == 0)?'':' hidden';?>" href="#package-modal" data-toggle="modal">ثبت نوبت چاپ</a>
    <table class="table">
        <thead class="thead">
            <tr>
                <th>نسخه چاپ</th>
                <th>حجم فایل</th>
                <th>تاریخ بارگذاری</th>
                <th>تاریخ انتشار</th>
                <th>قیمت نسخه الکترونیک</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <?php $this->widget('zii.widgets.CListView', array(
            'id'=>'packages-list',
            'dataProvider'=>$dataProvider,
            'itemView'=>'_package_list',
            'ajaxUrl'=>array('/publishers/books/update/'.$model->id),
            'itemsTagName'=>'tr',
            'tagName'=>'tbody',
            'emptyTagName'=>'td colspan="8"',
            'emptyCssClass'=>'text-center',
            'template'=>'{items}',
            'htmlOptions'=>array('class'=>'table'),
        ));?>
    </table>
<!---->
<!--    --><?php //echo CHtml::beginForm();?>
<!--        --><?php //echo CHtml::submitButton('ادامه', array('class'=>'btn btn-default', 'name'=>'packages-submit'));?>
<!--    --><?php //echo CHtml::endForm();?>
    <div id="package-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">ثبت نوبت چاپ جدید</h4>
                </div>
                <div class="modal-body">
                    <div class="form">
                        <div class="form-group">
                            <?php echo CHtml::beginForm('','post',array('id'=>'package-info-form'));?>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <?php echo CHtml::label('فایل PDF', ''); ?>
                                    <?php $this->widget('ext.dropZoneUploader.dropZoneUploader', array(
                                        'id' => 'uploaderPdfFile',
                                        'name' => 'pdf_file_name',
                                        'maxFileSize' => 1024,
                                        'maxFiles' => false,
                                        'url' => Yii::app()->createUrl('/publishers/books/uploadPdfFile'),
                                        'deleteUrl' => Yii::app()->createUrl('/publishers/books/deleteUploadPdfFile'),
                                        'acceptedFiles' => '.pdf',
                                        'serverFiles' => array(),
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
                                    ));?>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <?php echo CHtml::label('فایل EPUB', ''); ?>
                                    <?php $this->widget('ext.dropZoneUploader.dropZoneUploader', array(
                                        'id' => 'uploaderEpubFile',
                                        'name' => 'epub_file_name',
                                        'maxFileSize' => 1024,
                                        'maxFiles' => false,
                                        'url' => Yii::app()->createUrl('/publishers/books/uploadEpubFile'),
                                        'deleteUrl' => Yii::app()->createUrl('/publishers/books/deleteUploadEpubFile'),
                                        'acceptedFiles' => '.epub',
                                        'serverFiles' => array(),
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
                                    ));?>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <?php echo CHtml::textField('version', '', array('class'=>'form-control', 'placeholder'=>'نسخه چاپ'));?>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <?php echo CHtml::textField('isbn', '', array('class'=>'form-control', 'placeholder'=>'شابک *'));?>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <?php echo CHtml::textField('print_year', '', array('class'=>'form-control', 'placeholder'=>'سال چاپ *'));?>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <?php echo CHtml::textField('price', '', array('class'=>'form-control', 'placeholder'=>'قیمت نسخه الکترونیک (تومان)'));?>
                                        <div style="margin-top: 10px">
                                            <?php echo CHtml::checkBox('free', false);?>
                                            <?php echo CHtml::label('رایگان', 'free');?>
                                        </div>
                                    </div>
<!--                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="margin-top: 15px;">-->
<!--                                        --><?php //echo CHtml::checkBox('sale_printed', false, array('data-toggle'=>'collapse', 'data-target'=>'#printed-price'));?>
<!--                                        --><?php //echo CHtml::label('میخواهم نسخه چاپی این کتاب را هم بفروشم.', 'sale_printed');?>
<!--                                    </div>-->
<!--                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 collapse" id="printed-price">-->
<!--                                        --><?php //echo CHtml::textField('printed_price', '', array('class'=>'form-control', 'placeholder'=>'قیمت نسخه چاپی * (تومان)'));?>
<!--                                    </div>-->
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php echo CHtml::hiddenField('for', $for);?>
                                        <?php echo CHtml::hiddenField('book_id', $model->id);?>
                                        <?php echo CHtml::ajaxSubmitButton('ثبت', $this->createUrl('/publishers/books/savePackage'), array(
                                            'type'=>'POST',
                                            'dataType'=>'JSON',
                                            'data'=>'js:$("#package-info-form").serialize()',
                                            'beforeSend'=>"js:function(){
                                                if($('#package-info-form #isbn').val()=='' || $('#package-info-form #print_year').val()==''){
                                                    $('.uploader-message').text('لطفا فیلد های ستاره دار را پر کنید.').addClass('error');
                                                    return false;
                                                }else if($('#package-info-form #price').val()=='' && !$('#free').is(':checked')){
                                                    $('.uploader-message').text('لطفا قیمت را مشخص کنید.').addClass('error');
                                                    return false;
                                                }else if($('input[type=\"hidden\"][name=\"pdf_file_name\"]').length==0 && $('input[type=\"hidden\"][name=\"epub_file_name\"]').length==0){
                                                    $('.uploader-message').text('لطفا نوبت چاپ جدید را آپلود کنید.').addClass('error');
                                                    return false;
                                                }else
                                                    $('.uploader-message').text('در حال ثبت اطلاعات نوبت چاپ...').removeClass('error');
                                            }",
                                            'success'=>"js:function(data){
                                                if(data.status){
                                                    $.fn.yiiListView.update('packages-list');
                                                    $('.uploader-message').text('');
                                                    $('#package-modal').modal('hide');
                                                    $('.dz-preview').remove();
                                                    $('.dropzone').removeClass('dz-started');
                                                    $('#package-info-form #version').val('');
                                                    $('#package-info-form #package_name').val('');
                                                    $('#package-info-form #isbn').val('');
                                                    $('#package-info-form #price').val('');
                                                    $('#package-info-form #print_year').val('');
                                                    $('#package-info-form #printed_price').val('');
                                                    $('.add-package').addClass('hidden');
                                                } else
                                                    $('.uploader-message').html(data.message).addClass('error');
                                            }",
                                        ), array('class'=>'btn btn-success pull-left'));?>
                                        <h5 class="uploader-message error pull-right"></h5>
                                    </div>
                                </div>
                            <?php echo CHtml::endForm();?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php Yii::app()->clientScript->registerCss('package-form','
#package-info-form input[type="text"]{margin-top:20px;}
#package-info-form input[type="submit"], .uploader-message{margin-top:20px;}
.uploader-message{line-height:32px;}
');?>
<?php Yii::app()->clientScript->registerScript('inline-script', "
$('body').on('click', '.delete-package', function(){
    var confirmation=confirm('آیا از حذف این نسخه مطمئن هستید؟');
    if(confirmation){
        $.ajax({
            url:$(this).attr('href'),
            type:'GET',
            dataType:'JSON',
            success:function(data){
                if(data.status == 'success'){
                    $.fn.yiiListView.update('packages-list');
                    if(data.count == 0)
                        $('.add-package').removeClass('hidden');
                }else
                    alert('در انجام عملیات خطایی رخ داده است.');
            },
            error:function(){
                alert('در انجام عملیات خطایی رخ داده است.');
            }
        });
    }
    return false;
});

var price = null;
$('#free').on('change', function(){
    if($(this).is(':checked')){
        price = $('#price').val();
        $('#price').addClass('hidden').val(0);
        $(this).parent().css('margin-top', 30);
    }else{
        if(price==0)
            $('#price').val('').removeClass('hidden');
        else
            $('#price').val(price).removeClass('hidden');
        $(this).parent().css('margin-top', 10);
    }
});
");?>