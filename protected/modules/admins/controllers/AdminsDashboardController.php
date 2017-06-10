<?php

class AdminsDashboardController extends Controller
{
    /**
     * @return array actions type list
     */
    public static function actionsType()
    {
        return array(
            'backend' => array(
                'index'
            )
        );
    }

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'checkAccess - index', // perform access control for CRUD operations
        );
    }

    public function actionIndex()
    {
        $clinicCount = Clinics::model()->count();
        $doctorCount = ClinicPersonnels::model()->find(array(
            'select' => 'COUNT(Distinct(user_id)) as count',
            'condition' => 'post IN(2,3)'
        ))->count;
        $secCount = ClinicPersonnels::model()->find(array(
            'select' => 'COUNT(Distinct(user_id)) as count',
            'condition' => 'post = 4'
        ))->count;
        $personnelCount = ClinicPersonnels::model()->find(array(
            'select' => 'COUNT(Distinct(user_id)) as count',
        ))->count;
        $allVisitsCount = Yii::app()->db->createCommand()
            ->select('COUNT(id)')
            ->from('{{visits}}')
            ->queryScalar();
        $deletedVisitsCount = Yii::app()->db->createCommand()
            ->select('COUNT(id)')
            ->from('{{visits}}')
            ->where('status = 0')
            ->queryScalar();
        $pendingVisitsCount = Yii::app()->db->createCommand()
            ->select('COUNT(id)')
            ->from('{{visits}}')
            ->where('status = 1')
            ->queryScalar();
        $acceptedVisitsCount = Yii::app()->db->createCommand()
            ->select('COUNT(id)')
            ->from('{{visits}}')
            ->where('status = 2')
            ->queryScalar();
        $visitedVisitsCount = Yii::app()->db->createCommand()
            ->select('COUNT(id)')
            ->from('{{visits}}')
            ->where('status = 4')
            ->queryScalar();

        $this->render('index', array(
            'clinicCount' => $clinicCount,
            'doctorCount' => $doctorCount,
            'secCount' => $secCount,
            'personnelCount' => $personnelCount,
            'allVisitsCount' => $allVisitsCount,
            'deletedVisitsCount' => $deletedVisitsCount,
            'pendingVisitsCount' => $pendingVisitsCount,
            'acceptedVisitsCount' => $acceptedVisitsCount,
            'visitedVisitsCount' => $visitedVisitsCount,
        ));
    }
}