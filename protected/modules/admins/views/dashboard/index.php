<?php
/* @var $this DashboardController*/
/* @var $devIDRequests CActiveDataProvider*/
/* @var $newestPrograms CActiveDataProvider*/
/* @var $newestPublishers CActiveDataProvider*/
/* @var $newestPackages CActiveDataProvider*/
/* @var $newestFinanceInfo CActiveDataProvider*/
/* @var $comments Comment*/
/* @var $tickets []*/
?>
<?php if(Yii::app()->user->hasFlash('success')):?>
    <div class="alert alert-success fade in">
        <button class="close close-sm" type="button" data-dismiss="alert"><i class="icon-remove"></i></button>
        <?php echo Yii::app()->user->getFlash('success');?>
    </div>
<?php elseif(Yii::app()->user->hasFlash('failed')):?>
    <div class="alert alert-danger fade in">
        <button class="close close-sm" type="button" data-dismiss="alert"><i class="icon-remove"></i></button>
        <?php echo Yii::app()->user->getFlash('failed');?>
    </div>
<?php endif;?>
<?
if(Yii::app()->user->isGuest):
 $this->redirect(array('/admins/login'));
else:
    if(Yii::app()->user->roles == 'superAdmin' || Yii::app()->user->roles == 'admin'):
    ?>
    <div class="row">
        <div class="panel panel-default col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel-heading">جدیدترین کتاب ها</div>
            <div class="panel-body">
                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'newest-books-grid',
                    'dataProvider'=>$newestPrograms,
                    'itemsCssClass' => 'table',
                    'columns'=>array(
                        'title',
                        'publisher_id'=>array(
                            'name'=>'publisher_id',
                            'value'=>'(is_null($data->publisher_id) or empty($data->publisher_id))?$data->publisher_name:$data->publisher->userDetails->publisher_id'
                        ),
                        'category_id'=>array(
                            'name'=>'category_id',
                            'value'=>'$data->category->title'
                        ),
                        'lastPackage.price'=>array(
                            'name'=>'lastPackage.price',
                            'value'=>'number_format($data->lastPackage->price)." تومان"'
                        ),
                        array(
                            'class'=>'CButtonColumn',
                            'template' => '{view}{delete}',
                            'buttons'=>array(
                                'view'=>array(
                                    'label'=>'مشاهده کتاب',
                                    'url'=>'Yii::app()->createUrl("/manageBooks/baseManage/view/".$data->id)',
                                ),
                                'delete'=>array(
                                    'url'=>'CHtml::normalizeUrl(array(\'/manageBooks/baseManage/delete/\'.$data->id))'
                                ),
                            ),
                        ),
                    ),
                ));?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="panel panel-default col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="panel-heading">
                درخواست های تغییر شناسه ناشر
            </div>
            <div class="panel-body">
                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'dev-id-requests-grid',
                    'dataProvider'=>$devIDRequests,
                    'itemsCssClass' => 'table',
                    'columns'=>array(
                        'user_id'=>array(
                            'name'=>'user_id',
                            'value'=>'CHtml::link($data->user->userDetails->fa_name, Yii::app()->createUrl("/users/".$data->user->id))',
                            'type'=>'raw'
                        ),
                        'requested_id',
                        array(
                            'class'=>'CButtonColumn',
                            'template' => '{confirm}{delete}',
                            'buttons'=>array(
                                'confirm'=>array(
                                    'label'=>'تایید کردن',
                                    'url'=>"CHtml::normalizeUrl(array('/users/manage/confirmDevID', 'id'=>\$data->user_id))",
                                    'imageUrl'=>Yii::app()->theme->baseUrl.'/img/confirm.png',
                                ),
                                'delete'=>array(
                                    'url'=>'CHtml::normalizeUrl(array(\'/users/manage/deleteDevID\', \'id\'=>$data->user_id))'
                                ),
                            ),
                        ),
                    ),
                ));?>
            </div>
        </div>
        <div class="panel panel-default col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="panel-heading">
                اطلاعات ناشران<small>(تایید نشده)</small>
            </div>
            <div class="panel-body">
                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'newest-publishers-grid',
                    'dataProvider'=>$newestPublishers,
                    'itemsCssClass' => 'table',
                    'columns'=>array(
                        'email'=>array(
                            'name'=>'email',
                            'value'=>'CHtml::link($data->user->email, Yii::app()->createUrl("/users/".$data->user_id))',
                            'type'=>'raw'
                        ),
                        'fa_name',
                        array(
                            'class'=>'CButtonColumn',
                            'template' => '{view}{confirm}{refused}',
                            'buttons'=>array(
                                'confirm'=>array(
                                    'label'=>'تایید کردن',
                                    'url'=>"CHtml::normalizeUrl(array('/users/manage/confirmPublisher', 'id'=>\$data->user_id))",
                                    'imageUrl'=>Yii::app()->theme->baseUrl.'/img/confirm.png',
                                ),
                                'refused'=>array(
                                    'label'=>'رد کردن',
                                    'url'=>'CHtml::normalizeUrl(array(\'/users/manage/refusePublisher\', \'id\'=>$data->user_id))',
                                    'imageUrl'=>Yii::app()->theme->baseUrl.'/img/refused.png',
                                ),
                                'view'=>array(
                                    'url'=>'CHtml::normalizeUrl(array("/users/".$data->user_id))',
                                ),
                            ),
                        ),
                    ),
                ));?>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix">
            <div class="panel-heading">اطلاعات مالی ناشران<small>(تایید نشده)</small></div>
            <div class="panel-body">
                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'newest-finance-info-grid',
                    'dataProvider'=>$newestFinanceInfo,
                    'itemsCssClass' => 'table',
                    'columns'=>array(
                        array(
                            'name'=>'fa_name',
                            'value'=>'CHtml::link($data->fa_name, Yii::app()->createUrl("/users/".$data->user_id))',
                            'type'=>'raw'
                        ),
                        array(
                            'name' => 'account_type',
                            'value' => '$data->typeLabels[$data->account_type]',
                        ),
                        'account_owner_name',
                        array(
                            'name'=>'account_owner_family',
                            'header'=>'نام خانوادگی صاحب حساب / نوع حقوقی',
                        ),
                        'account_number',
                        'bank_name',
                        array(
                            'name'=>'iban',
                            'value'=>'"IR".$data->iban'
                        ),
                        array(
                            'name'=>'financial_info_status',
                            'value'=>function($data){
                                $form=CHtml::dropDownList("financial_info_status", "pending", $data->detailsStatusLabels, array("class"=>"change-finance-status", "data-id"=>$data->user_id));
                                $form.=CHtml::button("ثبت", array("class"=>"btn btn-success finance-confirm-status", 'style'=>'margin-right:5px;'));
                                return $form;
                            },
                            'type'=>'raw'
                        ),
                    ),
                ));?>
                <?php Yii::app()->clientScript->registerScript('changeFinanceStatus', "
                    $('body').on('click', '.finance-confirm-status', function(){
                        var el = $(this),
                            tr = el.parents('tr');
                        $.ajax({
                            url:'".$this->createUrl('/users/manage/changeFinanceStatus')."',
                            type:'POST',
                            dataType:'JSON',
                            data:{user_id:tr.find('.change-finance-status').data('id'), value:tr.find('.change-finance-status').val()},
                            success:function(data){
                                if(data.status)
                                    $.fn.yiiGridView.update('newest-finance-info-grid');
                                else
                                    alert('در انجام عملیات خطایی رخ داده است لطفا مجددا تلاش کنید.');
                            }
                        });
                    });
                ");?>
            </div>
        </div>
    </div>

    <?php endif;?>

    <div class="row">
        <div class="panel col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel-heading">
                نظرات کاربران
            </div>
            <div class="panel-body">
                <? $this->renderPartial('comments.views.comment._comments_list_widget',array('model' => $comments)) ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="panel <?= $tickets['new']?'panel-success':'panel-default' ?> col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="panel-heading">
                پشتیبانی
            </div>
            <div class="panel-body">
                <p>
                    تیکت های جدید: <?= $tickets['new'] ?>
                </p>
                <p>
                    <?= CHtml::link('لیست تیکت ها',$this->createUrl('/tickets/manage/admin'),array('class'=>'btn btn-success')) ?>
                </p>
            </div>
        </div>
        <div class="panel panel-default col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="panel-heading">
                آمار بازدیدکنندگان
            </div>
            <div class="panel-body">
                <p>
                    افراد آنلاین : <?php echo Yii::app()->userCounter->getOnline(); ?><br />
                    بازدید امروز : <?php echo Yii::app()->userCounter->getToday(); ?><br />
                    بازدید دیروز : <?php echo Yii::app()->userCounter->getYesterday(); ?><br />
                    تعداد کل بازدید ها : <?php echo Yii::app()->userCounter->getTotal(); ?><br />
                    بیشترین بازدید : <?php echo Yii::app()->userCounter->getMaximal(); ?><br />
                </p>
            </div>
        </div>
    </div>
<?php
endif;
