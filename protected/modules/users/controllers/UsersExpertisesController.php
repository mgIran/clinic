<?php

class UsersExpertisesController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	public static function actionsType()
	{
		return array(
			'backend' => array(
                'create',
                'update',
                'admin',
                'delete',
                'index',
                'upload'
            )
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
                    'acceptedTypes' => array('jpg', 'jpeg', 'png', 'svg')
                )
            ),
            'deleteUpload' => array(
                'class' => 'ext.dropZoneUploader.actions.AjaxDeleteUploadedAction',
                'modelName' => 'Expertises',
                'attribute' => 'icon',
                'uploadDir' => '/uploads/expertises',
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
			'checkAccess',
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$tmpDIR = Yii::getPathOfAlias("webroot") . '/uploads/temp/';
		if (!is_dir($tmpDIR))
			mkdir($tmpDIR);
		$iconsDIR = Yii::getPathOfAlias("webroot") . '/uploads/expertises/';
		if (!is_dir($iconsDIR))
			mkdir($iconsDIR);

		$tmpUrl = Yii::app()->baseUrl . '/uploads/temp/';

		$model=new Expertises;

		$this->performAjaxValidation($model);

        $icon = array();
		if(isset($_POST['Expertises']))
		{
            $iconFlag = false;
            if (isset($_POST['Expertises']['icon']) && file_exists($tmpDIR . $_POST['Expertises']['icon'])) {
                $file = $_POST['Expertises']['icon'];
                $icon = array(array('name' => $file, 'src' => $tmpUrl . '/' . $file, 'size' => filesize($tmpDIR . $file), 'serverName' => $file,));
                $iconFlag = true;
            }
			$model->attributes=$_POST['Expertises'];
			if($model->save()) {
                if ($iconFlag)
                    @rename($tmpDIR . $model->icon, $iconsDIR . $model->icon);

                Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
                $this->refresh();
            }else
                Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
		}

		$this->render('create',array(
			'model'=>$model,
            'icon' => $icon,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

        $tmpDIR = Yii::getPathOfAlias("webroot") . '/uploads/temp/';
        if (!is_dir($tmpDIR))
            mkdir($tmpDIR);
        $tmpUrl = Yii::app()->createAbsoluteUrl('/uploads/temp/');
        $iconsDIR = Yii::getPathOfAlias("webroot") . '/uploads/expertises/';
        $iconsUrl = Yii::app()->createAbsoluteUrl('/uploads/expertises');

		$this->performAjaxValidation($model);

        $icon = array();
        if (!is_null($model->icon))
            $icon = array(
                'name' => $model->icon,
                'src' => $iconsUrl . '/' . $model->icon,
                'size' => filesize($iconsDIR . $model->icon),
                'serverName' => $model->icon
            );

		if(isset($_POST['Expertises'])) {
            $iconFlag = false;
            if (isset($_POST['Expertises']['icon']) && file_exists($tmpDIR . $_POST['Expertises']['icon']) && $_POST['Expertises']['icon'] != $model->icon) {
                $file = $_POST['Expertises']['icon'];
                $icon = array(array('name' => $file, 'src' => $tmpUrl . '/' . $file, 'size' => filesize($tmpDIR . $file), 'serverName' => $file,));
                $iconFlag = true;
            }

            $model->attributes = $_POST['Expertises'];
            if ($model->save()) {
                if ($iconFlag)
                    @rename($tmpDIR . $model->icon, $iconsDIR . $model->icon);

                Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ویرایش شد.');
                $this->refresh();
            } else
                Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
        }

		$this->render('update',array(
			'model'=>$model,
            'icon' => $icon,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$model=$this->loadModel($id);

        $iconDIR = Yii::getPathOfAlias("webroot") . "/uploads/expertises/";
        @unlink($iconDIR . $model->icon);

        $model->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Expertises('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Expertises']))
			$model->attributes=$_GET['Expertises'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Expertises the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Expertises::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Expertises $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='expertises-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
