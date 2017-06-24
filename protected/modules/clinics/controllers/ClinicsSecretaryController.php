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
                'addNewVisit', 'search', 'selectDoctor', 'selectSchedule', 'selectTime', 'info', 'checkout',
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
        if(isset($_GET['print']) && $_GET['print']==true)
            $this->layout = '//layouts/print';

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

    public function actionAddNewVisit()
    {
        Yii::app()->theme = "frontend";
        $this->layout = "//layouts/panel";
        
        
        $expertises = Expertises::model()->findAll();
        $this->render('add_new_visit',array(
            'expertises' => $expertises,
            'active' => 1
        ));
    }

    public function actionSearch($id)
    {
        Yii::app()->theme = 'frontend';
        $this->layout = '//layouts/panel';

        if(!Yii::app()->user->hasState("reservation"))
            Yii::app()->user->setState("reservation",['expertiseID'=> $id]);
        $clinicPersonnel = new ClinicPersonnels('getDoctorsByExp');
        $clinicPersonnel->unsetAttributes();
        if (isset($_GET['ClinicPersonnels']))
            $clinicPersonnel->attributes = $_GET['ClinicPersonnels'];
        $clinicPersonnel->expertiseID = $id;
        $clinicPersonnel->clinic_id = Yii::app()->user->clinic->id;

        $this->render('add_new_visit', array(
            'doctors' => $clinicPersonnel,
            'active' => 2,
        ));
    }

    public function actionSelectDoctor()
    {
        if (isset($_POST['Reservation'])) {
            Yii::app()->user->setState('reservation', array(
                'doctorID' => $_POST['Reservation']['doctor_id'],
                'clinicID' => $_POST['Reservation']['clinic_id'],
                'expertiseID' => $_POST['Reservation']['expertise_id'],
            ));

            $this->redirect('selectSchedule');
        } else
            $this->redirect('search/'.Yii::app()->user->reservation['expertiseID']);

    }

    public function actionSelectSchedule()
    {
        Yii::app()->theme = 'frontend';
        $this->layout = '//layouts/panel';

        $renderOutput = array();
        $flag = true;
        if(isset($_POST['from']) and isset($_POST['to'])){
            if($_POST['from_altField'] == $_POST['to_altField'])
            {
                Yii::app()->user->setFlash('failed', 'تاریخ ها یکسان می باشند.');
                $flag = false;
            }
            elseif($_POST['from_altField'] > $_POST['to_altField'])
            {
                Yii::app()->user->setFlash('failed', 'تاریخ های انتخاب شده اشتباه است.');
                $flag = false;
            }
            elseif($_POST['from_altField'] < strtotime(date('Y/m/d 00:00',time())) || $_POST['to_altField'] < strtotime(date('Y/m/d 00:00',time())))
            {
                Yii::app()->user->setFlash('failed', 'تاریخ ها معتبر نمی باشند.');
                $flag = false;
            }
            else{
                $flag = true;
                $from = $_POST['from_altField'];
                $to = $_POST['to_altField'];
            }
        }else
            $flag = false;
        if(!$flag){
            $from = strtotime(date('Y/m/d 00:00',time()));
            $currentMonth = JalaliDate::date('m', $from, false);
            $currentYear = JalaliDate::date('Y', $from, false);
            $monthDaysCount = 30;
            if($currentMonth <= 6)
                $monthDaysCount = 31;
            elseif($currentMonth == 12 and !JalaliDate::date('L', $from, false))
                $monthDaysCount = 29;
            $endMonth = JalaliDate::mktime(23, 59, 59, $currentMonth, $monthDaysCount, $currentYear);
            $to = $endMonth;
        }
        if($from && $to){
            $user = Users::model()->findByPk(Yii::app()->user->reservation['doctorID']);
            $criteria = new CDbCriteria();
            $criteria->compare('clinic_id',Yii::app()->user->reservation['clinicID']);
            /* @var $schedules DoctorSchedules[] */
            $schedules = $user->doctorSchedules($criteria);
            $leaves = $user->doctorLeaves($criteria);
            $weekDays = CHtml::listData($schedules, 'week_day', 'week_day');
            $leaveDays = CHtml::listData($leaves, 'id', 'date');
            $now = strtotime(date('Y/m/d 00:00',time()));
            $from = strtotime(date('Y/m/d 00:00',$from));
            $fromIsToday = $now == $from?true:false;
            $to = strtotime(date('Y/m/d 23:59',$to));
            $daysCount = ($to - $from) / (60 * 60 * 24);
            $days = array();
            for($i = 0;$i <= $daysCount;$i++){
                $dayTimestamp = strtotime(date('Y/m/d 00:00', $from + ($i * (60 * 60 * 24))));
                if((int)Holidays::model()->countByAttributes(['date' => $dayTimestamp]) ===0){
                    if(in_array(JalaliDate::date('N', $dayTimestamp, false), $weekDays)){
                        if(!in_array(strtotime(date('Y/m/d 00:00', $dayTimestamp)), $leaveDays)){
                            if($dayTimestamp >= $from){
                                foreach($schedules as $key => $schedule)
                                    if($schedule->week_day == JalaliDate::date('N', $dayTimestamp, false)){
                                        $checkAM = true;
                                        if($i===0 && $fromIsToday && date('a',time()) != 'am')
                                            $checkAM = false;
                                        if($checkAM && !is_null($schedule->times['AM'])){
                                            $AMVisitsCount = Visits::getAllVisits(Yii::app()->user->reservation['clinicID'], Yii::app()->user->reservation['doctorID'], $dayTimestamp, Visits::TIME_AM, array(Visits::STATUS_PENDING, Visits::STATUS_DELETED), 'NOT IN');
                                            if($AMVisitsCount != $schedule->visit_count_am)
                                                $days[$dayTimestamp]['AM'] = $schedule->times['AM'];
                                        }

                                        if(!is_null($schedule->times['PM'])){
                                            $PMVisitsCount = Visits::getAllVisits(Yii::app()->user->reservation['clinicID'], Yii::app()->user->reservation['doctorID'], $dayTimestamp, Visits::TIME_PM, array(Visits::STATUS_PENDING, Visits::STATUS_DELETED), 'NOT IN');
                                            if($PMVisitsCount != $schedule->visit_count_pm)
                                                $days[$dayTimestamp]['PM'] = $schedule->times['PM'];
                                        }
                                    }
                            }
                        }
                    }
                }
            }
            $renderOutput = array(
                'days' => $days,
                'doctor' => $user,
                'from' => $from,
                'to' => $to,
                'clinic' => Clinics::model()->findByPk(Yii::app()->user->reservation['clinicID']),
                'active' => 3,
            );
        }

        $this->render('add_new_visit', $renderOutput);
    }

    public function actionSelectTime()
    {
        if (isset($_GET['d']) and isset($_GET['t'])) {
            $reservation = array(
                'doctorID' => Yii::app()->user->reservation['doctorID'],
                'clinicID' => Yii::app()->user->reservation['clinicID'],
                'expertiseID' => Yii::app()->user->reservation['expertiseID'],
                'date' => Yii::app()->request->getQuery('d'),
                'time' => Yii::app()->request->getQuery('t')
            );
            $schedule = DoctorSchedules::model()->findByAttributes(array(
                'doctor_id'=> $reservation['doctorID'],
                'clinic_id'=> $reservation['clinicID'],
                'week_day'=> (int)JalaliDate::date('w',$reservation['date'],false)+1,
            ));
            $reservation['visitStartTime'] = $schedule->{'entry_time_'.$reservation['time']};
            $reservation['visitEndTime'] = $schedule->{'exit_time_'.$reservation['time']};
            Yii::app()->user->setState('reservation', $reservation);

            $this->redirect('info');
        } else
            $this->redirect('selectSchedule');
    }

    public function actionInfo()
    {
        Yii::app()->theme = 'frontend';
        $this->layout = '//layouts/panel';
        $saveResult = false;

        $user = new Users();
        $user->setScenario('reserve_register');
        if(isset($_POST['Users'])){
            if(empty($_POST['Users']['mobile']))
                Yii::app()->user->setFlash('failed', 'تلفن همراه نمی تواند خالی باشد.');
            else{
                /* @var $existUser Users */
                $existUser = Users::model()->find('national_code = :national_code', array(':national_code' => $_POST['Users']['national_code']));
                if(!$existUser){
                    $user->email = $_POST['Users']['email'];
                    $user->national_code = $_POST['Users']['national_code'];
                    $user->role_id = UserRoles::model()->find('role = :role', array(':role' => 'user'))->id;
                    $user->create_date = time();
                    $user->status = 'active';
                    $user->change_password_request_count = 0;
                    $user->auth_mode = 'site';
                    $user->password = $user->generatePassword();
                    $user->mobile = $_POST['Users']['mobile'];
                    $user->first_name = $_POST['Users']['first_name'];
                    $user->last_name = $_POST['Users']['last_name'];
                    // for sms message
                    $pwd = $user->password;
                    $username = $user->national_code;

                    if($user->save()){
                        $userDetails = $user->userDetails;
                        $userDetails->user_id = $user->id;
                        $userDetails->first_name = $user->first_name;
                        $userDetails->last_name = $user->last_name;
                        $userDetails->mobile = $user->mobile;

                        // Send Sms
                        $siteName = Yii::app()->name;
                        $message = "ثبت نام شما در سایت {$siteName} با موفقیت انجام شد.
نام کاربری: {$username}
کلمه عبور: {$pwd}";
                        $phone = $userDetails->mobile;
                        if($phone)
                            Notify::SendSms($message, $phone);

                        if($userDetails->save()){
                            $existUser = $user;
                        }
                    }
                }else{
                    $existUser->setScenario('reserve_register');
                    if(!$existUser->userDetails->first_name)
                        $existUser->userDetails->first_name = $_POST['Users']['first_name'];
                    if(!$existUser->userDetails->last_name)
                        $existUser->userDetails->last_name = $_POST['Users']['last_name'];
                    if(!$existUser->email)
                        $existUser->email = $_POST['Users']['email'];
                    $existUser->save();
                    $existUser->userDetails->save();
                }

                if($existUser){
                    $time = (Yii::app()->user->reservation['time'] == 'am')?Visits::TIME_AM:Visits::TIME_PM;
                    $saveResult = $this->saveVisit($existUser->id, Yii::app()->user->reservation['clinicID'], Yii::app()->user->reservation['doctorID'], Yii::app()->user->reservation['expertiseID'], Yii::app()->user->reservation['date'], $time, Visits::STATUS_PENDING);
                }
            }
        }

        if($saveResult['saved']){
            if(!isset($saveResult['status']) || (isset($saveResult['status']) && $saveResult['status'] == Visits::STATUS_PENDING))
                Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
            elseif(isset($saveResult['status']) && $saveResult['status'] == Visits::STATUS_ACCEPTED)
                Yii::app()->user->setFlash('success', 'در این تاریخ قبلا رزرو ثبت شده است.');
            $this->redirect('checkout/' . $saveResult['modelID']);
        }

        $this->render('add_new_visit', array(
            'user' => $user,
            'active' => 4,
        ));
    }

    public function actionCheckout($id)
    {
        Yii::app()->theme = 'frontend';
        $this->layout = '//layouts/panel';

        /* @var $model Visits */
        $model = Visits::model()->findByPk($id);

        $criteria = new CDbCriteria();
        $criteria->addCondition('clinic_id = :id');
        $criteria->params[':id'] = $model->clinic_id;
        $doctorSchedule = $model->doctor->doctorSchedules($criteria);
        $personnel = false;
        $commission = 0; // for secreter commission is free
        if(isset($_POST['Confirm'])){
            $model->status=Visits::STATUS_ACCEPTED;
            if($model->save())
            {
                Yii::app()->user->setFlash('success', 'نوبت شما با موفقیت رزرو شد.');
                $reservation = Yii::app()->user->getState('reservation');
                $visitDate = JalaliDate::date('Y/m/d',$reservation['date']);
                $visitTimeLabel = $reservation['time']=='am'?'صبح':'بعدازظهر';
                $message = "نوبت شما با موفقیت رزرو شد.
کد رهگیری نوبت: {$model->tracking_code}
تاریخ مراجعه به مطب: {$visitDate}
ساعت مراجعه بین {$reservation['visitStartTime']} تا {$reservation['visitEndTime']} {$visitTimeLabel}";
                $phone = $model->user && $model->user->userDetails && $model->user->userDetails->mobile?$model->user->userDetails->mobile:null;
                if($phone)
                    Notify::SendSms($message, $phone);
                Yii::app()->user->setState('reservation', null);
            }
            $this->refresh();
        }

        $this->render('add_new_visit', array(
            'model' => $model,
            'doctorSchedule' => $doctorSchedule[0],
            'commission' => $commission,
            'personnel' => $personnel,
            'active' => 5,
        ));
    }

    private function saveVisit($userID, $clinicID, $doctorID, $expertiseID, $date, $time, $status)
    {
        $model = Visits::model()->findByAttributes(array(
            'user_id' => $userID,
            'clinic_id' => $clinicID,
            'doctor_id' => $doctorID,
            'expertise_id' => $expertiseID,
            'date' => $date,
            'time' => $time,
        ));
        if ($model) {
            return array(
                'saved' => true,
                'modelID' => $model->id,
                'status' => $model->status,
            );
        } else {
            $model = new Visits();
            $model->user_id = $userID;
            $model->clinic_id = $clinicID;
            $model->doctor_id = $doctorID;
            $model->expertise_id = $expertiseID;
            $model->date = $date;
            $model->time = $time;
            $model->status = $status;

            $result = $model->save();

            return array(
                'saved' => $result,
                'modelID' => $result ? $model->id : null,
            );
        }
    }
}