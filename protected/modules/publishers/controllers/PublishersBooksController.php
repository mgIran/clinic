<?php

class PublishersBooksController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';
    public $formats = '.epub ,.pdf';

    /**
     * @return array actions type list
     */
    public static function actionsType()
    {
        return array(
            'frontend' => array(
                'create',
                'update',
                'delete',
                'uploadImage',
                'deleteImage',
                'upload',
                'deleteUpload',
                'uploadPdfFile',
                'deleteUploadPdfFile',
                'uploadEpubFile',
                'deleteUploadEpubFile',
                'deleteFile',
                'images',
                'savePackage',
                'deletePackage',
                'getPackages',
                'updatePackage',
                'uploadPreview',
                'deleteUploadedPreview'
            ),
        );
    }

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'checkAccess + create, update, delete, uploadImage, deleteImage, upload, deleteUpload, uploadFile, deleteUploadFile, images, savePackage, getPackages, uploadPreview, deleteUploadedPreview', // perform access control for CRUD operations
            'ajaxOnly + getPackages'
        );
    }

    public function actions()
    {
        return array(
            'upload' => array(
                'class' => 'ext.dropZoneUploader.actions.AjaxUploadAction',
                'attribute' => 'icon',
                'rename' => 'random',
                'validateOptions' => array(
                    'dimensions' => array(
                        'minWidth' => 400,
                        'minHeight' => 590,
                        'maxWidth' => 400,
                        'maxHeight' => 600
                    ),
                    'acceptedTypes' => array('jpg', 'jpeg', 'png')
                )
            ),
            'uploadPdfFile' => array(
                'class' => 'ext.dropZoneUploader.actions.AjaxUploadAction',
                'attribute' => 'pdf_file_name',
                'rename' => 'random',
                'validateOptions' => array(
                    'acceptedTypes' => array('pdf')
                )
            ),
            'uploadEpubFile' => array(
                'class' => 'ext.dropZoneUploader.actions.AjaxUploadAction',
                'attribute' => 'epub_file_name',
                'rename' => 'random',
                'validateOptions' => array(
                    'acceptedTypes' => array('epub')
                )
            ),
            'uploadPreview' => array(
                'class' => 'ext.dropZoneUploader.actions.AjaxUploadAction',
                'attribute' => 'preview_file' ,
                'rename' => 'random' ,
                'validateOptions' => array(
                    'acceptedTypes' => array('epub' ,'pdf')
                )
            ) ,
            'uploadImage' => array(
                'class' => 'ext.dropZoneUploader.actions.AjaxUploadAction',
                'attribute' => 'image',
                'rename' => 'random',
                'validateOptions' => array(
                    'acceptedTypes' => array('jpg', 'jpeg', 'png')
                )
            ),
            'deleteUpload' => array(
                'class' => 'ext.dropZoneUploader.actions.AjaxDeleteUploadedAction',
                'modelName' => 'Books',
                'attribute' => 'icon',
                'uploadDir' => '/uploads/books/icons',
                'storedMode' => 'field'
            ),
            'deleteUploadPdfFile' => array(
                'class' => 'ext.dropZoneUploader.actions.AjaxDeleteUploadedAction',
                'modelName' => 'BookPackages',
                'attribute' => 'pdf_file_name',
                'uploadDir' => '/uploads/books/files',
                'storedMode' => 'record'
            ),
            'deleteUploadEpubFile' => array(
                'class' => 'ext.dropZoneUploader.actions.AjaxDeleteUploadedAction',
                'modelName' => 'BookPackages',
                'attribute' => 'epub_file_name',
                'uploadDir' => '/uploads/books/files',
                'storedMode' => 'record'
            ),
            'deletePdfFile' => array(
                'class' => 'ext.dropZoneUploader.actions.AjaxDeleteUploadedAction',
                'modelName' => 'BookPackages',
                'attribute' => 'pdf_file_name',
                'uploadDir' => '/uploads/books/files',
                'storedMode' => 'field'
            ),
            'deleteEpubFile' => array(
                'class' => 'ext.dropZoneUploader.actions.AjaxDeleteUploadedAction',
                'modelName' => 'BookPackages',
                'attribute' => 'epub_file_name',
                'uploadDir' => '/uploads/books/files',
                'storedMode' => 'field'
            ),
            'deleteUploadPreview' => array(
                'class' => 'ext.dropZoneUploader.actions.AjaxDeleteUploadedAction' ,
                'modelName' => 'Books' ,
                'attribute' => 'preview_file' ,
                'uploadDir' => '/uploads/books/previews' ,
                'storedMode' => 'field'
            ),
            'deleteImage' => array(
                'class' => 'ext.dropZoneUploader.actions.AjaxDeleteUploadedAction',
                'modelName' => 'BookImages',
                'attribute' => 'image',
                'uploadDir' => '/uploads/books/images',
                'storedMode' => 'record'
            )
        );
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        if (Yii::app()->user->isGuest || Yii::app()->user->type != 'admin') {
            $user = UserDetails::model()->findByPk(Yii::app()->user->getId());
            if ($user->details_status == 'refused') {
                Yii::app()->user->setFlash('failed', 'اطلاعات قرارداد شما رد شده است و نمیتوانید کتاب ثبت کنید. در صورت نیاز نسبت به تغییر اطلاعات خود اقدام کنید.');
                $this->redirect(array('/publishers/panel/account'));
            } elseif ($user->details_status == 'pending') {
                Yii::app()->user->setFlash('warning', 'اطلاعات قرارداد شما در انتظار تایید می باشد،لطفا پس از تایید اطلاعات مجددا تلاش کنید.');
                $this->redirect(array('/publishers/panel/account'));
            }
            if (!$user->publisher_id) {
                $devIdRequestModel = UserDevIdRequests::model()->findByAttributes(array('user_id' => Yii::app()->user->getId()));
                if ($devIdRequestModel)
                    Yii::app()->user->setFlash('warning', 'درخواست شما برای شناسه ناشر در انتظار تایید می باشد، لطفا شکیبا باشید.');
                else
                    Yii::app()->user->setFlash('failed', 'شناسه ناشر تنظیم نشده است. برای ثبت کتاب شناسه ناشر الزامیست.');
                $this->redirect(array('/publishers/panel/account'));
            }
            Yii::app()->getModule('setting');
            $tmpDIR = Yii::getPathOfAlias("webroot") . '/uploads/temp/';
            if (!is_dir($tmpDIR))
                mkdir($tmpDIR);
            $bookIconsDIR = Yii::getPathOfAlias("webroot") . '/uploads/books/icons/';
            if (!is_dir($bookIconsDIR))
                mkdir($bookIconsDIR);
            $bookPreviewDIR = Yii::getPathOfAlias("webroot") . "/uploads/books/previews/";
            if(!is_dir($bookPreviewDIR))
                mkdir($bookPreviewDIR);
            
            $tmpUrl = Yii::app()->baseUrl . '/uploads/temp/';

            Yii::app()->theme = 'frontend';
            $this->layout = '//layouts/panel';
            $model = new Books;
            // Uncomment the following line if AJAX validation is needed
            $this->performAjaxValidation($model);
            $icon = array();
            $previewFile = array();
            if (isset($_POST['Books'])) {
                $iconFlag = false;
                $previewFileFlag = false;
                if (isset($_POST['Books']['icon']) && file_exists($tmpDIR . $_POST['Books']['icon'])) {
                    $file = $_POST['Books']['icon'];
                    $icon = array(array('name' => $file, 'src' => $tmpUrl . '/' . $file, 'size' => filesize($tmpDIR . $file), 'serverName' => $file,));
                    $iconFlag = true;
                }
                if (isset($_POST['Books']['previewFile']) && file_exists($tmpDIR . $_POST['Books']['previewFile'])) {
                    $file = $_POST['Books']['previewFile'];
                    $previewFile = array(array('name' => $file, 'src' => $tmpUrl . '/' . $file, 'size' => filesize($tmpDIR . $file), 'serverName' => $file,));
                    $previewFileFlag = true;
                }
                $model->attributes = $_POST['Books'];
                $model->publisher_id = $user->user_id;
                $model->confirm = 'pending';
                $model->formTags = isset($_POST['Books']['formTags']) ? explode(',', $_POST['Books']['formTags']) : null;
                $model->formSeoTags = isset($_POST['Books']['formSeoTags']) ? explode(',', $_POST['Books']['formSeoTags']) : null;
                $model->formAuthor = isset($_POST['Books']['formAuthor']) ? explode(',', $_POST['Books']['formAuthor']) : null;
                $model->formTranslator = isset($_POST['Books']['formTranslator']) ? explode(',', $_POST['Books']['formTranslator']) : null;
                if ($model->save()) {
                    if ($iconFlag)
                        @rename($tmpDIR . $model->icon, $bookIconsDIR . $model->icon);
                    if ($previewFileFlag)
                        @rename($tmpDIR . $model->preview_file, $bookPreviewDIR . $model->preview_file);
                    Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد. لطفا مراحل بعدی را نیز انجام دهید.');
                    $this->redirect(array('/publishers/books/update/' . $model->id . '?step=2'));
                } else {
                    Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
                }
            }
            $this->render('create', array(
                'model' => $model,
                'icon' => $icon,
                'previewFile' => $previewFile,
                'tax' => SiteSetting::model()->findByAttributes(array('name' => 'tax'))->value,
                'commission' => SiteSetting::model()->findByAttributes(array('name' => 'commission'))->value,
            ));
        } else {
            Yii::app()->user->setFlash('failed', 'از طریق مدیریت اقدام کنید');
            $this->redirect(array('/admins/dashboard'));
        }
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $step = 1;
        Yii::app()->theme = 'frontend';
        $this->layout = '//layouts/panel';
        Yii::app()->user->returnUrl = $this->createUrl('update', array('id'=>$id));
        $model = $this->loadModel($id);
        if ($model->publisher_id != Yii::app()->user->getId()) {
            Yii::app()->user->setFlash('images-failed', 'شما اجازه دسترسی به این صفحه را ندارید.');
            $this->redirect($this->createUrl('/publishers/panel'));
        }
        $tmpDIR = Yii::getPathOfAlias("webroot") . '/uploads/temp/';
        if (!is_dir($tmpDIR))
            mkdir($tmpDIR);
        $tmpUrl = Yii::app()->createAbsoluteUrl('/uploads/temp/');
        $bookIconsDIR = Yii::getPathOfAlias("webroot") . '/uploads/books/icons/';
        $bookPreviewDIR = Yii::getPathOfAlias("webroot") . "/uploads/books/previews/";
        $bookImagesDIR = Yii::getPathOfAlias("webroot") . '/uploads/books/images/';
        $bookFilesDIR = Yii::getPathOfAlias("webroot") . "/uploads/books/files/";
        if (!is_dir($bookFilesDIR))
            mkdir($bookFilesDIR);
        $bookIconsUrl = Yii::app()->createAbsoluteUrl('/uploads/books/icons');
        $bookPreviewUrl = Yii::app()->createAbsoluteUrl('/uploads/books/previews');
        $bookImagesUrl = Yii::app()->createAbsoluteUrl('/uploads/books/images');

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        $icon = array();
        if (!is_null($model->icon))
            $icon = array(
                'name' => $model->icon,
                'src' => $bookIconsUrl . '/' . $model->icon,
                'size' => filesize($bookIconsDIR . $model->icon),
                'serverName' => $model->icon
            );
        $previewFile = array();
        if (!is_null($model->preview_file))
            $previewFile = array(
                'name' => $model->preview_file,
                'src' => $bookPreviewUrl . '/' . $model->preview_file,
                'size' => filesize($bookPreviewDIR . $model->preview_file),
                'serverName' => $model->preview_file
            );
        $images = array();
        if ($model->images)
            foreach ($model->images as $image)
                if (file_exists($bookImagesDIR . $image->image))
                    $images[] = array(
                        'name' => $image->image,
                        'src' => $bookImagesUrl . '/' . $image->image,
                        'size' => filesize($bookImagesDIR . $image->image),
                        'serverName' => $image->image,
                    );

        foreach ($model->showTags as $tag)
            array_push($model->formTags, $tag->title);
        foreach ($model->seoTags as $tag)
            array_push($model->formSeoTags, $tag->title);
        foreach ($model->persons(array('condition'=>'role_id = 1')) as $person)
            array_push($model->formAuthor, $person->name_family);
        foreach ($model->persons(array('condition'=>'role_id = 2')) as $person)
            array_push($model->formTranslator, $person->name_family);

        if (isset($_POST['packages-submit'])) {
            if (empty($model->packages))
                Yii::app()->user->setFlash('failed', 'نوبت چاپی تعریف نشده است.');
            else
                $this->redirect($this->createUrl('/publishers/panel'));
        }

        if (isset($_POST['Books'])) {
            $iconFlag = false;
            $previewFileFlag = false;
            if (isset($_POST['Books']['icon']) && file_exists($tmpDIR . $_POST['Books']['icon']) && $_POST['Books']['icon'] != $model->icon) {
                $file = $_POST['Books']['icon'];
                $icon = array(array('name' => $file, 'src' => $tmpUrl . '/' . $file, 'size' => filesize($tmpDIR . $file), 'serverName' => $file,));
                $iconFlag = true;
            }
            if (isset($_POST['Books']['preview_file']) && file_exists($tmpDIR . $_POST['Books']['preview_file'])) {
                $file = $_POST['Books']['preview_file'];
                $previewFile = array(array('name' => $file, 'src' => $tmpUrl . '/' . $file, 'size' => filesize($tmpDIR . $file), 'serverName' => $file,));
                $previewFileFlag = true;
            }
            $model->attributes = $_POST['Books'];
            $model->confirm = 'pending';
            $model->formTags = isset($_POST['Books']['formTags']) ? explode(',', $_POST['Books']['formTags']) : null;
            $model->formSeoTags = isset($_POST['Books']['formSeoTags']) ? explode(',', $_POST['Books']['formSeoTags']) : null;
            $model->formAuthor = isset($_POST['Books']['formAuthor']) ? explode(',', $_POST['Books']['formAuthor']) : null;
            $model->formTranslator = isset($_POST['Books']['formTranslator']) ? explode(',', $_POST['Books']['formTranslator']) : null;
            if ($model->save()) {
                if ($iconFlag)
                    @rename($tmpDIR . $model->icon, $bookIconsDIR . $model->icon);
                if ($previewFileFlag)
                    @rename($tmpDIR . $model->preview_file, $bookPreviewDIR . $model->preview_file);
                Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ویرایش شد.');
                $this->redirect(array('/publishers/books/update/' . $model->id . '?step=2'));
            } else {
                Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
            }
        }

        if (isset($_GET['step']) && !empty($_GET['step']))
            $step = (int)$_GET['step'];

        $criteria = new CDbCriteria();
        $criteria->addCondition('book_id=:book_id');
        $criteria->params = array(
            ':book_id' => $id,
        );
        $packagesDataProvider = new CActiveDataProvider('BookPackages', array('criteria' => $criteria));

        Yii::app()->getModule('setting');
        $this->render('update', array(
            'model' => $model,
            'imageModel' => new BookImages(),
            'images' => $images,
            'icon' => $icon,
            'previewFile' => $previewFile,
            'packagesDataProvider' => $packagesDataProvider,
            'step' => $step,
            'tax' => SiteSetting::model()->findByAttributes(array('name' => 'tax'))->value,
            'commission' => SiteSetting::model()->findByAttributes(array('name' => 'commission'))->value,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        Books::model()->updateByPk($id, array('deleted' => 1));

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/publishers/panel'));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Books the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Books::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Books $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'books-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionImages($id)
    {
        $tempDir = Yii::getPathOfAlias("webroot") . '/uploads/temp/';
        $uploadDir = Yii::getPathOfAlias("webroot") . '/uploads/books/images/';
        if (!is_dir($uploadDir))
            mkdir($uploadDir);
        if (isset($_POST['BookImages']['image'])) {
            $flag = true;
            foreach ($_POST['BookImages']['image'] as $image) {
                if (file_exists($tempDir . $image)) {
                    $model = new BookImages();
                    $model->book_id = (int)$id;
                    $model->image = $image;
                    rename($tempDir . $image, $uploadDir . $image);
                    if (!$model->save(false))
                        $flag = false;
                }
            }
            if ($flag) {
                Yii::app()->user->setFlash('images-success', 'اطلاعات با موفقیت ثبت شد.');
                $this->redirect($this->createUrl('/publishers/panel'));
            } else
                Yii::app()->user->setFlash('images-failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
        } else
            Yii::app()->user->setFlash('images-failed', 'تصاویر کتاب را آپلود کنید.');
        $this->redirect('update/' . $id . '/?step=3');
    }

    /**
     * Save book package info
     */
    public function actionSavePackage()
    {
        if (isset($_POST['book_id'])) {
            $uploadDir = Yii::getPathOfAlias("webroot") . '/uploads/books/files';
            $tempDir = Yii::getPathOfAlias("webroot") . '/uploads/temp';
            if (!is_dir($uploadDir))
                mkdir($uploadDir);

            $model = new BookPackages();
            $model->attributes = $_POST;
            $model->sale_printed = 0;
            if(!$model->printed_price || empty($model->printed_price))
                $model->printed_price = $model->price;
            if ($model->save()) {
                $response = ['status' => true, 'PdfFileName' => CHtml::encode($model->pdf_file_name), 'EpubFileName' => CHtml::encode($model->epub_file_name)];
                if(isset($_POST['pdf_file_name']))
                    rename($tempDir . DIRECTORY_SEPARATOR . $_POST['pdf_file_name'], $uploadDir . DIRECTORY_SEPARATOR . $model->pdf_file_name);

                if(isset($_POST['epub_file_name']))
                    rename($tempDir . DIRECTORY_SEPARATOR . $_POST['epub_file_name'], $uploadDir . DIRECTORY_SEPARATOR . $model->epub_file_name);
            } else
                $response = ['status' => false, 'message' => $this->implodeErrors($model)];

            echo CJSON::encode($response);
            Yii::app()->end();
        }
    }

    public function actionGetPackages($id)
    {
        $model=$this->loadModel($id);
        foreach($model->packages as $package)
            $this->renderPartial('_package_list', array('data'=>$package));
    }

    public function actionDeletePackage($id)
    {
        $model = BookPackages::model()->findByPk($id);
        /* @var $model BookPackages */
        if ($model === null || $model->book->publisher_id != Yii::app()->user->getId())
            throw new CHttpException(404, 'The requested page does not exist.');
        $uploadDir = Yii::getPathOfAlias("webroot") . '/uploads/books/files';
        // Remove PDF & EPUB files
        if (file_exists($uploadDir . '/' . $model->pdf_file_name)) {
            @unlink($uploadDir . '/' . $model->pdf_file_name);
            if (file_exists($uploadDir . '/' . $model->epub_file_name)) {
                @unlink($uploadDir . '/' . $model->epub_file_name);
                $bookID = $model->book_id;
                if ($model->delete()) {
                    $count = BookPackages::model()->count('book_id = :id', array(':id' => $bookID));
                    echo CJSON::encode(array(
                        'status' => 'success',
                        'count' => $count
                    ));
                } else
                    echo CJSON::encode(array(
                        'status' => 'failed'
                    ));
            } else
                echo CJSON::encode(array(
                    'status' => 'failed'
                ));
        } else
            echo CJSON::encode(array(
                'status' => 'failed'
            ));
    }


    public function actionUpdatePackage()
    {
        Yii::app()->theme = 'frontend';
        $this->layout= '//layouts/panel';
        if(isset($_GET['id']) && !empty($_GET['id'])){
            $id = (int)$_GET['id'];
            $model = BookPackages::model()->findByPk($id);
            /* @var $model BookPackages */
            if($model === null || $model->book->publisher_id != Yii::app()->user->getId())
                throw new CHttpException(404 ,'The requested page does not exist.');
            $uploadDir = Yii::getPathOfAlias("webroot") . '/uploads/books/files/';
            $uploadUrl = Yii::app()->baseUrl . '/uploads/books/files';
            $tempDir = Yii::getPathOfAlias("webroot") . '/uploads/temp';
            if(!is_dir($uploadDir))
                mkdir($uploadDir);

            $pdfPackage=$epubPackage = array();
            if($model->pdf_file_name && file_exists($uploadDir . $model->pdf_file_name))
                $pdfPackage = array(
                    'name' => $model->pdf_file_name ,
                    'src' => $uploadUrl . '/' . $model->pdf_file_name ,
                    'size' => filesize($uploadDir . $model->pdf_file_name) ,
                    'serverName' => $model->pdf_file_name ,
                );

            if($model->epub_file_name && file_exists($uploadDir . $model->epub_file_name))
                $epubPackage = array(
                    'name' => $model->epub_file_name ,
                    'src' => $uploadUrl . '/' . $model->epub_file_name,
                    'size' => filesize($uploadDir . $model->epub_file_name) ,
                    'serverName' => $model->epub_file_name,
                );
            if(isset($_POST['BookPackages'])){
                $model->attributes = $_POST['BookPackages'];
                $model->for = $model::FOR_OLD_BOOK;

                if((!isset($_POST['free'])) and (!isset($_POST['BookPackages']['price']) or empty($_POST['BookPackages']['price'])))
                    $model->price=0;

                if (!isset($_POST['BookPackages']['sale_printed']))
                    $model->sale_printed = 0;
                if($model->save()){
                    if($model->pdf_file_name && file_exists($tempDir .DIRECTORY_SEPARATOR. $model->pdf_file_name))
                        @rename($tempDir . DIRECTORY_SEPARATOR . $model->pdf_file_name ,$uploadDir . DIRECTORY_SEPARATOR . $model->pdf_file_name);
                    if($model->epub_file_name && file_exists($tempDir .DIRECTORY_SEPARATOR. $model->epub_file_name))
                        @rename($tempDir . DIRECTORY_SEPARATOR . $model->epub_file_name,$uploadDir . DIRECTORY_SEPARATOR . $model->epub_file_name);
                    Yii::app()->user->setFlash('success' ,'اطلاعات با موفقیت ثبت شد.');
                    $this->redirect(array('/publishers/books/update/' . $model->book_id . '?step=2'));
                }else
                    Yii::app()->user->setFlash('failed' ,'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
            }
            $this->render('update_package' ,array(
                'model' => $model ,
                'pdfPackage' => $pdfPackage,
                'epubPackage' => $epubPackage
            ));
        }else
            $this->redirect(array('/publishers/panel'));
    }
}