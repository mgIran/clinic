<?php
class Shop
{
    public static $qtyList = array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10);

    public static function getPaymentMethod()
    {
        if($payment_method = Yii::app()->user->getState('payment_method'))
            return ShopPaymentMethod::model()->findByPk($payment_method);
    }

    public static function getShippingMethod()
    {
        if($shipping_method = Yii::app()->user->getState('shipping_method'))
            return ShopShippingMethod::model()->findByPk($shipping_method);
    }

    public static function getDeliveryAddress()
    {
        if($delivery_address = Yii::app()->user->getState('delivery_address'))
            return ShopAddresses::model()->findByPk($delivery_address);
    }


    public static function getCartContent()
    {
        if(Yii::app()->user->hasState('cart')){
            if(is_string(Yii::app()->user->getState('cart')))
                return json_decode(Yii::app()->user->getState('cart'), true);
            else
                return Yii::app()->user->getState('cart');
        }elseif(Yii::app()->request->cookies['shop-basket'])
            return CJSON::decode(base64_decode(Yii::app()->request->cookies['shop-basket']->value));
    }

    public static function setCartContent($cart)
    {
        Yii::app()->request->cookies['shop-basket'] = new CHttpCookie('shop-basket',
            base64_encode(json_encode($cart)), array(
                'path' => '/',
            ));
        $cart = Yii::app()->user->setState('cart', json_encode($cart));
        return $cart;
    }

    public static function getCartCount()
    {
        $cart = self::getCartContent();
        $count = 0;
        if(!is_null($cart))
            foreach($cart as $item)
                $count += $item['qty'];
        return $count;
    }

    public static function clearCartContent()
    {
        unset(Yii::app()->request->cookies['shop-basket']);
        Yii::app()->user->setState('cart', null);
    }

    public static function clearOrderStates()
    {
        Yii::app()->user->setState('payment_method', null);
        Yii::app()->user->setState('shipping_method', null);
        Yii::app()->user->setState('delivery_address', null);
        Yii::app()->user->setState('customer_id', null);
        Yii::app()->user->setState('basket-position', null);
    }

    /**
     *
     * @param $cartStatistics
     * @return bool
     */
    public static function isEmpty($cartStatistics)
    {
        if(
            !$cartStatistics ||
            $cartStatistics['totalPrice'] == 0 ||
            $cartStatistics['totalPayment'] == 0
        )
            return true;
        return false;
    }

    /**
     * Return Prices of cart and order
     * @return array of order cart statistics
     */
    public static function getPriceTotal()
    {
        $response = [];
        $price_total = 0;
        $discount_total = 0;
        $payment_total = 0;
        $payment_price = 0;
        $shipping_price = 0;
        $discountCodeAmount = 0;
        foreach(Shop::getCartContent() as $book){
            $model = Books::model()->findByPk($book['book_id']);
            $price = (double)($model->getPrinted_price() * $book['qty']);
            $price_total += $price;
            // calculate tax
            $discount_price = (double)($model->getOff_printed_price() * $book['qty']);
            $discount_total += ($price - $discount_price);
            $payment_total += $discount_price;
        }

        $response['cartPrice'] = $payment_total;

        if($discount_codes = Users::model()->getDiscountCodes()){
            Yii::app()->getModule('discountCodes');
            $discountObj = DiscountCodes::model()->findByAttributes(['code' => $discount_codes]);
            $flag = true;
            if($discountObj === NULL || !$discountObj->shop_allow ||
                ($discountObj->limit_times && $discountObj->usedCount() >= $discountObj->limit_times) ||
                ($discountObj->user_id && Yii::app()->user->type == 'user' && $discountObj->user_id != Yii::app()->user->getId())
            ){
                $flag = false;
            }
            $used = $discountObj->codeUsed(array(
                    'condition' => 'user_id = :user_id',
                    'params' => array(':user_id' => Yii::app()->user->getId()),
                )
            );
            /* @var $used DiscountUsed */
            if($used){
                $flag = false;
            }
            if($flag)
                $discountCodeAmount = (double)$discountObj->getAmount($payment_total);
            else{
                $discount_codes = null;
                Yii::app()->user->setState('discount-codes', null);
            }
            DiscountCodes::calculateDiscountCodes($payment_total, 'shop');
        }

        if($shipping_method = Shop::getShippingMethod()){
            if(!$shipping_method->limit_price || ($shipping_method->limit_price && $payment_total < $shipping_method->limit_price)){
                $shipping_price = (double)$shipping_method->price;
                $payment_total += $shipping_price;
            }
        }

        if($payment_method = Shop::getPaymentMethod()){
            $payment_price = (double)$payment_method->price;
            $payment_total += $payment_price;
        }

        $response['paymentPrice'] = $payment_price;
        $response['shippingPrice'] = $shipping_price;
        $response['discountCodeAmount'] = $discountCodeAmount;
        $response['totalPrice'] = $price_total;
        $response['totalDiscount'] = $discount_total;
        $response['totalPayment'] = $payment_total;
        return $response;
    }

    /**
     * Return User
     * @return Users
     */
    public static function getCustomer()
    {
        if(!Yii::app()->user->isGuest && Yii::app()->user->type == 'user')
            if($customer = Users::model()->findByPk(Yii::app()->user->getId()))
                return $customer;
        return false;
    }

    public static function PrintStatusLine($status, $adminSide = false, $modelId = null)
    {
        $labels = ShopOrder::model()->statusLabels;
        $keys = array_keys($labels);
        if($status > 5)
            $status = 5;
        $start = 0;
        $data = '';
        if($adminSide)
            $data = " data-id='{$modelId}'";

        echo "<ul class='steps after-accept in-verify nav nav-justified'{$data}>";
        for($i = $start;$i < count($keys);$i++){
            $class = '';
            $data = '';
            if($adminSide)
                $data = " data-status-id='{$i}'";
            if($status >= $i)
                $class = ' class="done"';
            if($status + 1 == $i)
                $class = ' class="doing"';
            echo "<li{$class}{$data}>
                    <div>{$labels[$i]}</div>
                </li>";
        }
        echo '</ul>';
        if($adminSide)
            Yii::app()->clientScript->registerScript('change-status', '
                $("body").on("click", "[data-status-id]",function(){
                    var $this = $(this);
                        $id = $this.parents("ul").data("id");
                        $val = $this.data("status-id");
                    $.ajax({
                        url: "' . Yii::app()->createUrl('/shop/order/changeStatus') . '",
                        type: "POST",
                        data: {id: $id, value: $val},
                        dataType: "JSON",
                        beforeSend: function(){
                        },
                        success: function(data){
                            if(data.status)
                                location.reload();
                            else
                                alert(data.msg);
                        }
                    });
                });
            ');
    }

    public static function SetSuccessFlash($message = null)
    {
        Yii::app()->user->setFlash('success', $message?$message:'خرید شما با موفقیت ثبت شد.');
        Yii::app()->user->setFlash('message', 'سفارش شما جهت انجام مراحل بعدی در اختیار تیم کتابیک قرار گرفت. جهت پیگیری وضعیت سفارش خود از طریق پنل کاربری اقدام نمایید.');
    }

    public static function SetFailedFlash($message = null)
    {
        Yii::app()->user->setFlash('failed', $message?$message:'در انجام عملیات خرید خطایی رخ داده است. لطفا مجددا تلاش کنید.');
        Yii::app()->user->setFlash('message', 'درصورتیکه طی این فرآیند، مبلغی از حساب شما کسر شده است، طی 72 ساعت آینده، به حساب شما باز خواهد گشت.');
    }
}