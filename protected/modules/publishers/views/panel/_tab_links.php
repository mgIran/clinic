<?
/**
 * @var $active string
 */
if(!isset($active))
    $active = '';
?>
<ul class="nav nav-tabs">
    <li <?= $active == 'index'?'class="active"':'' ?> >
        <a href="<?php echo $this->createUrl('/publishers/panel');?>">کتاب ها</a>
    </li>
    <li <?= $active == 'discount'?'class="active"':'' ?> >
        <a href="<?php echo $this->createUrl('/publishers/panel/discount');?>">تخفیفات</a>
    </li>
    <li <?= $active == 'account'?'class="active"':'' ?> >
        <a href="<?php echo $this->createUrl('/publishers/panel/account');?>">حساب ناشر</a>
    </li>
    <li <?= $active == 'sales'?'class="active"':'' ?> >
        <a href="<?php echo $this->createUrl('/publishers/panel/sales');?>">گزارش فروش</a>
    </li>
    <li <?= $active == 'settlement'?'class="active"':'' ?> >
        <a href="<?php echo $this->createUrl('/publishers/panel/settlement');?>">تسویه حساب</a>
    </li>
    <li  <?= $active == 'documents'?'class="active"':'' ?> >
        <a href="<?php echo $this->createUrl('/publishers/panel/documents');?>">مستندات</a>
    </li>
</ul>