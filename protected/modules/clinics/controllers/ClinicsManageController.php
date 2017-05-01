<?php

class ClinicsManageController extends Controller
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
				'create', 'update', 'admin', 'delete', 'upload',
				'adminPersonnel', 'addPersonnel', 'addNewPersonnel', 'removePersonnel', 'updatePersonnel'
			)
		);
	}

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Clinics;

		$this->performAjaxValidation($model);

		if(isset($_POST['Clinics']))
		{
			$model->attributes=$_POST['Clinics'];
			$model->contracts = CJSON::encode($model->contracts);

			if($model->save()){
                Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
                $this->refresh();
            }else
                Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
		}

		$this->render('create',array(
			'model'=>$model,
            'towns'=>CHtml::listData(Towns::model()->findAll() , 'id' ,'name'),
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

		$this->performAjaxValidation($model);

		if(isset($_POST['Clinics']))
		{
			$model->attributes=$_POST['Clinics'];
            $model->contracts = CJSON::encode($model->contracts);

            if($model->save()){
                Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ویرایش شد.');
                $this->refresh();
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
		$model=new Clinics('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Clinics']))
			$model->attributes=$_GET['Clinics'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Clinics the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Clinics::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Clinics $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='clinics-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionAdminPersonnel($clinic){
		$model = new ClinicPersonnels();
		$model->unsetAttributes();
		if(isset($_GET['ClinicPersonnels']))
			$model->attributes = $_GET['ClinicPersonnels'];
		$model->clinic_id = $clinic;
		$this->render('admin_personnel',array(
			'model' => $model
		));
	}

	public function actionAddPersonnel($clinic)
	{
		$model = new ClinicPersonnels();
		$model->clinic_id = $clinic;
		if(isset($_POST['ClinicPersonnels'])){
			$model->user_id = $_POST['ClinicPersonnels']['user_id'];
			$model->post = $_POST['ClinicPersonnels']['post'];
			if($model->save()){
				Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
				$this->redirect(array('manage/adminPersonnel/'.$model->clinic_id));
			}else
				Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
		}

		$this->render('add_personnel', array(
			'model' => $model
		));
	}

	public function actionAddNewPersonnel($clinic)
	{
		$model = new ClinicPersonnels('add_personnel');
		$model->clinic_id = $clinic;
		if(isset($_POST['ClinicPersonnels'])){
			$userModel = new Users();
			$userModel->attributes = $_POST['ClinicPersonnels'];
			$userModel->role_id = $_POST['ClinicPersonnels']['post'];
			$userModel->status = 'active';
			$userModel->create_date = time();
			$userModel->password = $userModel->generatePassword();
			if($userModel->save()){
				$model->scenario = 'insert';
				$model->post = $_POST['ClinicPersonnels']['post'];
				$model->user_id = $userModel->id;
				if($model->save()){
					Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد. <span style="font-weight: 500;font-size: 18px">کلمه عبور کاربر: '.$userModel->generatePassword().'</span>');
					$this->redirect(array('manage/updatePersonnel/'.$model->clinic_id.'/'.$model->user_id));
				}else
					Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
			}else{
				$model->addErrors($userModel->errors);
				Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات کاربری خطایی رخ داده است! لطفا مجددا تلاش کنید.');
			}
		}

		$this->render('add_new_personnel', array(
			'model' => $model
		));
	}

	public function actionUpdatePersonnel($clinic, $person){
		$model = $this->loadPersonnelModel($clinic, $person);
		$model->scenario = 'update_personnel';
		$model->loadPropertyValues();
		if(isset($_POST['ClinicPersonnels'])){
			$model->post=$_POST['ClinicPersonnels']['post'];
			$model->loadPropertyValues($_POST['ClinicPersonnels']);
			$userModel = Users::model()->findByPk($model->user_id);
			$userModel->scenario = '';
			$userModel->attributes=$_POST['ClinicPersonnels'];
			if($userModel->save())
			{
				if($model->save()){
					Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
					$this->redirect(array('manage/adminPersonnel/'.$model->clinic_id));
				}else
					Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
			}else{
				$model->addErrors($userModel->errors);
				Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات کاربری خطایی رخ داده است! لطفا مجددا تلاش کنید.');
			}
		}
		$this->render('update_personnel',array(
			'model' => $model
		));
	}
	public function actionRemovePersonnel($clinic, $person){
		$this->loadPersonnelModel($clinic, $person)->delete();

		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('adminPersonnel'));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $clinic the Clinic ID of the model to be loaded
	 * @param integer $person the User ID of the model to be loaded
	 * @return ClinicPersonnels the loaded model
	 * @throws CHttpException
	 */
	public function loadPersonnelModel($clinic, $person)
	{
		$model=ClinicPersonnels::model()->findByAttributes(array('clinic_id' => $clinic, 'user_id' => $person));
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
