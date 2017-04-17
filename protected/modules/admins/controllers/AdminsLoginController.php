<?php

class AdminsLoginController extends Controller
{
    /**
     * @return array actions type list
     */
    public static function actionsType()
    {
        return array(
            'frontend' => array(
                'index',
                'logout'
            )
        );
    }

    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            'captcha' => array(
                'class' => 'CCaptchaAction' ,
                'backColor' => 0xFFFFFF ,
            ),
        );
    }

    /**
     * Index Action
     */
    public function actionIndex()
    {
        Yii::app()->theme = 'abound';
        $this->layout = '//layouts/login';
        if(!Yii::app()->user->isGuest && Yii::app()->user->type === 'admin')
            $this->redirect(array('/admins/'));

        $model = new AdminLoginForm;

        if (Yii::app()->user->getState('attempts-login') > 2) {
            $model->scenario = 'withCaptcha';
        }

        // if it is ajax validation request
        if ( isset( $_POST[ 'ajax' ] ) && $_POST[ 'ajax' ] === 'login-form' ) {
            echo CActiveForm::validate( $model );
            Yii::app()->end();
        }

// collect user input data
        if ( isset( $_POST[ 'AdminLoginForm' ] ) ) {
            $model->attributes = $_POST[ 'AdminLoginForm' ];
            // validate user input and redirect to the previous page if valid
            if ( $model->validate() && $model->login())
            {
                Yii::app()->user->setState('attempts-login', 0);
                $this->redirect(Yii::app()->createUrl('/admins/dashboard'));
            }else
            {
                Yii::app()->user->setState('attempts-login', Yii::app()->user->getState('attempts-login', 0) + 1);

                if (Yii::app()->user->getState('attempts-login') > 2) {
                    $model->scenario = 'withCaptcha'; //useful only for view
                }
            }
        }
// display the login form
        $this->render( 'index', array( 'model' => $model ) );
    }

    /**
     * Action Logout
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(array('/admins/login'));
    }
}