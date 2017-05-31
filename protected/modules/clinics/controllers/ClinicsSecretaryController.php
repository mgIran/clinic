<?php

class ClinicsSecretaryController extends Controller
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
                'doctors', 'visits', 'clinicChecked', 'clinicVisited', 'removeReserve',
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

    
    public function actionDoctors()
    {
        Yii::app()->theme = 'frontend';
        $clinicID = Yii::app()->user->clinic->id;

        $model = new ClinicPersonnels('search');
        if(isset($_GET['ClinicPersonnels']))
            $model->attributes = $_GET['ClinicPersonnels'];
        $model->clinic_id = $clinicID;
        $model->post = [2,3];

        $this->render('doctors', array(
            'model' => $model
        ));
    }

    public function actionVisits($id)
    {
        $date = false;
        if(isset($_GET['date']))
            $date = $_GET['date'];

        Yii::app()->theme = 'frontend';
        $doctorID = $id;
        $clinicID = Yii::app()->user->clinic->id;

        $model = new Visits('search');
        $model->unsetAttributes();
        if(isset($_GET['Visits']))
            $model->attributes = $_GET['Visits'];
        $model->clinic_id = $clinicID;
        $model->doctor_id = $doctorID;
        $model->date = $date?$date:time();
        $today = $date?false:true;
        if(!$date){
            if(!$model->time)
                $model->time = date('H') < 12?1:2;
        }else
            $model->time = null;

        if(Yii::app()->request->isAjaxRequest && !isset($_GET['ajax'])){
            echo CJSON::encode(['status' => true,
                'all' => Controller::parseNumbers(Visits::getAllVisits(Yii::app()->user->clinic->id, $doctorID, $model->date, $model->time)),
                'accepted' => Controller::parseNumbers(Visits::getAllVisits(Yii::app()->user->clinic->id, $doctorID, $model->date, $model->time, Visits::STATUS_ACCEPTED)),
                'checked' => Controller::parseNumbers(Visits::getAllVisits(Yii::app()->user->clinic->id, $doctorID, $model->date, $model->time, Visits::STATUS_CLINIC_CHECKED)),
                'visited' => Controller::parseNumbers(Visits::getAllVisits(Yii::app()->user->clinic->id, $doctorID, $model->date, $model->time, Visits::STATUS_CLINIC_VISITED)),
                'visiting' => Controller::parseNumbers(Visits::getNowVisit(Yii::app()->user->clinic->id, $doctorID,$model->date, $model->time)),
            ]);
            Yii::app()->end();
        }
        $this->render('visits', array(
            'model' => $model,
            'doctorID' => $doctorID,
            'today' => $today
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

    public function actionRemoveReserve($id)
    {
        Yii::app()->theme = 'frontend';
        $model = Visits::model()->findByPk($id);
        $model->status = Visits::STATUS_DELETED;
        if($model->save()){
            $send = false;
            if($model->date > strtotime(date('Y/m/d 23:59', time()))){
                $send = true;
                $date = JalaliDate::date('Y/m/d', $model->date);
                $time = $model->time == 'am'?'صبح':'بعدازظهر';
                $message = "نوبت شما با کدرهگیری {$model->tracking_code} که در تاریخ {$date} نوبت {$time} رزرو شده بود، توسط منشی لغو گردید.";
            }elseif($model->date == strtotime(date('Y/m/d 00:00', time()))){
                $send = true;
                $time = $model->time == 'am'?'صبح':'بعدازظهر';
                $message = "نوبت شما با کدرهگیری {$model->tracking_code} که برای امروز نوبت {$time} رزرو شده بود، توسط منشی لغو گردید.";
            }

            if($send && $model->user && $model->user->userDetails && $model->user->userDetails->mobile){
                $phone = $model->user->userDetails->mobile;
                Notify::SendSms($message, $phone);
            }
        }
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

    public function actionMonitoring(){
        $clinicID = Yii::app()->user->clinic->id;
        $clinicID = Yii::app()->user->clinic->id;
    }
}