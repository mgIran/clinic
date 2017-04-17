<?php
/**
 * @var $id string
 * @var $class string
 * @var $type string
 * @var $message string
 * @var $closeButton string
 * @var $autoHide string
 * @var $hideTimer string
 */
if(!isset($id) || empty($id))
    $id = rand();

if(!isset($hideTimer) || empty($hideTimer))
    $hideTimer = 5000;
?>
<div class="alert alert-<?= $type ?> fade in <?= isset($class)?$class:'' ?>" id="<?= $id ?>">
    <?if(isset($closeButton) && $closeButton):?><button class="close close-sm" type="button" data-dismiss="alert"><i class="icon-remove"></i></button><?endif;?>
    <?php echo $message ?>
</div>

<?php
if($autoHide){
    Yii::app()->clientScript->registerScript('alert-hide', "
        setTimeout(function(){
            $('#{$id}').fadeOut(function(){
                $('#{$id}').remove();
            });
        }, {$hideTimer});
    ", CClientScript::POS_READY);
}