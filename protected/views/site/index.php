<?
/* @var $this SiteController */
/* @var $expertises Expertises[] */
/* @var $slideShow Slideshow[] */

$cs = Yii::app()->clientScript;
$baseUrl = Yii::app()->theme->baseUrl;
$cs->registerScriptFile($baseUrl . '/js/persian-date.js');
$cs->registerScriptFile($baseUrl . '/js/persian-datepicker-0.4.5.min.js');
?>


<?php if ($slideShow && !isset($_GET['debug'])): ?>
    <?php
    $cs->registerCssFile($baseUrl . '/assets/slider/css/layerslider.css');
    $cs->registerCssFile($baseUrl . '/assets/slider/layerslider_skins/fullwidth/skin.css'); ?>
    <div class="big-image-container">
        <div class="slideshow" id="slider">
            <?php foreach ($slideShow as $item):
                $this->renderPartial('//site/_slide_show_item_view', array('data' => $item));
            endforeach;
            $skinPath = $baseUrl . '/assets/slider/layerslider_skins/'; ?>
        </div>

        <div class="black-gradient hidden-xs"></div>
        <div class="content">
            <div class="container">
                <h2>ما کار را برای شما آسوده کرده ایم</h2>
                <h3 class="hidden-xs">به راحتی می توانید از پزشک خود نوبت ویزیت دریافت کنید</h3>
                <a href="#reservation" class="anchor-link">نوبت دهی</a>
            </div>
        </div>

        <script src="<?= $baseUrl . '/assets/slider/js/slider-combine-min.js' ?>"></script>
        <script>var sh;
            $(window).width() > 768 ? 0 == $("#slider").find(".ls-bg.hidden-xs").length ? $("#slider").addClass("hidden") : (sh = 600 * $(window).width() / 1920 - 5,
                $("#slider").css({height: sh})) : 0 == $("#slider").find(".ls-bg.hidden-lg.hidden-md.hidden-sm").length && $("#slider").addClass("hidden");
            $(window).resize(function () {
                var d;
                $(window).width() > 768 ? 0 == $("#slider").find(".ls-bg.hidden-xs").length ? $("#slider").addClass("hidden") : (d = 600 * $(window).width() / 1920 - 5, $("#slider").css({height: d}).removeClass("hidden")) : 0 == $("#slider").find(".ls-bg.hidden-lg.hidden-md.hidden-sm").length ? $("#slider").addClass("hidden") : $("#slider").removeClass("hidden")
            });
            $("#slider").layerSlider({
                width: '100%',
                height: 'auto',
                startInViewport: !1,
                responsive: !0,
                responsiveUnder: 768,
                forceLoopNum: !1,
                autoPlayVideos: !1,
                skinsPath: "<?= $skinPath ?>",
                skin: "fullwidth",
                navPrevNext: !1,
                navStartStop: !1,
                pauseOnHover: !1,
                thumbnailNavigation: "hover"
            });</script>
    </div>
<?php else: ?>

    <div class="big-image-container">
        <img src="<?php echo Yii::app()->baseUrl . "/uploads/slide.jpg"; ?>">
        <div class="black-gradient hidden-xs"></div>
        <div class="content">
            <div class="container">
                <h2>ما کار را برای شما آسوده کرده ایم</h2>
                <h3 class="hidden-xs">به راحتی می توانید از پزشک خود نوبت ویزیت دریافت کنید</h3>
                <a href="#reservation" class="anchor-link">شروع کنید</a>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="steps-guide">
    <div class="container">
        <div class="item">
            <div class="content">
                <div class="num">
                    <div class="bg"></div>
                    <span>۱</span>
                </div>
                <div class="text">
                    <h4>پزشک خود را بیابید</h4>
                    <span>پزشک مورد نظر خود را با مشخص کردن تخصص، بیمارستان، درمانگاه و... پیدا کنید.</span>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="content">
                <div class="num">
                    <div class="bg"></div>
                    <span>۲</span>
                </div>
                <div class="text">
                    <h4>اطلاعات خود را وارد کنید</h4>
                    <span>فرم اطلاعات را جهت دریافت نوبت با مشخصات صحیح پر کنید.</span>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="content">
                <div class="num">
                    <div class="bg"></div>
                    <span>۳</span>
                </div>
                <div class="text">
                    <h4>حق ویزیت را پرداخت کنید</h4>
                    <span>با استفاده از کلیه کارت های عضو شتاب میتوانید حق ویزیت را پرداخت نمایید.</span>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="content">
                <div class="num">
                    <div class="bg"></div>
                    <span>۴</span>
                </div>
                <div class="text">
                    <h4>اطلاعات نوبت خود را دریافت کنید</h4>
                    <span>اطلاعات نوبت رزرو شده از طریق پست الکترونیکی برای شما ارسال خواهد شد.</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="reservation-box" id="reservation">
    <div class="container">
        <h2>همین حالا شروع کنید<small>جهت رزرو نوبت، یکی از تخصص های زیر را انتخاب کنید</small></h2>
        <div class="expertise-items">
            <?php
            foreach ($expertises as $expertise):
                if (!$expertise->icon || !file_exists(Yii::getPathOfAlias('webroot') . '/uploads/expertises/' . $expertise->icon))
                    break;
                ?>
                <div class="expertise-item">
                    <a href="<?php echo $this->createUrl('/reservation/search/' . $expertise->id); ?>">
                        <span style="background-image: url('<?= Yii::app()->baseUrl . "/uploads/expertises/" . $expertise->icon ?>')"></span>
                        <h3><?php echo $expertise->title; ?></h3>
                    </a>
                </div>
            <?php
            endforeach;
            ?>
            <!--            --><?php //for($i=0;$i<3;$i++):?>
            <!--                <div class="row">-->
            <!--                    --><?php //for($j=0;$j<6;$j++):?>
            <!--                        <div class="expertise-item">-->
            <!--                            <a href="-->
            <?php //echo $this->createUrl('/reservation/search/'.$expertises[$i*6+$j]->id);?><!--">-->
            <!--                                <span style="background-image: "></span>-->
            <!--                                <h3>--><?php //echo $expertises[$i*6+$j]->title;?><!--</h3>-->
            <!--                            </a>-->
            <!--                        </div>-->
            <!--                    --><?php //endfor;?>
            <!--                </div>-->
            <!--            --><?php //endfor;?>
        </div>
    </div>
</div>

<div class="statistics">
    <div class="container">
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <h2 class="counter-up" data-count="25">۰</h2>
            <h4>درمانگاه</h4>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <h2 class="counter-up" data-count="57">۰</h2>
            <h4>پزشک</h4>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <h2 class="counter-up" data-count="210">۰</h2>
            <h4>کاربر فعال</h4>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <h2 class="counter-up" data-count="120">۰</h2>
            <h4>نوبت رزرو شده</h4>
        </div>
    </div>
</div>

<div class="about-box">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                <h4>ویزیت 365 چیست؟</h4>
                <div class="text">
                    لـورم ایپسوم متـن ساخـتگی با تولیـد سـادگی نامفـهوم از صنـعت چـاپ و با استـفاده از طراحان گرافیک
                    است. چاپگرها و متون بـلکه روزنـامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی
                    تکـنولوژی مورد نیاز و کاربـردهـای متنـوع با هـدف بهبود ابزارهای کاربردی می باشد. کتابهای زیادی در
                    شصت و سه درصـد گـذشته، حـال و آیـنده شـناخت فراوان جامعه و متخصـصان را مـی طلـبد تا با نـرم افزارهـا
                    شناخت بیـشتری را بـرای طراحان رایانـه ای علـی الخـصوص طراحـان خلاقی و فرهنگ پیشرو در زبان فارسی
                    ایجاد کرد. در این صورت می توان امید داشت که تـمام و دشـواری موجود در ارائه راهکارها و شرایط سخت تایپ
                    به پایان رسد وزمان مورد نیاز شامل حروفـچینی دستـاوردهـای اصـلی و جوابـگوی سوالات پیوسته اهل دنیای
                    موجود طراحی اساسا مورد استفاده قرار گیرد.
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <h4>با ما در ارتباط باشید</h4>
                <div class="text">
                    <?= SiteSetting::get('address') ?>
                    <br>
                    کد پستی&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= SiteSetting::get('postal_code') ?><br>
                    تلفن تماس&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= SiteSetting::get('tel') ?><br>
                    شماره فکس&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= SiteSetting::get('fax') ?>
                </div>
            </div>
        </div>
    </div>
</div>