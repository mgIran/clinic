<?php
class ApiController extends ApiBaseController
{
    protected $request = null;
    public $active_gateway;

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'RestAccessControl + register, forgetPassword, getDoctors, getDates, doctorProfile, page',
            'RestAuthControl + profile, editProfile, transactions, visits, userInfo',
        );
    }

    public function beforeAction($action)
    {
        $this->request = $this->getRequest();
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    public function actionGetDoctors()
    {
        if (isset($this->request['id'])) {
            $clinicPersonnel = new ClinicPersonnels('getDoctorsByExp');
            $clinicPersonnel->unsetAttributes();
            if (isset($_GET['ClinicPersonnels']))
                $clinicPersonnel->attributes = $_GET['ClinicPersonnels'];
            $clinicPersonnel->expertiseID = $this->request['id'];
            $doctors = [];
            /* @var ClinicPersonnels $doctor */
            foreach($clinicPersonnel->getDoctorsByExp()->getData() as $doctor) {
                $days = [];
                foreach ($doctor->user->doctorSchedules as $schedule)
                    $days[] = DoctorSchedules::$weekDays[$schedule->week_day];

                $doctors[] = [
                    'name' => $doctor->user->userDetails->getShowName(),
                    'avatar' => $doctor->user->userDetails->getApiAvatar(),
                    'days' => implode('، ', $days),
                    'doctorID' => intval($doctor->user_id),
                    'clinicID' => intval($doctor->clinic_id),
                ];
            }

            if($doctors)
                $this->_sendResponse(200, CJSON::encode(['status' => true, 'doctors' => $doctors]), 'application/json');
            else
                $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'نتیجه ای یافت نشد.']), 'application/json');
        } else
            $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'ID variable is required.']), 'application/json');
    }

    public function actionGetDates()
    {
        if (isset($this->request['clinic_id']) and isset($this->request['doctor_id'])) {
            $clinicID = $this->request['clinic_id'];
            $doctorID = $this->request['doctor_id'];

            $from = $to = null;
            $flag = true;
            $message = '';
            if (isset($this->request['from']) and isset($this->request['to'])) {
                $from = $this->request['from'];
                $to = $this->request['to'];

                if ($from == $to) {
                    $message = 'تاریخ ها یکسان می باشند.';
                    $flag = false;
                } elseif ($from > $to) {
                    $message = 'تاریخ های انتخاب شده اشتباه است.';
                    $flag = false;
                } elseif ($from < strtotime(date('Y/m/d 00:00', time())) || $to < strtotime(date('Y/m/d 00:00', time()))) {
                    $message = 'تاریخ ها معتبر نمی باشند.';
                    $flag = false;
                } else
                    $flag = true;

                if(!$flag)
                    $this->_sendResponse(200, CJSON::encode([
                        'status' => false,
                        'message' => $message,
                    ]), 'application/json');
            } else
                $flag = false;

            if (!$flag) {
                $from = strtotime(date('Y/m/d 00:00', time()));
                $currentMonth = JalaliDate::date('m', $from, false);
                $currentYear = JalaliDate::date('Y', $from, false);
                $monthDaysCount = 30;
                if ($currentMonth <= 6)
                    $monthDaysCount = 31;
                elseif ($currentMonth == 12 and !JalaliDate::date('L', $from, false))
                    $monthDaysCount = 29;
                $endMonth = JalaliDate::mktime(23, 59, 59, $currentMonth, $monthDaysCount, $currentYear);
                $to = $endMonth;
            }

            $doctor = Users::model()->findByPk($doctorID);
            $criteria = new CDbCriteria();
            $criteria->compare('clinic_id', $clinicID);
            /* @var $schedules DoctorSchedules[] */
            $schedules = $doctor->doctorSchedules($criteria);
            $leaves = $doctor->doctorLeaves($criteria);
            $weekDays = CHtml::listData($schedules, 'week_day', 'week_day');
            $leaveDays = CHtml::listData($leaves, 'id', 'date');
            $now = strtotime(date('Y/m/d 00:00', time()));
            $from = strtotime(date('Y/m/d 00:00', $from));
            $fromIsToday = $now == $from ? true : false;
            $to = strtotime(date('Y/m/d 23:59', $to));
            $daysCount = ($to - $from) / (60 * 60 * 24);
            $days = array();
            for ($i = 0; $i <= $daysCount; $i++) {
                $dayTimestamp = strtotime(date('Y/m/d 00:00', $from + ($i * (60 * 60 * 24))));
                if ((int)Holidays::model()->countByAttributes(['date' => $dayTimestamp]) === 0) {
                    if (in_array(JalaliDate::date('N', $dayTimestamp, false), $weekDays)) {
                        if (!in_array(strtotime(date('Y/m/d 00:00', $dayTimestamp)), $leaveDays)) {
                            if ($dayTimestamp >= $from) {
                                foreach ($schedules as $key => $schedule)
                                    if ($schedule->week_day == JalaliDate::date('N', $dayTimestamp, false)) {
                                        $checkAM = true;
                                        if ($i === 0 && $fromIsToday && date('a', time()) != 'am')
                                            $checkAM = false;
                                        $temp = [];
                                        $strTemp = "";
                                        if ($checkAM && !is_null($schedule->times['AM'])) {
                                            $AMVisitsCount = Visits::getAllVisits($clinicID, $doctorID, $dayTimestamp, Visits::TIME_AM, array(Visits::STATUS_PENDING, Visits::STATUS_DELETED), 'NOT IN');
                                            if ($AMVisitsCount != $schedule->visit_count_am) {
                                                $temp[$key]['date'] = $dayTimestamp;
                                                $temp[$key]['AM'] = $schedule->times['AM'];
                                                $strTemp = $schedule->times['AM'] . " صبح";
                                            }
                                            //$days[$dayTimestamp]['AM'] = $schedule->times['AM'];
                                        }

                                        if (!is_null($schedule->times['PM'])) {
                                            $PMVisitsCount = Visits::getAllVisits($clinicID, $doctorID, $dayTimestamp, Visits::TIME_PM, array(Visits::STATUS_PENDING, Visits::STATUS_DELETED), 'NOT IN');
                                            if ($PMVisitsCount != $schedule->visit_count_pm) {
                                                $temp[$key]['date'] = $dayTimestamp;
                                                $temp[$key]['PM'] = $schedule->times['PM'];
                                                if (isset($temp[$key]['AM']))
                                                    $strTemp .= " / ";
                                                $strTemp .= $schedule->times['PM'] . " بعدازظهر";
                                            }
                                            //$days[$dayTimestamp]['PM'] = $schedule->times['PM'];
                                        }

                                        $temp[$key]['dateShow'] = str_replace('-', 'تا', $strTemp);
                                        $days[] = $temp[$key];
                                    }
                            }
                        }
                    }
                }
            }

            /* @var Clinics $clinic */
            $clinic = Clinics::model()->findByPk($clinicID);

            $this->_sendResponse(200, CJSON::encode([
                'status' => true,
                'days' => $days,
                'doctor' => [
                    'name' => $doctor->userDetails->getShowName(),
                    'avatar' => $doctor->userDetails->getApiAvatar(),
                ],
                'clinic' => [
                    'name' => $clinic->clinic_name,
                    'phone' => $clinic->phone,
                ],
                'from' => $from,
                'to' => $to,
            ]), 'application/json');
        } else
            $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'Clinic ID and Doctor ID variables is required.']), 'application/json');
    }

    public function actionPage()
    {
        if(isset($this->request['name'])){
            $text = null;
            Yii::import('pages.models.*');
            switch($this->request['name']){
                case "about":
                    $text = Pages::model()->findByPk(1)->summary;
                    break;

                case "help":
                    $text = Pages::model()->findByPk(6)->summary;
                    break;
            }

            if($text)
                $this->_sendResponse(200, CJSON::encode(['status' => true, 'text' => $text]), 'application/json');
            else
                $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'نتیجه ای یافت نشد.']), 'application/json');
        }else
            $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'Name variable is required.']), 'application/json');
    }

    /**
     * Register user
     */
    public function actionRegister()
    {
        Yii::import('users.models.*');
        $model = new Users('app-register');

        if(isset($this->request['user'])){
            $model->attributes = $this->request['user'];
            $model->status = 'active';
            $model->create_date = time();
            if($model->save()){
                $token = md5($model->id . '#' . $model->password . '#' . $model->email . '#' . $model->create_date);
                $model->updateByPk($model->id, array('verification_token' => $token));

                // Send Sms
                $siteName = Yii::app()->name;
                $message = "ثبت نام شما در سایت {$siteName} با موفقیت انجام شد.";
                $phone = $model->userDetails->mobile;
                if($phone)
                    Notify::SendSms($message, $phone);

                $this->_sendResponse(200, CJSON::encode(['status' => true, 'message' => 'ثبت نام شما با موفقیت انجام شد.']), 'application/json');
            }else {
                $errors = $model->getErrors();
                $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => reset($errors)[0]]), 'application/json');
            }
        }else
            $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'مقادیر الزامی ارسال نشده اند.']), 'application/json');
    }

    public function actionForgetPassword(){
        if(isset($this->request['mobile'])){
            /* @var UserDetails $userDetails */
            $userDetails = UserDetails::model()->findByAttributes(array('mobile' => $this->request['mobile']));
            if($userDetails){
                /* @var Users $model */
                $model = $userDetails->user;
                if($model->status == 'active'){
                    if($model->change_password_request_count != 3){
                        $token = md5($model->id . '#' . $model->password . '#' . $model->email . '#' . $model->create_date . '#' . time());
                        $count = intval($model->change_password_request_count);
                        $model->updateByPk($model->id, array('verification_token' => $token, 'change_password_request_count' => $count + 1));

                        $newPassword = substr($token, 0, 7);
                        /* @var Users $user */
                        $user = Users::model()->findByPk($model->id);
                        $user->setScenario('app-change-pass');
                        $user->password = $newPassword;
                        $user->password = $user->encrypt($user->password);
                        $user->save();

                        // Send Sms
                        $siteName = Yii::app()->name;
                        $message = "کلمه عبور شما در سایت {$siteName} تغییر یافت.
کلمه عبور جدید: {$newPassword}";
                        $phone = $userDetails->mobile;
                        if($phone)
                            Notify::SendSms($message, $phone);

                        $this->_sendResponse(200, CJSON::encode(['status' => true, 'message' => 'کلمه عبور جدید توسط پیامک ارسال خواهد شد.']), 'application/json');
                    }else
                        $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'بیش از 3 بار نمی توانید درخواست تغییر کلمه عبور بدهید.']), 'application/json');
                }elseif($model->status == 'pending')
                    $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'این حساب کاربری هنوز فعال نشده است.']), 'application/json');
                elseif($model->status == 'blocked')
                    $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'این حساب کاربری مسدود می باشد.']), 'application/json');
                elseif($model->status == 'deleted')
                    $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'این حساب کاربری حذف شده است.']), 'application/json');
            }else
                $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'شماره موبایل وارد شده اشتباه است.']), 'application/json');
        }
    }

    public function actionDoctorProfile()
    {
        if (isset($this->request['doctor_id']) and isset($this->request['clinic_id'])) {
            /* @var Users $doctor */
            $doctor = Users::model()->findByPk($this->request['doctor_id']);
            $criteria = new CDbCriteria();
            $criteria->addCondition('clinics.id = :id');
            $criteria->params[':id'] = $this->request['clinic_id'];
            $doctor->clinic = $doctor->clinics($criteria);
            if ($doctor->clinic)
                $doctor->clinic = $doctor->clinic[0];

            $doctorExpertises = [];
            foreach($doctor->expertises as $expertise)
                $doctorExpertises[] = $expertise->title;

            $clinic = [];
            if($doctor->clinic)
                $clinic = [
                    'name' => $doctor->clinic->clinic_name,
                    'town' => $doctor->clinic->town->name,
                    'city' => $doctor->clinic->place->name,
                    'address' => $doctor->clinic->address,
                    'zipCode' => $doctor->clinic->zip_code,
                    'phone' => $doctor->clinic->phone,
                    'fax' => $doctor->clinic->fax,
                    'contracts' => implode(' ، ', CJSON::decode($doctor->clinic->contracts)),
                ];

            $this->_sendResponse(200, CJSON::encode([
                'status' => true,
                'doctor' => [
                    'name' => $doctor->userDetails->getShowName(),
                    'avatar' => $doctor->userDetails->getApiAvatar(),
                    'email' => $doctor->email,
                    'membershipDate' => $doctor->create_date,
                    'expertises' => $doctorExpertises,
                ],
                'clinic' => $clinic
            ]), 'application/json');
        } else
            $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'Doctor ID and Clinic ID variable is required.']), 'application/json');
    }

    /** ------------------------------------------------- Authorized Api ------------------------------------------------ **/

    public function actionUserInfo()
    {
        if (isset($this->request['user']) and isset($this->request['clinic_id']) and isset($this->request['doctor_id']) and isset($this->request['expertise_id']) and isset($this->request['date']) and isset($this->request['time'])) {
            $userInfo = $this->request['user'];
            $saveResult = false;
            if (empty($userInfo['mobile']))
                $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'تلفن همراه نمی تواند خالی باشد.']), 'application/json');
            else {
                /* @var $existUser Users */
                $existUser = Users::model()->find('national_code = :national_code', array(':national_code' => $userInfo['national_code']));
                $existUser->setScenario('reserve_register');
                if (!$existUser->userDetails->first_name)
                    $existUser->userDetails->first_name = $userInfo['first_name'];
                if (!$existUser->userDetails->last_name)
                    $existUser->userDetails->last_name = $userInfo['last_name'];
                if (!$existUser->userDetails->mobile)
                    $existUser->userDetails->mobile = $userInfo['mobile'];
                if (!$existUser->national_code)
                    $existUser->national_code = $userInfo['national_code'];
                if (!$existUser->email)
                    $existUser->email = $userInfo['email'];
                $existUser->save();
                $existUser->userDetails->save();

                if ($existUser) {
                    $time = ($this->request['time'] == 'am') ? Visits::TIME_AM : Visits::TIME_PM;
                    $saveResult = $this->saveVisit($existUser->id, $this->request['clinic_id'], $this->request['doctor_id'], $this->request['expertise_id'], $this->request['date'], $time, Visits::STATUS_PENDING);
                }

                if ($saveResult['saved']) {
                    if (!isset($saveResult['status']) || (isset($saveResult['status']) && $saveResult['status'] == Visits::STATUS_PENDING))
                        $this->_sendResponse(200, CJSON::encode([
                            'status' => true,
                            'message' => 'اطلاعات با موفقیت ثبت شد.',
                            'modelID' => $saveResult['modelID']
                        ]), 'application/json');
                    elseif (isset($saveResult['status']) && $saveResult['status'] == Visits::STATUS_ACCEPTED)
                        $this->_sendResponse(200, CJSON::encode([
                            'status' => true,
                            'message' => 'در این تاریخ قبلا رزرو ثبت شده است.',
                            'modelID' => $saveResult['modelID']
                        ]), 'application/json');
                }
            }
        } else
            $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'User, Clinic ID, Doctor ID, Expertise ID, Date and Time variables is required.']), 'application/json');
    }

    public function actionTransactions()
    {
        $model = new UserTransactions('search');
        $model->unsetAttributes();
        if (isset($_GET['UserTransactions']))
            $model->attributes = $_GET['UserTransactions'];
        $model->user_id = $this->user->id;

        $transactions = [];
        $sum = 0;
        /* @var UserTransactions $transaction */
        foreach($model->search()->getData() as $transaction) {
            $sum += doubleval($transaction->amount);
            $transactions[] = [
                'amount' => doubleval($transaction->amount),
                'date' => $transaction->date,
                'gateway' => $transaction->gateway_name,
                'code' => $transaction->token,
            ];
        }

        $this->_sendResponse(200, CJSON::encode([
            'status' => true,
            'transactions' => $transactions,
            'count' => count($transactions),
            'sum' => doubleval($sum),
        ]), 'application/json');
    }

    public function actionVisits()
    {
        $model = new Visits('search');
        $model->unsetAttributes();
        $model->user_id = $this->user->id;

        $visits = [];
        /* @var Visits $visit */
        foreach($model->search()->getData() as $visit){
            $visits[] = [
                'clinic' => $visit->clinic->clinic_name,
                'doctor' => $visit->doctor->userDetails->getShowName(),
                'status' => $visit->statusLabels[$visit->status],
                'createDate' => $visit->create_date ? $visit->create_date : '',
                'date' => $visit->date ? $visit->date : '',
                'visitDate' => $visit->check_date ? $visit->check_date : '',
                'trackingCode' => $visit->tracking_code,
            ];
        }

        $this->_sendResponse(200, CJSON::encode([
            'status' => true,
            'visits' => $visits,
        ]), 'application/json');
    }

    public function actionProfile()
    {
//        $avatar = ($this->user->userDetails->avatar == '') ? Yii::app()->createAbsoluteUrl('/themes/frontend/images/default-user.png') : Yii::app()->createAbsoluteUrl('/uploads/users/avatar') . '/' . $this->user->userDetails->avatar;
        $this->_sendResponse(200, CJSON::encode(['status' => true, 'user' => [
            'email' => $this->user->email,
            'name' => $this->user->userDetails->fa_name,
            'role' => $this->user->userDetails->roleLabels[$this->user->role->role],
//            'avatar' => $avatar,
            'credit' => doubleval($this->user->userDetails->credit),
            'nationalCode' => $this->user->userDetails->national_code,
            'phone' => $this->user->userDetails->phone,
            'zipCode' => $this->user->userDetails->zip_code,
            'address' => $this->user->userDetails->address,
        ]]), 'application/json');
    }

    public function actionEditProfile()
    {
        if(isset($this->request['profile'])){
            $profile = $this->request['profile'];

            /* @var $model UserDetails */
            $model = UserDetails::model()->findByAttributes(array('user_id' => $this->user->id));

            $model->first_name = $profile['first_name'];
            $model->last_name = $profile['last_name'];
            $model->phone = $profile['phone'];
            $model->zip_code = $profile['zip_code'];
            $model->address = $profile['address'];
            $model->user->national_code = $profile['national_code'];
            $model->user->setScenario('app-update');

            if ($model->save() and $model->user->save())
                $this->_sendResponse(200, CJSON::encode([
                    'status' => true,
                    'message' => 'اطلاعات با موفقیت ثبت شد.',
                    'user' => [
                        'nationalCode' => strval($model->user->national_code),
                        'firstName' => strval($model->first_name),
                        'lastName' => strval($model->last_name),
                        'mobile' => strval($model->mobile),
                        'email' => strval($model->user->email),
                        'phone' => strval($model->phone),
                        'address' => strval($model->address),
                        'zipCode' => strval($model->zip_code),
                    ],
                ]), 'application/json');
            else {
                if ($model->getErrors())
                    $errors = $model->getErrors();
                else
                    $errors = $model->user->getErrors();
                $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => reset($errors)[0]]), 'application/json');
            }
        }else
            $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'Profile variable is required.']), 'application/json');
    }

    public function actionGateway(){
        Yii::app()->theme = 'market';
        $this->layout = 'panel';
        if(isset($_GET['rc']))
            $this->render('ext.MellatPayment.views._redirect', array('ReferenceId' => $_GET['rc']));
        else throw new CHttpException(400, 'خطا در عملیات.');
    }

    /** ------------------------------------------------- Required functions ------------------------------------------------ **/

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