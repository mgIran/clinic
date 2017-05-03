<?
/* @var $this SiteController */
/* @var $doctors CArrayDataProvider */
?>

<div class="inner-page">
    <?php $this->renderPartial('_steps', array('active'=>1));?>

    <div class="filters">
        <div class="container">
            <h4>فیلترها</h4>
            <form class="row">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <input type="text" placeholder="نام پزشک">
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <input type="text" placeholder="درمانگاه">
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <input type="text" placeholder="تخصص">
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <input type="submit" class="btn-red" value="جستجو">
                </div>
            </form>
        </div>
    </div>

    <div class="table-container">
        <div class="container table-responsive">
            <?php $this->widget('zii.widgets.grid.CGridView', array(
                'id'=>'doctors-list',
                'dataProvider'=>$doctors,
                'itemsCssClass'=>'table table-hover',
                'template'=>'{items}{pager}',
                'columns'=>array(
                    array(
                        'header'=>'پزشک',
                        'value'=>function($data) {
                            /* @var $data Users */
                            return '<img src="' . $data->userDetails->getAvatar() . '" class="img-circle">' . $data->userDetails->getShowName();
                        },
                        'type'=>'raw'
                    ),
                    array(
                        'header'=>'تخصص',
                        'value'=>function($data) {
                            /* @var $data Users */
                            return $data->expertises(array(
                                "condition" => "id = :id",
                                "params" => array(
                                    ":id" => Yii::app()->request->getQuery("exp")
                                )
                            ))[0]->title;
                        }
                    ),
                ),
            ));?>
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>درمانگاه</th>
                    <th>پزشک</th>
                    <th>تخصص</th>
                    <th>تاریخ</th>
                    <th>حضور پزشک</th>
                    <th>تا ساعت</th>
                    <th class="text-center">عملیات</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>درمانگاه امام رضا (ع)</td>
                    <td>دکتر محسن ایمانی</td>
                    <td>فوق تخصص نوزادان</td>
                    <td>27 فروردین 1396</td>
                    <td>17:30</td>
                    <td>21:30</td>
                    <td class="text-center"><input type="submit" class="btn-green btn-sm" value="رزرو نوبت"></td>
                </tr>
                <tr>
                    <td>درمانگاه امام رضا (ع)</td>
                    <td>دکتر محسن ایمانی</td>
                    <td>فوق تخصص نوزادان</td>
                    <td>27 فروردین 1396</td>
                    <td>17:30</td>
                    <td>21:30</td>
                    <td class="text-center"><input type="submit" class="btn-green btn-sm" value="رزرو نوبت"></td>
                </tr>
                <tr>
                    <td>درمانگاه امام رضا (ع)</td>
                    <td>دکتر محسن ایمانی</td>
                    <td>فوق تخصص نوزادان</td>
                    <td>27 فروردین 1396</td>
                    <td>17:30</td>
                    <td>21:30</td>
                    <td class="text-center"><input type="submit" class="btn-green btn-sm" value="رزرو نوبت"></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>