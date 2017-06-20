<?php

class HolidaysManageController extends Controller
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
				'create', 'update', 'admin', 'delete'
			)
		);
	}

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'checkAccess', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Holidays;

		if(isset($_POST['Holidays']))
		{
			$model->attributes=$_POST['Holidays'];
			$model->date = $model->date?strtotime(date('Y/m/d 00:00',$model->date)):null;
			if($model->save()){
				Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
				$this->redirect(array('admin'));
			}else
				Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
		}

		$this->render('create',array(
			'model'=>$model,
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
		
		if(isset($_POST['Holidays']))
		{
			$model->title=$_POST['Holidays']['title'];
			if($model->save()){
				Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
				$this->redirect(array('admin'));
			}else
				Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
		}

		$this->render('update',array(
			'model'=>$model,
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
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Holidays('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Holidays']))
			$model->attributes=$_GET['Holidays'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Holidays the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Holidays::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Holidays $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='holidays-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
