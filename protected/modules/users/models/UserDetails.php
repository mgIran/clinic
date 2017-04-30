<?php

/**
 * This is the model class for table "{{user_details}}".
 *
 * The followings are the available columns in table '{{user_details}}':
 * @property string $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $fa_web_url
 * @property string $en_web_url
 * @property string $national_code
 * @property string $national_card_image
 * @property string $phone
 * @property string $zip_code
 * @property string $address
 * @property double $credit
 * @property string $publisher_id
 * @property string $details_status
 * @property integer $monthly_settlement
 * @property string $iban
 * @property string $nickname
 * @property string $type
 * @property string $post
 * @property string $company_name
 * @property string $registration_number
 * @property string $registration_certificate_image
 * @property integer $score
 * @property string $avatar
 * @property string $publication_name
 * @property string $account_type
 * @property string $account_owner_name
 * @property string $account_owner_family
 * @property string $account_number
 * @property string $bank_name
 * @property string $financial_info_status
 * @property string $commission
 * @property string $tax_exempt
 * @property string $earning
 *
 * The followings are the available model relations:
 * @property Users $user
 */
class UserDetails extends CActiveRecord
{
    const ACCOUNT_TYPE_REAL = 'real';
    const ACCOUNT_TYPE_LEGAL = 'legal';

    const TAX_EXEMPT_NOT = 0;
    const TAX_EXEMPT = 1;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{user_details}}';
    }

    public $roleLabels = array(
        'user' => 'کاربر عادی',
        'clinicAdmin' => 'مدیر درمانگاه',
        'doctor' => 'دکتر',
        'secretary' => 'منشی درمانگاه',
    );

    public $detailsStatusLabels = array(
        'pending' => 'در انتظار تایید',
        'accepted' => 'تایید شده',
        'refused' => 'رد شده',
    );

    public $postLabels = array(
        'ceo' => 'مدیر عامل',
        'board' => 'جزء هیئت مدیره',
    );

    public $typeLabels = array(
        'real' => 'حقیقی',
        'legal' => 'حقوقی',
    );

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('first_name, publication_name, national_code, phone, zip_code, address, national_card_image, nickname, publisher_id', 'required', 'on' => 'update_real_profile'),
            array('first_name, publication_name, nickname, post, company_name, registration_number, phone, zip_code, address, registration_certificate_image, publisher_id', 'required', 'on' => 'update_legal_profile'),
            array('publisher_id', 'required', 'on' => 'confirmDev'),
            array('publisher_id', 'unique'),
            array('credit, national_code, phone, zip_code, score', 'numerical'),
            array('user_id, national_code, zip_code, earning', 'length', 'max' => 10),
            array('account_owner_name, account_owner_family', 'length', 'max' => 50),
            array('bank_name, account_number', 'length', 'max' => 50),
            array('national_code, zip_code', 'length', 'min' => 10),
            array('phone', 'length', 'min' => 8),
            array('first_name, last_name, national_card_image, company_name, registration_number, registration_certificate_image', 'length', 'max' => 50),
            array('fa_web_url, en_web_url, avatar', 'length', 'max' => 255),
            array('phone', 'length', 'max' => 11),
            array('publisher_id, nickname', 'length', 'max' => 20, 'min' =>3),
            array('address', 'length', 'max' => 1000),
            array('publication_name', 'length', 'max' => 100),
            array('details_status, financial_info_status', 'length', 'max' => 8),
            array('account_type, type, post', 'length', 'max' => 5),
            array('commission', 'length', 'max' => 3),
            array('tax_exempt', 'length', 'max' => 1),
            array('iban', 'length', 'is' => 24, 'on' => 'update-settlement, update_real_profile, update_legal_profile', 'message' => 'شماره شبا باید 24 کاراکتر باشد'),
            array('iban', 'ibanRequiredConditional', 'on' => 'update-settlement, update_real_profile, update_legal_profile'),
            array('monthly_settlement', 'numerical', 'integerOnly' => true),
            array('monthly_settlement', 'default', 'value' => 1),
            // change-credit scenario
            array('credit', 'required', 'on' => 'change-credit'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('user_id, first_name, last_name, fa_web_url, en_web_url, national_code, national_card_image, phone, zip_code, address, credit, publisher_id, details_status, monthly_settlement, iban, nickname, score, avatar, account_owner_name, account_owner_family, account_number, bank_name, financial_info_status, publication_name, earning', 'safe', 'on' => 'search'),
        );
    }

    public function ibanRequiredConditional($attribute, $params)
    {
        if (!$this->{$attribute} || empty($this->{$attribute}) || !preg_match('/^[0-9]{24}/', $this->{$attribute}))
            $this->addError($attribute, $this->getAttributeLabel($attribute) . ' نامعتبر است.');

    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'user_id' => 'کاربر',
            'first_name' => 'نام و نام خانوادگی',
            'last_name' => 'نام انگلیسی',
            'fa_web_url' => 'آدرس وبسایت',
            'en_web_url' => 'آدرس سایت انگلیسی',
            'national_code' => 'کد ملی',
            'national_card_image' => 'تصویر کارت ملی',
            'phone' => 'تلفن',
            'zip_code' => 'کد پستی',
            'address' => 'نشانی دقیق پستی',
            'credit' => 'اعتبار',
            'publisher_id' => 'شناسه ناشر',
            'status' => 'وضعیت کاربر',
            'details_status' => 'وضعیت اطلاعات کاربر',
            'monthly_settlement' => 'تسویه حساب ماهانه',
            'iban' => 'شماره شبا',
            'nickname' => 'نام نمایشی',
            'type' => 'نوع حساب',
            'post' => 'سمت در شرکت',
            'company_name' => 'نام شرکت',
            'registration_number' => 'شماره ثبت',
            'registration_certificate_image' => 'تصویر گواهی ثبت شرکت',
            'score' => 'امتیاز',
            'avatar' => 'آواتار',
            'publication_name' => 'نام انتشارات',
            'account_owner_name' => 'نام صاحب حساب',
            'account_owner_family' => 'نام خانوادگی صاحب حساب',
            'account_type' => 'نوع حساب',
            'account_number' => 'شماره حساب',
            'bank_name' => 'نام بانک',
            'financial_info_status' => 'وضعیت اطلاعات مالی',
            'commission' => 'کمیسیون ناشر',
            'tax_exempt' => 'معاف از مالیات',
            'earning' => 'درآمد',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('user_id', $this->user_id, true);
        $criteria->compare('first_name', $this->first_name, true);
        $criteria->compare('last_name', $this->last_name, true);
        $criteria->compare('fa_web_url', $this->fa_web_url, true);
        $criteria->compare('en_web_url', $this->en_web_url, true);
        $criteria->compare('national_code', $this->national_code, true);
        $criteria->compare('national_card_image', $this->national_card_image, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('zip_code', $this->zip_code, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('credit', $this->credit);
        $criteria->compare('publisher_id', $this->publisher_id, true);
        $criteria->compare('details_status', $this->details_status, true);
        $criteria->compare('monthly_settlement', $this->monthly_settlement);
        $criteria->compare('iban', $this->iban, true);
        $criteria->compare('nickname', $this->nickname, true);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('post', $this->post, true);
        $criteria->compare('company_name', $this->company_name, true);
        $criteria->compare('registration_number', $this->registration_number, true);
        $criteria->compare('registration_certificate_image', $this->registration_certificate_image, true);
        $criteria->compare('score',$this->score);
        $criteria->compare('avatar',$this->avatar,true);
        $criteria->compare('financial_info_status',$this->financial_info_status,true);
        $criteria->compare('publication_name',$this->publication_name,true);
        $criteria->compare('commission', $this->commission, true);
        $criteria->compare('tax_exempt', $this->tax_exempt, true);
        $criteria->compare('earning', $this->earning, true);
        
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return UserDetails the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Return amount of settlement
     */
    public function getSettlementAmount()
    {
        return ($this->earning < 0) ? 0 : $this->earning;
    }

    /**
     * @return string if user`s name is not empty return name ,otherwise return email
     */
    public function getShowName()
    {
        if ($this->first_name or $this->last_name)
            return $this->first_name . ' ' . $this->last_name;
        else
            return $this->user->email;
    }

    public function validateAccountingInformation()
    {
        if (
            !$this->iban || empty($this->iban) ||
            !$this->account_owner_name || empty($this->account_owner_name) ||
            !$this->account_owner_family || empty($this->account_owner_family) ||
            !$this->account_number || empty($this->account_number) ||
            !$this->bank_name || empty($this->bank_name)
        )
            return false;
        return true;
    }

    public static function SettlementCriteria()
    {
        Yii::app()->getModule('setting');
        $setting = SiteSetting::model()->find('name=:name', array(':name' => 'min_credit'));
        $criteria = new CDbCriteria();
        $criteria->addCondition('iban IS NOT NULL AND iban != ""');
        $criteria->addCondition('account_owner_name IS NOT NULL AND account_owner_name != ""');
        $criteria->addCondition('account_number IS NOT NULL AND account_number != ""');
        $criteria->addCondition('bank_name IS NOT NULL AND bank_name != ""');
        $criteria->addCondition('financial_info_status = "accepted"');
        $criteria->addCondition('earning > :earning');
        $criteria->params = array(':earning' => $setting->value);
        return $criteria;
    }

    public static function PendingFinanceUsersCriteria()
    {
        $criteria = new CDbCriteria();
        $criteria->addCondition('iban IS NOT NULL AND iban != ""');
        $criteria->addCondition('account_owner_name IS NOT NULL AND account_owner_name != ""');
        $criteria->addCondition('account_number IS NOT NULL AND account_number != ""');
        $criteria->addCondition('bank_name IS NOT NULL AND bank_name != ""');
        $criteria->addCondition('financial_info_status = "pending"');
        return $criteria;
    }

    public function getDetailsStatus(){
        return $this->detailsStatusLabels[$this->financial_info_status];
    }

    public function getPublisherName()
    {
        return $this->nickname ? $this->nickname : $this->first_name;
    }
}