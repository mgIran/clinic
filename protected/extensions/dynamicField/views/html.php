<?php
/* @var $model CActiveRecord */
/* @var $attributeName */
/* @var $attributeValue array */
/* @var $max string */
/* @var $inputType string */
/* @var $htmlOptions array */
/* @var $id string */

if(isset($htmlOptions['class']))
    $htmlOptions['class'].=' dynamic-field';
else
    $htmlOptions['class']='dynamic-field';
?>
<?php if($model->isNewRecord):?>
    <div id="<?php echo $id;?>" class="dynamic-field-container" data-name="<?php echo get_class($model).'['.$attributeName.']';?>" data-max="<?php echo $max;?>">
        <div class="input-container">
            <?php echo CHtml::$inputType(get_class($model).'['.$attributeName.'][0]', '', $htmlOptions);?>
        </div>
        <a href="#" class="add-dynamic-field"><i class="icon icon-plus"></i></a>
        <a href="#" class="remove-dynamic-field"><i class="icon icon-trash"></i></a>
    </div>
<?php else:?>
    <div id="<?php echo $id;?>" class="dynamic-field-container" data-name="<?php echo get_class($model).'['.$attributeName.']';?>" data-max="<?php echo $max;?>">
        <div class="input-container">
            <?php if(empty($attributeValue)):?>
                <?php echo CHtml::$inputType(get_class($model).'['.$attributeName.'][0]', '', $htmlOptions);?>
            <?php else:?>
                <?php foreach($attributeValue as $key=>$value):?>
                    <?php echo CHtml::$inputType(get_class($model).'['.$attributeName.']['.$key.']', $value, $htmlOptions);?>
                <?php endforeach;?>
            <?php endif;?>
        </div>
        <a href="#" class="add-dynamic-field"><i class="icon icon-plus"></i></a>
        <a href="#" class="remove-dynamic-field"><i class="icon icon-trash"></i></a>
    </div>
<?php endif;?>