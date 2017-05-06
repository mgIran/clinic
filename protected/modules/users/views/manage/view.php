<?php
/* @var $this UsersManageController */
/* @var $model Users */

Yii::app()->clientScript->registerCss('imgSize','
.national-card-image
{
	max-width:500px;
	max-height:500px;
}
');

$this->breadcrumbs=array(
	'کاربران'=>array('index'),
	$model->userDetails->fa_name && !empty($model->userDetails->fa_name)?$model->userDetails->fa_name:$model->email,
);
if($model->role_id == 2)
{
	$this->menu=array(
		array('label'=>'مدیرت کاربران', 'url'=>array($model->role_id == 2?'adminPublishers':'admin')),
		array('label'=>'نمایش کتابخانه کاربر', 'url'=>array("userLibrary",'id'=>$model->id)),
		array('label'=>'نمایش تراکنش های کاربر', 'url'=>array("userTransactions",'id'=>$model->id)),
		array('label'=>'نمایش جلسات کاری فعال کاربر', 'url'=>array("sessions",'id'=>$model->id)),
		array('label'=>'حذف کاربر', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'آیا از حذف کاربر اطمینان دارید؟')),
		array('label'=>'تایید اطلاعات کاربر', 'url'=>array('confirmPublisher', 'id'=>$model->id, 'view-page' => true), 'linkOptions' => array('style' => 'margin-top:30px')),
		array('label'=>'رد اطلاعات کاربر', 'url'=>array('refusePublisher', 'id'=>$model->id, 'view-page' => true)),
		array('label'=>'تایید شناسه درخواستی کاربر', 'url'=>array('confirmDevID', 'id'=>$model->id, 'view-page' => true), 'linkOptions' => array('style' => 'margin-top:30px')),
		array('label'=>'رد شناسه درخواستی کاربر', 'url'=>array('deleteDevID', 'id'=>$model->id, 'view-page' => true)),
		array('label'=>'تایید اطلاعات مالی کاربر', 'url'=>"#" , 'linkOptions' => array('style' => 'margin-top:30px', 'class' => 'change-finance-status', 'data-id' => $model->id, 'data-value' => "accepted")),
		array('label'=>'رد اطلاعات مالی کاربر', 'url'=>"#" , 'linkOptions' => array('class' => 'change-finance-status', 'data-id' => $model->id, 'data-value' => "refused")),
		array('label'=>'افزایش اعتبار کاربر', 'url'=>array('changeCredit', 'id'=>$model->id), 'linkOptions' => array('style' => 'margin-top:30px;font-weight:bold')),
	);

	Yii::app()->clientScript->registerScript('changeFinanceStatus', "
		$('body').on('click', '.change-finance-status', function(){
			$.ajax({
				url:'".$this->createUrl('/users/manage/changeFinanceStatus')."',
				type:'POST',
				dataType:'JSON',
				data:{user_id:$(this).data('id'), value:$(this).data('value')},
				success:function(data){
					if(data.status){
						alert('با موفقیت انجام شد.');
						location.reload();
					}else
						alert('در انجام عملیات خطایی رخ داده است لطفا مجددا تلاش کنید.');
				}
			});
		});
	");
}
else
	$this->menu=array(
		array('label'=>'مدیرت کاربران', 'url'=>array($model->role_id == 2?'adminPublishers':'admin')),
		array('label'=>'نمایش کتابخانه کاربر', 'url'=>array("userLibrary",'id'=>$model->id)),
		array('label'=>'نمایش جلسات کاری فعال کاربر', 'url'=>array("sessions",'id'=>$model->id)),
		array('label'=>'حذف کاربر', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'آیا از حذف کاربر اطمینان دارید؟')),
		array('label'=>'تایید اطلاعات کاربر', 'url'=>array('confirmPublisher', 'id'=>$model->id, 'view-page' => true), 'linkOptions' => array('style' => 'margin-top:30px')),
		array('label'=>'رد اطلاعات کاربر', 'url'=>array('refusePublisher', 'id'=>$model->id, 'view-page' => true)),
		array('label'=>'افزایش اعتبار کاربر', 'url'=>array('changeCredit', 'id'=>$model->id), 'linkOptions' => array('style' => 'margin-top:30px;font-weight:bold')),
	);
?>

<h1>نمایش اطلاعات <?php echo $model->userDetails->fa_name && !empty($model->userDetails->fa_name)?$model->userDetails->fa_name:$model->email; ?></h1>
<? $this->renderPartial('//partial-views/_flashMessage') ?>
<?php if($model->userDetails->type == 'real'):?>
	<?php $this->widget('zii.widgets.CDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			array(
				'name'=>'نام',
				'value'=>$model->userDetails->fa_name,
			),
			array(
				'name'=>'نام انگلیسی',
				'value'=>$model->userDetails->en_name,
			),
			array(
				'name'=>'شناسه ناشر',
				'value'=>$model->userDetails->publisher_id,
			),
			array(
				'name'=>'اعتبار',
				'value'=>number_format($model->userDetails->credit,0).'تومان',
			),
			array(
				'name'=>'آدرس وبسایت فارسی',
				'value'=>$model->userDetails->fa_web_url,
			),
			array(
				'name'=>'آدرس وبسایت انگلیسی',
				'value'=>$model->userDetails->en_web_url,
			),
			array(
				'name'=>'شماره تماس',
				'value'=>$model->userDetails->phone,
			),
			array(
				'name'=>'کد ملی',
				'value'=>$model->national_code,
			),
			array(
				'name'=>'کد پستی',
				'value'=>$model->userDetails->zip_code,
			),
			array(
				'name'=>'آدرس',
				'value'=>$model->userDetails->address,
			),
			array(
				'name'=>'نوع کاربری',
				'value'=>$model->role->name,
			),
			array(
				'name'=>'تصویر کارت ملی',
				'value'=>CHtml::image(Yii::app()->baseUrl."/uploads/users/national_cards/".$model->userDetails->national_card_image, '', array('class'=>'national-card-image')),
				'type'=>'raw'
			),
			array(
				'name'=>'امتیاز',
				'value'=>$model->userDetails->score,
			),
			array(
				'name'=>'وضعیت',
				'value'=>$model->statusLabels[$model->status],
			),
			array(
				'name'=>'وضعیت اطلاعات',
				'value'=>$model->userDetails->detailsStatusLabels[$model->userDetails->details_status],
			),
			array(
				'name'=>'نام صاحب حساب',
				'value'=>$model->userDetails->account_owner_name,
			),
			array(
				'name'=>'نام خانوادگی صاحب حساب',
				'value'=>$model->userDetails->account_owner_family,
			),
			array(
				'name'=>'شماره حساب',
				'value'=>$model->userDetails->account_number,
			),
			array(
				'name'=>'نام بانک',
				'value'=>$model->userDetails->bank_name,
			),
			array(
				'name'=>'شماره شبا',
				'value'=>'IR'.$model->userDetails->iban,
			),
			array(
				'name'=>'وضعیت اطلاعات مالی',
				'value'=>$model->userDetails->detailsStatusLabels[$model->userDetails->financial_info_status],
			),
		),
	)); ?>
<?php else:?>
	<?php $this->widget('zii.widgets.CDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			array(
				'name'=>'نوع عضویت',
				'value'=>$model->userDetails->typeLabels[$model->userDetails->type],
			),
			array(
				'name'=>'نام و نام خانوادگی',
				'value'=>$model->userDetails->fa_name,
			),
			array(
				'name'=>'شناسه ناشر',
				'value'=>$model->userDetails->publisher_id,
			),
			array(
				'name'=>'اعتبار',
				'value'=>number_format($model->userDetails->credit,0).'تومان',
			),
			array(
				'name'=>'آدرس وبسایت',
				'value'=>$model->userDetails->fa_web_url,
			),
			array(
				'name'=>'سمت',
				'value'=>$model->userDetails->postLabels[$model->userDetails->post],
			),
			array(
				'name'=>'نام شرکت',
				'value'=>$model->userDetails->company_name,
			),
			array(
				'name'=>'شماره ثبت',
				'value'=>$model->userDetails->registration_number,
			),
			array(
				'name'=>'شماره تماس',
				'value'=>$model->userDetails->phone,
			),
			array(
				'name'=>'کد پستی',
				'value'=>$model->userDetails->zip_code,
			),
			array(
				'name'=>'آدرس',
				'value'=>$model->userDetails->address,
			),
			array(
				'name'=>'نوع کاربری',
				'value'=>$model->role->name,
			),
			array(
				'name'=>'تصویر گواهی ثبت',
				'value'=>CHtml::image(Yii::app()->baseUrl."/uploads/users/registration_certificate/".$model->userDetails->registration_certificate_image, '', array('class'=>'national-card-image')),
				'type'=>'raw'
			),
			array(
				'name'=>'امتیاز',
				'value'=>$model->userDetails->score,
			),
			array(
				'name'=>'وضعیت',
				'value'=>$model->statusLabels[$model->status],
			),
			array(
				'name'=>'وضعیت اطلاعات',
				'value'=>$model->userDetails->detailsStatusLabels[$model->userDetails->details_status],
			),array(
				'name'=>'نام حقوقی صاحب حساب',
				'value'=>$model->userDetails->account_owner_name,
			),
			array(
				'name'=>'شماره حساب',
				'value'=>$model->userDetails->account_number,
			),
			array(
				'name'=>'نام بانک',
				'value'=>$model->userDetails->bank_name,
			),
			array(
				'name'=>'شماره شبا',
				'value'=>'IR'.$model->userDetails->iban,
			),
			array(
				'name'=>'وضعیت اطلاعات مالی',
				'value'=>$model->userDetails->detailsStatusLabels[$model->userDetails->financial_info_status],
			),
		),
	)); ?>
<?php endif;?>
