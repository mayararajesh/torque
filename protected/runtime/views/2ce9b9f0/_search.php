<?php /* source file: /var/www/html/torque/protected/views/node/_search.php */ ?>
<?php
/* @var $this NodeController */
/* @var $model Node */
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
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'np'); ?>
		<?php echo $form->textField($model,'np'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'gpus'); ?>
		<?php echo $form->textField($model,'gpus'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'mics'); ?>
		<?php echo $form->textField($model,'mics'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->