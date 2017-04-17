<?php

class BookCategoriesController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array actions type list
	 */
	public static function actionsType()
	{
		return array(
			'frontend' => array(
				'index',
			),
			'backend' => array(
				'create',
				'update',
				'admin',
				'delete',
				'upload',
				'deleteUpload',
				'uploadIcon',
				'deleteUploadIcon'
			)
		);
	}

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'checkAccess + create, update, admin, delete, upload, deleteUpload, uploadIcon, deleteUploadIcon',
			'postOnly + delete',
		);
	}

	public function actions(){
		return array(
			'upload' => array(
				'class' => 'ext.dropZoneUploader.actions.AjaxUploadAction',
				'uploadDir' => '/uploads/bookCategories/images',
				'attribute' => 'image',
				'rename' => 'random',
				'validateOptions' => array(
                    'dimensions' => array(
                        'minWidth' => 500,
                        'minHeight' => 280,
                    ),
					'acceptedTypes' => array('jpg','jpeg','png')
				),
				'insert' => true,
				'modelName' => 'BookCategories',
				'findAttributes' => 'array("id" =>$_POST["model_id"])',
				'storeMode' => 'field',
			),
            'deleteUpload' => array(
                'class' => 'ext.dropZoneUploader.actions.AjaxDeleteUploadedAction',
                'modelName' => 'Books',
                'attribute' => 'icon',
                'uploadDir' => '/uploads/books/icons',
                'storedMode' => 'field'
            ),
			'uploadIcon' => array(
				'class' => 'ext.dropZoneUploader.actions.AjaxUploadAction',
                'uploadDir' => '/uploads/bookCategories/icons',
				'attribute' => 'icon',
				'rename' => 'random',
				'validateOptions' => array(
					'acceptedTypes' => array('svg')
				),
				'insert' => true,
                'modelName' => 'BookCategories',
                'findAttributes' => 'array("id" =>$_POST["model_id"])',
				'storeMode' => 'field'
			),
            'deleteUploadIcon' => array(
                'class' => 'ext.dropZoneUploader.actions.AjaxDeleteUploadedAction',
                'modelName' => 'BookCategories',
                'attribute' => 'icon',
                'uploadDir' => '/uploads/bookCategories/icons',
                'storedMode' => 'field'
            )
		);
	}

	/**
	 * index of books in category id
	 * @param $id
	 * @throws CHttpException
	 */
	public function actionIndex($id)
	{
		Yii::app()->theme = 'frontend';
		$this->layout = '//layouts/index';
		$model=$this->loadModel($id);
		$catIds = $model->getCategoryChilds();
		$criteria = Books::model()->getValidBooks($catIds);
		$dataProvider = new CActiveDataProvider("Books",array(
			'criteria' => $criteria,
			'pagination' => array('pageSize' => 8)
		));
		$this->render('//bookCategories/index',array(
			'model'=>$model,
			'dataProvider'=>$dataProvider
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new BookCategories;
        $step = 1;
		if(isset($_POST['BookCategories']))
		{
			$model->attributes=$_POST['BookCategories'];
			if($model->save())
				$this->redirect(array('update?id='.$model->id.'&step=2'));
		}

		$this->render('create',array(
			'model'=>$model,
            'step' => $step
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
        $step = 1;
        if(isset($_GET['step']))
            $step = (int)$_GET['step'];
		$model=$this->loadModel($id);

		if(isset($_POST['BookCategories']))
		{
			$model->attributes=$_POST['BookCategories'];
			if($model->save())
				$this->redirect(array('admin'));
		}

        $imageDir = Yii::getPathOfAlias('webroot').'/uploads/bookCategories/images/';
        $iconDir = Yii::getPathOfAlias('webroot').'/uploads/bookCategories/icons/';
        $imageUrl = Yii::app()->baseUrl.'/uploads/bookCategories/images/';
        $iconUrl = Yii::app()->baseUrl.'/uploads/bookCategories/icons/';
        $image = array();
        if($model->image && file_exists($imageDir.$model->image))
            $image = array(
                'name' => $model->image,
                'src' => $imageUrl . $model->image,
                'size' => filesize($imageDir . $model->image),
                'serverName' => $model->image,
            );
        $icon = array();
        if($model->icon && file_exists($iconDir.$model->icon))
            $icon = array(
                'name' => $model->icon,
                'src' => $iconUrl . $model->icon,
                'size' => filesize($iconDir . $model->icon),
                'serverName' => $model->icon,
            );
		$this->render('update',array(
			'model'=>$model,
			'icon'=>$icon,
			'image'=>$image,
            'step' => $step
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$model = $this->loadModel($id);
        $imageDir = Yii::getPathOfAlias('webroot').'/uploads/bookCategories/images/';
        $iconDir = Yii::getPathOfAlias('webroot').'/uploads/bookCategories/icons/';
        if($model->delete()) {
            if(file_exists($imageDir.$model->image))
                @unlink($imageDir.$model->image);
            if(file_exists($iconDir.$model->icon))
                @unlink($iconDir.$model->icon);
        }

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new BookCategories('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['BookCategories']))
			$model->attributes=$_GET['BookCategories'];
		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return BookCategories the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=BookCategories::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param BookCategories $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='book-categories-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
