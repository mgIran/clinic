<?php

class UsersCreditController extends Controller
{
    public $layout = '//layouts/panel';

    /**
     * @return array actions type list
     */
    public static function actionsType()
    {
        return array(
            'frontend' => array('buy', 'bill', 'captcha', 'verify'),
            'backend' => array('reportCreditBuys', 'reportBonBuys')
        );
    }

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'checkAccess - captcha', // perform access control for CRUD operations
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
            )
        );
    }
    /**
     * Buy Credit
     */
    public function actionBuy()
    {
        Yii::app()->theme = 'frontend';
        $this->layout = '//layouts/panel';
        $model = Users::model()->findByPk(Yii::app()->user->getId());
        Yii::import('application.modules.setting.models.*');
        $buyCreditOptions = SiteSetting::model()->findByAttributes(array('name' => 'buy_credit_options'));
        $amounts = array();
        foreach (CJSON::decode($buyCreditOptions->value) as $amount)
            $amounts[$amount] = Controller::parseNumbers(number_format($amount, 0)) . ' تومان';
        
        // بن خرید
        $voucherForm = new VoucherForm();
        if(Yii::app()->user->getState('attempts-voucher') > 2)
            $voucherForm->scenario = 'withCaptcha';
        $voucherForm->user_id = Yii::app()->user->getId();
        if(isset($_POST['VoucherForm']))
        {
            $voucherForm->attributes =$_POST['VoucherForm'];
            if($voucherForm->validate())
            {
                $bon = $voucherForm->getBon();
                $bonRelModel = new UserBonRel();
                $bonRelModel->user_id= $model->id;
                $bonRelModel->bon_id = $bon->id;
                $bonRelModel->date = time();
                $bonRelModel->amount = $bon->amount;
                if($bonRelModel->save()){
                    $creditModel = UserDetails::model()->findByPk($model->id);
                    $creditModel->credit += $bon->amount;
                    $creditModel->save();
                    Yii::app()->user->setState('attempts-voucher', 0);
                    Yii::app()->user->setFlash('voucher-success', CHtml::encode($bon->title).' با موفقیت اعمال گردید و مبلغ '.
                        Controller::parseNumbers(number_format($bon->amount))
                        .' تومان به اعتبار شما اضافه شد.'
                    );
                    $this->refresh();
                }
            }else
            {
                Yii::app()->user->setState('attempts-voucher', Yii::app()->user->getState('attempts-voucher', 0) + 1);
                if (Yii::app()->user->getState('attempts-voucher') > 2) {
                    $voucherForm->scenario = 'withCaptcha';
                }
            }
        }
        $this->render('buy', array(
            'model' => $model,
            'voucherForm' => $voucherForm,
            'amounts' => $amounts,
        ));
    }

    /**
     * Show bill
     */
    public function actionBill()
    {
        if (isset($_POST['pay'])) {
            // Save payment
            $model = new UserTransactions();
            $model->user_id = Yii::app()->user->getId();
            $model->amount = $_POST['amount'];
            $model->date = time();
            $model->gateway_name='زرین پال';
            $model->type=UserTransactions::TRANSACTION_TYPE_CREDIT;
            if ($model->save()) {
                $gateway = new ZarinPal();
                $gateway->callback_url = Yii::app()->getBaseUrl(true) . '/users/credit/verify';
                $siteName = Yii::app()->name;
                $description = "افزایش اعتبار در {$siteName} از طریق درگاه {$gateway->getGatewayName()}";
                $result = $gateway->request(doubleval($model->amount), $description, Yii::app()->user->email, $this->userDetails && $this->userDetails->phone?$this->userDetails->phone:'0');
                $model->scenario = 'set-authority';
                $model->authority = $result->getAuthority();
                $model->save();
                //Redirect to URL You can do it also by creating a form
                if($result->getStatus() == 100)
                    $this->redirect($gateway->getRedirectUrl());
                else
                    throw new CHttpException(404, 'خطای بانکی: ' . $result->getError());
            }
        } elseif (isset($_POST['amount'])) {
            Yii::app()->theme = 'frontend';
            $amount = CHtml::encode($_POST['amount']);
            $model = Users::model()->findByPk(Yii::app()->user->getId());
            $this->render('bill', array(
                'model' => $model,
                'amount' => CHtml::encode($amount),
            ));
        } else
            $this->redirect($this->createUrl('/users/credit/buy'));
    }

    public function actionVerify()
    {
        Yii::app()->theme = 'frontend';
        $this->layout = '//layouts/panel';
        if(!isset($_GET['Authority']))
            $this->redirect(array('/users/credit/buy'));
        $Authority = $_GET['Authority'];
        $model = UserTransactions::model()->findByAttributes(array(
            'authority' => $Authority,
            'user_id' => Yii::app()->user->getId(),
            'status' => 'unpaid',
            'type' => UserTransactions::TRANSACTION_TYPE_CREDIT
        ));
        $userDetails = UserDetails::model()->findByAttributes(array('user_id' => Yii::app()->user->getId()));
        $Amount = $model->amount;

        if ($_GET['Status'] == 'OK') {
            $gateway = new ZarinPal();
            $gateway->verify($Authority, $Amount);
            if ($gateway->getStatus() == 100) {
                $model->status = 'paid';
                $model->token = $gateway->getRefId();
                $model->save();
                $userDetails->setScenario('update-credit');
                $userDetails->credit = doubleval($userDetails->credit) + doubleval($model->amount);
                // calculate festival gifts
                Yii::app()->getModule('festivals');
                $result = Festivals::CheckFestivals(Yii::app()->user->getId(), Festivals::FESTIVAL_TYPE_CREDIT, $model->amount);
                $gift = (float)$result['gift'];
                if($gift)
                    $userDetails->credit += $gift;
                if($userDetails->save())
                {
                    foreach($result['ids'] as $id)
                        Festivals::ApplyUsed($id, Yii::app()->user->getId(), $model->id);
                }
                Yii::app()->user->setFlash('success', 'پرداخت شما با موفقیت انجام شد.');
            } else {
                Yii::app()->user->setFlash('failed', $gateway->getError());
                $this->redirect(array('/users/credit/buy'));
            }
        } else
            Yii::app()->user->setFlash('failed', 'عملیات پرداخت ناموفق بوده یا توسط کاربر لغو شده است.');
        $this->render('verify', array(
            'model' => $model,
            'userDetails' => $userDetails,
            'gift' => isset($gift)?$gift:false,
        ));
    }


    public function actionReportCreditBuys()
    {
        Yii::app()->theme = 'abound';
        $this->layout = '//layouts/column1';

        $model = new UserTransactions('search');
        $model->unsetAttributes();
        if(isset($_GET['UserTransactions']))
            $model->attributes = $_GET['UserTransactions'];
        $model->type = UserTransactions::TRANSACTION_TYPE_CREDIT;
        $model->status = UserTransactions::TRANSACTION_STATUS_PAID;
        
        $this->render('report_credit_buys', array(
            'model' => $model,
        ));
    }

    public function actionReportBonBuys()
    {
        Yii::app()->theme = 'abound';
        $this->layout = '//layouts/column1';

        $model = new UserBonRel('search');
        $model->unsetAttributes();
        if(isset($_GET['UserBonRel']))
            $model->attributes = $_GET['UserBonRel'];
        $this->render('report_bon_buys', array(
            'model' => $model,
        ));
    }
}