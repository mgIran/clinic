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
                'index', 'schedules', 'leaves', 'removeLeaves', 'reserves', 'removeReserve', 'clinicChecked', 'clinicVisited',
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
        }elseif(Yii::app()->user->roles == 'secretary'){
            $personnel->post = [4, 3];
        }

        $this->render('index', array(
            'clinic' => $clinic,
            'personnel' => $personnel,
        ));
    }

    public function actionReserves()
    {
        Yii::app()->theme = 'frontend';
        $userID = Yii::app()->user->getId();
        $clinicID = Yii::app()->user->clinic->id;

        $model = new Visits('search');
        $model->unsetAttributes();
        if(isset($_GET['Visits']))
            $model->attributes = $_GET['Visits'];
        $model->clinic_id = $clinicID;
        $model->doctor_id = $userID;
        $model->date = time();
        $model->time = date('H')<12?1:2;
//        $model->status = Visits::STATUS_CLINIC_CHECKED;

        if(Yii::app()->request->isAjaxRequest && !isset($_GET['ajax'])){
            echo CJSON::encode(['status' => true,
                'all' => Controller::parseNumbers(Visits::getAllVisits(Yii::app()->user->clinic->id, Yii::app()->user->id, $model->date,$model->time)),
                'accepted' => Controller::parseNumbers(Visits::getAllVisits(Yii::app()->user->clinic->id, Yii::app()->user->id, $model->date, $model->time, Visits::STATUS_ACCEPTED)),
                'checked' => Controller::parseNumbers(Visits::getAllVisits(Yii::app()->user->clinic->id, Yii::app()->user->id, $model->date, $model->time, Visits::STATUS_CLINIC_CHECKED)),
                'visited' => Controller::parseNumbers(Visits::getAllVisits(Yii::app()->user->clinic->id, Yii::app()->user->id, $model->date, $model->time, Visits::STATUS_CLINIC_VISITED)),
                'visiting' => Controller::parseNumbers(Visits::getNowVisit(Yii::app()->user->clinic->id, Yii::app()->user->id,$model->date, $model->time)),
            ]);
            Yii::app()->end();
        }

        $this->render('reserves', array(
            'model' => $model
        ));
    }

    public function actionClinicChecked($id)
    {
        Yii::app()->theme = 'frontend';
        $model = Visits::model()->findByPk($id);
        $model->status = Visits::STATUS_CLINIC_CHECKED;
        $model->check_date = time();
        $model->clinic_checked_number = $model->getGenerateNewVisitNumber();
        if($model->save())
            echo CJSON::encode(['status' => true]);
        else
            echo CJSON::encode(['status' => false, 'msg' => 'متاسفانه مشکلی در اعمال تغییرات بوجو آمده است! لطفا مجددا بررسی فرمایید.']);
    }

    public function actionClinicVisited($id)
    {
        Yii::app()->theme = 'frontend';
        $model = Visits::model()->findByPk($id);
        $model->status = Visits::STATUS_CLINIC_VISITED;
        if($model->save())
            echo CJSON::encode(['status' => true]);
        else
            echo CJSON::encode(['status' => false, 'msg' => 'متاسفانه مشکلی در اعمال تغییرات بوجو آمده است! لطفا مجددا بررسی فرمایید.']);
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
        $model = $user->doctorSchedules(array('condition' => 'clinic_id = :clinic_id', 'params' => array(':clinic_id' => $clinicID)));
        $temp = [];
        foreach($model as $item)
            $temp[$item->week_day] = $item;
        $model = $temp;

        $errors = [];

        if(isset($_POST['DoctorSchedules'])){
            $flag = true;
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
                        $errors[$key] = $row->errors['error'];
                    }
                    $model[$key] = $row;
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
            'errors' => $errors
        ));
    }

    public function actionLeaves()
    {
        Yii::app()->theme = 'frontend';
        $userID = Yii::app()->user->getId();
        $clinicID = Yii::app()->user->clinic->id;

        // insert new leaves
        $model = new DoctorLeaves();
        if(isset($_POST['DoctorLeaves']) && isset($_POST['insert']) && $_POST['insert'] == true){
            $model->date = strtotime(date("Y/m/d", $_POST['DoctorLeaves']['date']) . " 00:00");
            $model->doctor_id = $userID;
            $model->clinic_id = $clinicID;
            if($model->save()){
                Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
                $this->refresh();
            }else
                Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
        }

        // Get CActiveDataProvider for grid
        $search = new DoctorLeaves('search');
        $search->unsetAttributes();
        if(isset($_POST['DoctorLeaves']) && !isset($_POST['insert']))
            $search->attributes = $_POST['DoctorLeaves'];
        $search->clinic_id = $clinicID;
        $search->doctor_id = $userID;

        $this->render('leaves', array(
            'model' => $model,
            'search' => $search
        ));
    }

    public function actionRemoveLeaves($id)
    {
        Yii::app()->theme = 'frontend';
        $userID = Yii::app()->user->getId();
        $clinicID = Yii::app()->user->clinic->id;
        $model = $this->loadLeavesModel($id);
        if($model->doctor_id == $userID && $model->clinic_id == $clinicID)
            $model->delete();
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl'])?$_POST['returnUrl']:array('admin'));
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
     * @param $id
     * @return DoctorLeaves
     * @throws CHttpException
     */
    public function loadLeavesModel($id)
    {
        $model = DoctorLeaves::model()->findByPk($id);
        if($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
}