<?php
/* @var $this PublishersPanelController */
/* @var $settlementHistory CActiveDataProvider*/
/* @var $settlementRequiredUsers CActiveDataProvider*/
?>

<?php $this->renderPartial('//layouts/_flashMessage');?>
<h3>لیست ناشران جهت تسویه حساب</h3>
<?php
echo CHtml::form('excel');
?>
<!--    <div class="row col-lg-6 col-md-6 col-sm-6 col-md-12">-->
<!--        --><?php //echo CHtml::label('از تاریخ', 'from_date');?>
<!--        --><?php //$this->widget('application.extensions.PDatePicker.PDatePicker', array(
//            'id'=>'from_date',
//            'options'=>array(
//                'format'=>'DD MMMM YYYY'
//            ),
//            'htmlOptions'=>array(
//                'class'=>'form-control'
//            ),
//        ));?>
<!--    </div>-->
<!--    <div class="row col-lg-6 col-md-6 col-sm-6 col-md-12">-->
<!--        --><?php //echo CHtml::label('تا تاریخ', 'to_date');?>
<!--        --><?php //$this->widget('application.extensions.PDatePicker.PDatePicker', array(
//            'id'=>'to_date',
//            'options'=>array(
//                'format'=>'DD MMMM YYYY'
//            ),
//            'htmlOptions'=>array(
//                'class'=>'form-control'
//            ),
//        ));?>
<!--    </div>-->
    <div class="row ">
        <?php echo CHtml::submitButton('دریافت فایل اکسل کامل', array(
            'class'=>'btn btn-success',
            'name'=>'show-chart',
            'id'=>'show-chart',
        ));?>
    </div>
<?php
echo CHtml::endForm();
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'required-settlements-grid',
    'dataProvider'=>$settlementRequiredUsers,
    'itemsCssClass' => 'table',
    'columns'=>array(
        'fa_name'=>array(
            'name'=>'fa_name',
            'value'=>'CHtml::link($data->fa_name, Yii::app()->createUrl("/users/manage/view/".$data->user_id))',
            'type'=>'raw'
        ),
        'iban'=>array(
            'name'=>'iban',
            'value'=>'"IR".$data->iban'
        ),
        'amount'=>array(
            'header'=>'مبلغ قابل تسویه',
            'value'=>'number_format($data->getSettlementAmount(), 0)." تومان"'
        ),
        'settled'=>array(
            'value'=>function($data){
                $form=CHtml::beginForm(Yii::app()->createUrl("/publishers/panel/manageSettlement"), 'post', array('class'=>'settlement-form'));
                $form.=CHtml::openTag('div', array('class'=>'input-group-container'));
                $form.=CHtml::openTag('div', array('class'=>'input-group'));
                $form.=CHtml::textField('iban', '', array('maxlength'=>24,'class'=>'form-control iban-input','placeholder'=>'شماره شبا *','aria-describedby'=>'iban-addon'));
                $form.=CHtml::tag('span', array('class'=>'input-group-addon', 'id'=>'iban-addon'), 'IR');
                $form.=CHtml::closeTag('div');
                $form.=CHtml::closeTag('div');
                $form.=CHtml::textField('token', '', array('class'=>'form-control token','placeholder'=>'کد رهگیری *'));
                $form.=CHtml::textField('amount', '', array('class'=>'form-control','placeholder'=>'مبلغ تسویه(تومان) *'));
                $form.=CHtml::hiddenField('user_id', $data->user_id);
                $form.=CHtml::submitButton('تسویه شد', array('class'=>'btn btn-success'));
                $form.=CHtml::endForm();
                $form.=CHtml::tag('small', array(), 'شماره شبا بدون IR و هیچ گونه فاصله و خط تیره باید ثبت شود');
                return $form;
            },
            'type'=>'raw'
        ),
    ),
));?>
<?php Yii::app()->clientScript->registerCss('this-page','
.settlement-form .form-control{
    width:150px;
    margin-left:3px;
}
.settlement-form .form-control.token{
    width:200px;   
}
');?>


    <h3>تاریخچه تسویه حساب ها</h3>
<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'settlements-grid',
    'dataProvider'=>$settlementHistory,
    'itemsCssClass'=>'table',
    'columns'=>array(
        'date'=>array(
            'name'=>'date',
            'value'=>'JalaliDate::date("d F Y", $data->date)'
        ),
        'amount'=>array(
            'name'=>'amount',
            'value'=>'Controller::parseNumbers(number_format($data->amount))." تومان"'
        ),
    ),
));?>