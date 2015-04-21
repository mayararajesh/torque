<div class="wide form">

<?php
	$formname='nfs-form';
	$form=$this->beginWidget('CActiveForm', array(
		'id'=>$formname,
		'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'share'); ?>
		<?php echo $form->textField($model,'share',array('size'=>40,'maxlength'=>256)); ?>
		
		<?php
			$this->widget('zii.widgets.jui.CJuiButton', array(
					'buttonType'=>'button',
					'name'=>'browse',
					'caption'=>'Open directory browser',
					'options'=>array('text'=>false, 'icons'=>'js:{primary:"ui-icon-folder-open"}'),
					'themeUrl'=>Yii::app()->baseUrl.'/css/jq',
					//'theme'=>'custom-theme',
					'onclick'=>'js:function(){jQuery("#filedirdialog").dialog("open");}',
			));
		?>
		<?php
			$returnid=CHtml::getIdByName(get_class($model).'[share]');
			$this->renderPartial('/widgets/_filedirpicker', array('title'=>'Select directory', 'path'=>'/',
					'inputtype'=>'text', 'readonly'=>true, 'dironly'=>true, 'remote'=>false, 'returnid'=>$returnid));
		?>
		<?php echo $form->error($model,'share'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'option'); ?>
		<?php echo $form->textField($model,'option',array('size'=>40,'maxlength'=>256)); ?>
		<?php echo $form->error($model,'option'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'comment'); ?>
		<?php echo $form->textField($model,'comment',array('size'=>40,'maxlength'=>512)); ?>
		<?php echo $form->error($model,'comment'); ?>
	</div>

	<div class="row buttons">
		<?php /*echo CHtml::submitButton($model->isNewRecord ? 'Add' : 'Update');*/ ?>

		<?php
			$this->widget('zii.widgets.jui.CJuiButton', array(
					'name'=>'Save',
					'caption'=>$model->isNewRecord ? 'Save' : 'Update',
					'buttonType'=>'button',
					'options'=>array('icons'=>'js:{primary:"ui-icon-disk"}'),
					'onclick'=>'js:function(){document.forms["'.$formname.'"].submit();}',
					'themeUrl'=>Yii::app()->baseUrl.'/css/jq',
					//'theme'=>'custom-theme',
			));
		?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->