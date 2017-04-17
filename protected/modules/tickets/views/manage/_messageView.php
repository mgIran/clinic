<?php
/* @var $this TicketsManageController */
/* @var $data TicketMessages */
?>
<div class="message-item">
	<div class="avatar">
		<?
		if($data->sender == 'user'):
			?>
			<span class="icon icon-user"></span>
			<?
		elseif($data->sender == 'supporter'):
			?>
			<span class="svg svg-supporter"></span>
			<?
		elseif($data->sender == 'admin'):
			?>
			<span class="svg svg-manager"></span>
			<?
		endif;
		?>
	</div>
	<div class="message-date">
		<span class="title pull-right">
			<?
			if($data->sender == 'user'):
				echo Yii::app()->user->hasState('fa_name')?Yii::app()->user->fa_name:'کاربر';
			elseif($data->sender == 'supporter'):
				echo 'پشتیبان';
			elseif($data->sender == 'admin'):
				echo 'مدیریت';
			endif;
			?>
		</span>
		<?= Controller::parseNumbers(JalaliDate::date("Y/m/d - H:i:s" ,$data->date)) ?>
	</div>
	<div class="message-content">
		<p><?= $data->text ?></p>
		<?php
		if($data->attachment && is_file(Yii::getPathOfAlias("webroot").DIRECTORY_SEPARATOR.'uploads/tickets/'.$data->attachment)):
		?>
			<a class="attachment-link" target="_blank" href="<?= Yii::app()->baseUrl.'/uploads/tickets/'.$data->attachment ?>" title="فایل ضمیمه">
				فایل ضمیمه
			</a>
		<?php
		endif;
		?>
	</div>
</div>
