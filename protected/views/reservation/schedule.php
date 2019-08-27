<?
/* @var $this ReservationController */
/* @var $days array */
/* @var $doctor Users */
/* @var $clinic Clinics */
?>
<div class="inner-page">
    <?php $this->renderPartial('_steps', array('active'=>2));?>

    <div class="page-help">
        <div class="container">
            <h4>انتخاب تاریخ و زمان</h4>
            <ul>
                <li>لطفا یک بازه زمانی مشخص کنید و سپس تاریخ و زمان مد نظر خود را انتخاب کنید.</li>
            </ul>
        </div>
    </div>

    <div class="container">
        <?php echo CHtml::beginForm('', 'post', array('class'=>'select-date-form row'));?>
            <?php $this->renderPartial('//partial-views/_flashMessage');?>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <?php echo CHtml::label('از', 'from');?>
                <?php $this->widget('ext.PDatePicker.PDatePicker', array(
                    'id'=>'from',
                    'value' => $from,
                    'options'=>array(
                        'maxDate' => (strtotime(date('Y/m/d 00:00',time()))-1)*1000,
                        'format'=>'DD MMMM YYYY',
                        'onShow'=>"js:function(){ $('.datepicker-plot-area').width($('#from').parent().width()) }"
                    ),
                ));?>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <?php echo CHtml::label('تا', 'to');?>
                <?php $this->widget('ext.PDatePicker.PDatePicker', array(
                    'id'=>'to',
                    'value' => $to,
                    'options'=>array(
                        'maxDate' => (strtotime(date('Y/m/d 00:00',time()))-1)*1000,
                        'format'=>'DD MMMM YYYY',
                        'onShow'=>"js:function(){ $('.datepicker-plot-area').width($('#to').parent().width()) }"
                    ),
                ));?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <?php echo CHtml::submitButton('جستجو', array('class'=>'btn-red'));?>
                <?php echo CHtml::link('بازگشت به مرحله قبل', $this->createUrl('search/'.Yii::app()->user->reservation['expertiseID']), array('class'=>'btn-black'));?>
            </div>
        <?php echo CHtml::endForm();?>

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
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
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
        <?php endif;?>
    </div>
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
                <div><?php echo CHtml::button('ثبت', array('class'=>'btn btn-green','id'=>'submit-select-time','style'=>'margin-top:20px;'))?></div>
            </div>
        </div>
    </div>
</div>

<?php Yii::app()->clientScript->registerScript('inline-scripts', "
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
");?>