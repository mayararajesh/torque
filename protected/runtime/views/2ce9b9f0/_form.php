<?php /* source file: /var/www/html/torque/protected/views/node/_form.php */ ?>
<?php
/* @var $this NodeController */
/* @var $model Node */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'node-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php /* line 20 */ echo $form->errorSummary($model); ?>

	<div class="row">
		<?php /* line 23 */ echo $form->labelEx($model,'name'); ?>
		<?php /* line 24 */ echo $form->textField($model,'name',array('maxlength'=>255)); ?>
		<?php /* line 25 */ echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php /* line 29 */ echo $form->labelEx($model,'np'); ?>
		<?php /* line 30 */ echo $form->textField($model,'np'); ?>
		<?php /* line 31 */ echo $form->error($model,'np'); ?>
	</div>

	<div class="row">
		<?php /* line 35 */ echo $form->labelEx($model,'gpu'); ?>
		<?php /* line 36 */ echo $form->textField($model,'gpu'); ?>
		<?php /* line 37 */ echo $form->error($model,'gpu'); ?>
	</div>

	<div class="row">
		<?php /* line 41 */ echo $form->labelEx($model,'phi'); ?>
		<?php /* line 42 */ echo $form->textField($model,'phi'); ?>
		<?php /* line 43 */ echo $form->error($model,'phi'); ?>
	</div>

	<div class="row buttons">
		<?php /* line 47 */ echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->