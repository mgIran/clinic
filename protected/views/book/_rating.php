<!--rate show-->
<?php
/* @var $model Books */
$hasUser = !Yii::app()->user->isGuest && Yii::app()->user->type == 'user'?true:false;
$hasRated = $hasUser && $model->userRated(Yii::app()->user->getId())?$model->userRated(Yii::app()->user->getId()):false;
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/jquery.rateyo.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.rateyo.js');
Yii::app()->clientScript->registerScript('rate-init'.time(),'
    function RatingInit($rate,$sendAjax){
        $rate = typeof $rate === "undefined"?null:$rate;
        $sendAjax = typeof $sendAjax === "undefined"?true:false;
        var $rateYo = $("#rateYo").rateYo({
            fullStar: true,
            ratedFill : "#FFB234",
            normalFill: "#e3e3e3",
            '.($hasRated?'readOnly: true,rating:'.$hasRated.',':'').'
            starSvg: "<svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" version=\"1.1\" viewBox=\"0 0 19.481 19.481\" enable-background=\"new 0 0 19.481 19.481\" width=\"512px\" height=\"512px\"><path d=\"m10.201,.758l2.478,5.865 6.344,.545c0.44,0.038 0.619,0.587 0.285,0.876l-4.812,4.169 1.442,6.202c0.1,0.431-0.367,0.77-0.745,0.541l-5.452-3.288-5.452,3.288c-0.379,0.228-0.845-0.111-0.745-0.541l1.442-6.202-4.813-4.17c-0.334-0.289-0.156-0.838 0.285-0.876l6.344-.545 2.478-5.864c0.172-0.408 0.749-0.408 0.921,0z\"/></svg>"
        });
        if($sendAjax)
            $rateYo.on("rateyo.set", function (e, data) {
                RateAjax(data.rating);
            });

        if($rate)
        {
            $rateYo.rateYo("option", "readOnly" , true);
            $rateYo.rateYo("option", "rating" , $rate);
        }
    };

    function RateAjax($rate){
        '.($hasUser?'
            $.ajax({
                url: "'.$this->createUrl('/book/rate').'",
                data:{ajax:true,book_id:'.$model->id.',rate:$rate},
                dataType:"JSON",
                success : function(data){
                    if(data.status)
                        $("#rate-wrapper").html(data.rate_wrapper);
                    else
                        alert(data.msg);
                    RatingInit(data.rate,false);
                    histogramAnimate();
                }
            });':'window.location = "'.$this->createUrl('/login').'";').'
    };

    function histogramAnimate(){
        $(".bar span").hide();
        $("#bar-five").animate({
            width: $("#bar-five").data("percent")+"%"}, 1000);
        $("#bar-four").animate({
            width: $("#bar-four").data("percent")+"%"}, 1000);
        $("#bar-three").animate({
            width: $("#bar-three").data("percent")+"%"}, 1000);
        $("#bar-two").animate({
            width: $("#bar-two").data("percent")+"%"}, 1000);
        $("#bar-one").animate({
            width: $("#bar-one").data("percent")+"%"}, 1000);

        setTimeout(function() {
            $(".bar span").fadeIn("slow");
        }, 1000);
    };

    RatingInit();
    histogramAnimate();
');
$rating = $model->calculateRating();
?>
<div class="rating-container">
    <div class="rate-box">
        <div class="center-block" >امتیاز شما</div>
        <div id="rateYo"></div>
    </div>
    <div class="inner">
        <div class="rating">
            <span class="rating-num"><?= number_format($rating['totalAvg'],1) ?></span>
            <div class="rating-stars">
                <?= Controller::printRateStars($rating['totalAvg']); ?>
            </div>
            <div class="rating-users">
                <i class="icon-user"></i> <?= number_format($rating['totalCount'])?>
            </div>
        </div>

        <div class="histo">
            <div class="five histo-rate">
                <span class="histo-star">
                  <i class="active icon-star"></i> 5
                </span>
                <span class="bar-block">
                  <span id="bar-five" data-percent="<?= $rating['fivePercent'] ?>" class="bar">
                    <span><?= number_format($rating['fiveCount']) ?></span>&nbsp;
                  </span>
                </span>
            </div>

            <div class="four histo-rate">
                <span class="histo-star">
                  <i class="active icon-star"></i> 4
                </span>
                <span class="bar-block">
                  <span id="bar-four" data-percent="<?= $rating['fourPercent'] ?>" class="bar">
                    <span><?= number_format($rating['fourCount']) ?></span>&nbsp;
                  </span>
                </span>
            </div>

            <div class="three histo-rate">
                <span class="histo-star">
                  <i class="active icon-star"></i> 3
                </span>
                <span class="bar-block">
                  <span id="bar-three" data-percent="<?= $rating['threePercent'] ?>" class="bar">
                    <span><?= number_format($rating['threeCount'])?></span>&nbsp;
                  </span>
                </span>
            </div>

            <div class="two histo-rate">
                <span class="histo-star">
                  <i class="active icon-star"></i> 2
                </span>
                <span class="bar-block">
                  <span id="bar-two" data-percent="<?= $rating['twoPercent'] ?>" class="bar">
                    <span><?= number_format($rating['twoCount'])?></span>&nbsp;
                  </span>
                </span>
            </div>

            <div class="one histo-rate">
                <span class="histo-star">
                  <i class="active icon-star"></i> 1
                </span>
                <span class="bar-block">
                  <span id="bar-one" data-percent="<?= $rating['onePercent'] ?>" class="bar">
                    <span><?= number_format($rating['oneCount']) ?></span>&nbsp;
                  </span>
                </span>
            </div>
        </div>
    </div>
</div>