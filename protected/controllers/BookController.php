<?php

class BookController extends Controller
{
    public $layout = '//layouts/inner';
    /**
     * @return array actions type list
     */
    public static function actionsType()
    {
        return array(
            'frontend' => array('discount' ,'tag' ,'search' ,'view' ,'download' ,'publisher' ,'buy' ,'bookmark' ,'rate' ,'verify' ,'updateVersion') ,
            'backend' => array('reportSales' ,'reportIncome','reportBookSales')
        );
    }

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'checkAccess + reportSales, reportIncome, reportBookSales, reportCreditBuys, buy, bookmark, rate, verify, updateVersion' ,
            'postOnly + bookmark' ,
        );
    }

    public function actionView($id)
    {
        Yii::import('users.models.*');
        Yii::app()->theme = "frontend";
        $this->layout = "//layouts/index";
        $model = $this->loadModel($id);
        $this->keywords = $model->getKeywords();
        $this->description = mb_substr(strip_tags($model->description), 0, 160, 'utf-8');
        $model->seen = $model->seen + 1;
        $model->save();
        $this->saveInCookie($model->category_id);
        // Has bookmarked this books by user
        $bookmarked = false;
        if (!Yii::app()->user->isGuest) {
            $hasRecord = UserBookBookmark::model()->findByAttributes(array('user_id' => Yii::app()->user->getId(), 'book_id' => $id));
            if ($hasRecord)
                $bookmarked = true;
        }
        // Get similar books
        $criteria = Books::model()->getValidBooks(array($model->category_id));
        $criteria->addCondition('id!=:id');
        $criteria->params[':id'] = $model->id;
        $criteria->limit = 10;
        $similar = new CActiveDataProvider('Books', array('criteria' => $criteria));

        Yii::import('pages.models.*');
        $about = Pages::model()->findByPk(3);

        $categories = BookCategories::model()->getCategoriesAsHtml(false, array('tagName' => 'li', 'content' => '<a href="{url}">{title}<small>({booksCount})</small></a>'), array('tagName' => 'ul', 'htmlOptions' => array('class' => 'categories')));

        $this->render('view', array(
            'model' => $model,
            'similar' => $similar,
            'bookmarked' => $bookmarked,
            'about' => $about,
            'categories' => $categories,
        ));
    }

    /**
     * Buy book
     */
    public function actionBuy($id ,$title)
    {
        Yii::app()->theme = 'frontend';
        $this->layout = 'panel';
        $userID = Yii::app()->user->getId();
        $model = $this->loadModel($id);

        if(Library::BookExistsInLib($model->id, $model->lastPackage->id, $userID)){
            Yii::app()->user->setFlash('warning', 'این کتاب در کتابخانه ی شما موجود است.');
            $this->redirect(array('/library'));
        }

        // price with publisher discount or not
        $basePrice = $model->hasDiscount()?$model->offPrice:$model->price;

        $buy = BookBuys::model()->findByAttributes(array('user_id' => $userID, 'book_id' => $id));

        Yii::app()->getModule('users');
        $user = Users::model()->findByPk($userID);
        /* @var $user Users */
        $price = 0;
        if($model->publisher_id != $userID){

            Yii::app()->getModule('discountCodes');
            $price = $basePrice; // price, base price with discount code
            $discountCodesInSession = DiscountCodes::calculateDiscountCodes($price,'digital');
            $discountObj = DiscountCodes::model()->findByAttributes(['code' => $discountCodesInSession]);
            // use Discount codes
            if (isset($_POST['DiscountCodes'])) {
                $code = $_POST['DiscountCodes']['code'];
                $criteria = DiscountCodes::ValidCodes();
                $criteria->compare('code', $code);
                $discount = DiscountCodes::model()->find($criteria);
                /* @var $discount DiscountCodes */
                if ($discount === NULL) {
                    Yii::app()->user->setFlash('failed', 'کد تخفیف مورد نظر موجود نیست.');
                    $this->refresh();
                }
                if ($discount->digital_allow) {
                    Yii::app()->user->setFlash('failed', 'کد تخفیف مورد نظر قابل استفاده در این بخش نیست.');
                    $this->refresh();
                }
                if ($discount->limit_times && $discount->usedCount() >= $discount->limit_times) {
                    Yii::app()->user->setFlash('failed', 'محدودیت تعداد استفاده از کد تخفیف مورد نظر به اتمام رسیده است.');
                    $this->refresh();
                }
                if ($discount->user_id && $discount->user_id != $userID) {
                    Yii::app()->user->setFlash('failed', 'کد تخفیف مورد نظر نامعتبر است.');
                    $this->refresh();
                }
                $used = $discount->codeUsed(array(
                        'condition' => 'user_id = :user_id',
                        'params' => array(':user_id' => $userID),
                    )
                );
                /* @var $used DiscountUsed */
                if ($used) {
                    $u_date = JalaliDate::date('Y/m/d - H:i', $used->date);
                    Yii::app()->user->setFlash('failed', "کد تخفیف مورد نظر قبلا در تاریخ {$u_date} استفاده شده است.");
                    $this->refresh();
                }
                if(DiscountCodes::addDiscountCodes($discount))
                    Yii::app()->user->setFlash('success', 'کد تخفیف با موفقیت اعمال شد.');
                else
                    Yii::app()->user->setFlash('failed', 'کد تخفیف در حال حاضر اعمال شده است.');
                $this->refresh();
            }
            if(isset($_POST['Buy'])){
                if($price !== 0){
                    if(isset($_POST['Buy']['credit'])){
                        if($user->userDetails->credit < $price){
                            Yii::app()->user->setFlash('credit-failed', 'اعتبار فعلی شما کافی نیست!');
                            Yii::app()->user->setFlash('failReason', 'min_credit');
                            $this->refresh();
                        }

                        $userDetails = UserDetails::model()->findByAttributes(array('user_id' => $userID));
                        $userDetails->setScenario('update-credit');
                        $userDetails->credit = $userDetails->credit - $price;
                        $userDetails->score = $userDetails->score + 1;
                        if($userDetails->save()){
                            $buyId = $this->saveBuyInfo($model, $user, 'credit', $basePrice, $price, $discountObj);
                            Library::AddToLib($model->id, $model->lastPackage->id, $user->id);
                            if($discountCodesInSession)
                                DiscountCodes::InsertCodes($user); // insert used discount code in db
                            Yii::app()->user->setFlash('success', 'خرید شما با موفقیت انجام شد.');
                            $this->redirect(array('/library'));
                        }else
                            Yii::app()->user->setFlash('failed', 'در انجام عملیات خرید خطایی رخ داده است. لطفا مجددا تلاش کنید.');
                    }elseif(isset($_POST['Buy']['gateway'])){
                        // Save payment
                        $transaction = new UserTransactions();
                        $transaction->user_id = $userID;
                        $transaction->amount = $price;
                        $transaction->date = time();
                        $transaction->gateway_name = 'زرین پال';
                        $transaction->type = UserTransactions::TRANSACTION_TYPE_BOOK;

                        if($transaction->save()){
                            $gateway = new ZarinPal();
                            $gateway->callback_url = Yii::app()->getBaseUrl(true) . '/book/verify/' . $id . '/' . urlencode($title);
                            $siteName = Yii::app()->name;
                            $description = "خرید کتاب {$title} از وبسایت {$siteName} از طریق درگاه {$gateway->getGatewayName()}";
                            $result = $gateway->request(doubleval($transaction->amount), $description, Yii::app()->user->email, $this->userDetails && $this->userDetails->phone?$this->userDetails->phone:'0');
                            $transaction->scenario = 'set-authority';
                            $transaction->description = $description;
                            $transaction->authority = $result->getAuthority();
                            $transaction->save();
                            //Redirect to URL You can do it also by creating a form
                            if($result->getStatus() == 100)
                                $this->redirect($gateway->getRedirectUrl());
                            else
                                throw new CHttpException(404, 'خطای بانکی: ' . $result->getError());
                        }
                    }
                }else{
                    $buyId = $this->saveBuyInfo($model, $user, 'credit', $basePrice, $price, $discountObj);
                    Library::AddToLib($model->id, $model->lastPackage->id, $userID);
                    if($discountCodesInSession)
                        DiscountCodes::InsertCodes($user); // insert used discount code in db
                    Yii::app()->user->setFlash('success', 'خرید شما با موفقیت انجام شد.');
                    $this->redirect(array('/library'));
                }
            }
            $user->refresh();
        }
        $this->render('buy', array(
            'model' => $model,
            'basePrice' => $basePrice,
            'price' => $price,
            'user' => $user,
            'bought' => ($buy)?true:false,
            'discountCodesInSession' => isset($discountCodesInSession)?$discountCodesInSession:false,
        ));
    }

    public function actionVerify($id ,$title)
    {
        if(!isset($_GET['Authority']))
            $this->redirect(array('/book/buy' ,'id' => $id ,'title' => $title));
        $Authority = $_GET['Authority'];
        Yii::app()->theme = 'frontend';
        $this->layout = '//layouts/panel';
        $model = UserTransactions::model()->findByAttributes(array(
            'authority' => $Authority,
            'user_id' => Yii::app()->user->getId(),
            'type' => UserTransactions::TRANSACTION_TYPE_BOOK
        ));
        $book = Books::model()->findByPk($id);
        $user = Users::model()->findByPk(Yii::app()->user->getId());
        $basePrice = $book->hasDiscount()?$book->offPrice:$book->price;
        $Amount = $model->amount; //Amount will be based on Toman

        Yii::app()->getModule('discountCodes');
        $price = $basePrice; // price, base price with discount code
        $discountCodesInSession = DiscountCodes::calculateDiscountCodes($price);
        $discountObj = DiscountCodes::model()->findByAttributes(['code' => $discountCodesInSession]);

        $transactionResult = false;
        if ($_GET['Status'] == 'OK') {
            $gateway = new ZarinPal();
            $gateway->verify($Authority, $Amount);
            if ($gateway->getStatus() == 100) {
                $model->scenario = 'update';
                $model->status = 'paid';
                $model->token = $gateway->getRefId();
                $model->save();
                $transactionResult = true;
                $buyId = $this->saveBuyInfo($book, $user, 'gateway', $basePrice, $Amount, $discountObj,$model->id);
                Library::AddToLib($book->id ,$book->lastPackage->id ,$user->id);
                if($discountCodesInSession)
                    DiscountCodes::InsertCodes($user); // insert used discount code in db
                Yii::app()->user->setFlash('success' ,'پرداخت شما با موفقیت انجام شد.');
            } else {
                Yii::app()->user->setFlash('failed', $gateway->getError());
                $this->redirect(array('/book/buy/'.$id.'/'.urlencode($title)));
            }
        } else
            Yii::app()->user->setFlash('failed' ,'عملیات پرداخت ناموفق بوده یا توسط کاربر لغو شده است.');
        //
        $this->render('verify' ,array(
            'transaction' => $model ,
            'book' => $book ,
            'user' => $user ,
            'price' => $model->amount ,
            'transactionResult' => $transactionResult ,
        ));
    }

    /**
     * Save buy information
     *
     * @param $book
     * @param $user
     * @param $method
     * @param $price
     * @param $basePrice
     * @param $discount DiscountCodes
     * @param null $transactionID
     * @return string
     * @throws CException
     */
    private function saveBuyInfo($book , $user ,$method, $basePrice, $price, $discount, $transactionID = null)
    {
        $book->download += 1;
        $book->setScenario('update-download');
        $book->save();
        $buy = new BookBuys();
        $buy->book_id = $book->id;
        $buy->base_price = $basePrice;
        $buy->user_id = $user->id;
        $buy->package_id = $book->lastPackage->id;
        $buy->method = $method;
        $buy->price = $price;
        if($method == 'gateway')
            $buy->rel_id = $transactionID;
        if($book->publisher){
            $book->publisher->userDetails->earning = $book->publisher->userDetails->earning + $book->getPublisherPortion($basePrice, $buy);
            $book->publisher->userDetails->save();
        }
        if($discount && $discount->digital_allow)
        {
            $buy->discount_code_type = $discount->off_type;
            if($discount->off_type == DiscountCodes::DISCOUNT_TYPE_PERCENT)
                $buy->discount_code_amount = $discount->percent;
            else if($discount->off_type == DiscountCodes::DISCOUNT_TYPE_AMOUNT)
                $buy->discount_code_amount = $discount->amount;
        }
        $buy->site_amount = $book->getSitePortion($price, $buy);
        $buy->save();
        $message =
            '<p style="text-align: right;">با سلام<br>کاربر گرامی، جزئیات خرید شما به شرح ذیل می باشد:</p>
            <div style="width: 100%;height: 1px;background: #ccc;margin-bottom: 15px;"></div>
            <table style="font-size: 9pt;text-align: right;">
                <tr>
                    <td style="font-weight: bold;width: 120px;">عنوان کتاب</td>
                    <td>' . CHtml::encode($book->title) . '</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;width: 120px;">قیمت</td>
                    <td>' . Controller::parseNumbers(number_format($price ,0)) . ' تومان</td>
                </tr>';
        if($method == 'gateway' && $buy->transaction)
            $message.= '<tr>
                    <td style="font-weight: bold;width: 120px;">کد رهگیری</td>
                    <td style="font-weight: bold;letter-spacing:4px">' . CHtml::encode($buy->transaction->token) . ' </td>
                </tr>
                <tr>
                    <td style="font-weight: bold;width: 120px;">روش پرداخت</td>
                    <td style="font-weight: bold;">درگاه ' . CHtml::encode($buy->transaction->gateway_name) . ' </td>
                </tr>';
        elseif($method == 'credit')
            $message.= '<tr>
                    <td style="font-weight: bold;width: 120px;">روش پرداخت</td>
                    <td style="font-weight: bold;">کسر از اعتبار</td>
                </tr>';
        $message.= '<tr>
                    <td style="font-weight: bold;width: 120px;">تاریخ</td>
                    <td>' . JalaliDate::date('d F Y - H:i' ,$buy->date) . '</td>
                </tr>
            </table>';
        Mailer::mail($user->email ,'اطلاعات خرید کتاب' ,$message ,Yii::app()->params['noReplyEmail']);
        return $buy->id;
    }

    /**
     * Download book
     *
     * public function actionDownload($id, $title)
     * {
     * $model = $this->loadModel($id);
     * if ($model->price == 0) {
     * $model->download += 1;
     * $model->setScenario('update-download');
     * $model->save();
     * $this->download($model->lastPackage->file_name, Yii::getPathOfAlias("webroot") . '/uploads/books/files');
     * } else {
     * $buy = BookBuys::model()->findByAttributes(array('user_id' => Yii::app()->user->getId(), 'book_id' => $id));
     * if ($buy) {
     * $model->download += 1;
     * $model->setScenario('update-download');
     * $model->save();
     * $this->download($model->lastPackage->file_name, Yii::getPathOfAlias("webroot") . '/uploads/books/files');
     * } else
     * $this->redirect(array('/book/buy/' . CHtml::encode($model->id) . '/' . CHtml::encode($model->title)));
     * }
     * }
     *
     * protected function download($fileName, $filePath)
     * {
     * $fakeFileName = $fileName;
     * $realFileName = $fileName;
     *
     * $file = $filePath . DIRECTORY_SEPARATOR . $realFileName;
     * $fp = fopen($file, 'rb');
     *
     * $mimeType = '';
     * switch (pathinfo($fileName, PATHINFO_EXTENSION)) {
     * case 'apk':
     * $mimeType = 'application/vnd.android.package-archive';
     * break;
     *
     * case 'xap':
     * $mimeType = 'application/x-silverlight-app';
     * break;
     *
     * case 'ipa':
     * $mimeType = 'application/octet-stream';
     * break;
     * }
     *
     * header('Pragma: public');
     * header('Expires: 0');
     * header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
     * header('Content-Transfer-Encoding: binary');
     * header('Content-Type: ' . $mimeType);
     * header('Content-Disposition: attachment; filename=' . $fakeFileName);
     *
     * echo stream_get_contents($fp);
     * }*/

    /**
     * Show books list
     */
    public function actionPublisher($title ,$id = null)
    {
        Yii::app()->theme = 'frontend';
        $this->layout = 'index';
        $criteria = Books::model()->getValidBooks();
        if(isset($_GET['t']) and $_GET['t'] == 1){
            $criteria->addCondition('publisher_name=:publisher');
            $publisher_id = $title;
        }else{
            $criteria->addCondition('publisher_id=:publisher');
            $publisher_id = $id;
        }
        $criteria->params[':publisher'] = $publisher_id;
        $dataProvider = new CActiveDataProvider('Books' ,array(
            'criteria' => $criteria ,
            'pagination' => array('pageSize' => 8)
        ));

        if($id){
            $user = UserDetails::model()->findByAttributes(array('user_id' => $id));
            $pageTitle = 'کتاب های ' . ($user->getPublisherName());
        }else
            $pageTitle = $title;
        $this->render('books_list' ,array(
            'dataProvider' => $dataProvider ,
            'title' => $pageTitle ,
            'pageTitle' => 'کتاب ها'
        ));
    }

    /**
     * show person books list
     */
    public function actionPerson($id ,$title = null)
    {
        Yii::app()->theme = 'frontend';
        $this->layout = 'index';
        $person = BookPersons::model()->findByPk((int)$id);
        if(!$person)
            throw new CHttpException(404 ,'The requested page does not exist.');
        else
            $pageTitle = 'کتاب های ' . $person->name_family;
        $criteria = Books::model()->getValidBooks();
        $criteria->together = true;
        $criteria->with[] = 'personRel';
        $criteria->addCondition('personRel.person_id =:person_id');
        $criteria->params[':person_id'] = $id;
        $dataProvider = new CActiveDataProvider('Books' ,array(
            'criteria' => $criteria ,
            'pagination' => array('pageSize' => 8)
        ));

        $this->render('books_list' ,array(
            'dataProvider' => $dataProvider ,
            'title' => $pageTitle ,
            'pageTitle' => 'کتاب ها'
        ));
    }

    public function actionIndex()
    {
        Yii::app()->theme = 'frontend';
        $this->layout = '//layouts/index';
        $criteria = Books::model()->getValidBooks();
        $dataProvider = new CActiveDataProvider("Books" ,array(
            'criteria' => $criteria ,
            'pagination' => array('pageSize' => 8)
        ));
        $this->render('books_list' ,array(
            'dataProvider' => $dataProvider
        ));
    }

    /**
     * Bookmark book
     */
    public function actionBookmark()
    {
        Yii::app()->getModule('users');
        $model = UserBookBookmark::model()->find('user_id=:user_id AND book_id=:book_id' ,array(':user_id' => Yii::app()->user->getId() ,':book_id' => $_POST['bookId']));
        if(!$model){
            $model = new UserBookBookmark();
            $model->book_id = $_POST['bookId'];
            $model->user_id = Yii::app()->user->getId();
            if($model->save())
                echo CJSON::encode(array(
                    'status' => true
                ));
            else
                echo CJSON::encode(array(
                    'status' => false
                ));
        }else{
            if(UserBookBookmark::model()->deleteAllByAttributes(array('user_id' => Yii::app()->user->getId() ,'book_id' => $_POST['bookId'])))
                echo CJSON::encode(array(
                    'status' => true
                ));
            else
                echo CJSON::encode(array(
                    'status' => false
                ));
        }
    }

    /**
     * Report sales
     */
    public function actionReportSales()
    {
        Yii::app()->theme = 'abound';
        $this->layout = '//layouts/main';

        $labels = $values = array();
        $showChart = false;
        $activeTab = 'monthly';
        $sumSales = 0;
        if(isset($_POST['show-chart-monthly'])){
            $activeTab = 'monthly';
            $startDate = JalaliDate::toGregorian(JalaliDate::date('Y' ,$_POST['month_altField'] ,false) ,JalaliDate::date('m' ,$_POST['month_altField'] ,false) ,1);
            $startTime = strtotime($startDate[0] . '/' . $startDate[1] . '/' . $startDate[2]);
            $endTime = '';
            if(JalaliDate::date('m' ,$_POST['month_altField'] ,false) <= 6)
                $endTime = $startTime + (60 * 60 * 24 * 31);
            else
                $endTime = $startTime + (60 * 60 * 24 * 30);
            $showChart = true;
            $criteria = new CDbCriteria();
            $criteria->addCondition('date >= :start_date');
            $criteria->addCondition('date <= :end_date');
            $criteria->params = array(
                ':start_date' => $startTime ,
                ':end_date' => $endTime ,
            );
            $report = BookBuys::model()->findAll($criteria);
            // show daily report
            $daysCount = (JalaliDate::date('m' ,$_POST['month_altField'] ,false) <= 6) ? 31 : 30;
            for($i = 0;$i < $daysCount;$i++){
                $labels[] = JalaliDate::date('d F Y' ,$startTime + (60 * 60 * (24 * $i)));
                $count = 0;
                foreach($report as $model){
                    if($model->date >= $startTime + (60 * 60 * (24 * $i)) and $model->date < $startTime + (60 * 60 * (24 * ($i + 1)))){
                        $count++;
                        $sumSales += $model->price;
                    }
                }
                $values[] = $count;
            }
        }elseif(isset($_POST['show-chart-yearly'])){
            $activeTab = 'yearly';
            $startDate = JalaliDate::toGregorian(JalaliDate::date('Y' ,$_POST['year_altField'] ,false) ,1 ,1);
            $startTime = strtotime($startDate[0] . '/' . $startDate[1] . '/' . $startDate[2]);
            $endTime = $startTime + (60 * 60 * 24 * 365);
            $showChart = true;
            $criteria = new CDbCriteria();
            $criteria->addCondition('date >= :start_date');
            $criteria->addCondition('date <= :end_date');
            $criteria->params = array(
                ':start_date' => $startTime ,
                ':end_date' => $endTime ,
            );
            $report = BookBuys::model()->findAll($criteria);
            // show monthly report
            $tempDate = $startTime;
            for($i = 0;$i < 12;$i++){
                if($i < 6)
                    $monthDaysCount = 31;
                else
                    $monthDaysCount = 30;
                $labels[] = JalaliDate::date('F' ,$tempDate);
                $tempDate = $tempDate + (60 * 60 * 24 * ($monthDaysCount));
                $count = 0;
                foreach($report as $model){
                    if($model->date >= $startTime + (60 * 60 * 24 * ($monthDaysCount * $i)) and $model->date < $startTime + (60 * 60 * 24 * ($monthDaysCount * ($i + 1)))){
                        $count++;
                        $sumSales += $model->price;
                    }
                }
                $values[] = $count;
            }
        }elseif(isset($_POST['show-chart-by-book'])){
            $activeTab = 'by-book';
            $showChart = true;
            $criteria = new CDbCriteria();
            $criteria->addCondition('date > :from_date');
            $criteria->addCondition('date < :to_date');
            $criteria->addCondition('book_id=:book_id');
            $criteria->params = array(
                ':from_date' => $_POST['from_date_altField'] ,
                ':to_date' => $_POST['to_date_altField'] ,
                ':book_id' => $_POST['book_id'] ,
            );
            $report = BookBuys::model()->findAll($criteria);
            if($_POST['to_date_altField'] - $_POST['from_date_altField'] < (60 * 60 * 24 * 30)){
                // show daily report
                $datesDiff = $_POST['to_date_altField'] - $_POST['from_date_altField'];
                $daysCount = ($datesDiff / (60 * 60 * 24));
                for($i = 0;$i < $daysCount;$i++){
                    $labels[] = JalaliDate::date('d F Y' ,$_POST['from_date_altField'] + (60 * 60 * (24 * $i)));
                    $count = 0;
                    foreach($report as $model){
                        if($model->date >= $_POST['from_date_altField'] + (60 * 60 * (24 * $i)) and $model->date < $_POST['from_date_altField'] + (60 * 60 * (24 * ($i + 1)))){
                            $count++;
                            $sumSales += $model->price;
                        }
                    }
                    $values[] = $count;
                }
            }else{
                // show monthly report
                $datesDiff = $_POST['to_date_altField'] - $_POST['from_date_altField'];
                $monthCount = ceil($datesDiff / (60 * 60 * 24 * 30));
                for($i = 0;$i < $monthCount;$i++){
                    $labels[] = JalaliDate::date('d F' ,$_POST['from_date_altField'] + (60 * 60 * 24 * (30 * $i))) . ' الی ' . JalaliDate::date('d F' ,$_POST['from_date_altField'] + (60 * 60 * 24 * (30 * ($i + 1))));
                    $count = 0;
                    foreach($report as $model){
                        if($model->date >= $_POST['from_date_altField'] + (60 * 60 * 24 * (30 * $i)) and $model->date < $_POST['from_date_altField'] + (60 * 60 * 24 * (30 * ($i + 1)))){
                            $count++;
                            $sumSales += $model->price;
                        }
                    }
                    $values[] = $count;
                }
            }
        }elseif(isset($_POST['show-chart-by-publisher'])){
            $activeTab = 'by-publisher';
            $showChart = true;
            $criteria = new CDbCriteria();
            $criteria->addCondition('date > :from_date');
            $criteria->addCondition('date < :to_date');
            $criteria->addInCondition('book_id' ,CHtml::listData(Books::model()->findAllByAttributes(array('publisher_id' => $_POST['publisher'])) ,'id' ,'id'));
            $criteria->params[':from_date'] = $_POST['from_date_publisher_altField'];
            $criteria->params[':to_date'] = $_POST['to_date_publisher_altField'];
            $report = BookBuys::model()->findAll($criteria);
            if($_POST['to_date_publisher_altField'] - $_POST['from_date_publisher_altField'] < (60 * 60 * 24 * 30)){
                // show daily report
                $datesDiff = $_POST['to_date_publisher_altField'] - $_POST['from_date_publisher_altField'];
                $daysCount = ($datesDiff / (60 * 60 * 24));
                for($i = 0;$i < $daysCount;$i++){
                    $labels[] = JalaliDate::date('d F Y' ,$_POST['from_date_publisher_altField'] + (60 * 60 * (24 * $i)));
                    $count = 0;
                    foreach($report as $model){
                        if($model->date >= $_POST['from_date_publisher_altField'] + (60 * 60 * (24 * $i)) and $model->date < $_POST['from_date_publisher_altField'] + (60 * 60 * (24 * ($i + 1)))){
                            $count++;
                            $sumSales += $model->price;
                        }
                    }
                    $values[] = $count;
                }
            }else{
                // show monthly report
                $datesDiff = $_POST['to_date_publisher_altField'] - $_POST['from_date_publisher_altField'];
                $monthCount = ceil($datesDiff / (60 * 60 * 24 * 30));
                for($i = 0;$i < $monthCount;$i++){
                    $labels[] = JalaliDate::date('d F' ,$_POST['from_date_publisher_altField'] + (60 * 60 * 24 * (30 * $i))) . ' الی ' . JalaliDate::date('d F' ,$_POST['from_date_publisher_altField'] + (60 * 60 * 24 * (30 * ($i + 1))));
                    $count = 0;
                    foreach($report as $model){
                        if($model->date >= $_POST['from_date_publisher_altField'] + (60 * 60 * 24 * (30 * $i)) and $model->date < $_POST['from_date_publisher_altField'] + (60 * 60 * 24 * (30 * ($i + 1)))){
                            $count++;
                            $sumSales += $model->price;
                        }
                    }
                    $values[] = $count;
                }
            }
        }

        $this->render('report_sales' ,array(
            'labels' => $labels ,
            'values' => $values ,
            'showChart' => $showChart ,
            'activeTab' => $activeTab ,
            'sumSales' => $sumSales ,
        ));
    }
    public function actionReportBookSales()
    {
        Yii::app()->theme = 'abound';
        $this->layout = '//layouts/column1';

        $model = new BookBuys('search');
        $model->unsetAttributes();
        if(isset($_GET['BookBuys']))
            $model->attributes = $_GET['BookBuys'];

        $this->render('report_book_sales', array(
            'model' => $model,
        ));
    }

    /**
     * Report income
     */
    public function actionReportIncome()
    {
        Yii::app()->theme = 'abound';
        $this->layout = '//layouts/main';

        $labels = $values = array();
        $sumIncome = $sumCredit = 0;
        $showChart = false;
        $sumCredit = UserDetails::model()->find(array('select' => 'SUM(credit) AS credit'));
        $sumCredit = $sumCredit->credit;
        if(isset($_POST['show-chart-monthly'])){
            $startDate = JalaliDate::toGregorian(JalaliDate::date('Y' ,$_POST['month_altField'] ,false) ,JalaliDate::date('m' ,$_POST['month_altField'] ,false) ,1);
            $startTime = strtotime($startDate[0] . '/' . $startDate[1] . '/' . $startDate[2]);
            $endTime = '';
            if(JalaliDate::date('m' ,$_POST['month_altField'] ,false) <= 6)
                $endTime = $startTime + (60 * 60 * 24 * 31);
            else
                $endTime = $startTime + (60 * 60 * 24 * 30);
            $showChart = true;
            $criteria = new CDbCriteria();
            $criteria->addCondition('date >= :start_date');
            $criteria->addCondition('date <= :end_date');
            $criteria->params = array(
                ':start_date' => $startTime ,
                ':end_date' => $endTime ,
            );
            $report = BookBuys::model()->findAll($criteria);
            Yii::app()->getModule('setting');
            $commission = SiteSetting::model()->findByAttributes(array('name' => 'commission'));
            $commission = $commission->value;
            // show daily report
            $daysCount = (JalaliDate::date('m' ,$_POST['month_altField'] ,false) <= 6) ? 31 : 30;
            for($i = 0;$i < $daysCount;$i++){
                $labels[] = JalaliDate::date('d F Y' ,$startTime + (60 * 60 * (24 * $i)));
                $amount = 0;
                foreach($report as $model){
                    if($model->date >= $startTime + (60 * 60 * (24 * $i)) and $model->date < $startTime + (60 * 60 * (24 * ($i + 1))))
                        $amount = $model->book->price;
                }
                $values[] = ($amount * $commission) / 100;
                $sumIncome += ($amount * $commission) / 100;
            }
        }

        $this->render('report_income' ,array(
            'labels' => $labels ,
            'values' => $values ,
            'showChart' => $showChart ,
            'sumIncome' => $sumIncome ,
            'sumCredit' => $sumCredit ,
        ));
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionSearch()
    {
        Yii::app()->theme = 'frontend';
        $this->layout = '//layouts/index';
        // book criteria
        $bookCriteria =  new CDbCriteria();
        $publisherCriteria =  new CDbCriteria();
        $personsCriteria =  new CDbCriteria();
        $categoryCriteria =  new CDbCriteria();

        $bookCriteria->addCondition('t.status=:status AND t.confirm=:confirm AND t.deleted=:deleted AND (SELECT COUNT(book_packages.id) FROM ym_book_packages book_packages WHERE book_packages.book_id=t.id) != 0');
        $bookCriteria->params[':status'] = 'enable';
        $bookCriteria->params[':confirm'] = 'accepted';
        $bookCriteria->params[':deleted'] = 0;
        $bookCriteria->order = 't.confirm_date DESC';

        $publisherCriteria->addCondition('role_id = :role');
        $publisherCriteria->params[':role'] = 2;
        $publisherCriteria->order = 'userDetails.fa_name DESC';
        $publisherCriteria->with = array('userDetails');

        $categoryCriteria->addCondition('t.status=:status AND t.confirm=:confirm AND t.deleted=:deleted AND (SELECT COUNT(book_packages.id) FROM ym_book_packages book_packages WHERE book_packages.book_id=t.id) != 0');
        $categoryCriteria->params[':status'] = 'enable';
        $categoryCriteria->params[':confirm'] = 'accepted';
        $categoryCriteria->params[':deleted'] = 0;
        $categoryCriteria->order = 't.confirm_date DESC';

        if(isset($_GET['term']) && !empty($term = $_GET['term'])){
            $terms = explode(' ' ,urldecode($term));
            $bookSql = '(t.title regexp :term OR t.description regexp :term)';
            $publisherSql = '(userDetails.fa_name regexp :term OR userDetails.nickname regexp :term)';
            $personsSql = '(t.name_family regexp :term)';
            $categorySql = '(category.title regexp :term)';
            $bookCriteria->params[":term"] = $term;
            $publisherCriteria->params[":term"] = $term;
            $personsCriteria->params[":term"] = $term;
            $categoryCriteria->params[":term"] = $term;
            foreach($terms as $key => $term)
                if($term){
                    if($bookSql)
                        $bookSql .= " OR (";
                    $bookSql .= "t.title regexp :term$key OR t.description regexp :term$key)";

                    if($publisherSql)
                        $publisherSql .= " OR (";
                    $publisherSql .= "userDetails.fa_name regexp :term$key OR userDetails.nickname regexp :term$key)";

                    if($personsSql)
                        $personsSql .= " OR (";
                    $personsSql .= "t.name_family regexp :term$key)";

                    if($categorySql)
                        $categorySql .= " OR (";
                    $categorySql .= "category.title regexp :term$key)";
                    // with correction
                    $bookCriteria->params[":term$key"] = $term;
                    $publisherCriteria->params[":term$key"] = $term;
                    $personsCriteria->params[":term$key"] = $term;
                    $categoryCriteria->params[":term$key"] = $term;
                }
            $bookCriteria->together = true;
            $categoryCriteria->together = true;

            $bookCriteria->addCondition($bookSql);

            $publisherCriteria->addCondition($publisherSql);

            $personsCriteria->addCondition($personsSql);

            $categoryCriteria->addCondition($categorySql);
            $categoryCriteria->with = array('category');
        }
        $pagination = new CPagination();
        $pagination->pageSize = 4;
        if(Yii::app()->request->isAjaxRequest){
            $bookCriteria->limit = 4;
            $publisherCriteria->limit = 4;
            $personsCriteria->limit = 4;
            $categoryCriteria->limit = 4;
            $pagination->pageSize = 4;
        }
        $bookDataProvider = new CActiveDataProvider('Books' ,array(
            'criteria' => $bookCriteria ,
            'pagination' => $pagination
        ));
        $publisherDataProvider = new CActiveDataProvider('Users' ,array(
            'criteria' => $publisherCriteria,
            'pagination' => $pagination
        ));
        $personsDataProvider = new CActiveDataProvider('BookPersons' ,array(
            'criteria' => $personsCriteria,
            'pagination' => $pagination
        ));
        $categoryDataProvider = new CActiveDataProvider('Books' ,array(
            'criteria' => $categoryCriteria,
            'pagination' => $pagination
        ));
        if(Yii::app()->request->isAjaxRequest && !isset($_GET['ajax'])){
            $response['html'] = '';
            if($bookDataProvider->totalItemCount){
                $this->beginClip('book-list');
                echo '<div class="result-group">کتاب ها</div>';
                $this->widget('zii.widgets.CListView', array(
                    'id' => 'search-book-list',
                    'dataProvider' => $bookDataProvider,
                    'itemView' => '//site/_search_book_item',
                    'template' => '{items}',
                ));
                $this->endClip();
                $response['html'] .= $this->clips['book-list'];
            }
            if($publisherDataProvider->totalItemCount){
                $this->beginClip('publisher-list');
                echo '<div class="result-group">انتشارات</div>';
                $this->widget('zii.widgets.CListView', array(
                    'id' => 'search-book-list',
                    'dataProvider' => $publisherDataProvider,
                    'itemView' => '//site/_search_book_item',
                    'viewData' => array('type' => 'publisher'),
                    'template' => '{items}',
                ));
                $this->endClip();
                $response['html'] .= $this->clips['publisher-list'];
            }
            if($personsDataProvider->totalItemCount){
                $this->beginClip('persons-list');
                echo '<div class="result-group">اشخاص و نویسندگان</div>';
                $this->widget('zii.widgets.CListView', array(
                    'id' => 'search-book-list',
                    'dataProvider' => $personsDataProvider,
                    'itemView' => '//site/_search_book_item',
                    'viewData' => array('type' => 'person'),
                    'template' => '{items}',
                ));
                $this->endClip();
                $response['html'] .= $this->clips['persons-list'];
            }
            if($categoryDataProvider->totalItemCount){
                $this->beginClip('category-list');
                echo '<div class="result-group">دسته بندی ها</div>';
                $this->widget('zii.widgets.CListView', array(
                    'id' => 'search-book-list',
                    'dataProvider' => $categoryDataProvider,
                    'itemView' => '//site/_search_book_item',
                    'template' => '{items}',
                ));
                $this->endClip();
                $response['html'] .= $this->clips['category-list'];
            }

            if($response['html'] == '')
                $response['html'] = 'نتیجه ای یافت نشد.';
            $response['status'] = true;
            echo CJSON::encode($response);
            Yii::app()->end();
        }else
            $this->render('search' ,array(
                'bookDataProvider' => $bookDataProvider,
                'publisherDataProvider' => $publisherDataProvider,
                'personsDataProvider' => $personsDataProvider,
                'categoryDataProvider' => $categoryDataProvider,
            ));
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionTag($id)
    {
        Yii::app()->theme = 'frontend';
        $this->layout = '//layouts/index';
        $criteria = Books::model()->getValidBooks();
        $criteria->compare('tagsRel.tag_id' ,$id);
        $criteria->with[] = 'tagsRel';
        $criteria->together = true;
        $dataProvider = new CActiveDataProvider('Books' ,array('criteria' => $criteria ,'pagination' => array('pageSize' => 8)));
        $this->render('tag' ,array(
            'model' => Tags::model()->findByPk($id) ,
            'dataProvider' => $dataProvider
        ));
    }

    /**
     * Show books list of category
     */
    public function actionDiscount()
    {
        Yii::app()->theme = 'market';
        $this->layout = 'public';
        $criteria = new CDbCriteria();
        $criteria->with[] = 'book';
        $criteria->addCondition('book.confirm=:confirm');
        $criteria->addCondition('book.deleted=:deleted');
        $criteria->addCondition('book.status=:status');
        $criteria->addCondition('(SELECT COUNT(book_images.id) FROM ym_book_images book_images WHERE book_images.book_id=book.id) != 0');
        $criteria->addCondition('(SELECT COUNT(book_packages.id) FROM ym_book_packages book_packages WHERE book_packages.book_id=book.id) != 0');
        $criteria->addCondition('start_date < :now AND end_date > :now');
        $criteria->addCondition('(SELECT COUNT(book_packages.id) FROM ym_book_packages book_packages WHERE book_packages.book_id=book.id) != 0');
        $criteria->params = array(
            ':confirm' => 'accepted' ,
            ':deleted' => 0 ,
            ':status' => 'enable' ,
            ':now' => time()
        );
        $criteria->order = 'book.id DESC';
        $dataProvider = new CActiveDataProvider('BookDiscounts' ,array(
            'criteria' => $criteria ,
        ));

        $this->render('books_discounts_list' ,array(
            'dataProvider' => $dataProvider ,
            'pageTitle' => 'تخفیفات'
        ));
    }

    /**
     * @param $book_id
     * @param $rate
     * @throws CException
     * @throws CHttpException
     */
    public function actionRate($book_id ,$rate)
    {
        $model = $this->loadModel($book_id);
        if($model){
            $rateModel = new BookRatings();
            $rateModel->rate = (int)$rate;
            $rateModel->book_id = $model->id;
            $rateModel->user_id = Yii::app()->user->getId();
            if($rateModel->save()){
                $this->beginClip('rate-view');
                $this->renderPartial('_rating' ,array(
                    'model' => $model
                ));
                $this->endClip();
                if(isset($_GET['ajax'])){
                    echo CJSON::encode(array('status' => true ,'rate' => $rateModel->rate ,'rate_wrapper' => $this->clips['rate-view']));
                    Yii::app()->end();
                }
            }else{
                if(isset($_GET['ajax'])){
                    echo CJSON::encode(array('status' => false ,'msg' => 'متاسفانه عملیات با خطا مواجه است! لطفا مجددا سعی فرمایید.'));
                    Yii::app()->end();
                }
            }
        }else{
            if(isset($_GET['ajax'])){
                echo CJSON::encode(array('status' => false ,'msg' => 'مقادیر ارسالی صحیح نیست.'));
                Yii::app()->end();
            }
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Books the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Books::model()->findByPk($id);
        if($model === null)
            throw new CHttpException(404 ,'The requested page does not exist.');
        return $model;
    }

    /**
     * Update Version of bought book
     */
    public function actionUpdateVersion($id)
    {
        $userID = Yii::app()->user->getId();
        $book = $this->loadModel($id);
        if(!Yii::app()->user->isGuest && Yii::app()->user->type == 'user'){
            if(!$book->publisher_id || $book->publisher_id != $userID){
                $bought = BookBuys::model()->findByAttributes(array(
                    'book_id' => $book->id ,
                    'user_id' => $userID ,
                ) ,array('order' => 'date DESC'));
                if($bought && $bought->package_id != $book->lastPackage->id){
                    $bought->package_id = $book->lastPackage->id;
                    $bought->date = time();
                    if($bought->save())
                        Yii::app()->user->setFlash('success' ,'<i class="icon-check" ></i>  کتاب شما با موفقیت به روز رسانی شد.');
                    else
                        Yii::app()->user->setFlash('failed' ,'متاسفانه مشکلی در به روز رسانی بوجود آمده است! لطفا مجددا اقدام فرمایید.');
                }else
                    Yii::app()->user->setFlash('success' ,'<i class="icon-check" ></i>  نسخه کتاب شما به روز است.');
            }elseif($book->publisher_id && $book->publisher_id == $userID)
                Yii::app()->user->setFlash('warning' ,'متاسفانه شما ناشر این کتاب هستید و امکان به روز رسانی برای شما وجود ندارد.');

        }
        $this->redirect(array('/book/' . $id . '/' . urlencode($book->title)));
    }
}