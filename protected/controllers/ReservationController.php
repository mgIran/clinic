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
                'info',
            ),
        );
    }

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'postOnly + selectDoctor'
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
            ));

            $this->redirect('schedule');
        }
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
                    if (in_array(JalaliDate::date('N', $dayTimestamp, false), $weekDays))
                        if (!in_array(strtotime(date('Y/m/d 00:00', $dayTimestamp)), $leaveDays)) {
                            foreach($schedules as $schedule)
                                if($schedule->week_day == JalaliDate::date('N', $dayTimestamp, false))
                                    $days[$dayTimestamp]=$schedule->times;
                        }
                }

                $renderOutput = array(
                    'days'=>$days,
                    'doctor'=>$user,
                    'clinic'=>Clinics::model()->findByPk(Yii::app()->user->reservation['clinicID']),
                );
            }
        }

        $this->render('schedule', $renderOutput);
    }

    public function actionInfo()
    {
        Yii::app()->theme = 'frontend';
        $this->layout = 'public';

        $this->render('info');
    }
}