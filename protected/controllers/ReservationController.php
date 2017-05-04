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

            if (Yii::app()->user->isGuest or Yii::app()->user->type == 'admin') {
                Yii::app()->user->returnUrl = 'reservation/info';
                $this->redirect(array('/login'));
            } else
                $this->redirect('info');
        }
    }

    public function actionInfo()
    {
        Yii::app()->theme='frontend';
        $this->layout='public';

        $this->render('info');
    }
}