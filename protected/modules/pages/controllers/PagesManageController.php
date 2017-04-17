<?php

class PagesManageController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

    public $categorySlug = null;
    public $categoryId = null;
    public $categoryName = null;
    public $categoryMultiple = 1;

	/**
	 * @return array actions type list
	 */
	public static function actionsType()
	{
		return array(
			'frontend'=>array(
				'view'
			),
			'backend' => array(
				'index',
				'create',
				'update',
				'admin',
				'delete'
			)
		);
	}

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'checkAccess + index, create, update, admin, delete', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

    public function beforeAction($action)
    {
        if($action->id != 'update'){
            if(isset($_GET['slug']) && !empty($_GET['slug'])){
                $this->categorySlug = CHtml::encode($_GET['slug']);
                $category = PageCategories::model()->find('slug = :slug' ,array(':slug' => $this->categorySlug));
                $this->categoryName = $category->name;
                $this->categoryId = $category->id;
                $this->categoryMultiple = $category->multiple;
            }else{
                $this->categorySlug = 'document';
                $category = PageCategories::model()->find('slug = :slug' ,array(':slug' => $this->categorySlug));
                $this->categoryId = $category->id;
            }
        }
        return parent::beforeAction($action);
    }

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
        Yii::app()->theme = 'market';
        $this->layout = '//layouts/index';

        $model = $this->loadModel($id);
        $this->categorySlug = $model->category->slug;
        $this->categoryId = $model->category->id;
		if($this->categorySlug == 'document')
		{
			$this->layout='//layouts/panel';
			$this->render('publishers.views.panel.document_view',array(
				'model'=>$model,
			));
		}
		else
			$this->render('view',array(
			'model'=>$model,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model = new Pages;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Pages'])){
			$model->attributes = $_POST['Pages'];
			$model->category_id = $this->categoryId;
			if($model->save()){
				Yii::app()->user->setFlash('success' ,'اطلاعات با موفقیت ثبت شد.');
				$this->redirect(array('manage/admin/slug/' . $this->categorySlug));
			}else
				Yii::app()->user->setFlash('failed' ,'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
		}

		$this->render('create' ,array(
			'model' => $model ,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        $this->categorySlug = $model->category->slug;
        $this->categoryId = $model->category->id;
        if(isset($_POST['Pages'])){
            $model->attributes = $_POST['Pages'];
            $model->category_id = $this->categoryId;
			if($model->save()){
				Yii::app()->user->setFlash('success' ,'اطلاعات با ویرایش شد.');
				$this->refresh();
			}else
				Yii::app()->user->setFlash('failed' ,'در ویرایش اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
        }

        $this->render('update' ,array(
            'model' => $model ,
        ));
    }

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Pages');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$this->layout='//layouts/main';
		$model=new Pages('search');
        $model->unsetAttributes();  // clear any default values
        $model->category_id=$this->categoryId;
        if(isset($_GET['Pages']))
			$model->attributes=$_GET['Pages'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Pages the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Pages::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Pages $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='pages-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
