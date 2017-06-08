<?php

class SiteController extends Controller
{
    /**
     * @return array actions type list
     */
    public static function actionsType()
    {
        return array(
            'frontend' => array(
                'index',
                'error',
                'contact',
                'about',
                'contactUs',
                'help',
                'transactions',
            ),
            'backend' => array(
                'transactions'
            )
        );
    }

    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&views=FileName
            'page' => array(
                'class' => 'CViewAction',
            )
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        Yii::app()->theme = "frontend";
        $this->layout = "public";

        $expertises = Expertises::model()->findAll();

        $this->render('index', array(
            'expertises' => $expertises,
        ));
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        Yii::app()->theme = 'frontend';
        $this->layout = '//layouts/error';
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact()
    {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
                $subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
                $headers = "From: $name <{$model->email}>\r\n" .
                    "Reply-To: {$model->email}\r\n" .
                    "MIME-Version: 1.0\r\n" .
                    "Content-Type: text/plain; charset=UTF-8";

                mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    public function actionAbout()
    {
        Yii::import('pages.models.*');
        Yii::app()->theme = 'frontend';
        $this->layout = '//layouts/public';
        $model = Pages::model()->findByPk(1);
        $this->render('//site/pages/page', array('model' => $model));
    }

    public function actionContactUs()
    {
        Yii::import('pages.models.*');
        Yii::app()->theme = 'frontend';
        $this->layout = '//layouts/public';
        $model = Pages::model()->findByPk(8);
        $this->render('//site/pages/page', array('model' => $model));
    }

    public function actionHelp()
    {
        Yii::import('pages.models.*');
        Yii::app()->theme = 'frontend';
        $this->layout = '//layouts/public';
        $model = Pages::model()->findByPk(6);
        $this->render('//site/pages/page', array('model' => $model));
    }

    public function actionTransactions()
    {
        Yii::app()->theme = 'abound';
        $this->layout = '//layouts/main';

        $model = new UserTransactions('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['UserTransactions']))
            $model->attributes = $_GET['UserTransactions'];

        $this->render('transactions', array(
            'model' => $model,
        ));
    }
}