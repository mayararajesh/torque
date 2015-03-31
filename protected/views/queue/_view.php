<?php
/* @var $this QueueController */
/* @var $data Queue */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('disallowed_types')); ?>:</b>
	<?php echo CHtml::encode($data->disallowed_types); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('enabled')); ?>:</b>
	<?php echo CHtml::encode($data->enabled); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('features_required')); ?>:</b>
	<?php echo CHtml::encode($data->features_required); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('keep_completed')); ?>:</b>
	<?php echo CHtml::encode($data->keep_completed); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kill_delay')); ?>:</b>
	<?php echo CHtml::encode($data->kill_delay); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('max_queuable')); ?>:</b>
	<?php echo CHtml::encode($data->max_queuable); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('max_running')); ?>:</b>
	<?php echo CHtml::encode($data->max_running); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('max_user_queuable')); ?>:</b>
	<?php echo CHtml::encode($data->max_user_queuable); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('max_user_run')); ?>:</b>
	<?php echo CHtml::encode($data->max_user_run); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('priority')); ?>:</b>
	<?php echo CHtml::encode($data->priority); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('queue_type')); ?>:</b>
	<?php echo CHtml::encode($data->queue_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('required_login_property')); ?>:</b>
	<?php echo CHtml::encode($data->required_login_property); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('started')); ?>:</b>
	<?php echo CHtml::encode($data->started); ?>
	<br />

	*/ ?>

</div>