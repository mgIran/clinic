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
                    'value' => isset($_POST['from_altField'])?$_POST['from_altField']:false,
                    'options'=>array(
                        'format'=>'DD MMMM YYYY',
                        'onShow'=>"js:function(){ $('.datepicker-plot-area').width($('#from').parent().width()) }"
                    ),
                ));?>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <?php echo CHtml::label('تا', 'to');?>
                <?php $this->widget('ext.PDatePicker.PDatePicker', array(
                    'id'=>'to',
                    'value' => isset($_POST['to_altField'])?$_POST['to_altField']:false,
                    'options'=>array(
                        'format'=>'DD MMMM YYYY',
                        'onShow'=>"js:function(){ $('.datepicker-plot-area').width($('#to').parent().width()) }"
                    ),
                ));?>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <?php echo CHtml::submitButton('جستجو', array('class'=>'btn-red'));?>
            </div>
        <?php echo CHtml::endForm();?>

        <?php if(isset($days)):?>
            <div class="calendar-container">
                <div class="doctor-info">
                    <label>نام پزشک</label>
                    <span><?php echo $doctor->userDetails->getShowName();?></span>
                    <label>نام بیمارستان/درمانگاه/مطب</label>
                    <span><?php echo $clinic->clinic_name;?></span>
                    <label>تلفن بیمارستان/درمانگاه/مطب</label>
                    <span><?php echo $clinic->phone;?></span>
                </div>
                <div class="calendar">
                    <div class="row">
                        <?php
                        $monthDiff=JalaliDate::date('m', $_POST['to_altField'], false)-JalaliDate::date('m', $_POST['from_altField'], false);
                        $currentMonth=JalaliDate::date('m', $_POST['from_altField'], false);
                        for($i=0;$i<=$monthDiff;$i++):
                            $monthDaysCount=30;
                            $currentYear=JalaliDate::date('Y', $_POST['from_altField']+($i*30*24*60*60), false);
                            if($currentMonth <= 6)
                                $monthDaysCount=31;
                            elseif($currentMonth==12 and !JalaliDate::date('L', $_POST['from_altField'], false))
                                $monthDaysCount=29;
                            ?>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <h4><?php echo JalaliDate::date('F', $_POST['from_altField']+($i*30*24*60*60))?></h4>
                                <div class="week-days-name">
                                    <div class="day"><?php echo JalaliDate::date('l', JalaliDate::mktime(0,0,0,$currentMonth,1,$currentYear));?></div>
                                    <div class="day"><?php echo JalaliDate::date('l', JalaliDate::mktime(0,0,0,$currentMonth,2,$currentYear));?></div>
                                    <div class="day"><?php echo JalaliDate::date('l', JalaliDate::mktime(0,0,0,$currentMonth,3,$currentYear));?></div>
                                    <div class="day"><?php echo JalaliDate::date('l', JalaliDate::mktime(0,0,0,$currentMonth,4,$currentYear));?></div>
                                    <div class="day"><?php echo JalaliDate::date('l', JalaliDate::mktime(0,0,0,$currentMonth,5,$currentYear));?></div>
                                    <div class="day"><?php echo JalaliDate::date('l', JalaliDate::mktime(0,0,0,$currentMonth,6,$currentYear));?></div>
                                    <div class="day"><?php echo JalaliDate::date('l', JalaliDate::mktime(0,0,0,$currentMonth,7,$currentYear));?></div>
                                </div>
                                <div class="days">
                                    <?php for($j=1;$j<=$monthDaysCount;$j++):
                                        $currentDay=JalaliDate::mktime(0,0,0,$currentMonth,$j,$currentYear);
                                        if($currentDay < strtotime(date('Y/m/d 00:00', $_POST['from_altField'])) or $currentDay > strtotime(date('Y/m/d 00:00', $_POST['to_altField']))):?>
                                            <div class="day disabled">
                                        <?php else:?>
                                            <div class="day">
                                        <?php endif;?>
                                            <?php if(key_exists($currentDay, $days)):?>
                                                <?php echo $j;?>
                                                <small><?php echo $days[$currentDay]['AM'];?></small>
                                                <a href="<?php echo $this->createUrl('selectDate', array('d'=>$currentDay));?>"></a>
                                            <?php else:?>
                                                <?php echo $j;?>
                                            <?php endif;?>
                                        </div>
                                    <?php endfor;?>
                                </div>
                            </div>
                            <?php $currentMonth++;?>
                        <?php endfor;?>
                        <?php foreach($days as $day=>$time):?>
                            <?php echo JalaliDate::date('Y/m/d H:i', $day);?><br>
<!--                            --><?php //echo $day;?><!--<br>-->
                        <?php endforeach;?>
                    </div>
                </div>
            </div>
        <?php endif;?>
    </div>
</div>
<?php Yii::app()->clientScript->registerScript('load-user-info', "
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