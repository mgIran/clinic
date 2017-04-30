<?php
class dynamicField extends CInputWidget
{
    public $id;
    public $attributeName = null;
    public $attributeValue = array();
    public $inputType = null;
    public $htmlOptions = array();
    public $max = 10;

    public function init()
    {
        if(is_null($this->attributeName))
            throw new CHttpException( 500, 'attributeName تنظیم نشده است.' );

        if(!is_array($this->attributeValue) and !is_null($this->attributeValue))
            throw new CHttpException( 500, 'attributeValue باید از نوع آرایه باشد.' );

        if(is_null($this->inputType))
            throw new CHttpException( 500, 'inputType تنظیم نشده است.' );

        if(Yii::getPathOfAlias('dynamicField') === false) Yii::setPathOfAlias('dynamicField', realpath(dirname(__FILE__) . '/..'));
        $cs = Yii::app()->clientScript;

        $js = "
            $('body').on('click', '#$this->id .add-dynamic-field', function () {
                var parent = $(this).parents('.dynamic-field-container'),
                    input = document.createElement('input');
                if (parent.find('.dynamic-field').length < parseInt(parent.data('max'))) {
                    input.type = parent.find('.dynamic-field').attr('type');
                    input.name = parent.data('name') + '[' + parent.find('.dynamic-field').length + ']';
                    if(typeof parent.find('.dynamic-field').attr('placeholder') != 'undefined')
                        input.placeholder = parent.find('.dynamic-field').attr('placeholder');
                    input.className = parent.find('.dynamic-field').attr('class');
                    $(parent).find('.input-container').append(input);
                }
                return false;
            });

            $('body').on('click', '#$this->id .remove-dynamic-field', function () {
                var parent = $(this).parents('.dynamic-field-container');
                if (parent.find('.dynamic-field').length > 1)
                    parent.find('.dynamic-field:last').remove();
                return false;
            });
        ";
        $cs->registerScript(__CLASS__ . $this->id, $js, CClientScript::POS_READY);
//        $inputType = $this->inputType;
//        echo CHtml::$inputType($this->id, '', $this->htmlOptions);
        $this->render('html', array(
            'model'=>$this->model,
            'attributeName'=>$this->attributeName,
            'attributeValue'=>$this->attributeValue?$this->attributeValue:array(),
            'max'=>$this->max,
            'inputType'=>$this->inputType,
            'htmlOptions'=>$this->htmlOptions,
            'id'=>$this->id,
        ));
    }
}