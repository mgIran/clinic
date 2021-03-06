<?php

class ReservationController extends Controller
{
    /**
     * @return array actions type list
     */
    public static function actionsType()
    {
        return array(
            'frontend' => array(
                'search',
                'selectDoctor',
                'schedule',
                'selectTime',
                'info',
                'checkout',
                'follow',
            ),
            'backend' => array(
                'admin',
                'view',
                'delete',
            )
        );
    }

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'postOnly + selectDoctor',
            'checkAccess + admin, delete, view',
        );
    }

    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            )
        );
    }

    public function actionSearch($id)
    {
        Yii::app()->theme = 'frontend';
        $this->layout = '//layouts/public';

        $clinicPersonnel = new ClinicPersonnels('getDoctorsByExp');
        $clinicPersonnel->unsetAttributes();
        if (isset($_GET['ClinicPersonnels']))
            $clinicPersonnel->attributes = $_GET['ClinicPersonnels'];
        $clinicPersonnel->expertiseID = $id;

        $this->render('search', array(
            'doctors' => $clinicPersonnel,
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

            $this->redirect('schedule');
        } else
            $this->redirect('search/' . Yii::app()->user->reservation['expertiseID']);

    }

    public function actionSchedule()
    {
        Yii::app()->theme = 'frontend';
        $this->layout = 'public';

        $renderOutput = array();
        $flag = true;
        if (isset($_POST['from']) and isset($_POST['to'])) {
            if ($_POST['from_altField'] == $_POST['to_altField']) {
                Yii::app()->user->setFlash('failed', 'تاریخ ها یکسان می باشند.');
                $flag = false;
            } elseif ($_POST['from_altField'] > $_POST['to_altField']) {
                Yii::app()->user->setFlash('failed', 'تاریخ های انتخاب شده اشتباه است.');
                $flag = false;
            } elseif ($_POST['from_altField'] < strtotime(date('Y/m/d 00:00', time())) || $_POST['to_altField'] < strtotime(date('Y/m/d 00:00', time()))) {
                Yii::app()->user->setFlash('failed', 'تاریخ ها معتبر نمی باشند.');
                $flag = false;
            } else {
                $flag = true;
                $from = $_POST['from_altField'];
                $to = $_POST['to_altField'];
            }
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
        if ($from && $to) {
            $user = Users::model()->findByPk(Yii::app()->user->reservation['doctorID']);
            $criteria = new CDbCriteria();
            $criteria->compare('clinic_id', Yii::app()->user->reservation['clinicID']);
            $criteria->addCondition('date >= :from and date <= :to');
            $criteria->params[':from'] = $from;
            $criteria->params[':to'] = $to;
            /* @var $schedules DoctorSchedules[] */
            $schedules = $user->doctorSchedules($criteria);
            $leaves = $user->doctorLeaves($criteria);
            $weekDays = CHtml::listData($schedules, 'id', 'date');
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
                    if (in_array($dayTimestamp, $weekDays)) {
                        if (!in_array($dayTimestamp, $leaveDays)) {
                            if ($dayTimestamp >= $from) {
                                foreach ($schedules as $key => $schedule)
                                    if ($schedule->date == $dayTimestamp) {
                                        $checkAM = true;
                                        if ($i === 0 && $fromIsToday && date('a', time()) != 'am')
                                            $checkAM = false;
                                        if ($checkAM && !is_null($schedule->times['AM'])) {
                                            $AMVisitsCount = Visits::getAllVisits(Yii::app()->user->reservation['clinicID'], Yii::app()->user->reservation['doctorID'], $dayTimestamp, Visits::TIME_AM, array(Visits::STATUS_PENDING, Visits::STATUS_DELETED), 'NOT IN');
                                            if ($AMVisitsCount != $schedule->visit_count_am)
                                                $days[$dayTimestamp]['AM'] = $schedule->times['AM'];
                                        }

                                        if (!is_null($schedule->times['PM'])) {
                                            $PMVisitsCount = Visits::getAllVisits(Yii::app()->user->reservation['clinicID'], Yii::app()->user->reservation['doctorID'], $dayTimestamp, Visits::TIME_PM, array(Visits::STATUS_PENDING, Visits::STATUS_DELETED), 'NOT IN');
                                            if ($PMVisitsCount != $schedule->visit_count_pm)
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
            );
        }

        $this->render('schedule', $renderOutput);
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
                'doctor_id' => $reservation['doctorID'],
                'clinic_id' => $reservation['clinicID'],
//                'week_day' => (int)JalaliDate::date('w', $reservation['date'], false) + 1,
                'date' => strtotime(date("Y/m/d 00:00",$reservation['date'])),
            ));
            $reservation['visitStartTime'] = $schedule->{'entry_time_' . $reservation['time']};
            $reservation['visitEndTime'] = $schedule->{'exit_time_' . $reservation['time']};
            Yii::app()->user->setState('reservation', $reservation);

            $this->redirect('info');
        } else
            $this->redirect('schedule');
    }

    public function actionInfo()
    {
        Yii::app()->theme = 'frontend';
        $this->layout = 'public';
        $saveResult = false;

        $user = new Users();
        $user->setScenario('reserve_register');
        if (isset($_POST['Users'])) {
            if (empty($_POST['Users']['mobile']))
                Yii::app()->user->setFlash('failed', 'تلفن همراه نمی تواند خالی باشد.');
            else {
                /* @var $existUser Users */
                $existUser = Users::model()->find('national_code = :national_code', array(':national_code' => $_POST['Users']['national_code']));
                if (!$existUser) {
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

                    if ($user->save()) {
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
                        if ($phone)
                            Notify::SendSms($message, $phone);

                        if ($userDetails->save()) {
                            $existUser = $user;
                        }
                    }
                } else {
                    $existUser->setScenario('reserve_register');
                    if (!$existUser->userDetails->first_name)
                        $existUser->userDetails->first_name = $_POST['Users']['first_name'];
                    if (!$existUser->userDetails->last_name)
                        $existUser->userDetails->last_name = $_POST['Users']['last_name'];
                    if (!$existUser->userDetails->mobile)
                        $existUser->userDetails->mobile = $_POST['Users']['mobile'];
                    if (!$existUser->email)
                        $existUser->email = $_POST['Users']['email'];
                    $existUser->save();
                    $existUser->userDetails->save();
                }

                if ($existUser) {
                    $time = (Yii::app()->user->reservation['time'] == 'am') ? Visits::TIME_AM : Visits::TIME_PM;
                    $saveResult = $this->saveVisit($existUser->id, Yii::app()->user->reservation['clinicID'], Yii::app()->user->reservation['doctorID'], Yii::app()->user->reservation['expertiseID'], Yii::app()->user->reservation['date'], $time, Visits::STATUS_PENDING);
                }
            }
        }

        if ($saveResult['saved']) {
            if (!isset($saveResult['status']) || (isset($saveResult['status']) && $saveResult['status'] == Visits::STATUS_PENDING))
                Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
            elseif (isset($saveResult['status']) && $saveResult['status'] == Visits::STATUS_ACCEPTED)
                Yii::app()->user->setFlash('success', 'در این تاریخ قبلا رزرو ثبت شده است.');
            $this->redirect('checkout/' . $saveResult['modelID']);
        }

        $this->render('info', array(
            'user' => $user,
        ));
    }

    public function actionCheckout($id)
    {
        Yii::app()->theme = 'frontend';
        $this->layout = 'public';

        /* @var $model Visits */
        $model = Visits::model()->findByPk($id);

        $criteria = new CDbCriteria();
        $criteria->addCondition('clinic_id = :id');
        $criteria->params[':id'] = $model->clinic_id;
        $doctorSchedule = $model->doctor->doctorSchedules($criteria);

        Yii::app()->getModule('setting');
        $commission = SiteSetting::model()->find('name = :name', array(':name' => 'commission'))->value;

        $personnel = false;
        if (!Yii::app()->user->isGuest && Yii::app()->user->type == 'user' && Yii::app()->user->roles != 'user') {
            $personnel = ClinicPersonnels::model()->findByAttributes([
                'clinic_id' => $model->clinic_id,
                'user_id' => Yii::app()->user->getId(),
            ]);
            if ($personnel)
                $commission = 0;
        }
        if (isset($_POST['Confirm'])) {
            $model->status = Visits::STATUS_ACCEPTED;
            if ($model->save()) {
                Yii::app()->user->setFlash('success', 'نوبت شما با موفقیت رزرو شد.');
                $reservation = Yii::app()->user->getState('reservation');
                $visitDate = JalaliDate::date('Y/m/d', $reservation['date']);
                $visitTimeLabel = $reservation['time'] == 'am' ? 'صبح' : 'بعدازظهر';
                $message = "نوبت شما با موفقیت رزرو شد.
کد رهگیری نوبت: {$model->tracking_code}
تاریخ مراجعه به مطب: {$visitDate}
ساعت مراجعه بین {$reservation['visitStartTime']} تا {$reservation['visitEndTime']} {$visitTimeLabel}";
                $phone = $model->user && $model->user->userDetails && $model->user->userDetails->mobile ? $model->user->userDetails->mobile : null;
                if ($phone)
                    Notify::SendSms($message, $phone);


                // Add Sms Schedules
                if ($phone) {
                    $doctorScheduleTime = $model->time == Visits::TIME_AM ? $doctorSchedule[0]->entry_time_am : $doctorSchedule[0]->entry_time_pm;
                    $doctorScheduleTimeEnd = $model->time == Visits::TIME_AM ? $doctorSchedule[0]->exit_time_am : $doctorSchedule[0]->exit_time_pm;
                    $sendDate = $reservation['date'] + (($doctorScheduleTime - (int)SiteSetting::get('sms_schedule_time')) * 60 * 60);

                    $smsScheduleText = strtr(SiteSetting::get('sms_schedule_message'), array(
                        '{from}' => $doctorScheduleTime,
                        '{to}' => $doctorScheduleTimeEnd,
                        '{turn}' => $visitTimeLabel,
                    ));

                    @SmsSchedules::AddSchedule(
                        $sendDate,
                        $smsScheduleText,
                        array($phone),
                        array($model->user->email)
                    );
                }


                Yii::app()->user->setState('reservation', null);
            }
            $this->refresh();
        } elseif (isset($_POST['Payment'])) {
            $transaction = new UserTransactions();
            $transaction->user_id = $model->user_id;
            $transaction->amount = $commission;
            $transaction->date = time();
            $transaction->gateway_name = 'زرین پال';
            $transaction->model_name = 'Visits';
            $transaction->model_id = $model->id;
            if ($model->save()) {
                $gateway = new ZarinPal();
                $gateway->callback_url = Yii::app()->getBaseUrl(true) . '/reservation/verifyPayment/' . $id;
                $siteName = Yii::app()->name;
                $description = "افزایش اعتبار در {$siteName} از طریق درگاه {$gateway->getGatewayName()}";
                $result = $gateway->request(doubleval($transaction->amount), $description, $model->user->email, $model->user && $model->user->userDetails && $model->user->userDetails->phone ? $model->user->userDetails->phone : '0');
                $transaction->scenario = 'set-authority';
                $transaction->authority = $result->getAuthority();
                @$transaction->save();
                //Redirect to URL You can do it also by creating a form
                if ($result->getStatus() == 100)
                    $this->redirect($gateway->getRedirectUrl());
                else
                    throw new CHttpException(404, 'خطای بانکی: ' . $result->getError());
            }
        }

        $this->render('checkout', array(
            'model' => $model,
            'doctorSchedule' => $doctorSchedule[0],
            'commission' => $commission,
            'personnel' => $personnel,
        ));
    }

    public function actionVerifyPayment($id)
    {
        Yii::app()->theme = 'frontend';
        $this->layout = 'public';
        if (!isset($_GET['Authority'])) {
            $this->redirect(array('/reservation/checkout/' . $id));
        } else {
            /* @var $visit Visits */
            $visit = Visits::model()->findByPk($id);
            $Authority = $_GET['Authority'];
            $transaction = UserTransactions::model()->findByAttributes(array(
                'authority' => $Authority,
                'user_id' => $visit->user_id
            ));
            if ($transaction->status == UserTransactions::TRANSACTION_STATUS_UNPAID) {
                $Amount = $transaction->amount;
                if ($_GET['Status'] == 'OK') {
                    $gateway = new ZarinPal();
                    $gateway->verify($Authority, $Amount);
                    if ($gateway->getStatus() == 100) {
                        $transaction->status = UserTransactions::TRANSACTION_STATUS_PAID;
                        $transaction->token = $gateway->getRefId();
                        @$transaction->save();
                    } else {
                        Yii::app()->user->setFlash('failed', $gateway->getError());
                        $this->redirect(array('/reservation/checkout/' . $id));
                    }
                } else {
                    Yii::app()->user->setFlash('failed', 'عملیات پرداخت ناموفق بوده یا توسط کاربر لغو شده است.');
                    $this->redirect(array('/reservation/checkout/' . $id));
                }
            }
            $criteria = new CDbCriteria();
            $criteria->addCondition('clinic_id = :id');
            $criteria->params[':id'] = $visit->clinic_id;
            $doctorSchedule = $visit->doctor->doctorSchedules($criteria);

            Yii::app()->getModule('setting');
            $commission = SiteSetting::model()->find('name = :name', array(':name' => 'commission'))->value;
            $visit->status = Visits::STATUS_ACCEPTED;
            $reservation = Yii::app()->user->getState('reservation');
            if ($visit->save() && $reservation) {
                Yii::app()->user->setFlash('success', 'پرداخت شما انجام و نوبت شما با موفقیت رزرو شد.');
                $visitDate = JalaliDate::date('Y/m/d', $reservation['date']);
                $visitTimeLabel = $reservation['time'] == 'am' ? 'صبح' : 'بعدازظهر';
                $message = "نوبت شما با موفقیت رزرو شد.
کد رهگیری نوبت: {$visit->tracking_code}
تاریخ مراجعه به مطب: {$visitDate}
ساعت مراجعه بین {$reservation['visitStartTime']} تا {$reservation['visitEndTime']} {$visitTimeLabel}";
                $phone = $visit->user && $visit->user->userDetails && $visit->user->userDetails->mobile ? $visit->user->userDetails->mobile : null;
                if ($phone)
                    Notify::SendSms($message, $phone);
                Yii::app()->user->setState('reservation', null);
            }

            $personnel = false;
            if (!Yii::app()->user->isGuest && Yii::app()->user->type == 'user' && Yii::app()->user->roles != 'user') {
                $personnel = ClinicPersonnels::model()->findByAttributes([
                    'clinic_id' => $visit->clinic_id,
                    'user_id' => Yii::app()->user->getId(),
                ]);
                if ($personnel)
                    $commission = 0;
            }

            $this->render('checkout', array(
                'model' => $visit,
                'doctorSchedule' => $doctorSchedule[0],
                'commission' => $commission,
                'transaction' => $transaction,
                'personnel' => $personnel,
            ));
        }
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

    public function actionAdmin()
    {
        $model = new Visits('search');
        $model->unsetAttributes();
        if (isset($_GET['Visits']))
            $model->attributes = $_GET['Visits'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function actionView($id)
    {
        $this->layout = 'column2';

        /* @var $model Visits */
        $model = Visits::model()->findByPk($id);

        $this->render('view', array(
            'model' => $model,
        ));
    }

    public function actionFollow($id = null)
    {
        Yii::app()->theme = 'frontend';
        $this->layout = 'public';
        $model = null;
        if ($id) {
            $model = Visits::model()->findByAttributes(array('tracking_code' => $id));
            if ($model === null) {
                if (!Yii::app()->request->isAjaxRequest)
                    Yii::app()->user->setFlash("failed", "نوبتی با این کد رهگیری یافت نشد! لطفا مجددا بررسی و تلاش کنید.");
                else {
                    echo CJSON::encode([
                        'status' => false,
                        'message' => "نوبتی با این کد رهگیری یافت نشد! لطفا مجددا بررسی و تلاش کنید."
                    ]);
                    Yii::app()->end();
                }
            } else {
                $transaction = UserTransactions::model()->findByAttributes(array(
                    'model_name' => 'Visits',
                    'model_id' => $model->id
                ));
                $criteria = new CDbCriteria();
                $criteria->addCondition('clinic_id = :id');
                $criteria->params[':id'] = $model->clinic_id;
                $doctorSchedule = $model->doctor->doctorSchedules($criteria);

                Yii::app()->getModule('setting');
                $commission = SiteSetting::model()->find('name = :name', array(':name' => 'commission'))->value;
                $this->beginClip('view-visit');
                $this->renderPartial('_view', array(
                    'model' => $model,
                    'doctorSchedule' => $doctorSchedule[0],
                    'commission' => $commission,
                    'transaction' => $transaction
                ));
                $this->endClip();
                echo CJSON::encode([
                    'status' => true,
                    'html' => $this->clips['view-visit']
                ]);
                Yii::app()->end();
            }
        }

        $this->render('follow', array(
            'model' => $model,
        ));
    }

    public function actionDelete($id)
    {
        /* @var $model Visits */
        $model = Visits::model()->findByPk($id);

        $model->delete();

        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }
}