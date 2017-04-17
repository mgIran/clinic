<?php
/* @var $this PanelController */
/* @var $books CActiveDataProvider */
/* @var $labels array */
/* @var $values array */
?>
<div class="white-form report-sale">
    <h3>گزارش فروش</h3>
    <p class="description">کتاب مورد نظر را انتخاب کنید:</p>
    <?php $this->renderPartial('//partial-views/_flashMessage');?>
    <?php echo CHtml::beginForm('', 'POST', array(
        'class'=>'form'
    ));?>
        <div class="panel panel-default">
            <div class="panel-body">
                <?php $this->widget('zii.widgets.CListView', array(
                    'dataProvider'=>$books,
                    'itemView'=>'_report_sale_book_list',
                    'template'=>'{items}'
                ));?>
            </div>
        </div>
        <div class="row" style="margin-bottom: 50px;">
            <div class="form-group col-lg-4 col-md-4 col-sm-6 col-md-12">
                <?php echo CHtml::label('از تاریخ', 'from_date');?>
                <?php $this->widget('application.extensions.PDatePicker.PDatePicker', array(
                    'id'=>'from_date',
                    'options'=>array(
                        'format'=>'DD MMMM YYYY'
                    ),
                    'htmlOptions'=>array(
                        'class'=>'form-control'
                    ),
                ));?>
            </div>
            <div class="form-group col-lg-4 col-md-4 col-sm-6 col-md-12">
                <?php echo CHtml::label('تا تاریخ', 'to_date');?>
                <?php $this->widget('application.extensions.PDatePicker.PDatePicker', array(
                    'id'=>'to_date',
                    'options'=>array(
                        'format'=>'DD MMMM YYYY'
                    ),
                    'htmlOptions'=>array(
                        'class'=>'form-control'
                    ),
                ));?>
            </div>
            <div class="form-group col-lg-4 col-md-4 col-sm-6 col-md-12">
                <?php echo CHtml::submitButton('جستجو', array(
                    'class'=>'btn btn-info',
                    'name'=>'show-chart',
                    'id'=>'show-chart',
                ));?>
            </div>
        </div>
        <?php if(isset($_POST['from_date_altField'])):?>
            <div class="panel panel-default chart-container">
                <div class="panel-body">
                    <h4>نمودار گزارش</h4>
                    <?php $this->widget(
                        'chartjs.widgets.ChBars',
                        array(
                            'width' => 700,
                            'height' => 400,
                            'htmlOptions' => array(
                                'class'=>'center-block report-canvas'
                            ),
                            'labels' => $labels,
                            'datasets' => array(
                                array(
                                    "fillColor" => "rgba(54, 162, 235, 0.5)",
                                    "strokeColor" => "rgba(54, 162, 235, 1)",
                                    "data" => $values
                                )
                            ),
                            'options' => array()
                        )
                    );?>
                </div>
            </div>
        <?php else:?>
            <div class="panel panel-default chart-container">
                <div class="panel-body">
                    <h4>فروش امروز</h4>
                    <?php $this->widget(
                        'chartjs.widgets.ChBars',
                        array(
                            'width' => 700,
                            'height' => 400,
                            'htmlOptions' => array(
                                'class'=>'center-block report-canvas'
                            ),
                            'labels' => $labels,
                            'datasets' => array(
                                array(
                                    "fillColor" => "rgba(54, 162, 235, 0.5)",
                                    "strokeColor" => "rgba(54, 162, 235, 1)",
                                    "data" => $values
                                )
                            ),
                            'options' => array()
                        )
                    );?>
                </div>
            </div>
        <?php endif;?>
    <?php echo CHtml::endForm();?>
</div>
<?php Yii::app()->clientScript->registerScript('submitReport', "
    $('#show-chart').click(function(){
        if($('input[name=\"book_id\"]:checked').length==0){
            alert('لطفا کتاب مورد نظر خود را انتخاب کنید.');
            return false;
        }
    });
");?>