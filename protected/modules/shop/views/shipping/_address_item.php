<?php /* @var $data ShopAddresses */ ?>
<div class="address-item<?= Yii::app()->user->getState('delivery_address') == $data->id?" selected":'' ?>">
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 radio-container">
        <div class="radio-control">
            <?php echo CHtml::radioButton("DeliveryAddress", Yii::app()->user->getState('delivery_address') == $data->id?true:false, array("id"=>"address-".$data->id, "value"=>$data->id));?>
            <?php echo CHtml::label("", "address-".$data->id);?>
        </div>
    </div>
    <div class="col-lg-11 col-md-11 col-sm-11 col-xs-12 info-container">
        <div class="pull-right">
            <h5 class="name"><?php echo CHtml::encode($data->transferee);?></h5>
            <div class="address">
                <span>استان: <?php echo CHtml::encode($data->town->name);?></span>
                <span>شهر: <?php echo CHtml::encode($data->place->name);?></span>
                <div><?php echo CHtml::encode($data->postal_address);?>- کدپستی: <?php echo CHtml::encode($data->postal_code);?></div>
            </div>
            <div class="phone">
                <div><span>شماره تماس اضطراری:</span><?php echo CHtml::encode($data->emergency_tel);?></div>
                <div><span>شماره تماس ثابت:</span><?php echo CHtml::encode($data->landline_tel);?></div>
            </div>
        </div>
        <div class="links pull-left">
            <?php echo CHtml::link('',array("/shop/addresses/update", "id"=>$data->id), array(
                'class' => 'edit-link'
            )); ?>
            <?php echo CHtml::link('',array("/shop/addresses/remove"), array(
                'class' => 'remove-link',
                'data-id' => $data->id
            )); ?>
        </div>
    </div>
</div>
