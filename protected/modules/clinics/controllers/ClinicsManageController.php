<?php

class ClinicsManageController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = '//layouts/column2';

	public static function actionsType()
	{
		return array(
			'frontend' => array(
				'view', 'create', 'update', 'admin', 'delete', 'upload',
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
			'checkAccess', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	public function actionView($id)
	{
		$this->render('view',array(
			'model' => $this->loadModel($id)
		));
	}
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model = new Clinics;

		$this->performAjaxValidation($model);

		if(isset($_POST['Clinics'])){
			$model->attributes = $_POST['Clinics'];
			$model->contracts = CJSON::encode($model->contracts);

			if($model->save()){
				Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
				$this->redirect(array('manage/adminPersonnel/'.$model->id));
			}else
				Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
		}

		$this->render('create', array(
			'model' => $model,
			'towns' => CHtml::listData(Towns::model()->findAll(), 'id', 'name'),
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id = null)
	{
		if(Yii::app()->user->type == 'user'){
			Yii::app()->theme = 'frontend';
			$this->layout = '//layouts/panel';
			$id = Yii::app()->user->clinic->id;
		}
		$model = $this->loadModel($id);

		$this->performAjaxValidation($model);

		if(isset($_POST['Clinics'])){
			$model->attributes = $_POST['Clinics'];
			$model->contracts = CJSON::encode($model->contracts);

			if($model->save()){
				Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ویرایش شد.');
				$this->refresh();
			}else
				Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
		}

		$this->render('update', array(
			'model' => $model,
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
			$this->redirect(isset($_POST['returnUrl'])?$_POST['returnUrl']:array('admin'));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model = new Clinics('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Clinics']))
			$model->attributes = $_GET['Clinics'];

		$this->render('admin', array(
			'model' => $model,
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
		$model = Clinics::model()->findByPk($id);
		if($model === null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Clinics $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax'] === 'clinics-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionAdminPersonnel($clinic)
	{
		$model = new ClinicPersonnels();
		$model->unsetAttributes();
		if(isset($_GET['ClinicPersonnels']))
			$model->attributes = $_GET['ClinicPersonnels'];
		$model->clinic_id = $clinic;
		$this->render('admin_personnel', array(
			'model' => $model
		));
	}

	public function actionAddPersonnel($clinic = null)
	{
		if(Yii::app()->user->type == 'user'){
			Yii::app()->theme = 'frontend';
			$this->layout = '//layouts/panel';
			$clinic = Yii::app()->user->clinic->id;
		}
		$model = new ClinicPersonnels();
		$model->clinic_id = $clinic;
		if(isset($_POST['ClinicPersonnels'])){
			$model->user_id = $_POST['ClinicPersonnels']['user_id'];
			$model->post = $_POST['ClinicPersonnels']['post'];
			if($model->save()){
				Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
				$this->redirect(Yii::app()->user->type == 'user'?array('/clinics/panel/'):
					array('manage/adminPersonnel/' . $model->clinic_id));
			}else
				Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
		}

		$this->render('add_personnel', array(
			'model' => $model
		));
	}

	public function actionAddNewPersonnel($clinic = null)
	{
		if(Yii::app()->user->type == 'user'){
			Yii::app()->theme = 'frontend';
			$this->layout = '//layouts/panel';
			$clinic = Yii::app()->user->clinic->id;
		}
		$model = new ClinicPersonnels('add_personnel');
		$model->clinic_id = $clinic;
		if(isset($_POST['ClinicPersonnels'])){
			$model->loadPropertyValues($_POST['ClinicPersonnels']);
			$userModel = new Users();
			$userModel->attributes = $_POST['ClinicPersonnels'];
			$userModel->loadPropertyValues($_POST['ClinicPersonnels']);
			$userModel->role_id = $_POST['ClinicPersonnels']['post'];
			$userModel->status = 'pending';
			$userModel->create_date = time();
			$userModel->password = $userModel->generatePassword();
			$pwd = $userModel->password;
			if($userModel->save() && !$userModel->hasErrors()){
				$token = md5($userModel->id . '#' . $userModel->password . '#' . $userModel->email . '#' . $userModel->create_date);
				$userModel->updateByPk($userModel->id, array('verification_token' => $token));
				$message = '<div style="color: #2d2d2d;font-size: 14px;text-align: right;">با سلام<br>حساب کاربری شما در وبسایت ' . Yii::app()->name . ' ایجاد گردید.<br>اطلاعات حساب کاربری شما به شرح زیر است:<br>';
				$message .= '<strong>نام کاربری: </strong>' . $userModel->email . '<br>';
				$message .= '<strong>کلمه عبور: </strong>' . $userModel->generatePassword() . '<br>';
				$message .= 'به دلایل امنیتی لطفا در اسرع وقت از طریق داشبورد حساب کاربری خود نسبت به تغییر کلمه عبور اقدام فرمایید.<br>';
				$message .= 'برای فعال کردن حساب کاربری خود در ' . Yii::app()->name . ' بر روی لینک زیر کلیک کنید:';
				$message .= '</div>';
				$message .= '<div style="text-align: right;font-size: 9pt;">';
				$message .= '<a href="' . Yii::app()->getBaseUrl(true) . '/users/public/verify/token/' . $token . '">' . Yii::app()->getBaseUrl(true) . '/users/public/verify/token/' . $token . '</a>';
				$message .= '</div>';
				$message .= '<div style="font-size: 8pt;color: #888;text-align: right;">این لینک فقط 3 روز اعتبار دارد.</div>';
				@Mailer::mail($model->email, 'ایجاد حساب کاربری', $message, Yii::app()->params['noReplyEmail'], Yii::app()->params['SMTP']);
				// Send Sms
				$siteName = Yii::app()->name;
				$message = "ثبت نام شما در سایت {$siteName} با موفقیت انجام شد.
نام کاربری: {$userModel->mobile}
کلمه عبور: {$pwd}";
				$phone = $userModel->mobile;
				if($phone)
					Notify::SendSms($message, $phone);
				$model->scenario = 'insert';
				$model->post = $_POST['ClinicPersonnels']['post'];
				$model->user_id = $userModel->id;
				if($model->post != 3 && $model->post != 2)
					$model->expertise = null;
				if($model->save()){
					Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد. <span style="font-weight: 500;font-size: 18px">کلمه عبور کاربر: ' . $userModel->generatePassword() . '</span>');
					$this->redirect(array('manage/updatePersonnel/' . $model->clinic_id . '/' . $model->user_id));
				}else
					Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
			}else{
				$model->addErrors($userModel->errors);
				$userModel->delete();
				Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات کاربری خطایی رخ داده است! لطفا مجددا تلاش کنید.');
			}
		}

		$this->render('add_new_personnel', array(
			'model' => $model
		));
	}

	public function actionUpdatePersonnel($clinic = null, $person = null, $id = null)
	{
		if(Yii::app()->user->type == 'user'){
			Yii::app()->theme = 'frontend';
			$this->layout = '//layouts/panel';
			if(!$person && $id)
				$person = $id;
			$clinic = Yii::app()->user->clinic->id;
		}
		$model = $this->loadPersonnelModel($clinic, $person);
		$model->scenario = 'update_personnel';
		$model->loadPropertyValues();
		$model->user->loadPropertyValues();
		if(isset($_POST['ClinicPersonnels'])){
			$model->post = $_POST['ClinicPersonnels']['post'];
			$model->loadPropertyValues($_POST['ClinicPersonnels']);
			$userModel = Users::model()->findByPk($model->user_id);
			$userModel->scenario = 'update';
			$userModel->attributes = $_POST['ClinicPersonnels'];
			$userModel->loadPropertyValues($_POST['ClinicPersonnels']);
			if($userModel->save() && !$userModel->hasErrors()){
				if($model->post != 3 && $model->post != 2)
					$model->expertise = null;
				if($model->save()){
					Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
					$this->redirect(Yii::app()->user->type == 'user'?array('/clinics/panel/'):
						array('manage/adminPersonnel/' . $model->clinic_id));
				}else
					Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
			}else{
				$model->addErrors($userModel->errors);
				$userModel->delete();
				Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات کاربری خطایی رخ داده است! لطفا مجددا تلاش کنید.');
			}
		}
		$this->render('update_personnel', array(
			'model' => $model
		));
	}

	public function actionRemovePersonnel($clinic = null, $person = null, $id = null)
	{
		if(Yii::app()->user->type == 'user'){
			Yii::app()->theme = 'frontend';
			$this->layout = '//layouts/panel';
			if(!$person && $id)
				$person = $id;
			$clinic = Yii::app()->user->clinic->id;
		}
		$this->loadPersonnelModel($clinic, $person)->delete();

		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl'])?$_POST['returnUrl']:array('adminPersonnel'));
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
		$model = ClinicPersonnels::model()->findByAttributes(array('clinic_id' => $clinic, 'user_id' => $person));
		if($model === null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}
}