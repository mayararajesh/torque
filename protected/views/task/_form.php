<div class="form"> 
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'task-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php
    echo $form->errorSummary($model);
    ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'queue'); ?>
        <?php echo CHtml::activeDropDownList($model, 'queue',array("" => '-- Select --')); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'nodes'); ?>
        <?php echo $form->textField($model, 'nodes'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'ppn'); ?>
        <?php echo $form->textField($model, 'ppn'); ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton('Generate Script'); ?>
    </div>
    <?php $this->endWidget(); ?>
</div><!-- form -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/tasks/taskManagerHome.js"></script>