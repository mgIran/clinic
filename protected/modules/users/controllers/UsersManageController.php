<?php

class UsersManageController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';
    public $defaultAction = 'admin';

    /**
     * @return array actions type list
     */
    public static function actionsType()
    {
        return array(
            'backend' => array(
                'index',
                'view',
                'sessions',
                'removeSession',
                'create',
                'update',
                'admin',
                'adminPublishers',
                'delete',
                'userLibrary',
                'userTransactions',
                'changeCredit',
                'changeFinanceStatus',
                'confirmDevID',
                'deleteDevID',
                'confirmPublisher',
                'refusePublisher'
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

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'views' page.
     */
    public function actionCreate()
    {
        $model = new Users();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Users'])){
            $model->attributes = $_POST['Users'];
            if($model->save())
                $this->redirect(array('views', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'views' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $model->scenario = 'changeStatus';
        if(isset($_POST['Users'])){
            $model->attributes = $_POST['Users'];
            if($model->save()){
                Yii::app()->user->setFlash('success', '<span class="icon-check"></span>&nbsp;&nbsp;اطلاعات با موفقیت ذخیره شد.');
                if(isset($_POST['ajax'])){
                    echo CJSON::encode(['status' => 'ok']);
                    Yii::app()->end();
                }else
                    $this->redirect(array('admin'));
            }else{
                Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
                if(isset($_POST['ajax'])){
                    echo CJSON::encode(['status' => 'error']);
                    Yii::app()->end();
                }
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $model = $this->loadModel($id);
        $model->updateByPk($model->id, array('status' => 'deleted'));

        // if AJAX request (triggered by deletion via admin grid views), we should not redirect the browser
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl'])?$_POST['returnUrl']:array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $this->actionAdmin();
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new Users('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Users']))
            $model->attributes = $_GET['Users'];
        $model->role_id = 1;
        $this->render('admin', array(
            'model' => $model,
            'role' => 1
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdminPublishers()
    {
        $model = new Users('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Users']))
            $model->attributes = $_GET['Users'];
        $model->role_id = 2;
        $this->render('admin', array(
            'model' => $model,
            'role' => 2
        ));
    }

    /**
     * Confirm requested ID of publisher
     *
     * @param $id
     * @throws CDbException
     */
    public function actionConfirmDevID($id)
    {
        $model = UserDetails::model()->findByAttributes(array('user_id' => $id));
        if($model->details_status != 'accepted')
            Yii::app()->user->setFlash('failed', 'اطلاعات ناشر مورد نظر هنوز تایید نشده است.');
        else{
            $model->scenario = 'confirmDev';
            $request = UserDevIdRequests::model()->findByAttributes(array('user_id' => $id));
            $model->publisher_id = $request->requested_id;
            if($model->save()){
                if($request->delete()){
                    $this->createLog('شناسه شما توسط مدیر سیستم تایید شد.', $model->user_id);
                    Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
                }else
                    Yii::app()->user->setFlash('failed', 'در انجام عملیات خطایی رخ داده است لطفا مجددا تلاش کنید.');
            }else
                Yii::app()->user->setFlash('failed', 'در انجام عملیات خطایی رخ داده است لطفا مجددا تلاش کنید.');
        }
        if(isset($_GET['view-page']) && $_GET['view-page']==true)
            $this->redirect(array('/users/'.$model->user_id));
        else
            $this->redirect(array('/admins'));
    }

    /**
     * Delete requested ID of publisher
     *
     * @param $id
     * @throws CDbException
     */
    public function actionDeleteDevID($id)
    {
        $model = UserDevIdRequests::model()->findByAttributes(array('user_id' => $id));
        if($model->delete()){
            $this->createLog('شناسه شما توسط مدیر سیستم رد شد.', $model->user_id);
            Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
        }else
            Yii::app()->user->setFlash('failed', 'در انجام عملیات خطایی رخ داده است لطفا مجددا تلاش کنید.');
        if(isset($_GET['view-page']) && $_GET['view-page']==true)
            $this->redirect(array('/users/'.$model->user_id));
        else
            $this->redirect(array('/admins'));
    }

    /**
     * Confirm publisher
     *
     * @param $id
     * @throws CDbException
     */
    public function actionConfirmPublisher($id)
    {
        $model = UserDetails::model()->findByAttributes(array('user_id' => $id));
        $model->details_status = 'accepted';
        if($model->update()){
            $this->createLog('اطلاعات شما توسط مدیر سیستم تایید شد.', $model->user_id);
            Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
        }else
            Yii::app()->user->setFlash('failed', 'در انجام عملیات خطایی رخ داده است لطفا مجددا تلاش کنید.');
        if(isset($_GET['view-page']) && $_GET['view-page']==true)
            $this->redirect(array('/users/'.$model->user_id));
        else
            $this->redirect(array('/admins'));
    }

    /**
     * Change User Finance Status
     */
    public function actionChangeFinanceStatus()
    {
        if (isset($_POST['user_id'])) {
            $model = UserDetails::model()->findByPk($_POST['user_id']);
            $model->financial_info_status = $_POST['value'];
            $model->setScenario('change-finance-info');

            if ($model->save()) {
                if ($_POST['value'] == 'accepted')
                    $this->createLog('اطلاعات حساب بانکی شما توسط تیم مدیریت تایید شد.', $model->user_id);
                elseif ($_POST['value'] == 'refused')
                    $this->createLog('اطلاعات حساب بانکی شما توسط تیم مدیریت تایید نشد. لطفا اطلاعات حساب بانکی خود را تغییر دهید.', $model->user_id);

                echo CJSON::encode(array('status' => true));
            } else
                echo CJSON::encode(array('status' => false));
        }
    }

    /**
     * Delete publisher
     *
     * @param $id
     * @throws CDbException
     */
    public function actionRefusePublisher($id)
    {
        $model = UserDetails::model()->findByAttributes(array('user_id' => $id));
        $model->details_status = 'refused';
        if($model->update()){
            $this->createLog('اطلاعات شما توسط مدیر سیستم رد شد.', $model->user_id);
            Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
        }else
            Yii::app()->user->setFlash('failed', 'در انجام عملیات خطایی رخ داده است لطفا مجددا تلاش کنید.');
        if(isset($_GET['view-page']) && $_GET['view-page']==true)
            $this->redirect(array('/users/'.$model->user_id));
        else
            $this->redirect(array('/admins'));
    }

    /**
     * Change User Credit
     *
     * @param $id
     */
    public function actionChangeCredit($id)
    {
        $model = UserDetails::model()->findByAttributes(array('user_id' => $id));
        $model->scenario = 'change-credit';
        $lastCredit = Controller::parseNumbers(number_format($model->credit));
        if(isset($_POST['UserDetails'])){
            $model->credit = $_POST['UserDetails']['credit'];
            $newCredit = Controller::parseNumbers(number_format($model->credit));
            if($lastCredit != $newCredit && $model->save()){
                if($lastCredit < $newCredit)
                    $text = 'افزایش';
                else
                    $text = 'کاهش';
                $this->createLog("اعتبار شما توسط مدیر از {$lastCredit} تومان به {$newCredit} تومان {$text} یافت.", $model->user_id);
                Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
            }else{
                if($lastCredit == $newCredit)
                    Yii::app()->user->setFlash('warning', 'اعتبار تغییر نکرده است. لطفا اعتبار موردنظر را وارد کنید.');
                else
                    Yii::app()->user->setFlash('failed', 'در تغییر اعتبار مشکلی رخ داده است! لطفا مجدد بررسی فرمایید.');
            }
            $this->refresh();
        }
        $this->render('change_credit', array('model' => $model));
    }

    /**
     * Show User Library
     *
     * @param $id
     */
    public function actionUserLibrary($id)
    {
        $user = Users::model()->findByPk($id);
        /* @var $user Users */
        // create downloaded model from Library for search in grid view
        $downloadBooks =new Library('search');
        $downloadBooks->unsetAttributes();
        if(isset($_GET['Library']) && isset($_GET['ajax']) && $_GET['ajax']=='downloaded-list')
            $downloadBooks->attributes = $_GET['Library'];
        $downloadBooks->user_id = $id;
        $downloadBooks->download_status = Library::STATUS_DOWNLOADED;
        //
        // create bought model from Library for search in grid view
        $boughtBooks =new Library('search');
        $boughtBooks->unsetAttributes();
        if(isset($_GET['Library']) && isset($_GET['ajax']) && $_GET['ajax']=='bought-list')
            $boughtBooks->attributes = $_GET['Library'];
        $boughtBooks->user_id = $id;
        $boughtBooks->download_status = Library::STATUS_DOWNLOADED_NOT;
        //
        // get my book
        $myBooks = false;
        if($user->role_id == 2){
            $criteria = Books::model()->getValidBooks();
            $criteria->addCondition('publisher_id = :publisher_id');
            $criteria->params[':publisher_id'] = $user->id;
            $myBooks = new CActiveDataProvider("Books",array(
                'criteria' => $criteria
            ));
        }
        ///

        $this->render('user_library', array(
            'user' => $user,
            'boughtBooks' => $boughtBooks,
            'downloadBooks' => $downloadBooks,
            'myBooks' => $myBooks,
        ));
    }

    /**
     * Show User Transactions
     *
     * @param $id
     */
    public function actionUserTransactions($id)
    {
        $model =new UserTransactions('search');
        $model->unsetAttributes();
        if(isset($_GET['UserTransactions']))
            $model->attributes = $_GET['UserTransactions'];
        $model->user_id = $id;
        //

        $this->render('user_transactions', array(
            'model' => $model
        ));
    }

    /**
     * Show User Sessions
     *
     * @param $id
     */
    public function actionSessions($id)
    {
        $model =new Sessions('search');
        $model->unsetAttributes();
        if(isset($_GET['Sessions']))
            $model->attributes = $_GET['Sessions'];
        $model->user_type = "user";
        $model->user_id = $id;
        //

        $this->render('view_sessions', array(
            'model' => $model
        ));
    }

    public function actionRemoveSession($id)
    {
        $model = Sessions::model()->findByPk($id);
        if($model !== null)
            $model->delete();
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Users the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Users::model()->findByPk($id);
        if($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Users $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax'] === 'users-form'){
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}