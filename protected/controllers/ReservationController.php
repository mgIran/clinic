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
        if(isset($_GET['ClinicPersonnels']))
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

        if (isset($_POST['from']) and isset($_POST['to'])) {
            if ($_POST['from'] == $_POST['to'])
                Yii::app()->user->setFlash('failed', 'تاریخ ها یکسان می باشند.');
            elseif($_POST['from'] > $_POST['to'])
                Yii::app()->user->setFlash('failed', 'تاریخ های انتخاب شده اشتباه است.');
            else{

            }
        }

        $this->render('schedule');
    }

    public function actionInfo()
    {
        Yii::app()->theme='frontend';
        $this->layout='public';

        $this->render('info');
    }
}