<?php

class UsersPublicController extends Controller
{
    /**
     * @return array actions type list
     */
    public static function actionsType()
    {
        return array(
            'frontend' => array(
                'dashboard',
                'logout',
                'setting',
                'notifications',
                'verify',
                'forgetPassword',
                'changePassword',
                'authCallback',
                'bookmarked',
                'downloaded',
                'transactions',
                'library',
                'index',
                'sessions',
                'removeSession',
                'ResendVerification',
                'profile',
                'upload',
                'deleteUpload',
            )
        );
    }

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'checkAccess + dashboard, logout, setting, notifications, bookmarked, downloaded, transactions, library, sessions, removeSession',
        );
    }

    public function actions()
    {
        return array(
            'upload' => array(
                'class' => 'ext.dropZoneUploader.actions.AjaxUploadAction',
                'attribute' => 'avatar',
                'rename' => 'random',
                'validateOptions' => array(
                    'acceptedTypes' => array('jpg', 'jpeg', 'png')
                )
            ),
            'deleteUpload' => array(
                'class' => 'ext.dropZoneUploader.actions.AjaxDeleteUploadedAction',
                'modelName' => 'UserDetails',
                'attribute' => 'avatar',
                'uploadDir' => '/uploads/users',
                'storedMode' => 'field'
            ),
        );
    }

    /**
     * Logout Action
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(array('//'));
    }

    /**
     * Dashboard Action
     */
    public function actionDashboard()
    {
        Yii::app()->theme = 'frontend';
        $this->layout = '//layouts/panel';

        if(isset(Yii::app()->user->clinic))
            $this->redirect(array('/clinics/panel'));

        /* @var $user Users */
        $user = Users::model()->findByPk(Yii::app()->user->id);
        $criteria = new CDbCriteria();
        $criteria->select = 'clinics_clinics.post as post, clinics.*';
        $clinics = new CArrayDataProvider($user->clinics($criteria));

        $this->render('dashboard', array(
            'clinics' => $clinics,
        ));
    }

    /**
     * Change password
     */
    public function actionSetting()
    {
        Yii::app()->theme = 'frontend';
        $this->layout = '//layouts/panel';
        $model = Users::model()->findByPk(Yii::app()->user->getId());
        $model->setScenario('update');

        $this->performAjaxValidation($model);

        if (isset($_POST['Users'])) {
            $model->attributes = $_POST['Users'];
            if ($model->validate()) {
                $model->password = $_POST['Users']['newPassword'];
                if ($model->save()) {
                    Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
                    $this->redirect($this->createUrl('/dashboard'));
                } else
                    Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
            }
        }

        $this->render('setting', array(
            'model' => $model,
        ));
    }

    /**
     * Change profile data
     */
    public function actionProfile()
    {
        Yii::app()->theme = 'frontend';
        $this->layout = '//layouts/panel';

        $tmpDIR = Yii::getPathOfAlias("webroot") . '/uploads/temp/';
        $tmpUrl = Yii::app()->createAbsoluteUrl('/uploads/temp/');
        $avatarDIR = Yii::getPathOfAlias("webroot") . '/uploads/users/';
        $avatarUrl = Yii::app()->createAbsoluteUrl('/uploads/users');

        /* @var $model UserDetails */
        $model = UserDetails::model()->findByAttributes(array('user_id' => Yii::app()->user->getId()));

        $this->performAjaxValidation($model);

        $avatar = array();
        if (!is_null($model->avatar))
            $avatar = array(
                'name' => $model->avatar,
                'src' => $avatarUrl . '/' . $model->avatar,
                'size' => filesize($avatarDIR . $model->avatar),
                'serverName' => $model->avatar
            );

        if (isset($_POST['UserDetails'])) {
            unset($_POST['UserDetails']['user_id']);

            $avatarFlag = false;
            if (isset($_POST['UserDetails']['avatar']) && file_exists($tmpDIR . $_POST['UserDetails']['avatar']) && $_POST['UserDetails']['avatar'] != $model->avatar) {
                $file = $_POST['UserDetails']['avatar'];
                $avatar = array(array(
                    'name' => $file,
                    'src' => $tmpUrl . '/' . $file,
                    'size' => filesize($tmpDIR . $file),
                    'serverName' => $file
                ));
                $avatarFlag = true;
            }

            $model->attributes = $_POST['UserDetails'];
            if ($model->save()) {
                if ($avatarFlag) {
                    @rename($tmpDIR . $model->avatar, $avatarDIR . $model->avatar);
                    Yii::app()->user->setState('avatar', $model->avatar);
                }

                Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
                $this->refresh();
            } else
                Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
        }

        $this->render('profile', array(
            'model' => $model,
            'avatar' => $avatar,
        ));
    }

    /**
     * Verify email
     */
    public function actionVerify()
    {
        if (!Yii::app()->user->isGuest and Yii::app()->user->type != 'admin')
            $this->redirect($this->createAbsoluteUrl('//'));
        else if (!Yii::app()->user->isGuest and Yii::app()->user->type == 'admin')
            Yii::app()->user->logout(false);

        $token = Yii::app()->request->getQuery('token');
        $model = Users::model()->find('verification_token=:token', array(':token' => $token));

        if ($model) {
            if ($model->status == 'pending') {
                if (time() <= $model->create_date + 259200) {
                    $model->updateByPk($model->id, array('status' => 'active'));
                    Yii::app()->user->setFlash('success', 'حساب کاربری شما فعال گردید.');
                    $login = new UserLoginForm('OAuth');
                    $login->email = $model->email;
                    $login->OAuth = true;
                    if ($login->validate() && $login->login(true) === true)
                        $this->redirect(array('/dashboard'));
                    $this->redirect($this->createUrl('/login'));
                } else {
                    Yii::app()->user->setFlash('failed', 'لینک فعال سازی منقضی شده و نامعتبر می باشد. لطفا مجددا ثبت نام کنید.');
                    $this->redirect($this->createUrl('/login'));
                }
            } elseif ($model->status == 'active') {
                Yii::app()->user->setFlash('failed', 'این حساب کاربری قبلا فعال شده است.');
                $this->redirect($this->createUrl('/login'));
            } else {
                Yii::app()->user->setFlash('failed', 'امکان فعال سازی این کاربر وجود ندارد. لطفا مجددا ثبت نام کنید.');
                $this->redirect($this->createUrl('/login'));
            }
        } else {
            Yii::app()->user->setFlash('failed', 'لینک فعال سازی نامعتبر می باشد.');
            $this->redirect($this->createUrl('/login'));
        }
    }

    /**
     * Forget password
     */
    public function actionForgetPassword()
    {
        Yii::app()->theme = 'frontend';
        $this->layout = '//layouts/public';
        if (!Yii::app()->user->isGuest and Yii::app()->user->type != 'admin')
            $this->redirect($this->createAbsoluteUrl('//'));
        else if (!Yii::app()->user->isGuest and Yii::app()->user->type == 'admin')
            Yii::app()->user->logout(false);

        if (isset($_POST['email'])) {
            $model = Users::model()->findByAttributes(array('email' => $_POST['email']));
            if ($model) {
                if ($model->status == 'active') {
                    if ($model->change_password_request_count != 3) {
                        $token = md5($model->id . '#' . $model->password . '#' . $model->email . '#' . $model->create_date . '#' . time());
                        $count = intval($model->change_password_request_count);
                        $model->updateByPk($model->id, array('verification_token' => $token, 'change_password_request_count' => $count + 1));
                        $message = '<div style="color: #2d2d2d;font-size: 14px;text-align: right;">با سلام<br>بنا به درخواست شما جهت تغییر کلمه عبور لینک زیر خدمتتان ارسال گردیده است.</div>';
                        $message .= '<div style="text-align: right;font-size: 9pt;">';
                        $message .= '<a href="' . Yii::app()->getBaseUrl(true) . '/users/public/changePassword/token/' . $token . '">' . Yii::app()->getBaseUrl(true) . '/users/public/changePassword/token/' . $token . '</a>';
                        $message .= '</div>';
                        $message .= '<div style="font-size: 8pt;color: #888;text-align: right;">اگر شخص دیگری غیر از شما این درخواست را صادر نموده است، یا شما کلمه عبور خود را به یاد آورده‌اید و دیگر نیازی به تغییر آن ندارید، کلمه عبور قبلی/موجود شما همچنان فعال می‌باشد و می توانید از طریق <a href="' . ((strpos($_SERVER['SERVER_PROTOCOL'], 'https')) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . '/login">این صفحه</a> وارد حساب کاربری خود شوید.</div>';
                        $result = Mailer::mail($model->email, 'درخواست تغییر کلمه عبور در ' . Yii::app()->name, $message, Yii::app()->params['noReplyEmail']);
                        if ($result) {
                            Yii::app()->user->setFlash('success', 'لینک تغییر کلمه عبور به ' . $model->email . ' ارسال شد.');
                            $this->refresh();
                        } else
                            Yii::app()->user->setFlash('failed', 'در انجام عملیات خطایی رخ داده است لطفا مجددا تلاش کنید.');
                    } else
                        Yii::app()->user->setFlash('failed', 'بیش از 3 بار نمی توانید درخواست تغییر کلمه عبور بدهید.');
                } elseif ($model->status == 'pending')
                    Yii::app()->user->setFlash('failed', 'این حساب کاربری هنوز فعال نشده است.');
                elseif ($model->status == 'blocked')
                    Yii::app()->user->setFlash('failed', 'این حساب کاربری مسدود می باشد.');
                elseif ($model->status == 'deleted')
                    Yii::app()->user->setFlash('failed', 'این حساب کاربری حذف شده است.');
            } else
                Yii::app()->user->setFlash('failed', 'پست الکترونیکی وارد شده اشتباه است.');
        }

        $this->render('forget_password');
    }

    /**
     * Change password
     */
    public function actionChangePassword()
    {
        if (!Yii::app()->user->isGuest and Yii::app()->user->type != 'admin')
            $this->redirect($this->createAbsoluteUrl('//'));
        else if (!Yii::app()->user->isGuest and Yii::app()->user->type == 'admin')
            Yii::app()->user->logout(false);

        $token = Yii::app()->request->getQuery('token');
        $model = Users::model()->find('verification_token=:token', array(':token' => $token));

        if (!$model)
            $this->redirect($this->createAbsoluteUrl('//'));
        elseif ($model->change_password_request_count == 0)
            $this->redirect($this->createAbsoluteUrl('//'));

        $model->setScenario('change_password');
        $this->performAjaxValidation($model);

        if ($model->status == 'active') {
            Yii::app()->theme = 'frontend';
            $this->layout = '//layouts/login';

            if (isset($_POST['Users'])) {
                $model->password = $_POST['Users']['password'];
                $model->repeatPassword = $_POST['Users']['repeatPassword'];
                $model->verification_token = null;
                $model->change_password_request_count = 0;
                if ($model->save()) {
                    Yii::app()->user->setFlash('success', 'کلمه عبور با موفقیت تغییر یافت.');
                    $this->redirect($this->createUrl('/login'));
                } else
                    Yii::app()->user->setFlash('failed', 'در انجام عملیات خطایی رخ داده است لطفا مجددا تلاش کنید.');
            }

            $this->render('change_password', array(
                'model' => $model
            ));
        } else
            $this->redirect($this->createAbsoluteUrl('//'));
    }

    /**
     * List all notifications
     */
    public function actionNotifications()
    {
        $this->layout = '//layouts/panel';
        Yii::app()->theme = 'frontend';
        $criteria = new CDbCriteria();
        $criteria->addCondition('user_id=:user_id');
        $criteria->order = 'id DESC';
        $criteria->params = array(
            ':user_id' => Yii::app()->user->getId()
        );
        $model = UserNotifications::model()->findAll($criteria);
        UserNotifications::model()->updateAll(array('seen' => '1'), 'user_id=:user_id', array(':user_id' => Yii::app()->user->getId()));
        $this->render('notifications', array(
            'model' => $model
        ));
    }

    /**
     * List all bookmarked
     */
    public function actionBookmarked()
    {
        Yii::app()->theme = 'frontend';
        $this->layout = '//layouts/panel';

        $user = Users::model()->findByPk(Yii::app()->user->getId());
        /* @var $user Users */

        $this->render('bookmarked', array(
            'bookmarked' => $user->bookmarkedBooks
        ));
    }

    /**
     * List all transactions
     */
    public function actionTransactions()
    {
        Yii::app()->theme = 'frontend';
        $this->layout = '//layouts/panel';

        $model = new UserTransactions('search');
        $model->unsetAttributes();
        if (isset($_GET['UserTransactions']))
            $model->attributes = $_GET['UserTransactions'];
        $model->user_id = Yii::app()->user->getId();
        //

        $this->render('transactions', array(
            'model' => $model
        ));
    }

    /**
     * List all bought and bookmarked books
     */
    public function actionLibrary()
    {
        Yii::app()->theme = 'frontend';
        $this->layout = '//layouts/panel';
        $userID = Yii::app()->user->getId();
        $user = Users::model()->findByPk($userID);
        /* @var $user Users */
        // create downloaded model from Library for search in grid view
        $downloadBooks = new Library('search');
        $downloadBooks->unsetAttributes();
        if (isset($_GET['Library']) && isset($_GET['ajax']) && $_GET['ajax'] == 'downloaded-list')
            $downloadBooks->attributes = $_GET['Library'];
        $downloadBooks->user_id = $userID;
        $downloadBooks->download_status = Library::STATUS_DOWNLOADED;
        //
        // create bought model from Library for search in grid view
        $boughtBooks = new Library('search');
        $boughtBooks->unsetAttributes();
        if (isset($_GET['Library']) && isset($_GET['ajax']) && $_GET['ajax'] == 'bought-list')
            $boughtBooks->attributes = $_GET['Library'];
        $boughtBooks->user_id = $userID;
        $boughtBooks->download_status = Library::STATUS_DOWNLOADED_NOT;
        //
        // get my book
        $myBooks = false;
        if ($user->role_id == 2) {
            $criteria = Books::model()->getValidBooks();
            $criteria->addCondition('publisher_id = :publisher_id');
            $criteria->params[':publisher_id'] = $user->id;
            $myBooks = new CActiveDataProvider("Books", array(
                'criteria' => $criteria
            ));
        }
        ///

        $this->render('library', array(
            'user' => $user,
            'boughtBooks' => $boughtBooks,
            'downloadBooks' => $downloadBooks,
            'myBooks' => $myBooks,
        ));
    }

    /**
     * Performs the AJAX validation.
     * @param Users $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'users-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionGoogleLogin()
    {
        if(isset($_GET['return-url']))
            Yii::app()->user->returnUrl = $_GET['return-url'];
        $googleAuth = new GoogleOAuth();
        $model = new UserLoginForm('OAuth');
        $googleAuth->login($model);
    }

    public function actionIndex()
    {
        Yii::app()->theme = 'frontend';
        $this->layout = '//layouts/public';

        if (!Yii::app()->user->isGuest && Yii::app()->user->type == 'user')
            $this->redirect($this->createAbsoluteUrl('//'));

        $login = new UserLoginForm;
        $register = new Users('create');

        // Login codes
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            $errors = CActiveForm::validate($login);
            if (CJSON::decode($errors)) {
                echo $errors;
                Yii::app()->end();
            }
        }
        // collect user input data
        if (isset($_POST['UserLoginForm'])) {
            $login->attributes = $_POST['UserLoginForm'];
            if(isset($_POST['returnUrl']))
                Yii::app()->user->returnUrl = $_POST['returnUrl'];
            // validate user input and redirect to the previous page if valid
            if ($login->validate() && $login->login()) {
                if (Yii::app()->user->returnUrl != Yii::app()->request->baseUrl . '/')
                    $redirect = Yii::app()->createUrl('/'.Yii::app()->user->returnUrl);
                else
                    $redirect = Yii::app()->createAbsoluteUrl('/users/public/dashboard');
                if (isset($_POST['ajax'])) {
                    echo CJSON::encode(array('status' => true, 'url' => $redirect, 'msg' => 'در حال انتقال ...'));
                    Yii::app()->end();
                } else
                    $this->redirect($redirect);
            } else
                $login->password = '';
        }
        // End of login codes
        
        // Register codes
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'register-form') {
            $errors = CActiveForm::validate($register);
            if (CJSON::decode($errors)) {
                echo $errors;
                Yii::app()->end();
            }
        }
        if (isset($_POST['Users'])) {
            $register->attributes = $_POST['Users'];
            $register->status = 'pending';
            $register->create_date = time();
            if ($register->save()) {
                $token = md5($register->id . '#' . $register->password . '#' . $register->email . '#' . $register->create_date);
                $register->updateByPk($register->id, array('verification_token' => $token));
                $message = '<div style="color: #2d2d2d;font-size: 14px;text-align: right;">با سلام<br>برای فعال کردن حساب کاربری خود در ' . Yii::app()->name . ' بر روی لینک زیر کلیک کنید:</div>';
                $message .= '<div style="text-align: right;font-size: 9pt;">';
                $message .= '<a href="' . Yii::app()->getBaseUrl(true) . '/users/public/verify/token/' . $token . '">' . Yii::app()->getBaseUrl(true) . '/users/public/verify/token/' . $token . '</a>';
                $message .= '</div>';
                $message .= '<div style="font-size: 8pt;color: #888;text-align: right;">این لینک فقط 3 روز اعتبار دارد.</div>';
                Mailer::mail($register->email, 'ثبت نام در ' . Yii::app()->name, $message, Yii::app()->params['noReplyEmail']);
                if (isset($_POST['ajax'])) {
                    echo CJSON::encode(array('status' => true, 'msg' => 'ایمیل فعال سازی به پست الکترونیکی شما ارسال شد. لطفا Inbox و Spam پست الکترونیکی خود را چک کنید.'));
                    Yii::app()->end();
                }else
                    Yii::app()->user->setFlash('register-success', 'ایمیل فعال سازی به پست الکترونیکی شما ارسال شد. لطفا Inbox و Spam پست الکترونیکی خود را چک کنید.');
            } else
            {
                if (isset($_POST['ajax'])) {
                    echo CJSON::encode(array('status' => false, 'msg' => 'متاسفانه در ثبت نام مشکلی بوجود آمده است. لطفا مجددا سعی کنید.'));
                    Yii::app()->end();
                }else
                    Yii::app()->user->setFlash('register-failed', 'متاسفانه در ثبت نام مشکلی بوجود آمده است. لطفا مجددا سعی کنید.');
            }
        }
        // End of register codes

        $this->render('index', array(
            'login' => $login,
            'register' => $register,
        ));
    }

    public function actionLogin()
    {
        Yii::app()->theme = 'frontend';
        $this->layout = '//layouts/public';

        if (!Yii::app()->user->isGuest && Yii::app()->user->type == 'user')
            $this->redirect($this->createAbsoluteUrl('//'));

        $model = new UserLoginForm;

        // Login codes
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            $errors = CActiveForm::validate($model);
            if (CJSON::decode($errors)) {
                echo $errors;
                Yii::app()->end();
            }
        }
        // collect user input data
        if (isset($_POST['UserLoginForm'])) {
            $model->attributes = $_POST['UserLoginForm'];
            if(isset($_POST['returnUrl']))
                Yii::app()->user->returnUrl = $_POST['returnUrl'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login()) {
                if (Yii::app()->user->returnUrl != Yii::app()->request->baseUrl . '/')
                    $redirect = Yii::app()->createUrl('/'.Yii::app()->user->returnUrl);
                else
                    $redirect = Yii::app()->createAbsoluteUrl('/users/public/dashboard');
                if (isset($_POST['ajax'])) {
                    echo CJSON::encode(array('status' => true, 'url' => $redirect, 'msg' => 'در حال انتقال ...'));
                    Yii::app()->end();
                } else
                    $this->redirect($redirect);
            } else
                $model->password = '';
        }
        // End of login codes

        $this->render('login', array(
            'model' => $model,
        ));
    }

    public function actionResendVerification()
    {
        $email = Yii::app()->request->getQuery('email');
        if (!is_null($email)) {
            $model = Users::model()->find('email = :email', array(':email' => $email));
            $token = md5($model->id . '#' . $model->password . '#' . $model->email . '#' . $model->create_date);
            $model->updateByPk($model->id, array('verification_token' => $token));
            $message = '<div style="color: #2d2d2d;font-size: 14px;text-align: right;">با سلام<br>برای فعال کردن حساب کاربری خود در ' . Yii::app()->name . ' بر روی لینک زیر کلیک کنید:</div>';
            $message .= '<div style="text-align: right;font-size: 9pt;">';
            $message .= '<a href="' . Yii::app()->getBaseUrl(true) . '/users/public/verify/token/' . $token . '">' . Yii::app()->getBaseUrl(true) . '/users/public/verify/token/' . $token . '</a>';
            $message .= '</div>';
            $message .= '<div style="font-size: 8pt;color: #888;text-align: right;">این لینک فقط 3 روز اعتبار دارد.</div>';
            Mailer::mail($model->email, 'ثبت نام در ' . Yii::app()->name, $message, Yii::app()->params['noReplyEmail']);
            Yii::app()->user->setFlash('success', 'ایمیل فعال سازی به پست الکترونیکی شما ارسال شد. لطفا Inbox و Spam پست الکترونیکی خود را چک کنید.');
            $this->redirect(array('/login'));
        } else
            $this->redirect(array('/site'));
    }

    /**
     * Show User Sessions
     */
    public function actionSessions()
    {
        Yii::app()->theme = 'frontend';
        $this->layout = '//layouts/panel';
        $model =new Sessions('search');
        $model->unsetAttributes();
        if(isset($_GET['Sessions']))
            $model->attributes = $_GET['Sessions'];
        $model->user_type = "user";
        $model->user_id = Yii::app()->user->getId();
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
        $this->redirect(array('/users/public/sessions'));
    }
}