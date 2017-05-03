<?php
/* @var $this TicketsManageController */
/* @var $model Tickets */
?>
<div class="white-form">
    <div class="pull-left">
        <?php if(!Yii::app()->user->isGuest && Yii::app()->user->type == 'admin'):?>
            <div class="form-group text-center">
                <a class="btn btn-info" href="<?= $this->createUrl('/tickets/manage/admin') ?>" >لیست تیکت ها</a>
            </div>
        <?php endif;?>
        <?php if(!Yii::app()->user->isGuest && Yii::app()->user->type == 'user'):?>
            <div class="form-group text-center">
                <a class="btn btn-info" href="<?= $this->createUrl('/tickets/manage/') ?>" >لیست تیکت ها</a>
            </div>
        <?php endif;?>
        <?php if($model->status != 'close'):?>
            <div class="form-group text-center">
                <a class="btn btn-danger" href="<?= $this->createUrl('/tickets/manage/closeTicket/'.$model->code) ?>" >بستن تیکت</a>
            </div>
        <?php endif;?>
        <?php if(!Yii::app()->user->isGuest && Yii::app()->user->type != 'user'):?>
            <?php if($model->status != 'pending'):?>
                <div class="form-group text-center">
                    <a class="btn btn-warning" href="<?= $this->createUrl('/tickets/manage/pendingTicket/'.$model->code) ?>" >در حال بررسی</a>
                </div>
            <?php endif;?>
            <?php if($model->status == 'pending' || $model->status == 'close' || $model->status == 'waiting'):?>
                <div class="form-group text-center">
                    <a class="btn btn-info" href="<?= $this->createUrl('/tickets/manage/openTicket/'.$model->code) ?>" >باز</a>
                </div>
            <?php endif;?>
        <?php endif;?>
    </div>

    <h3>تیکت شماره #<?php echo $model->code; ?></h3>
    <p class="description">با ارسال تیکت می توانید با بخش پشتیبانی در ارتباط باشید.</p>

    <?php if($model->status == 'close'):
        $this->renderPartial('//partial-views/_alertMessage',array(
            'type' => 'danger',
            'message' => 'تیکت مورد نظر بسته شده و امکان ارسال پیام وجود ندارد.'
        ));
    elseif($model->status == 'pending'):
        $this->renderPartial('//partial-views/_alertMessage',array(
            'type' => 'warning',
            'message' => 'پیام شما توسط کارشناسان در حال بررسی می باشد.'
        ));
    endif; ?>
    <?php if(!Yii::app()->user->isGuest && Yii::app()->user->type == 'admin')
        $this->renderPartial('//partial-views/_flashMessage');
    elseif(!Yii::app()->user->isGuest && Yii::app()->user->type == 'user')
        $this->renderPartial('//partial-views/_flashMessage'); ?>

    <div class="form-group">
        <label>موضوع:</label>
        <span><?= $model->subject ?></span>
    </div>
    <div class="form-group">
        <label>تاریخ ایجاد:</label>
        <span><?= Controller::parseNumbers(JalaliDate::date("Y/m/d H:i:s" ,$model->date)) ?></span>
    </div>
    <?php if($model->status != 'close'):
        $this->renderPartial('tickets.views.messages._form',array(
            'model' => new TicketMessages(),
            'ticket' => $model
        ));
    endif; ?>
</div>

<div class="transparent-form">
    <h3>لیست پیام ها</h3>
    <p class="description">لیست پیام های تیکت شماره #<?php echo $model->code; ?></p>

    <?php $this->widget('zii.widgets.CListView', array(
        'id' => 'message-list',
        'dataProvider' => new CArrayDataProvider($model->messages),
        'itemView' => '_messageView',
        'template' => '{items}'
    )); ?>
</div>
