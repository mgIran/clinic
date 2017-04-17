<?php

/**
 * VoucherForm class.
 * VoucherForm is the data structure for keeping
 */
class VoucherForm extends CFormModel
{
	public $user_id;
	public $code;
	public $verifyCode;

	private $_bon;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('user_id, code', 'required'),
			array('user_id, code', 'filter', 'filter'=>'strip_tags'),
			array('code', 'voucherExist'),
			array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements(),'on' => 'withCaptcha'),
		);
	}

	public function voucherExist($attribute){
		$model = UserBons::model()->findByAttributes(array('code' => $this->code));
		if($model === NULL)
			$this->addError($attribute,'بن خرید نامعتبر است.');
		else{
			if($model->status == 0)
				$this->addError($attribute,'بن خرید نامعتبر است.');
			elseif($model->checkUserUsed($this->user_id))
				$this->addError($attribute,'شما قبلا از این بن خرید استفاده کرده اید.');
			elseif($model->start_date > time())
				$this->addError($attribute ,'زمان استفاده از این بن خرید شروع نشده است.');
			elseif($model->end_date < time())
				$this->addError($attribute ,'بن خرید منقضی شده است.');
			elseif($model->user_limit && !empty($model->user_limit) && $model->getNumberUsed() >= $model->user_limit)
				$this->addError($attribute ,'بن خرید به اتمام رسیده است.');
		}

		if(!$this->hasErrors())
			$this->_bon = $model;
	}

	public function getBon(){
		return $this->_bon?$this->_bon:null;
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'code' => 'بن خرید',
			'verifyCode' => 'کد امنیتی',
		);
	}
}