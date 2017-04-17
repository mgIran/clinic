<?php

class CKEditor extends CWidget
{
    private $publishedAssetsPath;

    public $model;
    public $attribute;
    public $htmlOptions=array();
    public $config='default';
    public $id=false;


    public function init()
    {
        if(!$this->id)
            $this->id = rand(0,100);
    }

    public function run()
    {
        //if(Yii::getPathOfAlias('CKEditor') === false) Yii::setPathOfAlias('CKEditor', realpath(dirname(__FILE__) . '/..'));

        //Yii::app()->clientScript->registerScriptFile($this->getAssetsUrl().'/ckeditor.js', CClientScript::POS_END);
        //Yii::app()->clientScript->registerScriptFile(Yii::getPathOfAlias("webroot").'/js/ckeditor.js', CClientScript::POS_END);
        echo '<script type="application/javascript" src="'.Yii::app()->createAbsoluteUrl('/js/ckeditor/ckeditor.js').'"></script>';
        $this->render('view', array(
            'id' => $this->id,
            'model'=>$this->model,
            'attribute'=>$this->attribute,
            'htmlOptions'=>$this->htmlOptions,
            'config'=>$this->makeConfig(),
        ));
    }

    public function getAssetsUrl()
    {
        if(!isset($this->publishedAssetsPath))
        {
            $assetsSourcePath=Yii::getPathOfAlias('ext.ckeditor.assets');
            $publishedAssetsPath=Yii::app()->assetManager->publish($assetsSourcePath, false, -1);
            return $this->publishedAssetsPath=$publishedAssetsPath;
        }
        else return $this->publishedAssetsPath;
    }

    protected function makeConfig()
    {
        if($this->config=='default')
            return 'custom_config.js';
        elseif($this->config == 'basic')
            return 'basic_config.js';
        /*elseif(is_array($this->config))
        {
            $config='';
            foreach($this->config as $key=>$value)
                $config.=$key.": '".$value."',\n";
            return $config;
        }*/
        else
            throw new Exception('CKEditor config is not set correctly.');
    }
}