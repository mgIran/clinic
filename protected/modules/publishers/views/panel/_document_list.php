<?php
/* @var $data Pages */
?>

<li>
    <a href="<?= $this->createUrl('/documents/'.$data->id.'/'.urlencode($data->title)) ?>">
        <?= $data->title ?>
    </a>
</li>