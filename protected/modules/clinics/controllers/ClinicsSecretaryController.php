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
//                'expertises',
                'doctors', 'visits', 'clinicChecked', 'clinicVisited', 'removeReserve', 'schedules', 'leaves', 'removeLeaves',
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
        $action = isset($_GET['action']) && !empty($_GET['action'])?$_GET['action']:'visits';
        $clinicID = Yii::app()->user->clinic->id;
        if(1){
            $doctors = Yii::app()->user->getState('doctors');
            if($doctors && count($doctors) == 1)
                $this->redirect(Yii::app()->createUrl("/clinics/secretary/{$action}/{$doctors[0]}/"));
        }
        $model = new ClinicPersonnels('search');
        if(isset($_GET['ClinicPersonnels']))
            $model->attributes = $_GET['ClinicPersonnels'];
        $model->clinic_id = $clinicID;
        $model->post = [2, 3];

        $this->render('doctors', array(
            'model' => $model,
            'action' => $action
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

        if(isset($_POST['Visits']['date']) && !empty($_POST['Visits']['date']))
            $this->redirect(array('secretary/visits/'.$id.'?date=' . $_POST['Visits']['date']));

        $model = new Visits('search');
        $model->unsetAttributes();
        if(isset($_GET['Visits']))
            $model->attributes = $_GET['Visits'];
        $model->clinic_id = $clinicID;
        $model->doctor_id = $doctorID;
        $model->date = $date?$date:time();
        $today = $date?false:true;
        $model->status = [Visits::STATUS_ACCEPTED,Visits::STATUS_CLINIC_CHECKED,Visits::STATUS_CLINIC_VISITED];
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
                'visiting' => Controller::parseNumbers(Visits::getNowVisit(Yii::app()->user->clinic->id, $doctorID, $model->date, $model->time)),
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
        $lastStatus = $model->status;
        $model->status = Visits::STATUS_DELETED;
        if($model->save() && $lastStatus == Visits::STATUS_ACCEPTED){
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

    public function actionExpertises($id)
    {
        Yii::app()->theme = 'frontend';
        $userID = $id;
        $clinicID = Yii::app()->user->clinic->id;
        $model = $this->loadPersonnelModel($clinicID, $userID);
        $model->loadPropertyValues();
        if(isset($_POST['ClinicPersonnels'])){
            $model->loadPropertyValues($_POST['ClinicPersonnels']);
            if($model->post != 3 && $model->post != 2)
                $model->expertise = null;
            if($model->save()){
                Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
                $this->refresh();
            }else
                Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
        }

        $this->render('expertises', array(
            'model' => $model
        ));
    }

    /**
     * @param $id
     * @throws CDbException
     */
    public function actionSchedules($id)
    {
        Yii::app()->theme = 'frontend';
        $userID = $id;
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
            'doctor' => $user,
            'errors' => $errors
        ));
    }

    public function actionLeaves($id)
    {
        Yii::app()->theme = 'frontend';
        $userID = $id;
        $clinicID = Yii::app()->user->clinic->id;
        $user = Users::model()->findByPk($userID);

        $visitsExists = false;
        // insert new leaves
        $model = new DoctorLeaves();
        if(isset($_POST['DoctorLeaves']) && isset($_POST['insert']) && $_POST['insert'] == true){
            $flag = true;
            $model->date = strtotime(date("Y/m/d", $_POST['DoctorLeaves']['date']) . " 00:00");
            $model->doctor_id = $userID;
            $model->clinic_id = $clinicID;
            if($model->validate()){
                $startDate = $model->date;
                $endDate = $startDate + 24 * 60 * 60 - 1;
                $criteria = new CDbCriteria();
                $criteria->compare('clinic_id', $clinicID);
                $criteria->compare('doctor_id', $userID);
                $criteria->addBetweenCondition('date', $startDate, $endDate);
                $criteria->addCondition('status > 1');
                $visitsExists = Visits::model()->findAll($criteria);
                if(isset($_POST['visitsExists']) && $_POST['visitsExists'] == true){
                    $nowTime = date('a', time());
                    foreach($visitsExists as $item){
                        $lastStatus = $item->status;
                        $item->status = Visits::STATUS_DELETED;
                        if($item->save() && $lastStatus == Visits::STATUS_ACCEPTED){
                            $send = false;
                            if($item->date > strtotime(date('Y/m/d 23:59', time()))){
                                $send = true;
                                $date = JalaliDate::date('Y/m/d', $item->date);
                                $time = $item->time == 'am'?'صبح':'بعدازظهر';
                                $message = "نوبت شما با کدرهگیری {$item->tracking_code} که در تاریخ {$date} نوبت {$time} رزرو شده بود، بدلیل مرخصی پزشک لغو گردید.";
                            }elseif($item->date == strtotime(date('Y/m/d 00:00', time()))){
                                if($nowTime == 'am' || ($nowTime == 'pm' && $item->time == 'pm')){
                                    $send = true;
                                    $time = $item->time == 'am'?'صبح':'بعدازظهر';
                                    $message = "نوبت شما با کدرهگیری {$item->tracking_code} که برای امروز نوبت {$time} رزرو شده بود، بدلیل مرخصی پزشک لغو گردید.";
                                }
                            }
                            if($send && $item->user && $item->user->userDetails && $item->user->userDetails->mobile){
                                $phone = $item->user->userDetails->mobile;
                                Notify::SendSms($message, $phone);
                            }
                        }
                    }
                    $flag = true;
                }elseif($visitsExists)
                    $flag = false;
            }
            if($flag){
                if($model->save()){
                    Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
                    $this->refresh();
                }else
                    Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
            }else
                Yii::app()->user->setFlash('warning', 'در این روز نوبت رزرو شده است. لطفا رزروها را مدیریت کرده و سپس مرخصی را ثبت کنید.');
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
            'doctor' => $user,
            'search' => $search,
            'visitsExists' => $visitsExists
        ));
    }

    public function actionRemoveLeaves($id)
    {
        if(isset($_GET['lid']) && !empty((int)$_GET['lid'])){
            $lid = $_GET['lid'];
            Yii::app()->theme = 'frontend';
            $userID = $id;
            $clinicID = Yii::app()->user->clinic->id;
            $model = $this->loadLeavesModel($lid);
            if($model->doctor_id == $userID && $model->clinic_id == $clinicID)
                $model->delete();
            if(!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl'])?$_POST['returnUrl']:array('admin'));
        }
    }

    public function actionMonitoring()
    {
        $clinicID = Yii::app()->user->clinic->id;
        $clinicID = Yii::app()->user->clinic->id;
    }
}