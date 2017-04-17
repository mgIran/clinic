<?php
/* @var $addresses ShopAddresses[] */

$this->widget("zii.widgets.CListView", array(
    "id"=>"address-list",
    "itemsCssClass"=>"address-list",
    "dataProvider"=>new CArrayDataProvider($addresses, array(
        "pagination"=>false,
    )),
    "template"=>"{items}",
    "itemView"=>"shop.views.shipping._address_item",
    "emptyText"=>"آدرسی یافت نشد."
));