<?php
/* @var $this PanelController */
/* @var $detailsModel UserDetails */
/* @var $devIdRequestModel UserDevIdRequests */
/* @var $nationalCardImage array */
/* @var $registrationCertificateImage array */
?>
<div class="white-form">
    <h3>پروفایل ناشر</h3>
    <p class="description">لطفا فرم زیر را پر کنید.</p>

    <?php $this->renderPartial('//partial-views/_flashMessage'); ?>

    <div class="row">
        <?php $this->renderPartial('_update_profile_form', array(
            'model'=>$detailsModel,
            'nationalCardImage'=>$nationalCardImage,
            'registrationCertificateImage'=>$registrationCertificateImage,
        ));?>

        <?php if(empty($detailsModel->publisher_id)):?>
            <?php $this->renderPartial('_change_publisher_id_form', array(
                'model'=>$devIdRequestModel,
            ));?>
        <?php else:?>
            <div class="col-md-6">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#">شناسه ناشر</a></li>
                </ul>
                <div class="update-publisher-form">
                    <?php echo CHtml::label('شناسه شما: ', '');?>
                    <?php echo $detailsModel->publisher_id;?>
                    <p class="description">این شناسه دیگر قابل تغییر نیست.</p>
                </div>
            </div>
        <?php endif;?>
    </div>
</div>