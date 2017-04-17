<?php
/* @var $this BooksController */
/* @var $labels array */
/* @var $values array */
/* @var $showChart boolean */
/* @var $sumIncome integer */
/* @var $sumCredit integer */

Yii::app()->clientScript->registerCss('booksStyle','
.report-sale .book-item:nth-child(n+3){
    margin-top: 50px;
}
.report-sale .book-item input[type="radio"]{
    float: right;
    margin-top: 27px;
    margin-left: 15px;
}
.report-sale .book-item img{
    float: right;
    max-width: 70px;
    max-height:70px;
    height:auto;
    margin-left: 15px;
}
.report-canvas{
    margin-top: 50px;
    margin-bottom: 50px;
}
.chart-container{
    margin-top: 50px;
}
.report-sale .panel{
    border: 1px solid #ccc;
}
');
?>

<h1 style="margin-bottom: 50px;">گزارش درآمد</h1>

<?php $this->renderPartial('_report_monthly', array('labels'=>$labels,'values'=>$values)); ?>

<p style="margin-top: 50px;"><b>مجموع اعتبار کاربران: </b><?php echo number_format($sumCredit);?> تومان</p>
<small>این مبلغ به صورت موقت در حساب بانکی شما می باشد.</small>

<?php if($showChart):?>
    <div class="panel panel-default chart-container">
        <div class="panel-body">
            <h4>نمودار گزارش</h4>
            <p><b>مجموع در آمد این ماه: </b><?php echo number_format($sumIncome);?> تومان</p>
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