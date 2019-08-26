<?php
class AjaxUploadAction extends CAction
{

    const STORE_FIELD_MODE = 'field';
    const STORE_RECORD_MODE = 'record';

    /**
     * @var string upload folder Dir
     */
    public $uploadDir = '/uploads/temp';
    /**
     * @var string attribute name
     */
    public $attribute;
    /**
     * @var string rename random|none
     */
    public $rename;
    /**
     * @var string rename string length
     */
    public $randomLength = 5;
    /**
     * @var array of valid options
     */
    public $validateOptions = array();

    // The following attributes are for storage in a database.
    /**
     * @var boolean insert into db or not (true|false)
     */
    public $insert;
    /**
     * @var string module name
     */
    public $module;
    /**
     * @var string model class name
     */
    public $modelName;
    /**
     * @var array of search attribute names with values for STORE_FIELD_MODE
     */
    public $findAttributes = array();
    /**
     * @var array of params names with values for insert new record or update old record into database
     */
    public $insertAttributes = array();
    /**
     * @var string scenario for save
     */
    public $scenario;
    /**
     * @var string saving in Database mode for this file field|record
     */
    public $storeMode;
    /**
     * @var array of after save actions (resize)
     */
    public $afterSaveActions = array();

    private function init()
    {
        if (!$this->attribute)
            throw new CException('{attribute} attribute is not specified.', 500);

        if ($this->module)
            Yii::import($this->module.'.models.*');
        if ($this->insert && !$this->modelName)
            throw new CException('{model} model is not specified.', 500);
        if ($this->insert && !$this->storeMode)
            throw new CException('{storeMode} store mode is not specified. ("field" or "record")', 500);
        if ($this->insert && $this->storeMode === self::STORE_FIELD_MODE && !$this->findAttributes)
            throw new CException('{findAttributes} find attributes is not specified.', 500);
        // json decode data index of $_POST array
        if(isset($_POST['data']))
            $_POST = CJSON::decode($_POST['data']);

        if ($this->insert && $this->storeMode === self::STORE_FIELD_MODE && $this->findAttributes)
            $this->findAttributes = $this->evaluateExpression($this->findAttributes);
        if ($this->insert && $this->storeMode === self::STORE_FIELD_MODE && $this->insertAttributes)
            $this->insertAttributes = $this->evaluateExpression($this->insertAttributes);
    }

    public function run()
    {
        $this->init();
        if (Yii::app()->request->isAjaxRequest) {
            $validFlag = true;
            $uploadDir = Yii::getPathOfAlias("webroot").$this->uploadDir;
            if (!is_dir($uploadDir))
                mkdir($uploadDir);

            if (isset($_FILES[$this->attribute])) {
                $file = $_FILES[$this->attribute];
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                switch ($this->rename) {
                    case 'random':
                        $filename = Controller::generateRandomString($this->randomLength).time();
                        while (file_exists($uploadDir.DIRECTORY_SEPARATOR.$filename.'.'.$ext))
                            $filename = Controller::generateRandomString($this->randomLength).time();
                        $filename = $filename.'.'.$ext;
                        break;
                    case 'none':
                    default:
                        $filename = CHtml::encode(str_replace(' ', '_', $file['name']));
                        $filename = str_replace('.'.$ext, '', $filename);
                        $i = 1;
                        while (file_exists($uploadDir.DIRECTORY_SEPARATOR.$filename.'.'.$ext)) {
                            $filename = $filename.'('.$i.')';
                            $i++;
                        }
                        $filename = $filename.'.'.$ext;
                        break;
                }
                $msg = '';
                if ($this->validateOptions) {
                    if (isset($this->validateOptions['dimensions'])) {
                        $minW = isset($this->validateOptions['dimensions']['minWidth']) ? $this->validateOptions['dimensions']['minWidth'] : null;
                        $maxW = isset($this->validateOptions['dimensions']['maxWidth']) ? $this->validateOptions['dimensions']['maxWidth'] : null;
                        $minH = isset($this->validateOptions['dimensions']['minHeight']) ? $this->validateOptions['dimensions']['minHeight'] : null;
                        $maxH = isset($this->validateOptions['dimensions']['maxHeight']) ? $this->validateOptions['dimensions']['maxHeight'] : null;

                        $imager = new Imager();
                        $imageInfo = $imager->getImageInfo($file['tmp_name']);
                        if ($minW && $imageInfo['width'] < $minW) {
                            $msg .= 'عرض تصویر نباید کوچکتر از '.$minW.' پیکسل باشد.<br>';
                            $validFlag = false;
                        }
                        if ($maxW && $imageInfo['width'] > $maxW) {
                            $msg .= 'عرض تصویر نباید بزرگتر از '.$maxW.' پیکسل باشد.<br>';
                            $validFlag = false;
                        }
                        if ($minH && $imageInfo['height'] < $minH) {
                            $msg .= 'ارتفاع تصویر نباید کوچکتر از '.$minH.' پیکسل باشد.<br>';
                            $validFlag = false;
                        }
                        if ($maxH && $imageInfo['height'] > $maxH) {
                            $msg .= 'عرض تصویر نباید بزرگتر از '.$maxH.' پیکسل باشد.<br>';
                            $validFlag = false;
                        }
                    }
                    if (isset($this->validateOptions['acceptedTypes']) && is_array($this->validateOptions['acceptedTypes'])) {
                        if (!in_array($ext, $this->validateOptions['acceptedTypes'])) {
                            $msg .= 'فرمت فایل مجاز نیست.<br>فرمت های مجاز: '.implode(',', $this->validateOptions['acceptedTypes']).'<br>';
                            $validFlag = false;
                        }
                    }
                }
                if ($validFlag) {
                    if (move_uploaded_file($file['tmp_name'], $uploadDir.DIRECTORY_SEPARATOR.$filename)) {
                        $response = ['status' => true, 'fileName' => $filename];
                        if ($this->insert) {
                            // Save into database
                            if ($this->storeMode === self::STORE_RECORD_MODE)
                                $model = new $this->modelName();
                            if ($this->storeMode === self::STORE_FIELD_MODE) {
                                $ownerModel = call_user_func(array($this->modelName, 'model'));
                                $model = $ownerModel->findByAttributes($this->findAttributes);
                                if (!empty($model->{$this->attribute}))
                                    @unlink($uploadDir.DIRECTORY_SEPARATOR.$model->{$this->attribute});
                            }
                            $this->insertAttributes[$this->attribute] = $filename;
                            $model->attributes = $this->insertAttributes;
                            if ($this->scenario && !empty($this->scenario))
                                $model->scenario = $this->scenario;
                            $saveFlag = $model->save();
                            // end Save into database
                            // Actions that run after save into database
                            if ($saveFlag && $this->afterSaveActions) {
                                // Resize image
                                if (isset($this->afterSaveActions['resize']) &&
                                    isset($this->afterSaveActions['resize']['width']) &&
                                    isset($this->afterSaveActions['resize']['height'])
                                ) {
                                    $imager = new Imager();
                                    $imageInfo = $imager->getImageInfo($uploadDir.DIRECTORY_SEPARATOR.$model->{$this->attribute});
                                    if ($imageInfo['width'] > $this->afterSaveActions['resize']['width'] || $imageInfo['height'] > $this->afterSaveActions['resize']['height'])
                                        $imager->resize($uploadDir.DIRECTORY_SEPARATOR.$model->{$this->attribute},
                                            $uploadDir.DIRECTORY_SEPARATOR.$model->{$this->attribute},
                                            $this->afterSaveActions['resize']['width'], $this->afterSaveActions['resize']['height']);
                                }

                                // create thumbnail
                                if (isset($this->afterSaveActions['thumbnail']) &&
                                    isset($this->afterSaveActions['thumbnail']['width']) &&
                                    isset($this->afterSaveActions['thumbnail']['height'])
                                ) {
                                    $thumbnailPath = $uploadDir.DIRECTORY_SEPARATOR.$this->afterSaveActions['thumbnail']['width'].'x'.$this->afterSaveActions['thumbnail']['height'];
                                    if (!is_dir($thumbnailPath))
                                        mkdir($thumbnailPath);
                                    $imager = new Imager();
                                    $imager->createThumbnail($uploadDir.DIRECTORY_SEPARATOR.$model->{$this->attribute},
                                        $this->afterSaveActions['thumbnail']['width'], $this->afterSaveActions['thumbnail']['height'], false,
                                        $thumbnailPath.DIRECTORY_SEPARATOR.$model->{$this->attribute}, ($this->afterSaveActions['thumbnail']['quality']?:100));
                                }
                            }
                        }
                    } else
                        $response = ['status' => false, 'message' => 'در عملیات آپلود فایل خطایی رخ داده است.'];
                } else
                    $response = ['status' => false, 'message' => $msg];
            } else
                $response = ['status' => false, 'message' => 'فایلی ارسال نشده است.'];
            echo CJSON::encode($response);
            Yii::app()->end();
        }
    }
}