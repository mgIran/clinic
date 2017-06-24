<?php
/* @var $this ClinicsDoctorController */
/* @var $model Visits */
/* @var $form CActiveForm */
/* @var $active int */
?>
<h3>افزودن نوبت جدید</h3>
<p class="description">لطفا جهت افزودن نوبت جدید مراحل زیر را دنبال کنید.</p>

<?php
$this->renderPartial('_steps', array('active' => $active));
?>

<?
switch($active):
    case 1:
        echo CHtml::beginForm(array('/clinics/secretary/search'),'post',array('id'=>'exp-form'));
        ?>
        <h5>لطفا تخصص موردنظر را انتخاب کنید</h5>
        <div class="form-group col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <?php
            echo CHtml::dropDownList('id', false, CHtml::listData($expertises,'id','title'), array('id'=>'exp-list' ,'class' => 'selectpicker'));
            ?>
        </div>
        <div class="form-group col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <?php
            echo CHtml::submitButton('ادامه', array('class' => 'btn-success btn-sm'));
            ?>
        </div>
        <?php
        echo CHtml::endForm();
        Yii::app()->clientScript->registerScript('submit', '
            $("body").on("submit", "#exp-form", function(e){
                e.preventDefault();
                var $url = baseUrl+"/clinics/secretary/search/"+$("#exp-list").val();
                window.location = $url;
            });
        ');
        break;
    case 2:
        ?>
        <h5>لطفا پزشک موردنظر را انتخاب کنید</h5>
        <div class="table-container container-fluid">
            <div class="table-responsive row">
                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'doctors-list',
                    'dataProvider'=>$doctors->getDoctorsByExp(),
                    'itemsCssClass'=>'table table-hover',
                    'template'=>'{items}{pager}',
                    'columns'=>array(
                        array(
                            'header'=>'پزشک',
                            'value'=>function($data) {
                                /* @var $data ClinicPersonnels */
                                return '<img src="' . $data->user->userDetails->getAvatar() . '" class="img-circle">' . $data->user->userDetails->getShowName();
                            },
                            'type'=>'raw'
                        ),
                        array(
                            'header'=>'تخصص',
                            'value'=>'$data->user->expertises[0]->title'
                        ),
                        array(
                            'header'=>'روزهای حضور در مطب',
                            'value'=>function($data){
                                /* @var $data ClinicPersonnels */
                                $html=[];
                                foreach($data->user->doctorSchedules as $schedule)
                                    $html[] = DoctorSchedules::$weekDays[$schedule->week_day];
                                return implode('، ', $html);
                            }
                        ),
                        array(
                            'header'=>'عملیات',
                            'htmlOptions'=>array('class'=>'text-center'),
                            'headerHtmlOptions'=>array('class'=>'text-center'),
                            'value'=>function($data) {
                                /* @var $data ClinicPersonnels */
                                $html = CHtml::beginForm(array('/clinics/secretary/selectDoctor'));
                                $html .= CHtml::submitButton('رزرو نوبت', array('class' => 'btn-info btn-sm'));
                                $html .= CHtml::hiddenField('Reservation[doctor_id]', $data->user_id);
                                $html .= CHtml::hiddenField('Reservation[clinic_id]', $data->clinic_id);
                                $html .= CHtml::hiddenField('Reservation[expertise_id]', $data->user->expertises[0]->id);
                                $html .= CHtml::endForm();
                                return $html;
                            },
                            'type'=>'raw'
                        ),
                    ),
                ));?>
            </div>
        </div>
        <?php
        break;
    case 3:
        ?>
        <div class="container-fluid">
            <?php if(isset($days)):?>
            <div class="calendar-container">
                <div class="doctor-info">
                    <label>نام پزشک</label>
                    <span><?php echo $doctor->userDetails->getShowName();?></span>
                    <label>نام مطب</label>
                    <span><?php echo $clinic->clinic_name;?></span>
                    <label>تلفن مطب</label>
                    <span><?php echo $clinic->phone;?></span>
                </div>
                <div class="calendar">
                    <div class="row">
                        <?php
                        $months = array(
                            'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'
                        );
                        $monthDiff=0;
                        if(JalaliDate::date('Y', $to, false) != JalaliDate::date('Y', $from, false))
                            $monthDiff=((int)JalaliDate::date('m', $to, false)+12)-JalaliDate::date('m', $from, false);
                        else
                            $monthDiff=JalaliDate::date('m', $to, false)-JalaliDate::date('m', $from, false);
                        $currentMonth=JalaliDate::date('m', $from, false);
                        for($i=0;$i<=$monthDiff;$i++):
                        $monthDaysCount=30;
                        $currentYear=JalaliDate::date('Y', $from+($i*30*24*60*60), false);
                        if($currentMonth <= 6)
                            $monthDaysCount=31;
                        elseif($currentMonth==12 and !JalaliDate::date('L', $from, false))
                            $monthDaysCount=29;
                        ?>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h4><?php echo $months[((int)$currentMonth -1)%13] ?></h4>
                            <div class="week-days-name">
                                <div class="day">شنبه</div>
                                <div class="day">یکشنبه</div>
                                <div class="day">دوشنبه</div>
                                <div class="day">سه شنبه</div>
                                <div class="day">چهار شنبه</div>
                                <div class="day">پنج شنبه</div>
                                <div class="day">جمعه</div>
                            </div>
                            <div class="days">
                                <?php for($j=1;$j<=$monthDaysCount;$j++):
                                $currentDay=JalaliDate::mktime(0,0,0,$currentMonth,$j,$currentYear);
                                $isHoliday = Holidays::model()->findByAttributes(['date' => $currentDay]);
                                $fri = (int)JalaliDate::date('N', $currentDay, false) == 7?:false;
                                if($j == 1){
                                    $diff = JalaliDate::date('w', JalaliDate::mktime(0,0,0,$currentMonth,1,$currentYear),false);
                                    for($k = 0;$k < $diff;$k++){
                                        echo '<div class="day disabled"></div>';
                                    }
                                }

                                if($currentDay < strtotime(date('Y/m/d 00:00', $from)) or $currentDay > strtotime(date('Y/m/d 00:00', $to)) || $isHoliday || $fri):?>
                                <div class="day disabled<?= $isHoliday || $fri?" holiday":""?>">
                                    <?php else:?>
                                    <div class="day<?php echo key_exists($currentDay, $days)?' active':'';?>">
                                        <?php endif;?>
                                        <?php if(key_exists($currentDay, $days)):?>
                                            <?php echo $this->parseNumbers($j);?>
                                            <?php if(isset($days[$currentDay]['AM'])):?><small><?php echo $this->parseNumbers($days[$currentDay]['AM']);?></small><?php endif;?>
                                            <?php if(isset($days[$currentDay]['PM'])):?><small><?php echo $this->parseNumbers($days[$currentDay]['PM']);?></small><?php endif;?>
                                            <?php if(isset($days[$currentDay]['AM']) and isset($days[$currentDay]['PM'])):?>
                                                <a href="#select-time-modal" data-toggle="modal" class="select-time-link" data-date="<?php echo $currentDay;?>" data-am="<?php echo $days[$currentDay]['AM'];?>" data-pm="<?php echo $days[$currentDay]['PM'];?>"></a>
                                            <?php else:?>
                                                <a href="<?php echo $this->createUrl('selectTime', array('d'=>$currentDay, 't'=>(isset($days[$currentDay]['AM']))?'am':'pm'));?>"></a>
                                            <?php endif;?>
                                        <?php else:?>
                                            <?php echo $this->parseNumbers($j);?>
                                            <?php
                                            if($isHoliday) echo '<small>'.$isHoliday->title.'</small>';
                                            ?>
                                        <?php endif;?>
                                    </div>
                                    <?php endfor;?>
                                </div>
                            </div>
                            <?php $currentMonth = ($currentMonth == 12) ? 1 : (int)$currentMonth + 1;?>
                            <?php endfor;?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif;?>
        </div>
        <div id="select-time-modal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="border: none">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>لطفا زمان مورد نظر خود را مشخص کنید:</p>
                        <?php echo CHtml::radioButtonList('t', 'am', array('am'=>'صبح', 'pm'=>'بعد از ظهر'));?>
                        <?php echo CHtml::hiddenField('d', '');?>
                        <div><?php echo CHtml::button('ثبت', array('class'=>'btn btn-success','id'=>'submit-select-time','style'=>'margin-top:20px;'))?></div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        Yii::app()->clientScript->registerScript('inline-scripts', "
            $('.select-time-link').click(function(){
                $('#select-time-modal input#d[type=\"hidden\"]').val($(this).data('date'));
                $('#select-time-modal label[for=\"t_0\"]').text('صبح ('+$(this).data('am')+')');
                $('#select-time-modal label[for=\"t_1\"]').text('بعد از ظهر ('+$(this).data('pm')+')');
            });
            $('#submit-select-time').click(function(){
                window.location.href = '".$this->createUrl('selectTime')."?d='+$('#select-time-modal input#d[type=\"hidden\"]').val()+'&t='+$('#select-time-modal input[type=\"radio\"][name=\"t\"]:checked').val();
            });
            $('#PatientInfo_national_code').focusout(function(){
                if($(this).val() != ''){
                    if($(this).val().length < 10)
                        $('#national-code-error').text('کد ملی باید 10 رقم باشد.').removeClass('hidden');
                    else{
                        var pattern = new RegExp(/\\D/);
                        if(pattern.test($(this).val()))
                            $('#national-code-error').text('کد ملی باید عددی باشد.').removeClass('hidden');
                        else{
                            $('#national-code-error').text('').addClass('hidden');
                            $.ajax({
                                url: '".$this->createUrl('/users/public/getUserByCode')."',
                                type: 'POST',
                                dataType: 'JSON',
                                data: {code: $(this).val()},
                                success:function(data){
                                    if(data.status){
                                        $('#PatientInfo_name').val(data.name).prop('disabled', true);
                                        $('#PatientInfo_mobile').val(data.mobile).prop('disabled', true);
                                        $('#PatientInfo_email').val(data.email).prop('disabled', true);
                                    }
                                },
                                error:function(){
                                    alert('در برقراری ارتباط با سرور خطایی رخ داده است.');
                                }
                            });
                        }
                    }
                }else
                    $('#national-code-error').text('کد ملی نمی تواند خالی باشد.').removeClass('hidden');
            });
        ");
        break;
    case 4:
        ?>
        <div class="form-container">
            <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'users-form',
                'enableAjaxValidation'=>false,
                'enableClientValidation'=>true,
                'clientOptions'=>array(
                    'validateOnSubmit'=>true,
                ),
                'htmlOptions'=>array('class'=>'info-form'),
            )); ?>
            <div class="row" style="margin-bottom: 0;">
                <?php $this->renderPartial('//partial-views/_flashMessage');?>
                <?php echo $form->errorSummary($user); ?>
            </div>
            <div class="row">
                <?php echo $form->textField($user,'national_code',array('placeholder'=>'کد ملی *', 'maxlength'=>10)); ?>
                <?php echo $form->error($user,'national_code'); ?>
                <span class="errorMessage" id="national-code-error"></span>
                <div class="loading-container" id="national-code-loading">
                    <div class="spinner">
                        <div class="bounce3"></div>
                        <div class="bounce2"></div>
                        <div class="bounce1"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php echo $form->textField($user,'first_name',array('placeholder'=>'نام *')); ?>
                <?php echo $form->error($user,'first_name'); ?>
            </div>
            <div class="row">
                <?php echo $form->textField($user,'last_name',array('placeholder'=>'نام خانوادگی *')); ?>
                <?php echo $form->error($user,'last_name'); ?>
            </div>
            <div class="row">
                <?php echo $form->textField($user,'mobile',array('placeholder'=>'تلفن همراه *', 'maxlength'=>11)); ?>
                <?php echo $form->error($user,'mobile'); ?>
            </div>
            <div class="row">
                <?php echo $form->textField($user,'email',array('placeholder'=>'پست الکترونیکی')); ?>
                <?php echo $form->error($user,'email'); ?>
            </div>
            <div class="row">
                <input type="text" placeholder="کد امنیتی گوگل">
            </div>
            <div class="row">
                <small class="desc">در پایان فرایند رزرو نوبت، سیستم برای شما کد رهگیری در نظر می گیرد. لطفا هنگام مراجعه به درمانگاه، کد رهگیری را همراه داشته باشید.</small>
                <?php echo CHtml::submitButton('ثبت', array('class'=>'btn-success pull-left'));?>
            </div>
            <?php $this->endWidget();?>
        </div>
        <?php
        Yii::app()->clientScript->registerScript('load-user-info', "
            $('#Users_national_code').focusout(function(){
                if($(this).val() != ''){
                    if($(this).val().length == 10){
                        var pattern = new RegExp(/\\D/);
                        if(!pattern.test($(this).val())){
                            $('#national-code-error').text('').addClass('hidden');
                            $('#national-code-loading').show();
        
                            $.ajax({
                                url: '".$this->createUrl('/users/public/getUserByCode')."',
                                type: 'POST',
                                dataType: 'JSON',
                                data: {code: $(this).val()},
                                success:function(data){
                                    $('#national-code-loading').hide();
                                    if(data.status){
                                        if(data.first_name != '' && data.first_name != null){
                                            $('#Users_first_name').val(data.first_name).prop('disabled', true);
                                            $('<input>').attr({
                                                type: 'hidden',
                                                name: 'Users[first_name]',
                                                value: data.first_name
                                            }).appendTo('form#users-form');
                                        }
        
                                        if(data.last_name != '' && data.last_name != null){
                                            $('#Users_last_name').val(data.last_name).prop('disabled', true);
                                            $('<input>').attr({
                                                type: 'hidden',
                                                name: 'Users[last_name]',
                                                value: data.last_name
                                            }).appendTo('form#users-form');
                                        }
        
                                        if(data.mobile != '' && data.mobile != null){
                                            $('#Users_mobile').val(data.mobile).prop('disabled', true);
                                            $('<input>').attr({
                                                type: 'hidden',
                                                name: 'Users[mobile]',
                                                value: data.mobile
                                            }).appendTo('form#users-form');
                                        }
        
                                        if(data.email != '' && data.email != null){
                                            $('#Users_email').val(data.email).prop('disabled', true);
                                            $('<input>').attr({
                                                type: 'hidden',
                                                name: 'Users[email]',
                                                value: data.email
                                            }).appendTo('form#users-form');
                                        }
                                    }else{
                                        $('#Users_first_name').val('').prop('disabled', false);
                                        $('#Users_last_name').val('').prop('disabled', false);
                                        $('#Users_mobile').val('').prop('disabled', false);
                                        $('#Users_email').val('').prop('disabled', false);
                                    }
                                },
                                error:function(){
                                    $('#national-code-loading').hide();
                                    alert('در برقراری ارتباط با سرور خطایی رخ داده است.');
                                }
                            });
                        }
                    }
                }
            });
        ");
        break;
    case 5:
        ?>
        <div class="container-fluid">
            <div class="row">
                <?php
                if($personnel):
                    ?>
                    <div class="alert alert-info">
                        <b>ثبت توسط <?= $personnel->post_rel->name ?>:</b> <?= $personnel->user->userDetails->getShowName() ?><br>
                        <b>کمیسیون سایت:</b> رایگان
                    </div>
                    <?php
                endif;
                ?>
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
                    </div>
                </div>
                <?php
                if($model->status == Visits::STATUS_ACCEPTED && isset($transaction) && $transaction):
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
                <?php echo CHtml::beginForm();?>
                <?php if($model->status == Visits::STATUS_PENDING):?>
                    <?php if($commission != 0):?>
                        <h4>کمیسیون سایت: <span><?= Controller::parseNumbers(number_format($commission)) ?> <small>تومان</small></span></h4>
                        <?php echo CHtml::submitButton('پرداخت', array('class'=>'btn-red', 'name'=>'Payment'));?>
                    <?php else:?>
                        <span class="pull-right">جهت دریافت کد رهگیری لطفا اطلاعات فوق را تایید کنید.</span>
                        <?php echo CHtml::submitButton('تایید نهایی', array('class'=>'btn-success pull-left', 'name'=>'Confirm'));?>
                        <?php echo CHtml::link('بازگشت', $this->createUrl('info'), array('class'=>'btn-black'));?>
                    <?php endif;?>
                <?php else:?>
                    <h4>کد رهگیری: <span><?php echo $model->tracking_code;?></span></h4>
                <?php endif;?>
                <?php echo  CHtml::endForm();?>
            </div>
        </div>
        <?php
        break;
endswitch;
