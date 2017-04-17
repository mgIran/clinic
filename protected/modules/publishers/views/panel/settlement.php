<?php
/* @var $this PublishersPanelController */
/* @var $form CActiveForm */
/* @var $userDetailsModel UserDetails */
/* @var $helpText string */
/* @var $settlementHistory CActiveDataProvider */
/* @var $formDisabled boolean */
Yii::app()->clientScript->registerScript('account-type','
    if($("#UserDetails_account_type").val() == "legal")
        $("#account_owner_family > label").text("نوع حقوقی");
    else
        $("#account_owner_family > label").text("نام خانوادگی صاحب حساب");
    $("body").on("change", "#UserDetails_account_type", function () {
        if($(this).val() == "legal")
            $("#account_owner_family > label").text("نوع حقوقی");
        else
            $("#account_owner_family > label").text("نام خانوادگی صاحب حساب");
    });
', CClientScript::POS_READY);
?>
<div class="white-form">

    <?php
    $message=array();
    if(!$userDetailsModel->validateAccountingInformation()){
        $message['type'] = 'danger';
        $message['message'] = 'اطلاعات بانکی شما به منظور انجام تسویه حساب ناقص است و تا زمانی که این اطلاعات تکمیل نشود تسویه حساب برای شما انجام نمی شود.';
    }elseif($userDetailsModel->financial_info_status == 'pending'){
        $message['type'] = 'warning';
        $message['message'] = 'اطلاعات حساب بانکی شما باید توسط تیم مدیریت تایید گردد.';
    }elseif($userDetailsModel->financial_info_status == 'refused'){
        $message['type'] = 'danger';
        $message['message'] = 'اطلاعات حساب بانکی شما مورد تایید نمی باشد! لطفا اطلاعات حساب بانکی خود را تغییر دهید.';
    }
    if(!empty($message))
        $this->renderPartial('users.views.public._message' ,array(
            'data' => $message
        ));
    ?>
    <h3>تسویه حساب</h3>
    <p class="description">می توانید ماهیانه با کتابیک تسویه حساب کنید.</p>

    <?php $this->renderPartial('//partial-views/_flashMessage');?>

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'user-details-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
        'clientOptions' => array(
            'validateOnSubmit' => true
        ),
        'htmlOptions'=>array(
            'class'=>'form'
        )
    ));?>

    <label>مبلغ قابل تسویه این ماه :</label><span><?php echo Controller::parseNumbers(number_format($userDetailsModel->getSettlementAmount(), 0));?> تومان</span>
    <div class="form-row">
<!--        --><?php //echo $form->checkBox($userDetailsModel, 'monthly_settlement', array(
//            'onchange'=>"$('#UserDetails_iban').prop('disabled', function(i, v){return !v;});",
//            'disabled'=>$formDisabled,
//        ));?>
        <?php echo $form->label($userDetailsModel, 'monthly_settlement', array(
            'style'=>'display:inline-block;margin:15px 0;',
        ));?>
        <span>: مبلغ قابل تسویه حداقل <?= Controller::parseNumbers(number_format($min_credit)) ?> تومان است که 20اُم هر ماه به شماره شبای زیر واریز می شود.</span>
    </div>
    <div class="form-row">
        <h4>اطلاعات حساب بانکی</h4>
        <div class="iban-container">
            <?php echo $form->labelEx($userDetailsModel, 'account_type');?>
            <?php echo $form->dropDownList($userDetailsModel, 'account_type', $userDetailsModel->typeLabels, array(
                'class'=>'form-control'
            ));?>
            <?php echo $form->error($userDetailsModel, 'account_type');?>
        </div>
        <div class="iban-container">
            <?php echo $form->labelEx($userDetailsModel, 'account_owner_name');?>
            <?php echo $form->textField($userDetailsModel, 'account_owner_name', array(
                'class'=>'form-control',
                'maxLength' => 50,
            ));?>
            <?php echo $form->error($userDetailsModel, 'account_owner_name');?>
        </div>
        <div class="iban-container" id="account_owner_family">
            <?php echo $form->labelEx($userDetailsModel, 'account_owner_family');?>
            <?php echo $form->textField($userDetailsModel, 'account_owner_family', array(
                'class'=>'form-control',
                'maxLength' => 50,
            ));?>
            <?php echo $form->error($userDetailsModel, 'account_owner_family');?>
        </div>
        <div class="iban-container">
            <?php echo $form->labelEx($userDetailsModel, 'account_number');?>
            <?php echo $form->textField($userDetailsModel, 'account_number', array(
                'class'=>'form-control',
                'maxLength' => 50,
            ));?>
            <?php echo $form->error($userDetailsModel, 'account_number');?>
        </div>
        <div class="iban-container">
            <?php echo $form->labelEx($userDetailsModel, 'bank_name');?>
            <?php echo $form->textField($userDetailsModel, 'bank_name', array(
                'class'=>'form-control',
                'maxLength' => 50,
            ));?>
            <?php echo $form->error($userDetailsModel, 'bank_name');?>
        </div>
        <div class="iban-container">
            <?php echo $form->label($userDetailsModel, 'iban');?>
            <div class="input-group">
                <?php echo $form->textField($userDetailsModel, 'iban', array(
                    'class'=>'form-control',
                    'maxLength' => 24,
                    'aria-describedby'=>'basic-addon1',
                    'style'=>'direction: ltr;',
                    'placeholder'=>'100020003000400050006047:مثال',
                ));?>
                <span id="basic-addon1" class="input-group-addon">IR</span>
            </div>
            <small class="description">شماره شبا بدون IR و هیچ گونه فاصله و خط تیره باید ثبت شود</small>
            <?php echo $form->error($userDetailsModel, 'iban');?>
        </div>
        <div class="buttons overflow-hidden">
            <?php echo CHtml::submitButton('ثبت', array(
                'class'=>'btn btn-default pull-right',
                'id'=>'settlement-button',
                'disabled'=>$formDisabled,
            ));?>
        </div>
    </div>
    <?php $this->endWidget();?>

    <div class="settlement-history">
        <h3>تاریخچه تسویه حساب</h3>
        <p class="description">تسویه حساب هایی که تا به امروز انجام شده است.</p>
        <table class="table">
            <thead>
                <tr>
                    <td>نوع حساب</td>
                    <td>نام صاحب حساب</td>
                    <td>نام خانوادگی صاحب حساب</td>
                    <td>شماره حساب</td>
                    <td>نام بانک</td>
                    <td>شماره شبا</td>
                    <td>کد رهگیری</td>
                    <td>تاریخ</td>
                    <td>مبلغ</td>
                </tr>
            </thead>
            <tbody>
                <?php $this->widget('zii.widgets.CListView', array(
                    'dataProvider'=>$settlementHistory,
                    'itemView'=>'_settlement_list',
                    'template'=>'{items}'
                ));?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6">جمع کل درآمد</td>
                    <td colspan="2"><?= Controller::parseNumbers(number_format($this::$sumSettlement)).' تومان' ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>