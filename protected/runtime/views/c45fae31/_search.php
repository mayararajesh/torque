<?php /* source file: /var/www/html/torque/protected/views/queue/_search.php */ ?>
<?php
/* @var $this QueueController */
/* @var $model Queue */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>128)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'disallowed_types'); ?>
		<?php echo $form->textField($model,'disallowed_types'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'enabled'); ?>
		<?php echo $form->checkBox($model,'enabled'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'features_required'); ?>
		<?php echo $form->textField($model,'features_required',array('size'=>30,'maxlength'=>30)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'keep_completed'); ?>
		<?php echo $form->textField($model,'keep_completed'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'kill_delay'); ?>
		<?php echo $form->textField($model,'kill_delay'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'max_queuable'); ?>
		<?php echo $form->textField($model,'max_queuable'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'max_running'); ?>
		<?php echo $form->textField($model,'max_running'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'max_user_queuable'); ?>
		<?php echo $form->textField($model,'max_user_queuable'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'max_user_run'); ?>
		<?php echo $form->textField($model,'max_user_run'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'priority'); ?>
		<?php echo $form->textField($model,'priority'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'queue_type'); ?>
		<?php echo $form->textField($model,'queue_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'required_login_property'); ?>
		<?php echo $form->textField($model,'required_login_property',array('size'=>60,'maxlength'=>128)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'started'); ?>
		<?php echo $form->checkBox($model,'started'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->