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
                'enter',
                'personnel',
                'index',
                'removeVisit'
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
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * @param ClinicPersonnels $clinic
     */
    public function actionIndex($clinic = null)
    {
        Yii::app()->theme = 'frontend';
        $clinic = Yii::app()->user->getState('clinic');
        $doctors = Yii::app()->user->getState('doctors');

        if(Yii::app()->user->roles == 'secretary'){
            if($doctors && count($doctors) == 1)
                $this->redirect(Yii::app()->createUrl("/clinics/secretary/visits/".$doctors[0]."/?Visits[time]=".ClinicPersonnels::getNowTime()));
            $this->redirect(array('/clinics/secretary/doctors'));
        }

        $personnel = new ClinicPersonnels();
        $personnel->unsetAttributes();
        if(isset($_GET['ClinicPersonnels']))
            $personnel->attributes = $_GET['ClinicPersonnels'];
        $personnel->clinic_id = $clinic->id;
        if(Yii::app()->user->roles == 'clinicAdmin'){
            $personnel->post = [4,3];
        }
        elseif(Yii::app()->user->roles == 'doctor'){
            $personnel->post = 4;
        }

        $this->render('index', array(
            'clinic' => $clinic,
            'personnel' => $personnel,
        ));
    }

    public function actionPersonnel($clinic = null)
    {
        Yii::app()->theme = 'frontend';
        $clinic = Yii::app()->user->getState('clinic');

        $personnel = new ClinicPersonnels();
        $personnel->unsetAttributes();
        if(isset($_GET['ClinicPersonnels']))
            $personnel->attributes = $_GET['ClinicPersonnels'];
        $personnel->clinic_id = $clinic->id;
        if(Yii::app()->user->roles == 'clinicAdmin'){
            $personnel->post = [4,3];
        }
        elseif(Yii::app()->user->roles == 'doctor'){
            $personnel->post = 4;
        }

        $this->render('index', array(
            'clinic' => $clinic,
            'personnel' => $personnel,
        ));
    }

    public function actionEnter($id)
    {
        $model = ClinicPersonnels::model()->find('clinic_id = :clinic_id AND user_id = :user_id', array(':clinic_id' => $id, ':user_id' => Yii::app()->user->id));
        $role = UserRoles::model()->findByPk($model->post);

        $doctors = ClinicPersonnels::model()->findAllByAttributes(array(
            'clinic_id' => $model->clinic_id,
            'post' => [2,3]
        ),array('select' => 'user_id'));
        $doctorsArray = [];
        foreach($doctors as $key=>$doctor){
            $doctorsArray[$key] = $doctor->user_id;
        }

        Yii::app()->user->setState('roles', $role->role);
        Yii::app()->user->setState('clinic', $model->clinic);
        Yii::app()->user->setState('doctors', $doctorsArray);

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
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionRemoveVisit($id)
    {
        Visits::model()->findByPk($id)->delete();

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