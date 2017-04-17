<?php

class UsersRolesController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = '//layouts/column2';

	public $pageTitle = 'مدیریت نقش مدیران';

	/**
	 * @return array actions type list
	 */
	public static function actionsType()
	{
		return array(
			'backend' => array(
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
			'checkAccess',
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'views' page.
	 */
	public function actionCreate()
	{
		$this->pageTitle = 'افزودن نقش جدید';
		$model = new UserRoles('create');

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if (isset($_POST['UserRoles'])) {
			$model->attributes = $_POST['UserRoles'];
			if ($model->save()) {
				$permissions=CJSON::decode($_POST['UserRoles']['permissions']);
				foreach($permissions as $module=>$controllers){
					foreach($controllers as $controller=>$actions){
						$permission=new UserRolePermissions();
						$permission->role_id=$model->id;
						$permission->module_id=$module;
						$permission->controller_id=$controller;
						$permission->actions=implode(',',$actions);
						$permission->save();
					}
				}

				Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
				$this->refresh();
			} else
				Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است.');
		}

		$backendActions=$this->getAllActions('backend');
		$frontendActions=$this->getAllActions('frontend');
		foreach($frontendActions as $module=>$controllers) {
            if (!key_exists($module, $backendActions))
                $backendActions[$module] = array();
            foreach ($controllers as $controller => $actions) {
                if (!key_exists($controller, $backendActions[$module]))
                    $backendActions[$module][$controller] = array();
                foreach ($actions as $action) {
                    if (!in_array($action, $backendActions[$module][$controller]))
                        array_push($backendActions[$module][$controller], $action);
                }
            }
        }

		$this->render('create', array(
			'model' => $model,
			'actions' => $backendActions,
		));
	}


	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'views' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$this->pageTitle = 'ویرایش نقش';
		$model = $this->loadModel($id);
		$model->setScenario('update');
		// Uncomment the following line if AJAX validation is needed

		$this->performAjaxValidation($model);

		if (isset($_POST['UserRoles'])) {
			$model->attributes = $_POST['UserRoles'];
			if ($model->save()) {
				UserRolePermissions::model()->deleteAll('role_id = :role_id', array(':role_id'=>$id));

				$permissions=CJSON::decode($_POST['UserRoles']['permissions']);
				foreach($permissions as $module=>$controllers){
					foreach($controllers as $controller=>$actions){
						$permission=new UserRolePermissions();
						$permission->role_id=$model->id;
						$permission->module_id=$module;
						$permission->controller_id=$controller;
						$permission->actions=implode(',',$actions);
						$permission->save();
					}
				}

				Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
				$this->refresh();
			} else
				Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است.');
		}

		$model->permissions=array();
		foreach($model->userRolePermissions as $permission){
			$actions=explode(',',$permission->actions);
			foreach($actions as $action)
				$model->permissions[]=$permission->module_id.'-'.$permission->controller_id.'-'.$action;
		}

        $backendActions=$this->getAllActions('backend');
        $frontendActions=$this->getAllActions('frontend');
        foreach($frontendActions as $module=>$controllers) {
            if (!key_exists($module, $backendActions))
                $backendActions[$module] = array();
            foreach ($controllers as $controller => $actions) {
                if (!key_exists($controller, $backendActions[$module]))
                    $backendActions[$module][$controller] = array();
                foreach ($actions as $action) {
                    if (!in_array($action, $backendActions[$module][$controller]))
                        array_push($backendActions[$module][$controller], $action);
                }
            }
        }

		$this->render('update', array(
			'model' => $model,
			'actions' => $backendActions,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if ($id != Yii::app()->user->id)
			$this->loadModel($id)->delete();
		// if AJAX request (triggered by deletion via admin grid views), we should not redirect the browser
		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model = new UserRoles('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['AdminsRoles']))
			$model->attributes = $_GET['AdminsRoles'];

		$this->render('admin', array(
			'model' => $model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Admins the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model = UserRoles::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Admins $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'admin-roles-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}