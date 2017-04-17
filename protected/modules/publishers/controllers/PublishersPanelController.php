<?php

class PublishersPanelController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/panel';

    public static $sumSettlement = 0;

    /**
     * @return array actions type list
     */
    public static function actionsType()
    {
        return array(
            'frontend' => array(
                'account',
                'index',
                'discount',
                'settlement',
                'sales',
                'documents',
                'signup',
            ),
            'backend' => array(
                'manageSettlement',
                'uploadNationalCardImage',
                'uploadRegistrationCertificateImage',
                'update',
                'create',
                'excel'
            )
        );
    }

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'checkAccess',
        );
    }

    public function actions(){
        return array(
            'uploadNationalCardImage' => array(
                'class' => 'ext.dropZoneUploader.actions.AjaxUploadAction',
                'uploadDir' => '/uploads/users/national_cards',
                'attribute' => 'national_card_image',
                'rename' => 'random',
                'validateOptions' => array(
                    'acceptedTypes' => array('jpg','jpeg','png')
                ),
                'insert' => true,
                'module' => 'users',
                'modelName' => 'UserDetails',
                'findAttributes' => 'array("user_id" => $_POST["user_id"])',
                'scenario' => 'upload_photo',
                'storeMode' => 'field',
                'afterSaveActions' => array(
                    'resize' => array('width'=>500,'height'=>500)
                )
            ),
            'uploadRegistrationCertificateImage' => array(
                'class' => 'ext.dropZoneUploader.actions.AjaxUploadAction',
                'uploadDir' => '/uploads/users/registration_certificate',
                'attribute' => 'registration_certificate_image',
                'rename' => 'random',
                'validateOptions' => array(
                    'acceptedTypes' => array('jpg','jpeg','png')
                ),
                'insert' => true,
                'module' => 'users',
                'modelName' => 'UserDetails',
                'findAttributes' => 'array("user_id" => $_POST["user_id"])',
                'scenario' => 'upload_photo',
                'storeMode' => 'field',
                'afterSaveActions' => array(
                    'resize' => array('width'=>500,'height'=>500)
                )
            )
        );
    }
    
	public function actionIndex()
	{
        Yii::app()->theme='frontend';
        // create book buys for search in grid view
        $books =new Books('search');
        $books->unsetAttributes();
        if(isset($_GET['Books']) && isset($_GET['ajax']) && $_GET['ajax']=='books-list')
            $books->attributes = $_GET['Books'];
        $books->publisher_id = Yii::app()->user->getId();

        $this->render('index', array(
            'books' => $books,
        ));
    }


    public function actionDocuments()
    {
        Yii::app()->theme = 'market';
        Yii::app()->getModule("pages");
        $criteria = new CDbCriteria();
        $criteria->addCondition('category_id = 2');
        $documentsProvider = new CActiveDataProvider('Pages', array(
            'criteria' => $criteria,
        ));
        $this->render('documents', array(
            'documentsProvider' => $documentsProvider,
        ));
    }

    public function actionDiscount()
    {
        Yii::app()->theme = 'frontend';
        $this->layout = '//layouts/panel';
        $model = new BookDiscounts();

        if (isset($_GET['ajax']) && $_GET['ajax'] === 'books-discount-form') {
            $model->attributes = $_POST['BookDiscounts'];
            $errors = CActiveForm::validate($model);
            if (CJSON::decode($errors)) {
                echo $errors;
                Yii::app()->end();
            }
        }

        if (isset($_POST['BookDiscounts'])) {
            $model->attributes = $_POST['BookDiscounts'];
            if ($model->save()) {
                if (isset($_GET['ajax'])) {
                    echo CJSON::encode(array('status' => true, 'msg' => 'تخفیف با موفقیت اعمال شد.'));
                    Yii::app()->end();
                } else {
                    Yii::app()->user->setFlash('discount-success', 'اعمال تخفیف با موفقیت اعمال شد.');
                    $this->refresh();
                }
            } else
                Yii::app()->user->setFlash('discount-failed', 'متاسفانه در انجام درخواست مشکلی ایجاد شده است.');
        }

        $criteria = new CDbCriteria();
        $criteria->with[] = 'book';
        $criteria->addCondition('book.publisher_id = :user_id');
        $criteria->addCondition('book.deleted = 0');
        $criteria->addCondition('book.title != ""');
        $criteria->addCondition('end_date > :now');
        $criteria->params = array(
            ':user_id' => Yii::app()->user->getId(),
            ':now' => time()
        );
        $booksDataProvider = new CActiveDataProvider('BookDiscounts', array(
            'criteria' => $criteria,
        ));

        // delete expire discounts
        $criteria = new CDbCriteria();
        $criteria->addCondition('end_date < :now');
        $criteria->params = array(
            ':now' => time()
        );
        BookDiscounts::model()->deleteAll($criteria);

        Yii::app()->getModule('users');

        $criteria = new CDbCriteria();
        $criteria->addCondition('publisher_id = :user_id');
        $criteria->addCondition('deleted = 0');
        $criteria->addCondition('lastPackage.price != 0');
        $criteria->addCondition('title != ""');
        $criteria->with[] = 'discount';
        $criteria->with[] = 'lastPackage';
        $criteria->addCondition('discount.book_id IS NULL');
        $criteria->params = array(':user_id' => Yii::app()->user->getId());

        $books = CHtml::listData(Books::model()->findAll($criteria), 'id', 'title');
        $this->render('discount', array(
            'booksDataProvider' => $booksDataProvider,
            'books' => $books
        ));
    }

    /**
     * Update account
     */
    public function actionAccount()
    {
        Yii::app()->theme = 'frontend';
        Yii::import('application.modules.users.models.*');

        $detailsModel = UserDetails::model()->findByAttributes(array('user_id' => Yii::app()->user->getId()));
        $devIdRequestModel = UserDevIdRequests::model()->findByAttributes(array('user_id' => Yii::app()->user->getId()));
        if ($detailsModel->publisher_id == '' && is_null($devIdRequestModel))
            $devIdRequestModel = new UserDevIdRequests;

        $detailsModel->scenario = 'update_' . $detailsModel->type . '_profile';

        if (isset($_POST['ajax']) && $_POST['ajax'] === 'change-publisher-id-form')
            $this->performAjaxValidation($devIdRequestModel);
        else
            $this->performAjaxValidation($detailsModel);

        // Save publisher profile
        if (isset($_POST['UserDetails'])) {
            unset($_POST['UserDetails']['credit']);
            unset($_POST['UserDetails']['publisher_id']);
            unset($_POST['UserDetails']['details_status']);
            $detailsModel->attributes = $_POST['UserDetails'];
            $detailsModel->details_status = 'pending';
            if ($detailsModel->save()) {
                Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
                $this->refresh();
            } else
                Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
        }

        // Save the change request publisherID
        if (isset($_POST['UserDevIdRequests'])) {
            $devIdRequestModel->user_id = Yii::app()->user->getId();
            $devIdRequestModel->requested_id = $_POST['UserDevIdRequests']['requested_id'];
            if ($devIdRequestModel->save()) {
                Yii::app()->user->setFlash('success', 'شناسه درخواستی ثبت گردید و در انتظار تایید می باشد.');
                $this->refresh();
            } else
                Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
        }

        $nationalCardImageUrl = $this->createUrl('/uploads/users/national_cards');
        $nationalCardImagePath = Yii::getPathOfAlias('webroot') . '/uploads/users/national_cards';
        $nationalCardImage = array();
        if ($detailsModel->national_card_image != '')
            $nationalCardImage = array(
                'name' => $detailsModel->national_card_image,
                'src' => $nationalCardImageUrl . '/' . $detailsModel->national_card_image,
                'size' => (file_exists($nationalCardImagePath . '/' . $detailsModel->national_card_image)) ? filesize($nationalCardImagePath . '/' . $detailsModel->national_card_image) : 0,
                'serverName' => $detailsModel->national_card_image,
            );

        $registrationCertificateImageUrl = $this->createUrl('/uploads/users/registration_certificate');
        $registrationCertificateImagePath = Yii::getPathOfAlias('webroot') . '/uploads/users/registration_certificate';
        $registrationCertificateImage = array();
        if ($detailsModel->registration_certificate_image != '')
            $registrationCertificateImage = array(
                'name' => $detailsModel->registration_certificate_image,
                'src' => $registrationCertificateImageUrl . '/' . $detailsModel->registration_certificate_image,
                'size' => (file_exists($registrationCertificateImagePath . '/' . $detailsModel->registration_certificate_image)) ? filesize($registrationCertificateImagePath . '/' . $detailsModel->registration_certificate_image) : 0,
                'serverName' => $detailsModel->registration_certificate_image,
            );

        $this->render('account', array(
            'detailsModel' => $detailsModel,
            'devIdRequestModel' => $devIdRequestModel,
            'nationalCardImage' => $nationalCardImage,
            'registrationCertificateImage' => $registrationCertificateImage,
        ));
    }

    /**
     * Convert user account to publisher
     */
    public function actionSignup()
    {
        Yii::app()->theme = 'frontend';
        $data = array();

        switch (Yii::app()->request->getQuery('step')) {
            case 'agreement':
                Yii::import('application.modules.pages.models.*');
                $data['agreementText'] = Pages::model()->find('title=:title', array(':title' => 'قرارداد ناشران'));
                break;

            case 'profile':
                Yii::import('application.modules.users.models.*');
                Yii::import('application.modules.setting.models.*');
                $data['detailsModel'] = UserDetails::model()->findByAttributes(array('user_id' => Yii::app()->user->getId()));
                $minCredit = SiteSetting::model()->find('name=:name', array(':name' => 'min_credit'));

                if (is_null($data['detailsModel']->credit))
                    $data['detailsModel']->credit = 0;

                if ($data['detailsModel']->credit < $minCredit['value']) {
                    Yii::app()->user->setFlash('min_credit_fail', 'برای ثبت نام به عنوان ناشر باید حداقل ' . number_format($minCredit['value'], 0) . ' تومان اعتبار داشته باشید.');
                    $this->redirect($this->createUrl('/users/credit/buy'));
                }

                if (isset($_POST['ajax']) && ($_POST['ajax'] === 'update-real-profile-form' || $_POST['ajax'] === 'update-legal-profile-form')) {
                    $data['detailsModel']->scenario = 'update_' . $_POST['UserDetails']['type'] . '_profile';
                    $this->performAjaxValidation($data['detailsModel']);
                }

                // Save publisher profile
                if (isset($_POST['UserDetails'])) {
                    $data['detailsModel']->scenario = 'update_' . $_POST['UserDetails']['type'] . '_profile';
                    unset($_POST['UserDetails']['credit']);
                    unset($_POST['UserDetails']['publisher_id']);
                    unset($_POST['UserDetails']['details_status']);
                    $data['detailsModel']->attributes = $_POST['UserDetails'];
                    $data['detailsModel']->details_status = 'pending';
                    if ($data['detailsModel']->save()) {
                        $data['detailsModel']->user->role_id = 2;
                        $data['detailsModel']->user->scenario = 'change_role';
                        $data['detailsModel']->user->save(false);
                        Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
                        $this->redirect($this->createUrl('/publishers/panel/signup/step/finish'));
                    } else
                        Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
                }
                $nationalCardImageUrl = $this->createUrl('/uploads/users/national_cards');
                $nationalCardImagePath = Yii::getPathOfAlias('webroot') . '/uploads/users/national_cards';
                $data['nationalCardImage'] = array();
                if ($data['detailsModel']->national_card_image != '')
                    $data['nationalCardImage'] = array(
                        'name' => $data['detailsModel']->national_card_image,
                        'src' => $nationalCardImageUrl . '/' . $data['detailsModel']->national_card_image,
                        'size' => (file_exists($nationalCardImagePath . '/' . $data['detailsModel']->national_card_image)) ? filesize($nationalCardImagePath . '/' . $data['detailsModel']->national_card_image) : 0,
                        'serverName' => $data['detailsModel']->national_card_image,
                    );
                $registrationCertificateImageUrl = $this->createUrl('/uploads/users/registration_certificate');
                $registrationCertificateImagePath = Yii::getPathOfAlias('webroot') . '/uploads/users/registration_certificate';
                $data['registrationCertificateImage'] = array();
                if ($data['detailsModel']->registration_certificate_image != '')
                    $data['registrationCertificateImage'] = array(
                        'name' => $data['detailsModel']->registration_certificate_image,
                        'src' => $registrationCertificateImageUrl . '/' . $data['detailsModel']->registration_certificate_image,
                        'size' => (file_exists($registrationCertificateImagePath . '/' . $data['detailsModel']->registration_certificate_image)) ? filesize($registrationCertificateImagePath . '/' . $data['detailsModel']->registration_certificate_image) : 0,
                        'serverName' => $data['detailsModel']->registration_certificate_image,
                    );
                break;

            case 'finish':
                if (isset($_POST['goto_publisher_panel'])) {
                    Yii::app()->user->setState('roles', 'publisher');
                    $this->redirect($this->createUrl('/publishers/panel'));
                }
                Yii::import('application.modules.users.models.*');
                $data['userDetails'] = UserDetails::model()->findByAttributes(array('user_id' => Yii::app()->user->getId()));
                break;
        }

        $this->render('signup', array(
            'step' => Yii::app()->request->getQuery('step'),
            'data' => $data,
        ));
    }

    /**
     * Settlement
     */
    public function actionSettlement()
    {
        Yii::app()->theme = 'frontend';
        $this->layout = '//layouts/panel';
        Yii::app()->getModule('setting');
        $setting = SiteSetting::model()->find('name=:name', array(':name' => 'min_credit'));
        Yii::app()->getModule('users');
        Yii::app()->getModule('pages');
        $userDetailsModel = UserDetails::model()->findByAttributes(array('user_id' => Yii::app()->user->getId()));
        $userDetailsModel->setScenario('update-settlement');
        // Get history of settlements
        $criteria = new CDbCriteria();
        $criteria->addCondition('user_id=:user_id');
        $criteria->params = array(':user_id' => Yii::app()->user->getId());
        $settlementHistory = new CActiveDataProvider('UserSettlement', array(
            'criteria' => $criteria,
        ));

        $this->performAjaxValidation($userDetailsModel);

        if(isset($_POST['UserDetails'])) {
            $userDetailsModel->iban=$_POST['UserDetails']['iban'];
            $userDetailsModel->account_owner_name=$_POST['UserDetails']['account_owner_name'];
            $userDetailsModel->account_owner_family=$_POST['UserDetails']['account_owner_family'];
            $userDetailsModel->account_type=$_POST['UserDetails']['account_type'];
            $userDetailsModel->account_number=$_POST['UserDetails']['account_number'];
            $userDetailsModel->bank_name=$_POST['UserDetails']['bank_name'];
            $userDetailsModel->financial_info_status='pending';
            if($userDetailsModel->save())
            {
                Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
                $this->refresh();
            } else
                Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است لطفا مجددا تلاش کنید.');
        }

        $purifier = new CHtmlPurifier();

        $this->render('settlement', array(
            'userDetailsModel' => $userDetailsModel,
            'settlementHistory' => $settlementHistory,
            'min_credit' => $setting->value,
            'formDisabled' => false,
        ));
    }

    /**
     * Report sales
     */
    public function actionSales()
    {
        Yii::app()->theme = 'frontend';
        $this->layout = '//layouts/panel';

        // user's books
        $criteria = new CDbCriteria();
        $criteria->addCondition('publisher_id=:dev_id');
        $criteria->addCondition('title!=""');
        $criteria->params = array(':dev_id' => Yii::app()->user->getId());
        $books = new CActiveDataProvider('Books', array(
            'criteria' => $criteria,
        ));

        $labels = $values = array();
        if (isset($_POST['show-chart'])) {
            $criteria = new CDbCriteria();
            $criteria->addCondition('date > :from_date');
            $criteria->addCondition('date < :to_date');
            $criteria->addCondition('book_id=:book_id');
            $criteria->params = array(
                ':from_date' => $_POST['from_date_altField'],
                ':to_date' => $_POST['to_date_altField'],
                ':book_id' => $_POST['book_id'],
            );
            $report = BookBuys::model()->findAll($criteria);
            if ($_POST['to_date_altField'] - $_POST['from_date_altField'] < (60 * 60 * 24 * 30)) {
                // show daily report
                $datesDiff = $_POST['to_date_altField'] - $_POST['from_date_altField'];
                $daysCount = ($datesDiff / (60 * 60 * 24));
                for ($i = 0; $i < $daysCount; $i++) {
                    $labels[] = JalaliDate::date('d F Y', $_POST['from_date_altField'] + (60 * 60 * (24 * $i)));
                    $count = 0;
                    foreach ($report as $model) {
                        if ($model->date >= $_POST['from_date_altField'] + (60 * 60 * (24 * $i)) and $model->date < $_POST['from_date_altField'] + (60 * 60 * (24 * ($i + 1))))
                            $count++;
                    }
                    $values[] = $count;
                }
            } else {
                // show monthly report
                $datesDiff = $_POST['to_date_altField'] - $_POST['from_date_altField'];
                $monthCount = ceil($datesDiff / (60 * 60 * 24 * 30));
                for ($i = 0; $i < $monthCount; $i++) {
                    $labels[] = JalaliDate::date('d F', $_POST['from_date_altField'] + (60 * 60 * 24 * (30 * $i))) . ' الی ' . JalaliDate::date('d F', $_POST['from_date_altField'] + (60 * 60 * 24 * (30 * ($i + 1))));
                    $count = 0;
                    foreach ($report as $model) {
                        if ($model->date >= $_POST['from_date_altField'] + (60 * 60 * 24 * (30 * $i)) and $model->date < $_POST['from_date_altField'] + (60 * 60 * 24 * (30 * ($i + 1))))
                            $count++;
                    }
                    $values[] = $count;
                }
            }
        } else {
            $userBooks = Books::model()->findAllByAttributes(array('publisher_id' => Yii::app()->user->getId()));
            $criteria = new CDbCriteria();
            $criteria->addCondition('date > :from_date');
            $criteria->addCondition('date < :to_date');
            $criteria->addInCondition('book_id', CHtml::listData($userBooks, 'id', 'id'));
            $criteria->params[':from_date'] = strtotime(date('Y/m/d 00:00:01'));
            $criteria->params[':to_date'] = strtotime(date('Y/m/d 23:59:59'));
            $report = BookBuys::model()->findAll($criteria);
            for ($i = 0; $i < count($userBooks); $i++) {
                $labels[] = CHtml::encode($userBooks[$i]->title);
                $count = 0;
                foreach ($report as $model) {
                    if ($model->book_id == $userBooks[$i]->id)
                        $count++;
                }
                $values[] = $count;
            }
        }

        $this->render('sales', array(
            'books' => $books,
            'labels' => $labels,
            'values' => $values,
        ));
    }

    /**
     * Manage settlement
     */
    public function actionManageSettlement()
    {
        Yii::app()->theme = 'abound';
        $this->layout = '//layouts/main';

        if (isset($_POST['token'])) {
            if (!isset($_POST['token']) or $_POST['token'] == '') {
                Yii::app()->user->setFlash('failed', 'کد رهگیری نمی تواند خالی باشد.');
                $this->refresh();
            }
            if (!isset($_POST['amount']) or $_POST['amount'] == '') {
                Yii::app()->user->setFlash('failed', 'مبلغ تسویه نمی تواند خالی باشد.');
                $this->refresh();
            }
            $amount = doubleval($_POST['amount']);
            if (!$amount) {
                Yii::app()->user->setFlash('failed', 'مبلغ تسویه نامعتبر است. لطفا مبلغ را با دقت وارد کنید.');
                $this->refresh();
            }
            $iban = $_POST['iban'];
            if(!$iban){
                Yii::app()->user->setFlash('failed', 'شماره شبا نمی تواند خالی باشد.');
                $this->refresh();
            }else {
                if (strlen($iban) < 24 or strlen($iban) < 24) {
                    Yii::app()->user->setFlash('failed', 'شماره شبا باید 24 کاراکتر باشد.');
                    $this->refresh();
                }
                else {
                    if (preg_match('/^\d{24}/', $iban) == 0) {
                        Yii::app()->user->setFlash('failed', 'شماره شبا نامعتبر است.');
                        $this->refresh();
                    }
                }
            }

            $userDetails=UserDetails::model()->findByAttributes(array('user_id'=>$_POST['user_id']));
            $model=new UserSettlement();
            $model->user_id=$userDetails->user_id;
            $model->token=$_POST['token'];
            $model->account_type= $userDetails->account_type;
            $model->account_owner_name= $userDetails->account_owner_name;
            $model->account_owner_family= $userDetails->account_owner_family;
            $model->account_number= $userDetails->account_number;
            $model->bank_name= $userDetails->bank_name;
            $model->iban=$iban;
            $model->amount= $amount;
            $model->date=time();
            if($model->save()) {
                $userDetails->earning = $userDetails->earning - $amount;
                $userDetails->save();
                $this->createLog('مبلغ ' . Controller::parseNumbers(number_format($model->amount)) . ' تومان در تاریخ ' .
                    JalaliDate::date('Y/m/d - H:i', $model->date) .
                    ' با کد رهگیری ' . $model->token . ' به شماره شبای IR' . $model->iban . ' واریز شد.', $userDetails->user_id);
                Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
                $this->refresh();
            } else
                Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است لطفا مجددا تلاش کنید.');
        }

        $criteria = new CDbCriteria();
        $criteria->select = 'SUM(amount) AS amount,date , DAY(FROM_UNIXTIME(date)) as order_day';
        $criteria->group = 'order_day';
        $settlementHistory = new CActiveDataProvider('UserSettlement', array(
            'criteria' => $criteria,
            'pagination' => array('pageSize' => 20)
        ));

        $criteria = UserDetails::SettlementCriteria();
        $settlementRequiredUsers = new CActiveDataProvider('UserDetails', array(
            'criteria' => $criteria,
        ));
        $this->render('manage_settlement', array(
            'settlementHistory' => $settlementHistory,
            'settlementRequiredUsers' => $settlementRequiredUsers,
        ));
    }

    /**
     * Performs the AJAX validation.
     * @param Books $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax'])) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * export excel
     */
    public function actionExcel()
    {
        $criteria = UserDetails::SettlementCriteria();
        $settlementUsers = UserDetails::model()->findAll($criteria);

        $objPHPExcel = Yii::app()->yexcel->createPHPExcel();
        $objPHPExcel = new PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Ketabic Website")
            ->setLastModifiedBy("")
            ->setTitle("YiiExcel Test Document")
            ->setSubject("Settlement Users Detail");
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()
            ->setCellValue('A1', 'شماره شبا')
            ->setCellValue('B1', 'مبلغ قابل تسویه (تومان)')
            ->setCellValue('C1', 'نام صاحب حساب / نوع حقوقی')
            ->setCellValue('D1', 'نام خانوادگی / نام صاحب حساب حقوقی')
            ->setCellValue('E1', 'نام بانک')
            ->setCellValue('F1', 'شماره حساب')
            ->setCellValue('G1', 'نام انتشارات');

        foreach ($settlementUsers as $key => $settlementUser) {
            $row = $key + 2;
            if ($settlementUser->account_type == UserDetails::ACCOUNT_TYPE_REAL) {
                $name = $settlementUser->account_owner_name;
                $family = $settlementUser->account_owner_family;
            } elseif ($settlementUser->account_type == UserDetails::ACCOUNT_TYPE_LEGAL) {
                $name = $settlementUser->account_owner_family;
                $family = $settlementUser->account_owner_name;
            }
            $objPHPExcel->getActiveSheet()
                ->setCellValue('A' . $row, $settlementUser->iban.' ')
                ->setCellValue('B' . $row, $settlementUser->getSettlementAmount().' ')
                ->setCellValue('C' . $row, $name)
                ->setCellValue('D' . $row, $family)
                ->setCellValue('E' . $row, $settlementUser->bank_name)
                ->setCellValue('F' . $row, $settlementUser->account_number.' ')
                ->setCellValue('G' . $row, $settlementUser->publication_name);
        }
        // Save a xls file
        $filename = 'Settlement Publishers';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = Yii::app()->yexcel->createActiveSheet($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        unset($this->objWriter);
        unset($this->objWorksheet);
        unset($this->objReader);
        unset($this->objPHPExcel);
        exit();
    }

    public function actionCreate()
    {
        Yii::app()->theme = 'abound';
        $this->layout = '//layouts/column2';

        $model = new Users();
        /* @var $model Users */

        $this->performAjaxValidation($model);

        if (isset($_POST['Users'])) {
            $model->attributes = $_POST['Users'];
            $model->role_id = 2;
            $model->create_date = time();

            if($model->save()){
                Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
                $this->redirect(array('update', 'id' => $model->id));
            } else
                Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است لطفا مجددا تلاش کنید.');
        }

        $this->render('create', array(
            'model' => $model
        ));
    }

    public function actionUpdate($id)
    {
        Yii::app()->theme = 'abound';
        $this->layout = '//layouts/column2';

        $model = Users::model()->findByPk($id);
        /* @var $model Users */

        $model->userDetails->scenario = 'update_' . $model->userDetails->type . '_profile';

        if (isset($_POST['Users']))
            $this->performAjaxValidation($model);
        else
            $this->performAjaxValidation($model->userDetails);

        // Save publisher profile
        if (isset($_POST['UserDetails'])) {
            $model->userDetails->attributes = $_POST['UserDetails'];

            if (isset($_POST['default_commission']))
                $model->userDetails->commission = null;

            if ($model->userDetails->save()) {
                Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ثبت شد.');
                $this->refresh();
            } else
                Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
        }

        $nationalCardImageUrl = $this->createUrl('/uploads/users/national_cards');
        $nationalCardImagePath = Yii::getPathOfAlias('webroot') . '/uploads/users/national_cards';
        $nationalCardImage = array();
        if ($model->userDetails->national_card_image != '')
            $nationalCardImage = array(
                'name' => $model->userDetails->national_card_image,
                'src' => $nationalCardImageUrl . '/' . $model->userDetails->national_card_image,
                'size' => (file_exists($nationalCardImagePath . '/' . $model->userDetails->national_card_image)) ? filesize($nationalCardImagePath . '/' . $model->userDetails->national_card_image) : 0,
                'serverName' => $model->userDetails->national_card_image,
            );

        $registrationCertificateImageUrl = $this->createUrl('/uploads/users/registration_certificate');
        $registrationCertificateImagePath = Yii::getPathOfAlias('webroot') . '/uploads/users/registration_certificate';
        $registrationCertificateImage = array();
        if ($model->userDetails->registration_certificate_image != '')
            $registrationCertificateImage = array(
                'name' => $model->userDetails->registration_certificate_image,
                'src' => $registrationCertificateImageUrl . '/' . $model->userDetails->registration_certificate_image,
                'size' => (file_exists($registrationCertificateImagePath . '/' . $model->userDetails->registration_certificate_image)) ? filesize($registrationCertificateImagePath . '/' . $model->userDetails->registration_certificate_image) : 0,
                'serverName' => $model->userDetails->registration_certificate_image,
            );

        $this->render('update', array(
            'model' => $model,
            'nationalCardImage' => $nationalCardImage,
            'registrationCertificateImage' => $registrationCertificateImage,
        ));
    }
}