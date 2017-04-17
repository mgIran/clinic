<?php
/* @var $this Controller */
/* @var $category BookCategories */
$parentsID=array();
?>
<div id="categories-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="close-icon"></i></button>
                <h4 class="modal-title">موضوعات<small><?php echo number_format(count($this->navbarCategories), 0, '.', '.');?> موضوع / <?php echo number_format($this->booksCount, 0, '.', '.');?> عنوان کتاب</small></h4>
            </div>
            <div class="modal-body">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 cats-list">
                    <ul class="nav nav-pills nav-stacked row">
                        <?php $i=0;foreach($this->navbarCategories as $category):?>
                            <?php if(is_null($category->parent_id)):$parentsID['cat-'.$category->id]=$category->id;?>
                                <li role="presentation"<?php echo ($i==0)?' class="active"':'';?>><a data-toggle="tab" href="#cat-<?php echo $category->id;?>"><?php echo CHtml::encode($category->title)?></a></li>
                            <?php endif;?>
                        <?php $i++;endforeach;?>
                    </ul>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 col-lg-offset-4 col-mg-offset-4 col-sm-offset-4 cats-content">
                    <div class="tab-content row">
                        <?php $i=0;foreach($parentsID as $key=>$id):?>
                            <div id="<?php echo $key;?>" class="tab-pane fade<?php echo ($i==0)?' in active':'';$i++;?>">
                                <?php $subCategories=array();
                                foreach($this->navbarCategories as $category)
                                    if($category->parent_id==$id)
                                        $subCategories[]=$category;
                                ?>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12<?php echo (count($subCategories)==0)?' hidden':'';?>">
                                    <h5>زیر مجموعه ها</h5>
                                    <ul>
                                        <?php foreach($subCategories as $category):?>
                                            <li><a href="<?php echo $this->createUrl('/category/index', array('id'=>$category->id,'title'=>$category->title));?>"><?php echo CHtml::encode($category->title);?></a></li>
                                        <?php endforeach;?>
                                    </ul>
                                </div>
                                <?php $books=$this->getCategoryBooks($id);?>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12<?php echo (count($books)==0)?' hidden':'';?>">
                                    <h5>کتاب های تازه</h5>
                                    <ul>
                                        <?php foreach($books as $book):?>
                                            <li><a href="<?php echo $this->createUrl('/book/view', array('id'=>$book->id,'title'=>$book->title));?>"><?php echo CHtml::encode($book->title);?></a></li>
                                        <?php endforeach;?>
                                    </ul>
                                </div>
                            </div>
                        <?php endforeach;?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>