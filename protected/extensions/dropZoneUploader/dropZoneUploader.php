<?php
/**
 * Created by PhpStorm.
 * User: App-PC
 * Date: 12/10/2015
 * Time: 10:04 PM
 */

class dropZoneUploader extends CWidget
{
    /**
     * @var array of scripts and styles
     */
    private $_scripts;

    /**
     * @var string The URL that handles the file upload
     */
    public $url = null;
    /**
     * @var string The URL that handles the file delete form server
     */
    public $deleteUrl = false;
    /**
     * @var string DropZone id
     */
    public $id = null;
    /**
     * @var string The name of the file field
     */
    public $name = null;
    /**
     * @var CModel The model for the file field
     */
    public $model = false;
    /**
     * @var string The attribute of the model
     */
    public $attribute = false;
    /**
     * @var int Max file size
     */
    public $maxFileSize = 200; // MB
    /**
     * @var int Number of uploads
     */
    public $maxFiles = 2;
    /**
     * @var bool Show or Hide Remove Button
     */
    public $addRemoveLinks = 1;
    /**
     * @var string accepted file types , separate types with comma : "image/* , .zip"
     */
    public $acceptedFiles;
    /**
     * @var string DropZone Box text
     */
    public $dictDefaultMessage = 'بکشید و رها کنید';
    /**
     * @var string invalid file type message
     */
    public $dictInvalidFileType = 'نوع فایل معتبر نمی باشد';
    /**
     * @var string cancel upload button text
     */
    public $dictCancelUpload = '';
    /**
     * @var string remove file button text
     */
    public $dictRemoveFile = '';
    /**
     * @var string Max file Exceeded message
     */
    public $dictMaxFilesExceeded = "تعداد فایل مجاز به اتمام رسیده است";
    /**
     * @var string File too big message
     */
    public $dictFileTooBig = "حجم فایل زیاد است.\n حداکثر حجم مجاز :{{maxFilesize}}";

    /**
     * @var array Of array files attributes that exist on server and we will added to DropZone
     * this array format :
     * array(
     *      array(
     *          'name' => file name on server
     *          'src' => file Url on server
     *          'size' => filesize(file path on server)
     *          'serverName' => file name on server
     *      ),
     * )
     */
    public $serverFiles = false;
    /**
     * @var string The Javascript to be called in case of a successful upload
     */
    public $onSuccess;
    /**
     * @var array html tag options
     */
    public $htmlOptions = array();
    /**
     * @var array data
     */
    public $data = array();

    /**
     * init widget
     */
    public function init()
    {
        if ( $this->url === null ) {
            throw new CHttpException( 500, 'Url تنظیم نشده است.' );
        }

        if ( $this->name === null ) {
            throw new CHttpException( 500, 'Field Name تنظیم نشده است.' );
        }
        if ( $this->id === null ) {
            throw new CHttpException( 500, 'ID تنظیم نشده است.' );
        }else
        {
            $this->id = $this->camelCase($this->id);
        }
        $this->dictDefaultMessage = '<i class="icon icon-cloud-upload icon-4x"></i><span style="display: block;">'.$this->dictDefaultMessage.'</span>';
        Yii::app()->clientScript->registerCoreScript( 'jquery' );
        $this->_scripts = array(
            'js' . DIRECTORY_SEPARATOR . 'dropzone.js', // core DropZone Js File
            'css' . DIRECTORY_SEPARATOR . 'dropzone.css', // core DropZone Css File
            'css' . DIRECTORY_SEPARATOR . 'basic.css', // basic DropZone Css File
        );
        return parent::init();
    }

    /**
     * the appropriate Javascripts
     */
    protected function registerClientScript()
    {
        $cs = Yii::app()->clientScript;
        foreach($this->_scripts as $script) {
            $file = Yii::getPathOfAlias('ext.dropZoneUploader.assets').DIRECTORY_SEPARATOR.$script;
            $type = explode(DIRECTORY_SEPARATOR, $script);
            if($type[0] === 'css')
                $cs->registerCssFile(Yii::app()->getAssetManager()->publish($file));
            else if($type[0] === 'js')
                $cs->registerScriptFile(Yii::app()->getAssetManager()->publish($file));
        }
        // assign hidden field name
        if($this->model && $this->attribute) {
            $this->name = $this->attribute;
            $hiddenFieldName = CHtml::activeName($this->model, $this->attribute);
        } else if($this->model && !$this->attribute && $this->name)
            $hiddenFieldName = CHtml::activeName($this->model, $this->name);
        else if(!$this->model && !$this->attribute && $this->name)
            $hiddenFieldName = "{$this->name}";
        // get files from server and added to drop zone
        if($this->serverFiles) {
            $data = CJSON::encode($this->serverFiles);

            if($this->maxFiles > 1)
            {
                $filesAddScript = '
                var data = '.$data.';
                $.each(data, function(key,value){
                    var mockFile = { name: value.name, size: value.size ,serverName : value.name ,accepted : true};
                    if ((thisDropzone.options.maxFiles != null) && thisDropzone.getAcceptedFiles().length < thisDropzone.options.maxFiles) {
                        thisDropzone.options.addedfile.call(thisDropzone, mockFile);
                        if($.inArray(value.name.split(\'.\').pop(), extArr) > -1)
                        {
                            thisDropzone.createThumbnailFromUrl(mockFile , value.src);
                            thisDropzone.options.thumbnail.call(thisDropzone, mockFile, value.src);
                        }
                        thisDropzone.options.complete.call(thisDropzone, mockFile);
                        thisDropzone.files.push(mockFile);
                        createHiddenInput'.self::camelCase($this->id).'(mockFile ,"'.$hiddenFieldName.'", value.name);
                    }
                });
                ';
            }else
            {
                $filesAddScript = '
                var value = '.$data.';
                var mockFile = { name: value.name, size: value.size ,serverName : value.name ,accepted : true};
                if ((thisDropzone.options.maxFiles != null) && thisDropzone.getAcceptedFiles().length < thisDropzone.options.maxFiles) {
                    thisDropzone.options.addedfile.call(thisDropzone, mockFile);
                    if($.inArray(value.name.split(\'.\').pop(), extArr) > -1)
                    {
                        thisDropzone.createThumbnailFromUrl(mockFile , value.src);
                        thisDropzone.options.thumbnail.call(thisDropzone, mockFile, value.src);
                    }
                    thisDropzone.options.complete.call(thisDropzone, mockFile);
                    thisDropzone.files.push(mockFile);
                    createHiddenInput'.self::camelCase($this->id).'(mockFile ,"'.$hiddenFieldName.'", value.name);
                }
                ';
            }

            $addedFiles = '
                var extArr = ["jpg","jpeg","png","bmp","gif"];
                var thisDropzone = this;
                '.$filesAddScript.'
                ';
        } else $addedFiles = '';

        if($this->maxFiles === 1) {
            $error = 'this.on("error", function(file, message) {
                alert(message);
                this.removeFile(file);
            });';
        } else $error = '';

        $this->onSuccess = str_ireplace('{serverName}', 'file.serverName', $this->onSuccess);

        if(is_string($this->data) and strpos("js:", $this->data) == 0) {
            //            $this->data = str_ireplace("js:","",$this->data);
            //            $this->data = eval();
            //            $data = "'".$this->data."'";
        } elseif(is_array($this->data))
            $data = "'".CJSON::encode($this->data)."'";
        $deleteFunc = '';
        if($this->deleteUrl)
            $deleteFunc = '
                jQuery.ajax({
                    url:"'.$this->deleteUrl.'",
                    data:{fileName : file.serverName ,data:'.$data.'},
                    type : "POST",
                    dataType : "json"
                });';
        $options = array(
            'url' => $this->url,
            'paramName' => $this->name,
            'maxFilesize' => $this->maxFileSize, // MB
            'parallelUploads' => 1,
            'uploadMultiple' => 0,
            'maxFiles' => ($this->maxFiles ? $this->maxFiles : 'null'),
            'addRemoveLinks' => $this->addRemoveLinks,
            'dictDefaultMessage' => $this->dictDefaultMessage,
            'dictInvalidFileType' => $this->dictInvalidFileType,
            'dictFileTooBig' => $this->dictFileTooBig,
            'dictCancelUpload' => $this->dictCancelUpload,
            'dictRemoveFile' => $this->dictRemoveFile,
            'dictMaxFilesExceeded' => $this->dictMaxFilesExceeded,
            'acceptedFiles' => $this->acceptedFiles,
            'accept' => 'js: function(file, done){done();}',
            'init' => 'js: function() {
                    this.on("removedfile", function(file) {
                        '.$deleteFunc.'
                    });
                    this.on("sending", function(file, xhr, formData) {
                        formData.append("data", '.$data.');
                    });
                    this.on("success", function(file,res) {
                        '.$this->onSuccess.'
                        if(file.serverName)
                            createHiddenInput'.self::camelCase($this->id).'(file ,"'.$hiddenFieldName.'",file.serverName);
                    });
                    '.$addedFiles.'
                    '.$error.'
                }',
        );
        $options = CJavaScript::encode($options);
        $cs->registerScript('DropZone'.$this->id, "Dropzone.options.{$this->id} = {$options};var fileList = [];
            function createHiddenInput".self::camelCase($this->id)."(file ,name , value){
                file._hiddenField = Dropzone.createElement(\"<input type='hidden' name='\"+name+\"".($this->maxFiles > 1 ? '[]' : '')."' value='\"+value+\"'>\");
                file.previewElement.appendChild(file._hiddenField);
            }
        ");
    }

    /**
     * @param $str
     * @param array $noStrip
     * @return mixed|string Convert any string to camelCase format
     */
    public static function camelCase($str, array $noStrip = [])
    {
        // non-alpha and non-numeric characters become spaces
        $str = preg_replace('/[^a-z0-9' . implode("", $noStrip) . ']+/i', ' ', $str);
        $str = trim($str);
        // uppercase the first character of each word
        $str = ucwords($str);
        $str = str_replace(" ", "", $str);
        $str = lcfirst($str);

        return $str;
    }

    /**
     * create a div to make the div into the file upload area
     */
    public function run()
    {
        $class = $this->maxFiles == 1 ? 'single':'';
        $this->registerClientScript();
        echo CHtml::openTag('div', CMap::mergeArray(array('class' => 'dropzone '.$class, 'id' => $this->id ),$this->htmlOptions));
        echo CHtml::closeTag('div');
    }
}