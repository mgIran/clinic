<?php
/* @var $this TicketsManageController */
/* @var $model Tickets[] */

?>
<div class="transparent-form">
    <h3>پشتیبانی</h3>
    <p class="description">لیست تیکت هایی که ارسال یا دریافت کرده اید.</p>

    <div class="buttons">
        <a class="btn btn-success" href="<?= $this->createUrl('/tickets/manage/create') ?>">ارسال تیکت جدید</a>
    </div>

    <table class="table">
        <thead>
        <tr>
            <th>کد تیکت</th>
            <th>موضوع</th>
            <th>بخش</th>
            <th>وضعیت</th>
            <th>زمان</th>
        </tr>
        </thead>
        <tbody>
            <?php if(!$model):?>
                <tr>
                    <td colspan="5" class="text-center">نتیجه ای یافت نشد.</td>
                </tr>
            <?php else:?>
                <?php foreach($model as $key => $ticket):?>
                    <tr>
                        <td><a href="<?= $this->createUrl('/tickets/'.$ticket->code) ?>">
                            <?php echo $ticket->code ?></a></td>
                        <td><a href="<?= $this->createUrl('/tickets/'.$ticket->code) ?>">
                            <?php echo CHtml::encode($ticket->subject);?></a></td>
                        <td><?php echo CHtml::encode($ticket->department->title);?></td>
                        <td><?php echo CHtml::encode($ticket->statusLabels[$ticket->status]);?></td>
                        <td><?php echo JalaliDate::date('d F Y H:i', $ticket->date);?></td>
                    </tr>
                <?php endforeach;?>
            <?php endif;?>
            </tbody>
        </table>
</div>