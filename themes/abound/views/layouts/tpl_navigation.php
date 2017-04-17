    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container">
                <a href="#" class="menu-btn icon-reorder show-in-tablet" data-toggle="collapse" data-target=".nav-collapse">
                </a>

                <!-- Be sure to leave the brand out there if you want it shown -->
                <a class="brand" target="_blank" href="<?= Yii::app()->createAbsoluteUrl('//'); ?>"><?= Yii::app()->name; ?></a>
                <?php
                if(!Yii::app()->user->isGuest) {
                    ?>
                    <div class="nav-collapse">
                        <?php $this->widget( 'zii.widgets.CMenu', array(
                            'htmlOptions' => array( 'class' => 'pull-right nav' ),
                            'submenuHtmlOptions' => array( 'class' => 'dropdown-menu' ),
                            'itemCssClass' => 'item-test',
                            'encodeLabel' => false,
                            'items' => Controller::createAdminMenu()
                        ) ); ?>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>