<?php
if(!isset($prefix))
    $prefix = '';
?>

<?php if(Yii::app()->user->hasFlash($prefix.'success')):?>
    <div class="alert alert-success fade in">
        <button class="close close-sm" type="button" data-dismiss="alert"><i class="icon-remove"></i></button>
        <?php echo Yii::app()->user->getFlash($prefix.'success');?>
    </div>
<?php elseif(Yii::app()->user->hasFlash($prefix.'failed')):?>
    <div class="alert alert-danger fade in">
        <button class="close close-sm" type="button" data-dismiss="alert"><i class="icon-remove"></i></button>
        <?php echo Yii::app()->user->getFlash($prefix.'failed');?>
    </div>
<?php elseif(Yii::app()->user->hasFlash($prefix.'warning')):?>
    <div class="alert alert-warning fade in">
        <button class="close close-sm" type="button" data-dismiss="alert"><i class="icon-remove"></i></button>
        <?php echo Yii::app()->user->getFlash($prefix.'warning');?>
    </div>
<?php endif;?>
