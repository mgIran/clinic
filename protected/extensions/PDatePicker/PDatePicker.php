<?php
class PDatePicker extends CInputWidget
{
    protected $publishedAssetsPath;
    public $id;
    public $value = false;
    public $options;
    public $htmlOptions;

    public function init()
    {
        $active = false;
        if($this->model && $this->attribute)
            $active = true;
        if(Yii::getPathOfAlias('PDatePicker') === false) Yii::setPathOfAlias('PDatePicker', realpath(dirname(__FILE__) . '/..'));
        $cs = Yii::app()->clientScript;
        $cs->registerCssFile($this->getAssetsUrl().'/css/persian-datepicker-0.4.5.min.css');
        $cs->registerScriptFile($this->getAssetsUrl().'/js/persian-date.js');
        $cs->registerScriptFile($this->getAssetsUrl().'/js/persian-datepicker-0.4.5.min.js');

        if(!isset($this->options['altField']))
        {
            $this->options['altField']='#'.$this->id.'_altField';
            $this->options['altFormat']='X';
        }

        $js = "$('#$this->id').persianDatepicker(".CJavaScript::encode($this->options).");";
        $cs->registerScript(__CLASS__ . $this->id, $js, CClientScript::POS_READY);
        echo CHtml::textField($this->id, '', $this->htmlOptions);
        if($active)
        {
            echo CHtml::activeHiddenField($this->model,$this->attribute,array('id'=>$this->id.'_altField'));
            $value = explode('/', JalaliDate::date("Y/m/d/H/i/s", ($this->model->{$this->attribute}?$this->model->{$this->attribute}:time()), false));
            $cs->registerScript(__CLASS__ . $this->id .'-set-values', '
                $("#'.$this->id.'").persianDatepicker("setDate",['.$value[0].','.$value[1].','.$value[2].','.$value[3].','.$value[4].','.$value[5].']);
            ');
        }
        else if($this->value){
            echo CHtml::hiddenField($this->id.'_altField',$this->value);
            $value = explode('/', JalaliDate::date("Y/m/d/H/i/s", $this->value, false));
            $cs->registerScript(__CLASS__ . $this->id .'-set-values', '
                $("#'.$this->id.'").persianDatepicker("setDate",['.$value[0].','.$value[1].','.$value[2].','.$value[3].','.$value[4].','.$value[5].']);
            ');
        }
        else
            echo CHtml::hiddenField($this->id.'_altField');
    }

    public function getAssetsUrl()
    {
        if(!isset($this->publishedAssetsPath))
        {
            $assetsSourcePath = Yii::getPathOfAlias('ext.PDatePicker.assets');

            $publishedAssetsPath = Yii::app()->assetManager->publish($assetsSourcePath, false, -1);

            return $this->publishedAssetsPath = $publishedAssetsPath;
        }
        else return $this->publishedAssetsPath;
    }
}