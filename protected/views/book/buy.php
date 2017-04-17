<?php
/* @var $this BookController */
/* @var $model Books */
/* @var $user Users */
/* @var $bought boolean */
/* @var $basePrice string */
/* @var $price string */
/* @var $discountCodesInSession [] */

$hasFlash=Yii::app()->user->hasFlash('success');
?>
<div class="white-form">
    <?php
    if(Yii::app()->user->getId()!=$model->publisher_id){
        ?>
        <?php $this->renderPartial('//partial-views/_flashMessage'); ?>
        <h3>خرید کتاب</h3>
        <p class="description">جزئیات خرید شما به شرح ذیل می باشد:</p>

        <?php if(Yii::app()->user->hasFlash('credit-failed')): ?>
            <div class="alert alert-danger fade in">
                <?php echo Yii::app()->user->getFlash('credit-failed'); ?>
                <?php if(Yii::app()->user->hasFlash('failReason') and Yii::app()->user->getFlash('failReason') == 'min_credit'): ?>
                    <a href="<?php echo $this->createUrl('/users/credit/buy'); ?>">خرید اعتبار</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <p><label class="buy-label">کتاب</label><span><a
                    href="<?php echo $this->createUrl('/book/view', array('id' => $model->id, 'title' => $model->title)); ?>"
                    title="<?php echo CHtml::encode($model->title); ?>"><?php echo CHtml::encode($model->title); ?></a></span>
        </p>
            <p><label class="buy-label">مبلغ</label><span><?php echo CHtml::encode(number_format($basePrice, 0)); ?>
                تومان</span>
            </p>
        <?php
        if($basePrice != $price):
            ?>
        <p><label class="buy-label">مبلغ با تخفیف</label><span><?php echo CHtml::encode(number_format($price, 0)); ?>
                تومان</span></p>
            <?php
        endif;
        ?>
        <hr>
        <p><label class="buy-label">اعتبار
                فعلی</label><span><?php echo CHtml::encode(number_format($user->userDetails->credit, 0)); ?>
                تومان</span></p>
        <?php if($bought): ?>
            <?php if(!$hasFlash): ?>
                <div class="alert alert-success">این کتاب قبلا خریداری شده است. شما می توانید از طریق برنامه موبایل و
                    ویندوز کتاب مورد نظر را دریافت و مطالعه کنید.
                </div>
            <?php endif; ?>
        <?php else: ?>

            <?php
            if(!$discountCodesInSession):
                echo CHtml::beginForm('', 'post', array('class' => 'form-inline'));
                ?>
                <div class="form-group">
                    <?php
                    echo CHtml::textField('DiscountCodes[code]', '', array('placeholder' => 'کد تخفیف', 'class' => 'form-control'))
                    ?>
                </div>
                <div class="form-group">

                    <?php echo CHtml::submitButton('اعمال تخفیف', array(
                        'class' => 'btn btn-default',
                        'name' => 'DiscountCodes[btn]'
                    )) ?>
                </div>
                <?php
                echo CHtml::endForm(); ?>
                <?php
            else:
                ?>
                <div class="form-group row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="description">کد تخفیف اعمال شده</span>
                        <ul class="discount-codes">
                            <?php
                            $url = $this->createUrl('/discountCodes/manage/removeCode');
                            if(is_array($discountCodesInSession)){
                                foreach($discountCodesInSession as $code)
                                    echo '<li><a href="#" data-url="' . $url . '" class="delete-code" data-code="' . $code . '" title="حذف کد تخفیف"><span>' . $code . '</span><span>&times</span></a></li>';
                            }else
                                echo '<li><a href="#" data-url="' . $url . '" class="delete-code" data-code="' . $discountCodesInSession . '" title="حذف کد تخفیف"><span>' . $discountCodesInSession . '</span><span>&times</span></a></li>';
                            ?>
                        </ul>
                    </div>
                </div>
                <?php
                Yii::app()->clientScript->registerScript('delete-code','
                    $("body").on("click", ".delete-code", function(e){
                        e.preventDefault();
                        var $this = $(this), 
                            $url = $this.data("url"),
                            $code = $this.data("code");
                        $.ajax({
                            url: $url,
                            type: "POST",
                            dataType: "JSON",
                            data: {code: $code},
                            beforeSend: function(){
                                $("#loading").removeClass("hidden").addClass("in");
                            },
                            success: function(data){
                                if(data.status)
                                    location.reload();
                                else{
                                    $("#loading").removeClass("in").addClass("hidden");
                                    alert(data.msg);
                                }
                            }
                        });
                    });
                ');
            endif;
            ?>

            <?php $form = $this->beginWidget('CActiveForm', array(
                'id' => 'book-buys-form',
                'enableAjaxValidation' => false,
            )); ?>
            <div class="buttons">
                <?php echo CHtml::submitButton('کسر از اعتبار', array(
                    'class' => 'btn btn-default',
                    'name' => 'Buy[credit]'
                )) ?>
                <?php echo CHtml::submitButton('پرداخت از طریق درگاه', array(
                    'class' => 'btn btn-default',
                    'name' => 'Buy[gateway]'
                )) ?>
            </div>
            <?php $this->endWidget(); ?>
        <?php endif; ?>
        <?php
    }else{
        ?>
        <h3>خرید کتاب</h3>
        <p class="description">شما ناشر این کتاب هستید. کتاب مورد نظر در کتابخانه شما موجود است.</p>
        <?php echo CHtml::link('رفتن به کتابخانه', array('/users/public/library'), array(
            'class' => 'btn btn-default'
        )) ?>
        <?php
    }
    ?>
</div>