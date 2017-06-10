<?php
/* @var $this UsersPublicController */
/* @var $model Users */

$purifier=new CHtmlPurifier();
?>

<div class="inner-page">
    <div class="container profile">
        <div class="info">
            <img src="<?php echo $model->userDetails->getAvatar();?>">
            <div class="text">
                <h1><?php echo $model->userDetails->getShowName();?></h1>
                <small><span><?php echo $model->email;?></span><span>تاریخ عضویت: <?php echo JalaliDate::date('d F Y', $model->create_date);?></span></small>
                <?php if($model->expertises):?>
                    <div class="clinic-items">
                        <h4>تخصص ها</h4>
                        <?php foreach($model->expertises as $expertise):?>
                            <div class="clinic-item"><?php echo $expertise->title;?></div>
                        <?php endforeach;?>
                    </div>
                <?php endif;?>
                <?php if($model->clinic):?>
                    <div class="clinic-items">
                        <h4>اطلاعات مطب</h4>
                        <div class="clinic-item">
                            <label>عنوان مطب:</label><span><?php echo $model->clinic->clinic_name;?></span>
                        </div>
                        <?php if($model->clinic->town_id):?>
                            <div class="clinic-item">
                                <label>استان:</label><span><?php echo $model->clinic->town->name;?></span>
                            </div>
                        <?php endif;?>
                        <?php if($model->clinic->place_id):?>
                            <div class="clinic-item">
                                <label>شهر:</label><span><?php echo $model->clinic->place->name;?></span>
                            </div>
                        <?php endif;?>
                        <?php if($model->clinic->address):?>
                            <div class="clinic-item">
                                <label>آدرس:</label><span><?php echo $model->clinic->address.(($model->clinic->zip_code)?' - کد پستی: '.$model->clinic->zip_code:'');?></span>
                            </div>
                        <?php endif;?>
                        <?php if($model->clinic->phone):?>
                            <div class="clinic-item">
                                <label>شماره تلفن:</label><span><?php echo $model->clinic->phone;?></span>
                            </div>
                        <?php endif;?>
                        <?php if($model->clinic->fax):?>
                            <div class="clinic-item">
                                <label>شماره فکس:</label><span><?php echo $model->clinic->fax;?></span>
                            </div>
                        <?php endif;?>
                        <?php if($model->clinic->contracts):?>
                            <div class="clinic-item">
                                <label>طرف قرارداد ها:</label><span><?php echo implode(' ، ', CJSON::decode($model->clinic->contracts));?></span>
                            </div>
                        <?php endif;?>
                        <?php if($model->clinic->description):?>
                            <div class="clinic-item">
                                <label>توضیحات:</label><span><?php echo $purifier->purify($model->clinic->description);?></span>
                            </div>
                        <?php endif;?>
                    </div>
                <?php endif;?>
                <?php if($model->clinic and $model->userDetails->doctor_resume):?>
                    <hr>
                <?php endif;?>
                <?php if($model->userDetails->doctor_resume):?>
                    <div class="resume">
                        <h4>رزومه</h4>
                        <?php echo $purifier->purify($model->userDetails->doctor_resume);?>
                    </div>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>
