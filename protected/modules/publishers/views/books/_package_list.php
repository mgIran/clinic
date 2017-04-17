<?php
/* @var $data BookPackages*/
$pdfSize=$epubSize=null;
if(!is_null($data->pdf_file_name))
    $pdfSize=Controller::fileSize(Yii::getPathOfAlias("webroot") . '/uploads/books/files/'.$data->pdf_file_name);
if(!is_null($data->epub_file_name))
    $epubSize=Controller::fileSize(Yii::getPathOfAlias("webroot") . '/uploads/books/files/'.$data->epub_file_name);
$sizeString='';
if(!is_null($pdfSize)) {
    $sizeString .= 'PDF: '.$pdfSize;
    if (!is_null($epubSize))
        $sizeString .= '<br>EPUB:' . $epubSize;
}elseif(!is_null($epubSize)){
    $sizeString .= 'EPUB:'.$epubSize;
    if (!is_null($pdfSize))
        $sizeString .= '<br>PDF:' . $pdfSize;
}

?>

<tr>
    <td><?php echo CHtml::encode($data->version);?></td>
    <td style="direction: ltr;text-align: right;"><?php echo $sizeString;?></td>
    <td><?php echo JalaliDate::date('d F Y', $data->create_date);?></td>
    <td><?php $data->publish_date?JalaliDate::date('d F Y', $data->publish_date):'-';?></td>
    <td><?php echo ($data->price==0)?'رایگان':(Controller::parseNumbers(number_format($data->price)).' تومان');?></td>
<!--    <td>--><?php //echo Controller::parseNumbers(number_format($data->printed_price)).' تومان'?><!--</td>-->
    <td>
        <span style="margin-right: 6px;font-size: 17px">
            <a class="icon-pencil text-info" href="<?php echo $this->createUrl('/publishers/books/updatePackage/'.$data->id);?>"></a>
        </span>
        <span style="font-size: 16px">
            <a class="icon-trash text-danger delete-package" href="<?php echo $this->createUrl('/publishers/books/deletePackage/'.$data->id);?>"></a>
        </span>
    </td>
</tr>
