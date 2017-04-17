<?php
/* @var $this PanelController */
/* @var $step String */
/* @var $data Array */
?>

<div class="white-form row">
    <h3>ناشر کتاب شوید</h3>
    <p class="description">جهت نشر الکترونیک کتاب های خود در کتابیک به عنوان ناشر ثبت نام کنید.</p>

    <div class="steps-container">
        <ul class="steps">
            <li class="col-md-4<?php if($step=='agreement'):?> active<?php endif;?>">
                <span>توافق نامه</span>
            </li>
            <li class="col-md-4<?php if($step=='profile'):?> active<?php endif;?>">
                <span>اطلاعات قرارداد</span>
            </li>
            <li class="col-md-4<?php if($step=='finish'):?> active<?php endif;?>">
                <span>اتمام</span>
            </li>
        </ul>
        <div class="step-content">
            <?php switch($step){
                case 'agreement':
                    $this->renderPartial('_agreement', array(
                        'text'=>$data['agreementText']['summary']
                    ));
                    break;
                case 'profile':
                    $this->renderPartial('_profile', array(
                        'model'=>$data['detailsModel'],
                        'nationalCardImage'=>$data['nationalCardImage'],
                        'registrationCertificateImage'=>$data['registrationCertificateImage'],
                    ));
                    break;
                case 'finish':
                    $this->renderPartial('_finish', array(
                        'model'=>$data['userDetails'],
                    ));
                    break;
            }?>
        </div>
    </div>
</div>