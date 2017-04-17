<?php

class ShopOrderController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/public';

    /**
     * @return array actions type list
     */
    public static function actionsType()
    {
        return array(
            'frontend' => array('create', 'addDiscount', 'removeDiscount', 'back', 'confirm', 'payment', 'verify', 'details', 'history', 'getInfo', 'changePayment'),
            'backend' => array('admin', 'index', 'view', 'delete', 'update', 'changeStatus', 'exportCode', 'report')
        );
    }

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'checkAccess + admin, index, view, delete, update, changeStatus, history, getInfo, exportCode, report',
            'postOnly + delete',
            'ajaxOnly + changeStatus',
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->layout = '//layouts/column1';
        Yii::app()->getModule('places');
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creation of a new Order
     * Before we create a new order, we need to gather Customer information.
     * If the user is logged in, we check if we already have customer information.
     * If so, we go directly to the Order confirmation page with the data passed
     * over. Otherwise we need the user to enter his data, and depending on
     * whether he is logged in into the system it is saved with his user
     * account or once just for this order.
     *
     * @param null $customer
     * @param null $payment_method
     * @param null $shipping_method
     * @param null $delivery_address
     */
    public function actionCreate($customer = null, $payment_method = null, $shipping_method = null, $delivery_address = null)
    {
        Yii::app()->theme = "frontend";
        $this->layout = "//layouts/index";
        Yii::app()->getModule('users');
        Yii::app()->getModule('discountCodes');

        if(!Yii::app()->user->hasState('basket-position'))
            Yii::app()->user->setState('basket-position', 1);
        $cart = Shop::getCartContent();
        if(!$cart)
            $this->redirect(array('/shop/cart/view'));
        if(!Yii::app()->user->isGuest && Yii::app()->user->type == 'user'){
            $customer = Yii::app()->user->getId();
            Yii::app()->user->setState('customer_id', $customer);
            if(Yii::app()->user->getState('basket-position') < 2)
                Yii::app()->user->setState('basket-position', 2);
        }
        if(isset($_POST['DeliveryAddress']))
            Yii::app()->user->setState('delivery_address', $_POST['DeliveryAddress']);
        if(isset($_POST['ShippingMethod'])){
            Yii::app()->user->setState('shipping_method', $_POST['ShippingMethod']);
            if(Yii::app()->user->hasState('delivery_address'))
                Yii::app()->user->setState('basket-position', 3);
        }
        if(isset($_POST['PaymentMethod'])){
            Yii::app()->user->setState('payment_method', $_POST['PaymentMethod']);
            Yii::app()->user->setState('basket-position', 4);
        }
        if(!$shipping_method)
            $shipping_method = (int)Yii::app()->user->getState('shipping_method');
        if(!$delivery_address)
            $delivery_address = (int)Yii::app()->user->getState('delivery_address');
        if(!$payment_method)
            $payment_method = (int)Yii::app()->user->getState('payment_method');
        if(Yii::app()->user->getState('basket-position') == 1){
            $this->render('login');
            Yii::app()->end();
        }
        if(Yii::app()->user->getState('basket-position') == 2){
            Yii::app()->getModule('places');
            if(isset($_POST['form']) && $_POST['form'] == 'shipping-form'){
                if(!$shipping_method && !$delivery_address)
                    Yii::app()->user->setFlash('warning', 'لطفا آدرس تحویل و شیوه ارسال را انتخاب کنید.');
                elseif(!$shipping_method)
                    Yii::app()->user->setFlash('warning', 'لطفا شیوه ارسال را انتخاب کنید.');
                elseif(!$delivery_address)
                    Yii::app()->user->setFlash('warning', 'لطفا آدرس تحویل را انتخاب کنید.');
            }
            $this->render('/shipping/choose', array(
                'user' => Shop::getCustomer(),
                'shippingMethods' => ShopShippingMethod::model()->findAll(array(
                    'condition' => 'status <> :deactive',
                    'order' => 't.order',
                    'params' => array(':deactive' => ShopShippingMethod::STATUS_DEACTIVE)
                )),
            ));
            Yii::app()->end();
        }
        if(Yii::app()->user->getState('basket-position') == 3){
            if(!$shipping_method){
                Yii::app()->user->setState('basket-position', 2);
                $this->refresh();
            }
            if(isset($_POST['form']) && $_POST['form'] == 'payment-form'){
                if(!$payment_method)
                    Yii::app()->user->setFlash('warning', 'لطفا شیوه پرداخت را انتخاب کنید.');
            }
            $shipping_object = ShopShippingMethod::model()->findByPk($shipping_method);
            $this->render('/payment/choose', array(
                'user' => Shop::getCustomer(),
                'paymentMethods' => $shipping_object->getPaymentMethodObjects()
            ));
            Yii::app()->end();
        }

        if(Yii::app()->user->getState('basket-position') == 4){
            if(is_numeric($customer))
                $customer = Users::model()->findByPk($customer);
            if(is_numeric($shipping_method))
                $shipping_method = ShopShippingMethod::model()->findByPk($shipping_method);
            if(is_numeric($delivery_address))
                $delivery_address = ShopAddresses::model()->findByPk($delivery_address);
            if(is_numeric($payment_method))
                $payment_method = ShopPaymentMethod::model()->findByPk($payment_method);

            if(!$customer){
                Yii::app()->user->setState('basket-position', 1);
                $this->refresh();
            }

            if(!$shipping_method){
                Yii::app()->user->setState('basket-position', 2);
                $this->refresh();
            }

            if(!$payment_method){
                Yii::app()->user->setState('basket-position', 3);
                $this->refresh();
            }

            $this->render('/order/create', array(
                'user' => $customer,
                'shippingMethod' => $shipping_method,
                'deliveryAddress' => $delivery_address,
                'paymentMethod' => $payment_method
            ));
        }
    }

    /**
     * Back to last position in shop
     */
    public function actionBack()
    {
        $position = Yii::app()->user->getState('basket-position');
        if($position > 2)
            $position--;
        elseif($position == 2)
            $this->redirect(array('/shop/cart/view'));
        Yii::app()->user->setState('basket-position', $position);
        $this->redirect(array('/shop/order/create'));
    }

    /**
     * Save Order in database and clear all cookie and session states that in set in shop
     * @param null $customer
     * @throws CDbException
     */
    public function actionConfirm($customer = null)
    {
        Yii::app()->theme = "frontend";
        $this->layout = "//layouts/index";
        Yii::app()->getModule('users');
        Yii::app()->getModule('discountCodes');
        // check cart content and statistics
        $cartStatistics = Shop::getPriceTotal();
        $cart = Shop::getCartContent();
        if(!$cart || Shop::isEmpty($cartStatistics))
            $this->redirect(array('/shop/cart/view'));
        // check order fields that is correct to be send
        if(!Yii::app()->user->isGuest && Yii::app()->user->type == 'user')
            $customer = Yii::app()->user->getId();
        elseif(Yii::app()->user->hasState('customer_id'))
            $customer = Yii::app()->user->getState('customer_id');

        $shipping_method = Yii::app()->user->getState('shipping_method');
        $delivery_address = Yii::app()->user->getState('delivery_address');
        $payment_method = Yii::app()->user->getState('payment_method');
        if(!$customer || !$shipping_method || !$delivery_address || !$payment_method)
            $this->redirect(array('/shop/order/create'));
        // order save in db
        $order = new ShopOrder();
        $order->user_id = $customer;
        $order->shipping_method = $shipping_method;
        $order->payment_method = $payment_method;
        $order->delivery_address_id = $delivery_address;
        $order->ordering_date = time();
        $order->update_date = time();
        $order->status = ShopOrder::STATUS_ACCEPTED;
        $order->payment_amount = (double)$cartStatistics['totalPayment'];
        $order->price_amount = (double)$cartStatistics['totalPrice'];
        $order->discount_amount = (double)$cartStatistics['totalDiscount'] + (double)$cartStatistics['discountCodeAmount'];
        $order->shipping_price = (double)$cartStatistics['shippingPrice'];
        $order->payment_price = (double)$cartStatistics['paymentPrice'];
        if($order->save()){
            // order items save in db
            $flag = true;
            foreach($cart as $id => $array){
                $id = $array['book_id'];
                $qty = $array['qty'];
                $model = Books::model()->findByPk($id);
                $orderItem = new ShopOrderItems();
                $orderItem->order_id = $order->id;
                $orderItem->model_name = get_class($model);
                $orderItem->model_id = $id;
                $orderItem->base_price = $model->printed_price;
                $orderItem->qty = $qty;
                $orderItem->payment = $model->off_printed_price;
                $flag = @$orderItem->save();
                if(!$flag)
                    break;
            }
            if(!$flag){
                $order->delete();
                Yii::app()->user->setFlash('failed', 'متاسفانه در ثبت سفارش مشکلی پیش آمده است! لطفا موارد را بررسی کرده و مجدد تلاش فرمایید.');
                Yii::app()->user->setState('basket-position', 4);
                $this->redirect(array('create'));
            }

            // clear cart content and all order states
            Shop::clearCartContent();
            Shop::clearOrderStates();

            // redirect to payment method runs action
            $this->redirect(array('payment', 'id' => $order->id));
        }else{
            Yii::app()->user->setFlash('failed', 'متاسفانه در ثبت سفارش مشکلی پیش آمده است! لطفا موارد را بررسی کرده و مجدد تلاش فرمایید.');
            Yii::app()->user->setState('basket-position', 4);
            $this->redirect(array('create'));
        }
    }

    /**
     * Payment action
     * @param $id
     * @throws CHttpException
     */
    public function actionPayment($id)
    {
        Yii::app()->theme = "frontend";
        $this->layout = "//layouts/index";
        Yii::app()->getModule('users');
        Yii::app()->getModule('discountCodes');
        $order = $this->loadModel($id);
        if($order->payment_status == ShopOrder::PAYMENT_STATUS_UNPAID){
            if($order->payment_amount !== 0){
                if($order->paymentMethod->name == ShopPaymentMethod::METHOD_CASH){
                    DiscountCodes::InsertCodes($order->user); // insert used discount code in db
                    $order->setStatus(ShopOrder::STATUS_PENDING)->save();
                    Shop::SetSuccessFlash();
                }else if($order->paymentMethod->name == ShopPaymentMethod::METHOD_CREDIT){
                    if($order->user->userDetails->credit < $order->payment_amount){
                        Yii::app()->user->setFlash('failed', 'اعتبار فعلی شما برای پرداخت مبلغ فاکتور کافی نیست! لطفا برای افزایش اعتبار از پنل کاربری خود اقدام کنید.');
                        $this->redirect(array('details', 'id' => $order->id));
                    }
                    $userDetails = UserDetails::model()->findByAttributes(array('user_id' => $order->user_id));
                    $userDetails->setScenario('update-credit');
                    $userDetails->credit = $userDetails->credit - $order->payment_amount;
                    if($userDetails->save()){
                        $order->setStatus(ShopOrder::STATUS_PAID)->setPaid()->save();
                        DiscountCodes::InsertCodes($order->user); // insert used discount code in db
                        Shop::SetSuccessFlash();
                    }else
                        Shop::SetFailedFlash();
                }else if($order->paymentMethod->name == ShopPaymentMethod::METHOD_GATEWAY){
                    // Save transaction
                    $transaction = new UserTransactions();
                    $transaction->user_id = $order->user_id;
                    $transaction->amount = $order->payment_amount;
                    $transaction->date = time();
                    $transaction->gateway_name = 'زرین پال';
                    $transaction->type = UserTransactions::TRANSACTION_TYPE_SHOP;
                    $transaction->type_id = $order->id;

                    if($transaction->save()){
                        $gateway = new ZarinPal();
                        $gateway->callback_url = Yii::app()->getBaseUrl(true) . '/shop/order/verify/' . $order->id;
                        $siteName = Yii::app()->name;
                        $description = "پرداخت فاکتور {$order->getOrderID()} در وبسایت {$siteName} از طریق درگاه {$gateway->getGatewayName()}";
                        $result = $gateway->request(doubleval($transaction->amount), $description, Yii::app()->user->email, $this->userDetails && $this->userDetails->phone?$this->userDetails->phone:'0');
                        $transaction->scenario = 'set-authority';
                        $transaction->description = $description;
                        $transaction->authority = $result->getAuthority();
                        $transaction->save();
                        $order->transaction_id = $transaction->id;
                        @$order->save(false);
                        //Redirect to URL You can do it also by creating a form
                        if($result->getStatus() == 100)
                            $this->redirect($gateway->getRedirectUrl());
                        else
                            throw new CHttpException(404, 'خطای بانکی: ' . $result->getError());
                    }
                }
            }else{
                DiscountCodes::InsertCodes($order->user); // insert used discount code in db
                Shop::SetSuccessFlash();
            }
        }
        $this->redirect(array('details', 'id' => $order->id));
    }

    /**
     * Verify Bank Transaction after bank callback
     * @param $id
     * @throws CHttpException
     */
    public function actionVerify($id)
    {
        if(!isset($_GET['Authority']))
            $this->redirect(array('/shop/order/create'));
        Yii::app()->getModule('discountCodes');
        $Authority = $_GET['Authority'];
        $model = UserTransactions::model()->findByAttributes(array(
            'authority' => $Authority,
            'user_id' => Yii::app()->user->getId(),
            'type' => UserTransactions::TRANSACTION_TYPE_SHOP
        ));
        $order = $this->loadModel($id);
        $Amount = $model->amount; //Amount will be based on Toman
        if($_GET['Status'] == 'OK'){
            $gateway = new ZarinPal();
            $gateway->verify($Authority, $Amount);
            if($gateway->getStatus() == 100){
                $model->scenario = 'update';
                $model->status = 'paid';
                $model->token = $gateway->getRefId();
                $model->save();
                DiscountCodes::InsertCodes($order->user); // insert used discount code in db
                $order->setStatus(ShopOrder::STATUS_PAID)->setPaid()->save();
                Shop::SetSuccessFlash();
            }else
                Shop::SetFailedFlash($gateway->getError());
        }else
            Shop::SetFailedFlash('عملیات پرداخت ناموفق بوده یا توسط کاربر لغو شده است.');
        $this->redirect(array('details', 'id' => $order->id));
    }

    public function actionDetails($id)
    {
        Yii::app()->theme = "frontend";
        $this->layout = "//layouts/index";
        $order = $this->loadModel($id);
        $this->render('details', array(
            'order' => $order,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['ShopOrder'])){
            $model->attributes = $_POST['ShopOrder'];
            if($model->save())
                $this->redirect(array('view', 'id' => $model->id));
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
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl'])?$_POST['returnUrl']:array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('ShopOrder');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $this->layout = '//layouts/column1';
        $model = new ShopOrder('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['ShopOrder']))
            $model->attributes = $_GET['ShopOrder'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return ShopOrder the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = ShopOrder::model()->findByPk($id);
        if($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param ShopOrder $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax'] === 'shop-order-form'){
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Change Status action
     * @throws CHttpException
     */
    public function actionChangeStatus()
    {
        if(isset($_POST['id']) && !empty($_POST['id'])){
            $id = (int)$_POST['id'];
            $model = $this->loadModel($id);
            $model->update_date = time();
            if($model->setStatus($_POST['value'])->save())
                echo CJSON::encode(['status' => true]);
            else
                echo CJSON::encode(['status' => false, 'msg' => 'در تغییر وضعیت این آیتم مشکلی بوجود آمده است! لطفا مجددا بررسی کنید.']);
        }
    }

    /**
     * Add discount code to order invoice
     */
    public function actionAddDiscount()
    {
        Yii::app()->user->setState('basket-position', 3);
        Yii::app()->getModule('discountCodes');
        // use Discount codes
        if(isset($_POST['DiscountCodes'])){
            $code = $_POST['DiscountCodes']['code'];
            $criteria = DiscountCodes::ValidCodes();
            $criteria->compare('code', $code);
            $discount = DiscountCodes::model()->find($criteria);
            /* @var $discount DiscountCodes */
            if($discount === NULL){
                Yii::app()->user->setFlash('failed', 'کد تخفیف مورد نظر موجود نیست.');
                $this->redirect(array('/shop/order/create'));
            }
            if(!$discount->shop_allow){
                Yii::app()->user->setFlash('failed', 'کد تخفیف مورد نظر قابل استفاده در این بخش نیست.');
                $this->redirect(array('/shop/order/create'));
            }
            if($discount->limit_times && $discount->usedCount() >= $discount->limit_times){
                Yii::app()->user->setFlash('failed', 'محدودیت تعداد استفاده از کد تخفیف مورد نظر به اتمام رسیده است.');
                $this->redirect(array('/shop/order/create'));
            }
            if(!Yii::app()->user->isGuest && $discount->user_id && $discount->user_id != Yii::app()->user->getId()){
                Yii::app()->user->setFlash('failed', 'کد تخفیف مورد نظر نامعتبر است.');
                $this->redirect(array('/shop/order/create'));
            }
            $used = $discount->codeUsed(array(
                    'condition' => 'user_id = :user_id',
                    'params' => array(':user_id' => Yii::app()->user->getId()),
                )
            );
            /* @var $used DiscountUsed */
            if($used){
                $u_date = JalaliDate::date('Y/m/d - H:i', $used->date);
                Yii::app()->user->setFlash('failed', "کد تخفیف مورد نظر قبلا در تاریخ {$u_date} استفاده شده است.");
                $this->redirect(array('/shop/order/create'));
            }
            if(DiscountCodes::addDiscountCodes($discount))
                Yii::app()->user->setFlash('success', 'کد تخفیف با موفقیت اعمال شد.');
            else
                Yii::app()->user->setFlash('failed', 'کد تخفیف در حال حاضر اعمال شده است.');
            $this->redirect(array('/shop/order/create'));
        }
    }

    /**
     * Remove added discount code to order invoice
     */
    public function actionRemoveDiscount()
    {
        Yii::app()->user->setState('basket-position', 3);
        Yii::app()->getModule('discountCodes');
        // use Discount codes
        if(isset($_GET['code'])){
            $code = $_GET['code'];
            if(Yii::app()->user->hasState('discount-codes')){
                $discountCodesInSession = Yii::app()->user->getState('discount-codes');
                $discountCodesInSession = CJSON::decode(base64_decode($discountCodesInSession));
                if(is_array($discountCodesInSession) && in_array($code, $discountCodesInSession)){
                    $key = array_search($code, $discountCodesInSession);
                    unset($discountCodesInSession[$key]);
                }else if(!is_array($discountCodesInSession) && $code == $discountCodesInSession)
                    $discountCodesInSession = null;
                if($discountCodesInSession)
                    Yii::app()->user->setState('discount-codes', base64_encode(CJSON::encode($discountCodesInSession)));
                else
                    Yii::app()->user->setState('discount-codes', null);
            }
        }
        $this->redirect(array('/shop/order/create'));
    }

    /**
     * Show user order histoy.
     */
    public function actionHistory()
    {
        Yii::app()->theme = "frontend";
        $this->layout = "//layouts/panel";

        $model = new ShopOrder("search");
        $model->unsetAttributes();
        if(isset($_GET['ShopOrder']))
            $model->attributes = $_GET['ShopOrder'];
        $model->user_id = Yii::app()->user->getId();

        $this->render("history", array(
            "model" => $model,
        ));
    }

    /**
     * Get order info.
     * @param $id
     * @throws CException
     * @throws CHttpException
     */
    public function actionGetInfo($id)
    {
        $model = $this->loadModel($id);

        if($model){
            $this->beginClip("order-item");
            $this->renderPartial("_order_item", array(
                "model" => $model,
            ));
            $this->endClip();

            echo CJSON::encode(array(
                "status" => true,
                "content" => $this->clips["order-item"]
            ));
        }else
            echo CJSON::encode(array(
                "status" => false,
            ));
    }

    /**
     * Save Export Code in model.
     * @param $id
     * @throws CHttpException
     */
    public function actionExportCode($id)
    {
        $model = $this->loadModel($id);
        if(isset($_POST['ShopOrder'])){
            $model->scenario = 'export-code';
            $model->export_code = $_POST['ShopOrder']['export_code'];
            if($model->save())
                Yii::app()->user->setFlash('success', 'کد مرسوله با موفقیت ثبت شد.');
            else
                Yii::app()->user->setFlash('success', 'در ثبت کد مرسوله مشکلی پیش آمده است! لطفا مجددا تلاش فرمایید.');
        }
        $this->redirect(array('view', 'id' => $model->id));
    }

    public function actionChangePayment($id)
    {
        $model = $this->loadModel($id);
        if(isset($_GET['method'])){
            $payment = ShopPaymentMethod::model()->findByAttributes(array('name' => $_GET['method']));
            if($payment && $payment->status == ShopPaymentMethod::STATUS_ACTIVE){
                $model->scenario = 'change-payment';
                $payment_amount = (double)($model->payment_amount - $model->payment_price);
                $model->payment_method = $payment->id;
                $model->payment_price = $payment->price;
                $model->payment_amount = $payment_amount + $payment->price;
                if($model->save())
                    $this->redirect(array('payment', 'id' => $model->id));
            }
            Yii::app()->user->setFlash('failed', 'در پرداخت سفارش مشکلی پیش آمده است! لطفا مجددا تلاش فرمایید.');
            $this->redirect(array('details', 'id' => $model->id));
        }
    }


    public function actionReport()
    {
        Yii::app()->theme = 'abound';
        $this->layout = isset($_GET['print'])?'//layouts/print':'//layouts/column1';

        $model = new ShopOrder('search');
        $model->unsetAttributes();
        if(isset($_GET['ShopOrder']))
            $model->attributes = $_GET['ShopOrder'];
        $this->render(isset($_GET['print']) && $_GET['print'] == true?'report_print':'report', array(
            'model' => $model,
        ));
    }
}