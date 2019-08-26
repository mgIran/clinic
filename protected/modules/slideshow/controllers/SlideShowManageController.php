<?php

class SlideShowManageController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
    public $tempPath = 'uploads/temp';
    public $imagePath = 'uploads/slideshow';
    public $imageOptions = ['resize' => ['width' => 1920, 'height' => 600]];
    public $mobileImageOptions = [
//        'thumbnail' => ['width' => 100, 'height' => 100],
        'resize' => ['width' => 400, 'height' => 400]];

	/**
	 * @return array action filters
	 */
	public static function actionsType()
	{
		return array(
			'frontend'=>array(),
			'backend' => array(
				'create',
				'update',
				'admin',
				'delete',
				'upload',
				'uploadMobile',
				'deleteUpload',
				'deleteUploadMobile',
			)
		);
	}

    public function actions()
    {
        return array(
            'upload' => array( // brand logo upload
                'class' => 'ext.dropZoneUploader.actions.AjaxUploadAction',
                'attribute' => 'image',
                'rename' => 'random',
                'validateOptions' => array(
                    'acceptedTypes' => array('png', 'jpg', 'jpeg')
                )
            ),
            'deleteUpload' => array( // delete brand logo uploaded
                'class' => 'ext.dropZoneUploader.actions.AjaxDeleteUploadedAction',
                'modelName' => 'Slideshow',
                'attribute' => 'image',
                'uploadDir' => '/uploads/slideshow/',
                'storedMode' => 'field'
            ),
            'uploadMobile' => array( // brand logo upload
                'class' => 'ext.dropZoneUploader.actions.AjaxUploadAction',
                'attribute' => 'mobile_image',
                'rename' => 'random',
                'validateOptions' => array(
                    'acceptedTypes' => array('png', 'jpg', 'jpeg')
                )
            ),
            'deleteUploadMobile' => array( // delete brand logo uploaded
                'class' => 'ext.dropZoneUploader.actions.AjaxDeleteUploadedAction',
                'modelName' => 'Slideshow',
                'attribute' => 'mobile_image',
                'uploadDir' => '/uploads/slideshow/',
                'storedMode' => 'field'
            ),
        );
    }

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'checkAccess', // perform access control for CRUD operations
			'postOnly + delete,deleteSelected', // we only allow deletion via POST request
		);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Slideshow;

        $image = array();
        $mobileImage = array();
		if(isset($_POST['Slideshow']))
		{
			$model->attributes=$_POST['Slideshow'];
            $image = new UploadedFiles($this->tempPath, $model->image, $this->imageOptions);
            $mobileImage = new UploadedFiles($this->tempPath, $model->mobile_image, $this->mobileImageOptions);
			if($model->save()) {
                $image->move($this->imagePath);
                $mobileImage->move($this->imagePath);
                Yii::app()->user->setFlash('success', '<span class="icon-check"></span>&nbsp;&nbsp;اطلاعات با موفقیت ذخیره شد.');
                $this->redirect(array('admin'));
			}
			else
				Yii::app()->user->setFlash('failed' , 'متاسفانه در افزودن تصویر مشکل رخ داده است.');
		}

        $this->render('create',compact('model', 'image', 'mobileImage'));
	}

    /**
     * @param $id
     * @throws CHttpException
     */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

        $image = new UploadedFiles($this->imagePath, $model->image, $this->imageOptions);
        $mobileImage = new UploadedFiles($this->imagePath, $model->mobile_image, $this->mobileImageOptions);
		if(isset($_POST['Slideshow']))
		{
            // store model image value in oldImage variable
            $oldImage= $model->image;
            $oldMobileIMage = $model->mobile_image;

			$model->attributes=$_POST['Slideshow'];

			if($model->save())
			{
                $image->update($oldImage, $model->image, $this->tempPath);
                $mobileImage->update($oldMobileIMage, $model->mobile_image, $this->tempPath);
                Yii::app()->user->setFlash('success', '<span class="icon-check"></span>&nbsp;&nbsp;اطلاعات با موفقیت ذخیره شد.');
                $this->redirect(array('admin'));
			}
			else
                Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
		}

		$this->render('update',compact('model', 'image', 'mobileImage'));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
    {
        $model = $this->loadModel($id);
        $image = new UploadedFiles($this->imagePath, $model->image, $this->imageOptions);
        $image->removeAll(true);
        $mobileImage = new UploadedFiles($this->imagePath, $model->mobile_image, $this->mobileImageOptions);
        $mobileImage->removeAll(true);
        $model->delete();
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Slideshow('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Slideshow']))
			$model->attributes=$_GET['Slideshow'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Slideshow the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Slideshow::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Slideshow $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='slideshow-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
