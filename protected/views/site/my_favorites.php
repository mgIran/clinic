<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 rtl grid-show">
    <div class="panel panel-success">
        <div class="panel-heading">
            <h4>
                آگهی های مورد علاقه
            </h4>
        </div>
    </div>
    <?php
    $this->widget('zii.widgets.CListView', array(
            'id' => 'advertises-list',
            'dataProvider' => $dataProvider,
            'itemView' => 'advertises.views.search._itemRowView',
            'template' => '{items} {pager}',
            'pager' => array(
                'class' => 'ext.infiniteScroll.IasPager',
                'rowSelector'=>'.item',
                'listViewId' => 'advertises-list',
                'header' => '',
                'loaderText'=>'در حال دریافت ...',
                'options' => array('history' => false, 'triggerPageTreshold' => $dataProvider->totalItemCount, 'trigger'=>'بیشتر'),
            )
        )
    );
    ?>
</div>