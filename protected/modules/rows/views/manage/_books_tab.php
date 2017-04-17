<?php
/* @var $this RowsManageController */
/* @var $model RowsHomepage */
/* @var $form CActiveForm */


Yii::app()->clientScript->registerScript('add-remove-books', "
$('body').on('click','.add-in-row',function(){
    var rowId = $(this).parents('tr').attr('data-row-id');
    var bookId = $(this).parents('tr').attr('data-book-id');
	$.ajax({
		url: '".$this->createUrl('add')."',
		type:'POST',
		data:{row_id:rowId, book_id:bookId},
		beforeSend:function(){
    		$('#books-tab .loading-container').show();
		},
		success:function(res){
    		$('#books-tab .loading-container').hide();
    		$('#row-books-grid').yiiGridView('update');
	        $('#other-books-grid').yiiGridView('update');
		},
	});
	return false;
});
$('body').on('click','.remove-from-row',function(){
	var rowId = $(this).parents('tr').attr('data-row-id');
    var bookId = $(this).parents('tr').attr('data-book-id');
	$.ajax({
		url: '".$this->createUrl('remove')."',
		type:'POST',
		data:{row_id:rowId, book_id:bookId},
		beforeSend:function(){
    		$('#books-tab .loading-container').show();
		},
		success:function(res){
    		$('#books-tab .loading-container').hide();
    		$('#row-books-grid').yiiGridView('update');
	        $('#other-books-grid').yiiGridView('update');
		},
	});
});
");

?>

<div class="form relative">
	<? $this->renderPartial('//layouts/_flashMessage'); ?>
	<? $this->renderPartial('//layouts/_loading'); ?>
	<div class="row">
		<div class="description">لیست کتاب های این ردیف</div>
		<div class="description">** می توانید با جابجا کردن سطرها کتاب های ردیف را مرتب سازی کنید.</div>
		<?php $this->widget('ext.yiiSortableModel.widgets.SortableCGridView', array(
			'orderField' => 'order',
			'idField' => 'row_id,book_id',
			'orderUrl' => 'order',
			'id'=>'row-books-grid',
			'beforeAjaxUpdate' => 'function(id) { $(\'#books-tab .loading-container\').show(); }',
    		'afterAjaxUpdate' => 'function(id) { $(\'#books-tab .loading-container\').hide(); }',
			'dataProvider'=>$model->searchBooks(),
            'rowHtmlOptionsExpression'=>'array("data-row-id"=>$data->row_id,"data-book-id"=>$data->book_id)',
			'columns'=>array(
				'book.title',
				array(
					'class'=>'CButtonColumn',
					'template' => '{remove}',
					'buttons' => array(
						'remove' => array(
							'label'=>'حذف',
							'url'=>'"#"',
							'options'=>array('class' => 'remove-from-row btn btn-danger')
						)
					)
				),
			),
		)); ?>
	</div>
	<div class="row">
		<div class="description">لیست کتاب های دیگر</div>
		<?php $this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'other-books-grid',
			'beforeAjaxUpdate' => 'function(id) { $(\'#books-tab .loading-container\').show(); }',
			'afterAjaxUpdate' => 'function(id) { $(\'#books-tab .loading-container\').hide(); }',
			'dataProvider'=>$model->searchOtherBooks(),
            'rowHtmlOptionsExpression'=>'array("data-book-id"=>$data->id,"data-row-id"=>'.$model->id.')',
			'columns'=>array(
				'title',
				array(
					'class'=>'CButtonColumn',
					'template' => '{add}',
					'buttons' => array(
						'add' => array(
							'label'=>'انتخاب',
							'url'=>'"#"',
							'options'=>array('class' => 'add-in-row btn btn-success')
						)
					)
				),
			),
		)); ?>
	</div>

</div><!-- form -->