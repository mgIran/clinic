<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#mobile-menu" aria-expanded="false">
                <span class="sr-only">نمایش / پنهان کردن منو</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?= Yii::app()->baseUrl ?>"><img src="<?php echo Yii::app()->theme->baseUrl.'/svg/logo.svg';?>" alt="<?php echo Yii::app()->name;?>"><h1>کــــــــتابیــــــــــک<small>نزدیکترین کتابفروشی شهر</small></h1></a>
        </div>

        <div class="collapse navbar-collapse" id="mobile-menu">
            <?php
            echo CHtml::beginForm(array('/book/search'),'get',array('class'=>'navbar-form navbar-center'))
            ?>
                <div class="form-group">
                    <?php echo CHtml::textField('term',isset($_GET['term'])?CHtml::encode($_GET['term']):'',array('id'=>'search-term','class'=>'form-control','placeholder'=>'جستجو کنید...','autocomplete' => 'off')) ?>
                    <span class="search-btn-icon">
                        <?php echo CHtml::submitButton('',array('name'=>'','class'=>'btn btn-default')) ?>
                    </span>
                </div>
                <div class="search-suggest-box">
                    <div class="search-entries"></div>
                </div>
            <?php
            echo CHtml::endForm();
            ?>
            <ul class="nav navbar-nav navbar-left">
                <li><a href="<?php echo $this->createUrl("/shop/cart/view");?>">سبد خرید<?php if(Shop::getCartCount() != 0):?><small class="cart-count"><?php echo Controller::parseNumbers(Shop::getCartCount());?></small><?php endif;?></a></li>
                <li><a href="#categories-modal" data-toggle="modal">موضوعات</a></li>
                <? if(Yii::app()->user->isGuest ||  Yii::app()->user->type == 'admin'):?>
                    <li><a href="#login-modal" data-toggle="modal">ثبت نام&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;ورود</a></li>
                <? elseif(!Yii::app()->user->isGuest && Yii::app()->user->type == 'user'):
                // @todo after user login change login and register buttons
                ?>
                    <li><a href="<?= $this->createUrl('/dashboard') ?>">داشبورد</a></li>
                    <li><a href="<?= $this->createUrl('/logout') ?>" class="error">خروج</a></li>
                <? endif;?>
            </ul>
        </div>
    </div>
</nav>