<?php
/* @var $this NodeController */
/* @var $data Node */
?>

<div class="view">
	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
    <?php echo CHtml::link(CHtml::encode($data->name), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('np')); ?>:</b>
	<?php echo CHtml::encode($data->np); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('gpus')); ?>:</b>
	<?php echo CHtml::encode($data->gpus); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('mics')); ?>:</b>
	<?php echo CHtml::encode($data->mics); ?>
	<br />


</div>