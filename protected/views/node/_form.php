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

	<%= $form->errorSummary($model); %>

	<div class="row">
		<%= $form->labelEx($model,'name'); %>
		<%= $form->textField($model,'name',array('maxlength'=>255)); %>
		<%= $form->error($model,'name'); %>
	</div>

	<div class="row">
		<%= $form->labelEx($model,'np'); %>
		<%= $form->textField($model,'np'); %>
		<%= $form->error($model,'np'); %>
	</div>

	<div class="row">
		<%= $form->labelEx($model,'gpu'); %>
		<%= $form->textField($model,'gpu'); %>
		<%= $form->error($model,'gpu'); %>
	</div>

	<div class="row">
		<%= $form->labelEx($model,'phi'); %>
		<%= $form->textField($model,'phi'); %>
		<%= $form->error($model,'phi'); %>
	</div>

	<div class="row buttons">
		<%= CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); %>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->