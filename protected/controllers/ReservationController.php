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
            ),
            'backend'=>array(
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

    public function actionSearch($id)
    {
        Yii::app()->theme = 'frontend';
        $this->layout = 'public';

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
            $this->redirect('search/'.Yii::app()->user->reservation['expertiseID']);

    }

    public function actionSchedule()
    {
        Yii::app()->theme = 'frontend';
        $this->layout = 'public';

        $renderOutput = array();
        if (isset($_POST['from']) and isset($_POST['to'])) {
            if ($_POST['from'] == $_POST['to'])
                Yii::app()->user->setFlash('failed', 'تاریخ ها یکسان می باشند.');
            elseif ($_POST['from_altField'] > $_POST['to_altField'])
                Yii::app()->user->setFlash('failed', 'تاریخ های انتخاب شده اشتباه است.');
            else {
                $user = Users::model()->findByPk(Yii::app()->user->reservation['doctorID']);
                $criteria = new CDbCriteria();
                $criteria->addCondition('clinic_id = :clinic_id');
                $criteria->params[':clinic_id'] = Yii::app()->user->reservation['clinicID'];
                /* @var $schedules DoctorSchedules[] */
                $schedules = $user->doctorSchedules($criteria);
                $leaves = $user->doctorLeaves($criteria);
                $weekDays = CHtml::listData($schedules, 'week_day', 'week_day');
                $leaveDays = CHtml::listData($leaves, 'id', 'date');
                $daysCount = ($_POST['to_altField'] - $_POST['from_altField']) / (60 * 60 * 24);
                $days = array();
                for ($i = 0; $i <= $daysCount; $i++) {
                    $dayTimestamp = strtotime(date('Y/m/d 00:00', strtotime(date('Y/m/d 00:00', $_POST['from_altField'])) + ($i * (60 * 60 * 24))));
                    if (in_array(JalaliDate::date('N', $dayTimestamp, false), $weekDays)) {
                        if (!in_array(strtotime(date('Y/m/d 00:00', $dayTimestamp)), $leaveDays)) {
                            if ($dayTimestamp > time()) {
                                foreach ($schedules as $schedule)
                                    if ($schedule->week_day == JalaliDate::date('N', $dayTimestamp, false)) {
                                        $AMVisitsCount = Visits::getAllVisits(Yii::app()->user->reservation['clinicID'], Yii::app()->user->reservation['doctorID'], $dayTimestamp, Visits::TIME_AM, array(Visits::STATUS_PENDING, Visits::STATUS_DELETED), 'NOT IN');
                                        $PMVisitsCount = Visits::getAllVisits(Yii::app()->user->reservation['clinicID'], Yii::app()->user->reservation['doctorID'], $dayTimestamp, Visits::TIME_PM, array(Visits::STATUS_PENDING, Visits::STATUS_DELETED), 'NOT IN');

                                        if ($AMVisitsCount != $schedule->visit_count_am)
                                            if (!is_null($schedule->times['AM']))
                                                $days[$dayTimestamp]['AM'] = $schedule->times['AM'];

                                        if ($PMVisitsCount != $schedule->visit_count_pm)
                                            if (!is_null($schedule->times['PM']))
                                                $days[$dayTimestamp]['PM'] = $schedule->times['PM'];
                                    }
                            }
                        }
                    }
                }

                $renderOutput = array(
                    'days' => $days,
                    'doctor' => $user,
                    'clinic' => Clinics::model()->findByPk(Yii::app()->user->reservation['clinicID']),
                );
            }
        }

        $this->render('schedule', $renderOutput);
    }

    public function actionSelectTime()
    {
        if (isset($_GET['d']) and isset($_GET['t'])) {
            Yii::app()->user->setState('reservation', array(
                'doctorID' => Yii::app()->user->reservation['doctorID'],
                'clinicID' => Yii::app()->user->reservation['clinicID'],
                'expertiseID' => Yii::app()->user->reservation['expertiseID'],
                'date' => Yii::app()->request->getQuery('d'),
                'time' => Yii::app()->request->getQuery('t'),
            ));

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

                    if ($user->save()) {
                        $userDetails = $user->userDetails;
                        $userDetails->user_id = $user->id;
                        $userDetails->first_name = $user->first_name;
                        $userDetails->last_name = $user->last_name;
                        $userDetails->mobile = $user->mobile;

                        /* @todo send sms to user */

                        if ($userDetails->save()) {
                            $time = (Yii::app()->user->reservation['time'] == 'am') ? Visits::TIME_AM : Visits::TIME_PM;
                            $saveResult = $this->saveVisit($user->id, Yii::app()->user->reservation['clinicID'], Yii::app()->user->reservation['doctorID'], Yii::app()->user->reservation['expertiseID'], Yii::app()->user->reservation['date'], $time, Visits::STATUS_PENDING);
                        }
                    }
                } else {
                    $existUser->setScenario('reserve_register');
                    if (!$existUser->userDetails->first_name)
                        $existUser->userDetails->first_name = $_POST['Users']['first_name'];
                    if (!$existUser->userDetails->last_name)
                        $existUser->userDetails->last_name = $_POST['Users']['last_name'];
                    if (!$existUser->email)
                        $existUser->email = $_POST['Users']['email'];
                    $existUser->save();
                    $existUser->userDetails->save();

                    $time = (Yii::app()->user->reservation['time'] == 'am') ? Visits::TIME_AM : Visits::TIME_PM;
                    $saveResult = $this->saveVisit($existUser->id, Yii::app()->user->reservation['clinicID'], Yii::app()->user->reservation['doctorID'], Yii::app()->user->reservation['expertiseID'], Yii::app()->user->reservation['date'], $time, Visits::STATUS_PENDING);
                }
            }
        }

        if ($saveResult['saved']) {
            Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
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
        $commission = SiteSetting::model()->find('name = :name', array(':name' => 'commission'));

        if(isset($_POST['Confirm'])){
            $model->status=Visits::STATUS_ACCEPTED;
            if($model->save())
                Yii::app()->user->setFlash('success', 'نوبت شما با موفقیت رزرو شد.');
        }

        $this->render('checkout', array(
            'model' => $model,
            'doctorSchedule' => $doctorSchedule[0],
            'commission' => $commission,
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
            'status' => $status
        ));

        if ($model) {
            return array(
                'saved' => true,
                'modelID' => $model->id,
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
        $model= new Visits('search');
        $model->unsetAttributes();
        if (isset($_GET['Visits']))
            $model->attributes = $_GET['Visits'];

        $this->render('admin', array(
            'model'=>$model,
        ));
    }

    public function actionView($id)
    {
        $this->layout='column2';

        /* @var $model Visits */
        $model=Visits::model()->findByPk($id);

        $this->render('view', array(
            'model'=>$model,
        ));
    }

    public function actionDelete($id)
    {
        /* @var $model Visits */
        $model=Visits::model()->findByPk($id);

        $model->delete();

        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }
}