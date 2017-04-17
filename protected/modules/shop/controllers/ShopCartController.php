<?php

class ShopCartController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/public';

	/**
	 * @return array actions type list
	 */
	public static function actionsType()
	{
		return array(
			'frontend' => array('view', 'index', 'add', 'remove', 'getPriceTotal', 'updateQty'),
		);
	}

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'checkAccess + s',
			'postOnly + add, remove, updateQty',
		);
	}

	public function actionView()
	{
		Yii::app()->theme="frontend";
		$this->layout="//layouts/index";
		Yii::app()->user->setState('basket-position',1);
		$cart = Shop::getCartContent();
		$this->render('view',array(
			'books'=>$cart
		));
	}

	public function actionGetPriceTotal() {
		echo Shop::getPriceTotal();
	}

	public function actionUpdateQty()
	{
		$cart = Shop::getCartContent();

		if(isset($_POST)){
			$key = $_POST['book_id'];
			$value = $_POST['qty'];
			if($value == '')
				return true;
			if(!is_numeric($value) || $value <= 0)
				throw new CException('تعداد نامعتبر است.');

			if(isset($cart[$key]['qty']))
				$cart[$key]['qty'] = $value;
			Shop::setCartContent($cart);
			$cart = Shop::getCartContent();
			$this->beginClip('basket-table');
			$this->renderPartial('_basket_table',array('books' => $cart));
			$this->endClip();
			echo CJSON::encode([
				'status' => true,
				'countCart' => Controller::parseNumbers(number_format(Shop::getCartCount())),
				'table' => $this->clips['basket-table']
			]);
			Yii::app()->end();
		}
	}


	public function actionRemove(){
		if(isset($_POST)){
			$id = (int)$_POST['book_id'];
			$cart = json_decode(Yii::app()->user->getState('cart'), true);

			unset($cart[$id]);
			Yii::app()->user->setState('cart', json_encode($cart));
			$this->beginClip('basket-table');
			$this->renderPartial('_basket_table', array('books' => $cart));
			$this->endClip();
			echo CJSON::encode([
				'status' => true,
				'countCart' => Controller::parseNumbers(number_format(Shop::getCartCount())),
				'table' => $this->clips['basket-table']
			]);
			Yii::app()->end();
		}
	}

	public function actionAdd(){
		$cart = Shop::getCartContent();
		// remove potential clutter
		if(isset($_POST['yt0']))
			unset($_POST['yt0']);
		if(isset($_POST['yt1']))
			unset($_POST['yt1']);
		$id = $_POST['book_id'];
		if(is_array($cart) && array_key_exists($id,$cart))
		{
			$qty = $cart[$id]['qty'];
			if($qty<10)
				$cart[$id]['qty']++;
		}else
			$cart[$id] = $_POST;
		Shop::setCartcontent($cart);
		$this->redirect(array('//shop/cart/view'));
	}

	public function actionIndex()
	{
		$this->redirect(array('view'));
	}
}
