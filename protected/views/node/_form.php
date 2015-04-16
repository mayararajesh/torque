<?php
/* @var $this NodeController */
/* @var $model Node */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'node-form',
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
        <?php echo $form->labelEx($model,'name'); ?>
        <?php
        if ($model->isNewRecord) {
            echo $form->textField($model, 'name', array('maxlength' => 255));
        } else {
            echo $form->textField($model, 'name', array('readonly' => 'readonly', 'maxlength' => 255));
        }
        ?>
        <?php echo $form->error($model,'name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'np'); ?>
        <?php echo $form->textField($model,'np'); ?>
        <?php echo $form->error($model,'np'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'gpus'); ?>
        <?php echo $form->textField($model,'gpus'); ?>
        <?php echo $form->error($model,'gpus'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'mics'); ?>
        <?php echo $form->textField($model,'mics'); ?>
        <?php echo $form->error($model,'mics'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->