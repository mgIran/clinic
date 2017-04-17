<?php
/**
 * Created by PhpStorm.
 * User: App-PC
 * Date: 12/10/2015
 * Time: 10:04 PM
 */

class dropDown extends CWidget
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
     * @var string The name of the dropDown field
     */
    public $name = false;
    /**
     * @var string The label of the dropDown field
     */
    public $label = false;
    /**
     * @var CModel The model for the dropDown field
     */
    public $model = false;
    /**
     * @var string The attribute of the model
     */
    public $attribute = false;
    /**
     * @var array of select data
     */
    public $data = array();
    /**
     * @var string,int selected default data array index
     */
    public $selected = false;
    /**
     * @var string custom caret icon
     */
    public $caret = false;
    /**
     * @var bool|string add empty option in drop down list
     */
    public $emptyOpt = true;
    /**
     * @var bool toogle list
     */
    public $autoToggle = true;
    /**
     * @var array html tag options
     */
    public $onclickAjax = array();
    /**
     * @var array html tag options
     */
    public $onchange = false;
    /**
     * @var array html tag options
     */
    public $containerClass = false;

    /**
     * init widget
     */
    public function init()
    {
        Yii::app()->clientScript->registerCoreScript( 'jquery' );
        $this->_scripts = array(
            'css'.DIRECTORY_SEPARATOR.'style.css'
        );
        return parent::init();
    }

    /**
     * the appropriate Javascripts
     */
    protected function registerClientScript()
    {
        $cs = Yii::app()->clientScript;
        foreach ( $this->_scripts as $script ) {
            $file = Yii::getPathOfAlias( 'ext.dropDown.assets' ) . DIRECTORY_SEPARATOR . $script;
            $type = explode( DIRECTORY_SEPARATOR, $script );
            if ( $type[ 0 ] === 'css' )
                $cs->registerCssFile( Yii::app()->getAssetManager()->publish( $file ) );
            else if ( $type[ 0 ] === 'js' )
                $cs->registerScriptFile( Yii::app()->getAssetManager()->publish( $file ) );
        }
        // assign hidden field name
        if ( $this->model && $this->attribute ) {
            $this->name = CHtml::activeName( $this->model, $this->attribute );
        } else if ( $this->model && !$this->attribute && $this->name )
            $this->name = CHtml::activeName( $this->model, $this->name );
        else if ( !$this->model && $this->attribute )
            $this->name = $this->attribute;

        $script = '';

        if($this->autoToggle){
            $script .= '$target.closest(".btn-group").children( ".dropdown-toggle" ).dropdown( "toggle" );';
        }

        if ( isset( $this->onclickAjax[ 'url' ] ) && !empty( $this->onclickAjax[ 'url' ] ) &&
            isset( $this->onclickAjax[ 'type' ] ) && !empty( $this->onclickAjax[ 'type' ] ) &&
            isset( $this->onclickAjax[ 'dataType' ] ) && !empty( $this->onclickAjax[ 'dataType' ] ) &&
            isset( $this->onclickAjax[ 'success' ] )
        ) {
            $this->onclickAjax[ 'success' ] = str_ireplace('{id}','$target.data("id")',$this->onclickAjax[ 'success' ]);
            $this->onclickAjax[ 'success' ] = str_ireplace('{toggle}','$target.closest(".btn-group").children( ".dropdown-toggle" ).dropdown( "toggle" )',$this->onclickAjax[ 'success' ]);
            $ajaxFunc = '
            $.ajax({
                    "url" : "' . $this->onclickAjax[ 'url' ] . '",
                    "type" : "' . $this->onclickAjax[ 'type' ] . '",
                    "dataType" : "' . $this->onclickAjax[ 'dataType' ] . '",
                    "data" : {id : $target.data("id")},
                    "success" : function(data){
                        ' . $this->onclickAjax[ 'success' ] . '
                    }
                })
            ';

            if(isset( $this->onclickAjax[ 'condition' ] ) && !empty( $this->onclickAjax[ 'condition' ] ))
            {
                $this->onclickAjax[ 'condition' ] = str_ireplace('{id}','$target.data("id")',$this->onclickAjax[ 'condition' ]);
                $this->onclickAjax[ 'condition' ] = str_ireplace('{ajax}',$ajaxFunc,$this->onclickAjax[ 'condition' ]);
                $this->onclickAjax[ 'condition' ] = str_ireplace('{toggle}','$target.closest(".btn-group").children( ".dropdown-toggle" ).dropdown( "toggle" )',$this->onclickAjax[ 'condition' ]);
                $script.= $this->onclickAjax[ 'condition' ];
            }else
                $script.= $ajaxFunc;
        }

        if($this->onchange)
        {
            $this->onchange = str_ireplace('{toggle}','$target.closest(".btn-group").children( ".dropdown-toggle" ).dropdown( "toggle" )',$this->onchange);
            $this->onchange = str_ireplace('{id}','$target.data("id")',$this->onchange);
            $script.= $this->onchange;
        }

        $cs->registerScript( 'dropDown-' . $this->id, '
        $( "body").on( "click", "#' . $this->id . '.dropdown-menu li", function( event ) {
            var $target = $( event.currentTarget );
            $target.closest(".btn-group")
                .find("[data-bind=\'label\']").text( $target.text() )
                .end();
            $target.closest(".btn-group")
                .find("#' . $this->id . '-hidden").val($target.data("id"));
            ' . $script . '
                return false;
        });'
        );
    }

    public function run()
    {
        $this->registerClientScript();
        $this->render('_dropDown',array(
            'id' => $this->id,
            'name' => $this->name,
            'label' => $this->label,
            'data' => $this->data,
            'selected' => $this->selected,
            'caret' => $this->caret,
            'emptyOpt' => $this->emptyOpt,
            'containerClass' => $this->containerClass,
        ));
    }
}