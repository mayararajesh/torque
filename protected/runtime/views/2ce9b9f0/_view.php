<?php /* source file: /var/www/html/torque/protected/views/node/_view.php */ ?>
<?php
/* @var $this NodeController */
/* @var $data Node */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('np')); ?>:</b>
	<?php echo CHtml::encode($data->np); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('gpu')); ?>:</b>
	<?php echo CHtml::encode($data->gpu); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('phi')); ?>:</b>
	<?php echo CHtml::encode($data->phi); ?>
	<br />


</div>