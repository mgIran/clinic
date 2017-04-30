<?php
/* @var $this UsersPublicController */
/* @var $clinics CArrayDataProvider */
?>
<div class="transparent-form">
    <h3>لیست درمانگاه ها</h3>
    <p class="description">درمانگاه هایی که در آنها عضو هستید.</p>
    <?php $this->renderPartial('//partial-views/_flashMessage');?>
    <table class="table">
        <thead>
        <tr>
            <th>عنوان</th>
            <th>استان</th>
            <th>شهر</th>
            <th>آدرس</th>
            <th></th>
        </tr>
        </thead>
            <?php if(!$clinics->totalItemCount):?>
                <tbody>
                <tr>
                    <td colspan="5" class="text-center">نتیجه ای یافت نشد.</td>
                </tr>
                </tbody>
            <?php else:?>
                <?php $this->widget('zii.widgets.CListView', array(
                    'dataProvider'=>$clinics,
                    'itemView'=>'_clinics_list',
                    'itemsTagName'=>'tbody',
                    'template'=>'{items}'
                ));?>
            <?php endif;?>
    </table>
    <?php $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'expertises-grid',
        'dataProvider'=>$clinics,
        'itemsCssClass'=>'table',
        'columns'=>array(
            'clinic_name',
            array(
                'class'=>'CButtonColumn',
                'template'=>'{update} {delete}'
            ),
        ),
    )); ?>
</div>