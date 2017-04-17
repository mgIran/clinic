<?
$filePath = '/uploads/advertiseCategories/thumbs/90x90/';
?>
<li>
    <a href="<?= Yii::app()->createUrl("advertises/add/".urlencode($data->name)."/$data->id"); ?>"></a>
    <img src="<?= Yii::app()->createAbsoluteUrl($filePath.$data->image_path); ?>" alt="<?= $data->name; ?>">
    <div class="overlay">
        <?= $data->name ?>
    </div>
</li>