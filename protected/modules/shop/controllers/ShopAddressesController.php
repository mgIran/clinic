<?php

class ShopAddressesController extends Controller
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
			'frontend' => array('add', 'remove', 'update'),
		);
	}

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'checkAccess',
			'postOnly + add, remove',
		);
	}

	public function actionAdd()
	{
		$model = new ShopAddresses();
		$model->user_id = Yii::app()->user->getId();
		if(isset($_POST['ajax']) && $_POST['ajax'] === 'address-form'){
			$errors = CActiveForm::validate($model);
			if(CJSON::decode($errors)){
				echo $errors;
				Yii::app()->end();
			}
		}
		if(isset($_POST['ShopAddresses'])){
			$model->attributes = $_POST['ShopAddresses'];
			$model->user_id = Yii::app()->user->getId();
			Yii::app()->getModule('places');
			$this->beginClip('address-list');
			if($model->save())
				$this->renderPartial('shop.views.shipping._alertMessage', array(
					'type' => 'success',
					'class' => 'address-list',
					'message' => '<span class="icon icon-check"></span> آدرس با موفقیت ثبت شد.',
					'autoHide' => true
				));
			else
				$this->renderPartial('shop.views.shipping._alertMessage', array(
					'type' => 'danger',
					'class' => 'address-list',
					'message' => 'متاسفانه در ثبت آدرس مشکلی پیش آمده است! لطفا مجددا تلاش کنید.',
					'autoHide' => true
				));

			$this->renderPartial('shop.views.shipping._addresses_list', array('addresses' => $model->user->addresses));
			$this->endClip();
			echo CJSON::encode(['status' => true, 'content' => $this->clips['address-list']]);
		}
	}

	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);
		$model->user_id = Yii::app()->user->getId();
		if(isset($_POST['ajax']) && $_POST['ajax'] === 'address-form'){
			$errors = CActiveForm::validate($model);
			if(CJSON::decode($errors)){
				echo $errors;
				Yii::app()->end();
			}
		}
		Yii::app()->getModule('places');
		if(isset($_POST['ShopAddresses'])){
			$model->attributes = $_POST['ShopAddresses'];
			$model->user_id = Yii::app()->user->getId();
			$this->beginClip('address-list');
			if($model->save())
				$this->renderPartial('shop.views.shipping._alertMessage', array(
					'type' => 'success',
					'class' => 'address-list',
					'message' => '<span class="icon icon-check"></span> آدرس با موفقیت به روزرسانی شد.',
					'autoHide' => true
				));
			else
				$this->renderPartial('shop.views.shipping._alertMessage', array(
					'type' => 'danger',
					'class' => 'address-list',
					'message' => 'متاسفانه در ویرایش آدرس مشکلی پیش آمده است! لطفا مجددا تلاش کنید.',
					'autoHide' => true
				));

			$this->renderPartial('shop.views.shipping._addresses_list', array('addresses' => $model->user->addresses));
			$this->endClip();
			echo CJSON::encode(['status' => true, 'content' => $this->clips['address-list']]);
			Yii::app()->end();
		}

		$this->beginClip('add-address-modal');
		$this->renderPartial('shop.views.shipping._add_address_modal', array('model' => $model));
		$this->endClip();
		echo CJSON::encode(['status' => true, 'content' => $this->clips['add-address-modal']]);
	}

	public function actionRemove(){
		if(isset($_POST['id'])){
			$model = $this->loadModel((int)$_POST['id']);
			$model->deleted = 1;
			Yii::app()->getModule('places');

			if(Yii::app()->user->getState('delivery_address') == $model->id)
				Yii::app()->user->setState('delivery_address', null);

			$this->beginClip('address-list');
			if($model->save(false))
				$this->renderPartial('shop.views.shipping._alertMessage', array(
					'type' => 'success',
					'class' => 'address-list',
					'message' => '<span class="icon icon-check"></span> آدرس با موفقیت حذف شد.',
					'autoHide' => true
				));
			else
				$this->renderPartial('shop.views.shipping._alertMessage', array(
					'type' => 'danger',
					'class' => 'address-list',
					'message' => 'متاسفانه در حذف آدرس مشکلی پیش آمده است! لطفا مجددا تلاش کنید.',
					'autoHide' => true
				));

			$this->renderPartial('shop.views.shipping._addresses_list', array('addresses' => $model->user->addresses));
			$this->endClip();
			echo CJSON::encode(['status' => true, 'content' => $this->clips['address-list']]);
		}
	}


	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return ShopAddresses the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=ShopAddresses::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
