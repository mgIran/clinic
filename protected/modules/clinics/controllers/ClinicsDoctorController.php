<?php

class ClinicsDoctorController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/panel';

    public static function actionsType()
    {
        return array(
            'backend' => array(
                'index', 'schedules',
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
     * @param ClinicPersonnels $clinic
     */
    public function actionIndex($clinic = null)
    {
        Yii::app()->theme = 'frontend';

        $clinic = Yii::app()->user->getState('clinic');

        $personnel = new ClinicPersonnels();
        $personnel->unsetAttributes();
        if(isset($_GET['ClinicPersonnels']))
            $personnel->attributes = $_GET['ClinicPersonnels'];
        $personnel->clinic_id = $clinic->id;
        if(Yii::app()->user->roles == 'clinicAdmin'){
            $personnel->post = [4, 3];
        }elseif(Yii::app()->user->roles == 'doctor'){
            $personnel->post = 4;
        }

        $this->render('index', array(
            'clinic' => $clinic,
            'personnel' => $personnel,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionSchedules()
    {
        Yii::app()->theme = 'frontend';
        $user = Users::model()->findByPk(Yii::app()->user->getId());
        $model = $user->doctorSchedules;
//var_dump($model);exit;
        $this->render('schedules', array(
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
}