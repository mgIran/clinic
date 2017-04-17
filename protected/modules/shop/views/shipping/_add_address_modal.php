<?php
/* @var $form CActiveForm */
/* @var $model ShopAddresses */
if(!isset($model))
    $model=new ShopAddresses();
?>
<div id="add-address-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="close-icon"></i></button>
                <h4 class="modal-title">افزودن آدرس جدید</small></h4>
            </div>
            <div class="modal-body" style="overflow: visible !important;">
                <?php $this->renderPartial('shop.views.shipping._loading')?>
                <?php $form=$this->beginWidget('CActiveForm', array(
                    'id'=>'address-form',
                    'action'=>$model->isNewRecord?array('/shop/addresses/add'):array('/shop/addresses/update', 'id'=>$model->id),
                    'enableAjaxValidation'=>false,
                    'enableClientValidation'=>true,
                    'clientOptions'=>array(
                        'validateOnSubmit'=>true,
                    )
                ));
                echo CHtml::hiddenField('ajax','address-form');
                ?>

                <div class="form-group">
                    <?php echo $form->textField($model,'transferee' ,array(
                        'placeholder' => $model->getAttributeLabel("transferee"),
                        'class' => 'text-field'
                    ));
                    echo $form->error($model,'transferee'); ?>
                </div>

                <div class="form-group row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 right-control">
                        <?php $this->widget('ext.dropDown.dropDown', array(
                            'id' => 'towns',
                            'model'=>$model,
                            'attribute' => 'town_id',
                            'label' => 'استان مورد نظر را انتخاب کنید',
                            'emptyOpt' => false,
                            'data' => CHtml::listData(Towns::model()->findAll() , 'id' ,'name'),
                            'caret' => '<i class="icon icon-chevron-down"></i>',
                            'selected' => $model->town_id?$model->town_id:false,
                            'containerClass'=>'dropdown',
                            'onclickAjax' => array(
                                'url' => Yii::app()->createUrl('/places/cities/getCities'),
                                'type' => 'GET',
                                'dataType' => 'html',
                                'success' => '
                                    $("#places-label").html("شهرستان مورد نظر را انتخاب کنید");
                                    $("#places").html(data);
                                    $("#places-hidden").val("");'
                            )
                        )); ?>
                        <?php echo $form->error($model,'town_id'); ?>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 left-control">
                        <?php $this->widget('ext.dropDown.dropDown', array(
                            'id' => 'places',
                            'model'=>$model,
                            'attribute' => 'place_id',
                            'label' => 'شهرستان مورد نظر را انتخاب کنید',
                            'selected' => $model->place_id?$model->place_id:false,
                            'containerClass'=>'dropdown',
                            'data' => $model->town_id?CHtml::listData(Places::model()->findAll('town_id = :id' ,array(':id'=>$model->town_id)) , 'id' ,'name'):null,
                            'caret' => '<i class="icon icon-chevron-down"></i>',
                        )); ?>
                        <?php echo $form->error($model,'place_id'); ?>
                    </div>
                </div>

                <div class="form-group">
                    <?php echo $form->textField($model,'district' ,array(
                        'placeholder' => $model->getAttributeLabel("district"),
                        'class' => 'text-field'
                    ));
                    echo $form->error($model,'district'); ?>
                </div>

                <div class="form-group row">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 right-control">
                        <?php echo $form->textField($model,'postal_address' ,array(
                            'placeholder' => $model->getAttributeLabel("postal_address"),
                            'class' => 'text-field'
                        ));?>
                        <?php echo $form->error($model,'postal_address'); ?>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 left-control">
                        <?php echo $form->textField($model,'postal_code' ,array(
                            'placeholder' => $model->getAttributeLabel("postal_code"),
                            'class' => 'text-field'
                        ));?>
                        <?php echo $form->error($model,'postal_code'); ?>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 right-control">
                        <?php echo $form->textField($model,'emergency_tel' ,array(
                            'placeholder' => $model->getAttributeLabel("emergency_tel"),
                            'class' => 'text-field'
                        ));?>
                        <?php echo $form->error($model,'emergency_tel'); ?>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 left-control">
                        <?php echo $form->textField($model,'landline_tel' ,array(
                            'placeholder' => $model->getAttributeLabel("landline_tel"),
                            'class' => 'text-field'
                        ));?>
                        <?php echo $form->error($model,'landline_tel'); ?>
                    </div>
                </div>

                <div class="form-group"><p id="summary-errors" class="text-center"></p></div>

                <div class="buttons overflow-hidden">
                    <?= CHtml::submitButton('ثبت',array('class'=>"btn-blue pull-left")); ?>
                </div>

                <? $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>