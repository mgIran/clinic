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
        $userID = Yii::app()->user->getId();
        $clinicID = Yii::app()->user->clinic->id;
        $user = Users::model()->findByPk($userID);
        $model = $user->doctorSchedules(array('clinic_id' => $clinicID));
        $temp = [];
        foreach($model as $item)
            $temp[$item->week_day] = $item;
        $model = $temp;
        if(isset($_POST['DoctorSchedules'])){
            $flag = true;
            $errors = [];
            foreach($_POST['DoctorSchedules'] as $key => $values){
                $row = DoctorSchedules::model()->findByAttributes(array(
                    'clinic_id' => $clinicID,
                    'doctor_id' => $userID,
                    'week_day' => $key
                ));
                if(isset($values['week_day']) && $values['week_day'] == $key){
                    if($row === null){
                        $row = new DoctorSchedules();
                        $row->clinic_id = $clinicID;
                        $row->doctor_id = $userID;
                        $row->week_day = $key;
                    }
                    $row->attributes = $values;
                    if(!$row->save()){
                        $flag = false;
                        $errors = CMap::mergeArray($errors, $row->errors);
                    }

                }elseif($row !== null)
                    $row->delete();
            }
            if($flag){
                Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
                $this->refresh();
            }else
                Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
        }

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