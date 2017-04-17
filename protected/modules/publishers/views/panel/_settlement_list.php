<?php
/* @var $this PublishersPanelController */
/* @var $data UserSettlement */
$this::$sumSettlement += $data->amount;
?>

<tr>
    <td><?php echo CHtml::encode($data->typeLabels[$data->account_type]) ?></td>
    <td><?php echo CHtml::encode($data->account_owner_name) ?></td>
    <td><?php echo CHtml::encode($data->account_owner_family) ?></td>
    <td><?php echo CHtml::encode($data->account_number) ?></td>
    <td><?php echo CHtml::encode($data->bank_name) ?></td>
    <td class="ltr">IR<?php echo CHtml::encode($data->iban);?></td>
    <td><?php echo CHtml::encode($data->token) ?></td>
    <td class="ltr"><?php echo JalaliDate::date('Y/m/d - H:i', $data->date);?></td>
    <td><?php echo CHtml::encode(Controller::parseNumbers(number_format($data->amount, 0)));?> تومان</td>
</tr>