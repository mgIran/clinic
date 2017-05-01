<?php

class ClinicsPanelController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/panel';

    public static function actionsType()
    {
        return array(
            'frontend' => array(
                'enter'
            ),
            'backend' => array(
                'index',
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

    public function actionIndex()
    {
        Yii::app()->theme = 'frontend';


        $criteria = new CDbCriteria();
        $criteria->addCondition('clinic_id = :clinic_id');
        $criteria->params = array(
            ':clinic_id' => Users::model()->findByPk(Yii::app()->user->id)
        );
        $personalDataProvider = new CActiveDataProvider('ClinicPersonnels');

        $this->render('index', array());
    }

    public function actionEnter($id)
    {
        $model = ClinicPersonnels::model()->find('clinic_id = :clinic_id AND user_id = :user_id', array(':clinic_id' => $id, ':user_id' => Yii::app()->user->id));
        $role = UserRoles::model()->findByPk($model->post);

        Yii::app()->user->setState('roles', $role->role);
        Yii::app()->user->setState('clinic', $model->clinic);

        $this->redirect(array('/clinics/panel'));
    }

    public function actionLeave()
    {
        $user= Users::model()->findByPk(Yii::app()->user->id);

        Yii::app()->user->setState('roles', $user->role->role);
        Yii::app()->user->setState('clinic', null);

        $this->redirect(array('/dashboard'));
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
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new Clinics('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Clinics']))
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
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Clinics $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'clinics-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}