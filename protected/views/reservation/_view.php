<?php
/* @var $this ReservationController */
/* @var $model Visits */
?>
<div class="row">
	<?php $this->renderPartial('//partial-views/_flashMessage');?>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 first">
		<h4>اطلاعات بیمار</h4>
		<div class="items">
			<div class="row">
				<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">نام و نام خانوادگی</div>
				<div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><?php echo $model->user->userDetails->getShowName();?></div>
			</div>
			<div class="row">
				<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">کد ملی</div>
				<div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><?php echo $model->user->national_code;?></div>
			</div>
			<div class="row">
				<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">تلفن همراه</div>
				<div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><?php echo $model->user->userDetails->mobile;?></div>
			</div>
			<div class="row">
				<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">پست الکترونیکی</div>
				<div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><?php echo $model->user->email;?></div>
			</div>
		</div>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		<h4>اطلاعات نوبت</h4>
		<div class="items">
			<div class="row">
				<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">مطب</div>
				<div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><?php echo $model->clinic->clinic_name;?></div>
			</div>
			<div class="row">
				<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">پزشک</div>
				<div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><a href="<?php echo $this->createUrl('/users/' . $model->doctor_id . '/clinic/' . $model->clinic_id)?>" target="_blank"><?php echo $model->doctor->userDetails->getShowName();?></a></div>
			</div>
			<div class="row">
				<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">تخصص</div>
				<div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><?php echo $model->expertise->title;?></div>
			</div>
			<div class="row">
				<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">تاریخ مراجعه</div>
				<div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><?php echo JalaliDate::date(' d F Y', $model->date, false);?></div>
			</div>
			<div class="row">
				<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">ساعت حضور پزشک</div>
				<div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><?php echo (($model->time == Visits::TIME_AM)?$doctorSchedule->entry_time_am:$doctorSchedule->entry_time_pm).':00';?> الی <?php echo ($model->time == Visits::TIME_AM)?$doctorSchedule->exit_time_am.':00 صبح':$doctorSchedule->exit_time_pm.':00 بعد از ظهر';?></div>
			</div>
			<div class="row">
				<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">وضعیت نوبت</div>
				<div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><?php echo $model->getStatusLabel();?></div>
			</div>
		</div>
	</div>
	<?php
	if(isset($transaction) && $transaction):
		?>
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<h4>اطلاعات تراکنش</h4>
			<div class="items">
				<div class="row">
					<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">مبلغ پرداخت</div>
					<div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><?php echo Controller::parseNumbers(number_format($transaction->amount))?> تومان</div>
				</div>
				<div class="row">
					<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">تاریخ پرداخت</div>
					<div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 ltr text-right"><?php echo JalaliDate::date('Y/m/d - H:i', $transaction->date)?></div>
				</div>
				<div class="row">
					<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">وضعیت پرداخت</div>
					<div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><?php echo $transaction->statusLabels[$transaction->status];?></div>
				</div>
				<div class="row">
					<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">کد رهگیری تراکنش</div>
					<div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 ltr text-right" style="font-weight: 600; font-size: 18px; letter-spacing: 2px;"><?php echo CHtml::encode($transaction->token); ?></div>
				</div>
				<div class="row">
					<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">درگاه پرداخت</div>
					<div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><?php echo $transaction->gateway_name ?></div>
				</div>
			</div>
		</div>
		<?php
	endif;
	?>
</div>
<div class="price-info">
	<h4>کد رهگیری: <span><?php echo $model->tracking_code;?></span></h4>
</div>