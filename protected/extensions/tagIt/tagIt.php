<?php
/**
 * Created by PhpStorm.
 * User: Yusef-PC
 * Date: 12/10/2015
 * Time: 10:04 PM
 */

class tagIt extends CWidget
{
    /**
     * @var array of scripts and styles
     */
    private $_scripts;
    /**
     * @var string DropZone id
     */
    public $id = false;
    /**
     * @var string The name of the field
     */
    public $name = false;
    /**
     * @var CModel The model for the field
     */
    public $model = false;
    /**
     * @var string The attribute of the model
     */
    public $attribute = false;

    /**
     * @var array Existing list items will be pre-added to the tags
     */
    public $data = array();

    /**
     * @var string type of available Tags
     *  Array Or Json
     */
    public $suggestType = 'array';

    /**
     * @var string suggest json get url
     */
    public $suggestUrl = false;

    /**
     * @var array of suggest data
     */
    public $availableTags = array();

    /**
     * @var string placeholder
     */
    public $placeholder = 'تایپ کنید ...';

    /**
     * @var string autoComplete minLength
     */
    public $minLength = 2;

    /**
     * init widget
     */
    public function init()
    {
        if(!$this->id)
            $this->id = rand(0, 100);

        Yii::app()->clientScript->registerCoreScript('jquery');
        $this->_scripts = array(
            'css'.DIRECTORY_SEPARATOR.'jquery.tagit.css',
            'css'.DIRECTORY_SEPARATOR.'tagit.ui-zendesk.css',
            'js'.DIRECTORY_SEPARATOR.'tag-it.min.js'
        );
        return parent::init();
    }

    /**
     * the appropriate Javascripts
     */
    protected function registerClientScript()
    {
        Yii::app()->clientScript->registerCoreScript('jquery');
        Yii::app()->clientScript->registerCoreScript('jquery.ui');
        $cs = Yii::app()->clientScript;
        foreach($this->_scripts as $script) {
            $file = Yii::getPathOfAlias('ext.tagIt.assets').DIRECTORY_SEPARATOR.$script;
            $type = explode(DIRECTORY_SEPARATOR, $script);
            if($type[0] === 'css')
                $cs->registerCssFile(Yii::app()->getAssetManager()->publish($file));
            else if($type[0] === 'js')
                $cs->registerScriptFile(Yii::app()->getAssetManager()->publish($file));
        }
        // assign hidden field name
        if($this->model && $this->attribute) {
            $this->name = CHtml::activeName($this->model, $this->attribute);
        } else if($this->model && !$this->attribute && $this->name)
            $this->name = CHtml::activeName($this->model, $this->name);
        else if(!$this->model && $this->attribute)
            $this->name = $this->attribute;

        $script = array(
            'allowSpaces' => true,
            'removeConfirmation' => true,
            'autocomplete' => array(
                'delay'=> 0,
                'minLength'=> $this->minLength
            ),
            'placeholderText' => $this->placeholder
        );
        if($this->suggestType && strtolower($this->suggestType) == 'json' && $this->suggestUrl && !empty($this->suggestUrl)) {
            $script['tagSource'] = 'js:function( request, response ) {
                var assignedTags = $("#tagIt-'.$this->id.'").tagit("assignedTags");
                $.ajax({
                    url: "'.$this->suggestUrl.'",
                    data: { term:request.term ,currentTags : JSON.stringify(assignedTags)},
                    dataType: "json",
                    success: function( data ) {
                        response( $.map( data, function( item ) {
                            return {
                                label: item.text,
                                value: item.text
                            }
                        }));
                    }
                });
            }
            ';
        } else if($this->suggestType && strtolower($this->suggestType) == 'array' && $this->availableTags) {
            if(is_array($this->availableTags))
                $script['availableTags'] = $this->availableTags;
        }
        $cs->registerScript('tagIt-'.$this->id, '
            $("#tagIt-'.$this->id.'").tagit('.CJavaScript::encode($script).');
        ');
    }

    public function run()
    {
        $this->registerClientScript();
        echo CHtml::textField($this->name,
            $this->data && is_array($this->data) ? implode(',', $this->data) : '',
            array('id' => 'tagIt-'.$this->id)
        );
    }
}