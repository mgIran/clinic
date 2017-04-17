<?php
echo CHtml::activeTextArea($model, $attribute, $htmlOptions);

Yii::app()->clientScript->registerScript("CKEditor-{$id}","
    CKEDITOR.replace( '".get_class($model).'_'.$attribute."', {
        customConfig: '".$config."'
    });
");