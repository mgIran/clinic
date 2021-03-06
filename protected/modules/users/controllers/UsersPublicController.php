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
                'recoverPassword',
                'authCallback',
                'downloaded',
                'transactions',
                'visits',
                'index',
                'sessions',
                'removeSession',
                'ResendVerification',
                'profile',
                'upload',
                'deleteUpload',
                'viewProfile',
                'getUserByCode',
                'login',
            )
        );
    }

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'checkAccess + dashboard, setting, notifications, bookmarked, downloaded, transactions, library, sessions, removeSession',
            'ajaxOnly + getUserByCode'
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
        Yii::app()->user->setState('clinic',null);
        $this->redirect(Yii::app()->createAbsoluteUrl('//'));
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
        $clinics = $user->clinics($criteria);
        if(count($clinics) == 1)
        {
            $this->redirect(array('/clinics/panel/enter/'.$clinics[0]->id));
        }
        $clinics = new CArrayDataProvider($clinics,array(
            'pagination' => false
        ));
        $this->render('dashboard', array(
            'clinics' => $clinics,
            'user' => $user,
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
        $model->setScenario('change_password');

        if (isset($_POST['Users'])) {
            $model->attributes = $_POST['Users'];
            if ($model->validate()) {
                $model->password = $_POST['Users']['newPassword'];
                $model->password = $model->encrypt($model->password);
                if ($model->save(false)) {
                    Yii::app()->user->setFlash('success', 'کلمه عبور با موفقیت تغییر یافت.');
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
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionViewProfile($id)
    {
        Yii::app()->theme = 'frontend';
        $this->layout = '//layouts/public';

        $model = Users::model()->findByPk($id);
        if ($clinicID = Yii::app()->request->getQuery('clinic')) {
            $criteria = new CDbCriteria();
            $criteria->addCondition('clinics.id = :id');
            $criteria->params[':id'] = $clinicID;
            $model->clinic = $model->clinics($criteria);
            if ($model->clinic)
                $model->clinic = $model->clinic[0];
        }

        $this->render('view-profile', array(
            'model' => $model,
        ));
    }

    public function actionGetUserByCode()
    {
        $nationalCode = $_POST['code'];
        /* @var $model Users */
        $model = Users::model()->find('national_code = :code', array(':code' => $nationalCode));

        if ($model)
            echo CJSON::encode(array(
                'status' => true,
                'first_name' => $model->userDetails->first_name,
                'last_name' => $model->userDetails->last_name,
                'mobile' => $model->userDetails->mobile,
                'email' => $model->email,
            ));
        else
            echo CJSON::encode(array(
                'status' => false
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
                if (time() <= (double)$model->create_date + 259200) {
                    $model->updateByPk($model->id, array('status' => 'active_number'));
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
            }  elseif ($model->status == 'active_number') {
                Yii::app()->user->setFlash('failed', 'حساب کاربری در انتظار تایید مدیریت است.');
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
                        $message .= '<a href="' . Yii::app()->getBaseUrl(true) . '/users/public/recoverPassword/token/' . $token . '">' . Yii::app()->getBaseUrl(true) . '/users/public/recoverPassword/token/' . $token . '</a>';
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
    public function actionRecoverPassword()
    {
        Yii::app()->theme = 'frontend';
        $this->layout = '//layouts/public';

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

        $model->setScenario('recover_password');

        $this->performAjaxValidation($model);

        if ($model->status == 'active') {

            if (isset($_POST['Users'])) {
                $model->password = $_POST['Users']['password'];
                $model->repeatPassword = $_POST['Users']['repeatPassword'];
                if($model->validate()){
                    $model->verification_token = null;
                    $model->change_password_request_count = 0;
                    $model->password = $model->encrypt($model->password);
                    if($model->save(false)){
                        Yii::app()->user->setFlash('success', 'کلمه عبور با موفقیت تغییر یافت.');
                        $this->redirect($this->createUrl('/login'));
                    }else
                        Yii::app()->user->setFlash('failed', 'در انجام عملیات خطایی رخ داده است لطفا مجددا تلاش کنید.');
                }
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
     * List all transactions
     */
    public function actionVisits()
    {
        Yii::app()->theme = 'frontend';
        $this->layout = '//layouts/panel';

        $model = new Visits('search');
        $model->unsetAttributes();
        if (isset($_GET['Visits']))
            $model->attributes = $_GET['Visits'];
        $model->user_id = Yii::app()->user->getId();
        //

        $this->render('visits', array(
            'model' => $model
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
                if (Yii::app()->user->returnUrl != Yii::app()->request->baseUrl . '/' &&
                    Yii::app()->user->returnUrl != 'logout')
                    $redirect = Yii::app()->createUrl('/'.Yii::app()->user->returnUrl);
                else
                    $redirect = Yii::app()->createAbsoluteUrl('/dashboard');
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

    public function actionRegister()
    {
        Yii::app()->theme = 'frontend';
        $this->layout = '//layouts/public';

        $model = new Users('create');

        if (isset($_POST['ajax']) && $_POST['ajax'] === 'register-form') {
            $errors = CActiveForm::validate($model);
            if (CJSON::decode($errors)) {
                echo $errors;
                Yii::app()->end();
            }
        }

        if (isset($_POST['Users'])) {
            $model->attributes = $_POST['Users'];
            $model->status = 'active_number';
            $model->create_date = time();
            $pwd = $model->password;
            $username = $model->mobile;
            if ($model->save()) {

                if($model->email) {
                    $token = md5($model->id . '#' . $model->password . '#' . $model->email . '#' . $model->create_date);
                    $model->updateByPk($model->id, array('verification_token' => $token));
                    $message = '<div style="color: #2d2d2d;font-size: 14px;text-align: right;">با سلام<br>برای فعال کردن حساب کاربری خود در ' . Yii::app()->name . ' بر روی لینک زیر کلیک کنید:</div>';
                    $message .= '<div style="text-align: right;font-size: 9pt;">';
                    $message .= '<a href="' . Yii::app()->getBaseUrl(true) . '/users/public/verify/token/' . $token . '">' . Yii::app()->getBaseUrl(true) . '/users/public/verify/token/' . $token . '</a>';
                    $message .= '</div>';
                    $message .= '<div style="font-size: 8pt;color: #888;text-align: right;">این لینک فقط 3 روز اعتبار دارد.</div>';
                    Mailer::mail($model->email, 'ثبت نام در ' . Yii::app()->name, $message, Yii::app()->params['noReplyEmail']);
                }

                // Send Sms
                $siteName = Yii::app()->name;
                $message = "ثبت نام شما در سایت {$siteName} با موفقیت انجام شد.
نام کاربری: {$username}
کلمه عبور: {$pwd}";
                $phone = $model->userDetails->mobile;
                if($phone)
                    Notify::SendSms($message, $phone);
                if (isset($_POST['ajax'])) {
//                    echo CJSON::encode(array('status' => true, 'msg' => 'ایمیل فعال سازی به پست الکترونیکی شما ارسال شد. لطفا Inbox و Spam پست الکترونیکی خود را چک کنید.'));
                    echo CJSON::encode(array('status' => true, 'msg' => 'ثبت نام شما با موفقیت انجام شد و در انتظار تایید مدیریت قرار گرفت. پس از تایید میتوانید وارد حساب کاربری خود شوید.'));
                    Yii::app()->end();
                } else
//                    Yii::app()->user->setFlash('register-success', 'ایمیل فعال سازی به پست الکترونیکی شما ارسال شد. لطفا Inbox و Spam پست الکترونیکی خود را چک کنید.');
                    Yii::app()->user->setFlash('register-success', 'ثبت نام شما با موفقیت انجام شد و در انتظار تایید مدیریت قرار گرفت. پس از تایید میتوانید وارد حساب کاربری خود شوید.');

//                Yii::app()->session->set('user_id',$model->id);
                $this->refresh();
                $this->redirect(array('verify'));
            } else {
                if (isset($_POST['ajax'])) {
                    echo CJSON::encode(array('status' => false, 'msg' => 'متاسفانه در ثبت نام مشکلی بوجود آمده است. لطفا مجددا سعی کنید.'));
                    Yii::app()->end();
                } else
                    Yii::app()->user->setFlash('register-failed', 'متاسفانه در ثبت نام مشکلی بوجود آمده است. لطفا مجددا سعی کنید.');
            }
        }

        $this->render('register', array(
            'model' => $model,
        ));
    }

//    public function actionVerify()
//    {
//
//    }

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