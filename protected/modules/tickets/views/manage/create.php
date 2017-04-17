<?php
/* @var $this TicketsManageController */
/* @var $model Tickets */

?>
<div class="white-form">
	<h3>ارسال تیکت جدید</h3>
	<p class="description">لطفا فرم زیر را پر کنید.</p>

	<?php $this->renderPartial('_form', array('model'=>$model)); ?>
</div>
