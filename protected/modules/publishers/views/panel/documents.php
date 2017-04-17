<?php
/* @var $this PanelController */
/* @var $documentsProvider CActiveDataProvider */
?>
<div class="container dashboard-container">

    <? $this->renderPartial('_tab_links',array('active' => $this->action->id)); ?>

    <a class="btn btn-success publisher-signup-link" href="<?php echo Yii::app()->createUrl('/dashboard')?>">پنل کاربری</a>
    <div class="tab-content card-container">
        <h2>مستندات ناشران</h2><br>
        <h4>فهرست</h4>
        <ul>
            <?php $this->widget('zii.widgets.CListView', array(
                'dataProvider'=>$documentsProvider,
                'itemView'=>'_document_list',
                'template'=>'{items}'
            ));?>
        </ul>
    </div>
</div>