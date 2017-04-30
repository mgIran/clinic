<?php
/* @var $this UsersPublicController */
/* @var $data Clinics */
?>
<tr>
    <td><?php echo $data->clinic_name;?></td>
    <td><?php echo $data->town->name;?></td>
    <td><?php echo $data->place->name;?></td>
    <td><?php echo $data->address;?></td>
    <td class="text-center"><a href="<?php echo $this->createUrl('')?>" class="btn btn-info btn-sm">ورود به درمانگاه</a></td>
</tr>