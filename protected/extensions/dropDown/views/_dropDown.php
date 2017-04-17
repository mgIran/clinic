<div class="btn-group btn-input<?= $containerClass?' '.$containerClass:'' ?>" id="<?= $id; ?>-container" >
    <button type="button" class="btn btn-default dropdown-toggle form-control wd" data-toggle="dropdown">
        <span data-bind="label" id="<?= $id; ?>-label" >
            <?php
            $hiddenValue = '';
            if($selected)
            {
                if((int)$selected)
                {
                    $hiddenValue = $selected;
                    echo $data[$hiddenValue];
                }
                elseif(in_array($selected ,$data))
                {
                    $hiddenValue = $selected;
                    echo $hiddenValue;
                }elseif($label)
                    echo $label;
                else
                    echo 'انتخاب کنید';

            }
            else if($label)
                echo $label;
            else
                echo 'انتخاب کنید';
            ?>
        </span> <?php if($caret) echo '<span class="my-caret">'.$caret.'</span>'; else echo '<span class="caret"></span>';?>
    </button>
    <ul class="dropdown-menu" role="menu" id="<?= $id; ?>" >
        <?php
        if($emptyOpt === true)
            echo '<li data-id=""><a href="#">-</a></li>';
        else if(is_array($emptyOpt))
            echo '<li data-id="'.(isset($emptyOpt['data'])?$emptyOpt['data']:'').'"><a href="#">'.(isset($emptyOpt['title'])?$emptyOpt['title']:'-').'</a></li>';
        ?>
        <?php
        if($data) {
            foreach ( $data as $key => $value ) {
                echo '<li data-id="' . $key . '"><a href="#">' . $value . '</a></li>';
            }
        }
        ?>
    </ul>
    <?php
    echo CHtml::hiddenField($name,$hiddenValue,array('id' => $id.'-hidden'));
    ?>
</div>