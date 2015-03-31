<?php
/* @var $this QueueController */
/* @var $model Queue */
/* @var $form CActiveForm */
?>
<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'queue-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name'); ?>

        <?php echo $form->error($model, 'name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'disallowed_types'); ?>
        <?php echo $form->checkBox($model,'disallowed_types[]',array('uncheckValue'=>NULL,'value'=>'interactive'));?> Interactive<br>
        <?php echo $form->checkBox($model,'disallowed_types[]',array('uncheckValue'=>NULL,'value'=>'batch'));?> batch<br>
        <?php echo $form->checkBox($model,'disallowed_types[]',array('uncheckValue'=>NULL,'value'=>'rerunable'));?> rerunable<br>
        <?php echo $form->checkBox($model,'disallowed_types[]',array('uncheckValue'=>NULL,'value'=>'nonrerunable'));?> nonrerunable<br>
        <?php echo $form->checkBox($model,'disallowed_types[]',array('uncheckValue'=>NULL,'value'=>'fault_tolerant'));?> Fault Tolerant<br>
        <?php echo $form->checkBox($model,'disallowed_types[]',array('uncheckValue'=>NULL,'value'=>'fault_intolerant'));?> Fault Intolerant<br>
        <?php echo $form->checkBox($model,'disallowed_types[]',array('uncheckValue'=>NULL,'value'=>'job_array'));?> Job Array<br>
        <?php echo $form->error($model, 'disallowed_types'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'enabled'); ?>
        <?php echo $form->checkBox($model, 'enabled',array('uncheckValue'=>0,'value' => 1)); ?>
        <?php echo $form->error($model, 'enabled'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'features_required'); ?>
        <?php echo $form->textField($model, 'features_required', array('size' => 30, 'maxlength' => 30)); ?>
        <?php echo $form->error($model, 'features_required'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'keep_completed'); ?>
        <?php echo $form->textField($model, 'keep_completed'); ?>
        <?php echo $form->error($model, 'keep_completed'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'kill_delay'); ?>
        <?php echo $form->textField($model, 'kill_delay'); ?>
        <?php echo $form->error($model, 'kill_delay'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'max_queuable'); ?>
        <?php echo $form->textField($model, 'max_queuable'); ?>
        <?php echo $form->error($model, 'max_queuable'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'max_running'); ?>
        <?php echo $form->textField($model, 'max_running'); ?>
        <?php echo $form->error($model, 'max_running'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'max_user_queuable'); ?>
        <?php echo $form->textField($model, 'max_user_queuable'); ?>
        <?php echo $form->error($model, 'max_user_queuable'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'max_user_run'); ?>
        <?php echo $form->textField($model, 'max_user_run'); ?>
        <?php echo $form->error($model, 'max_user_run'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'priority'); ?>
        <?php echo $form->textField($model, 'priority'); ?>
        <?php echo $form->error($model, 'priority'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'queue_type'); ?>
        <?php echo $form->radioButton($model, 'queue_type',array('uncheckValue'=>NULL,'value' => 'execution','checked'=>'checked')); ?> Execution
        <?php echo $form->radioButton($model, 'queue_type',array('uncheckValue'=>NULL,'value' => 'route')); ?> Route
        <?php echo $form->error($model, 'queue_type'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'required_login_property'); ?>
        <?php echo $form->textField($model, 'required_login_property', array('size' => 60, 'maxlength' => 128)); ?>
        <?php echo $form->error($model, 'required_login_property'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'started'); ?>
        <?php echo $form->checkBox($model, 'started',array('uncheckValue'=>0,'value' => 1)); ?>
        <?php echo $form->error($model, 'started'); ?>
    </div>

    <div style="textalign: center !important;" class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

    <?php $this->endWidget(); ?>

    <?php #echo Yii::app()->getClientScript()->registerCoreScript('jquery.js'); ?>
</div><!-- form -->
<script type="text/javascript">
$(document).ready(function(){
    
});
</script>