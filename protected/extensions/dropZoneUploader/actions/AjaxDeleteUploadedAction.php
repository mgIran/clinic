<?php
class AjaxDeleteUploadedAction extends CAction
{
    const STORED_FIELD_MODE = 'field';
    const STORED_RECORD_MODE = 'record';
    /**
     * @var string temp folder Dir
     */
    public $tempDir = '/uploads/temp';
    /**
     * @var string upload folder Dir
     */
    public $uploadDir;

    /**
     * @var array thumbnail sizes array
     */
    public $thumbSizes = array();

    /**
     * @var string model class name
     */
    public $modelName;
    /**
     * @var string attribute name
     */
    public $attribute;
    /**
     * @var string saved in Database mode for this file field|record
     */
    public $storedMode;

    private function init(){
        if (!$this->uploadDir)
            throw new CException('{uploadDir} main files folder path is not specified.', 500);
        if (!$this->attribute)
            throw new CException('{attribute} attribute is not specified.', 500);
        if ($this->modelName && (empty($this->storedMode) || ($this->storedMode !== self::STORED_FIELD_MODE && $this->storedMode !== self::STORED_RECORD_MODE)))
            throw new CException('{storedMode} stored mode in db is not specified. ("field" or "record")', 500);
    }

    public function run()
    {
        $this->init();
        if (Yii::app()->request->isAjaxRequest) {
            $deleteFlag = false;
            $uploadDir = Yii::getPathOfAlias("webroot").$this->uploadDir;
            if (isset($_POST['fileName'])) {
                $fileName = $_POST['fileName'];
                $tempDir = Yii::getPathOfAlias("webroot").$this->tempDir;
                $ownerModel = call_user_func(array($this->modelName, 'model'));
                $model = $ownerModel->findByAttributes(array($this->attribute => $fileName));
                if ($model) {
                    if ($this->storedMode === self::STORED_FIELD_MODE)
                        $deleteFlag = $ownerModel->updateByPk($model->id, array($this->attribute => null))?true:false;
                    elseif ($this->storedMode === self::STORED_RECORD_MODE)
                        $deleteFlag = $model->delete()?true:false;
                    if ($deleteFlag) {
                        @unlink($uploadDir.DIRECTORY_SEPARATOR.$fileName);
                        if($this->thumbSizes)
                            foreach($this->thumbSizes as $size)
                                if(is_dir($uploadDir.DIRECTORY_SEPARATOR.$size) && file_exists($uploadDir.DIRECTORY_SEPARATOR.$size.DIRECTORY_SEPARATOR.$fileName))
                                    @unlink($uploadDir.DIRECTORY_SEPARATOR.$size.DIRECTORY_SEPARATOR.$fileName);
                        $response = ['status' => true, 'msg' => 'فایل با موفقیت حذف شد.'];
                    } else
                        $response = ['status' => false, 'msg' => 'در حذف فایل مشکل ایجاد شده است'];
                } else {
                    @unlink($tempDir.DIRECTORY_SEPARATOR.$fileName);
                    $response = ['status' => true, 'msg' => 'فایل با موفقیت حذف شد.'];
                }
            } else
                $response = ['status' => false, 'message' => 'نام فایل موردنظر ارسال نشده است.'];
            echo CJSON::encode($response);
            Yii::app()->end();
        }
    }
}