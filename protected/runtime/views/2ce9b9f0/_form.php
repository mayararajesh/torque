<?php /* source file: /var/www/html/torque/protected/views/node/_form.php */ ?>
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

    <?php /* line 22 */ echo $form->errorSummary($model); ?>

    <div class="row">
        <?php /* line 25 */ echo $form->labelEx($model,'name'); ?>
        <?php
        if ($model->isNewRecord) {
            echo $form->textField($model, 'name', array('maxlength' => 255));
        } else {
            echo $form->textField($model, 'name', array('readonly' => 'readonly', 'maxlength' => 255));
        }
        ?>
        <?php /* line 33 */ echo $form->error($model,'name'); ?>
    </div>

    <div class="row">
        <?php /* line 37 */ echo $form->labelEx($model,'np'); ?>
        <?php /* line 38 */ echo $form->textField($model,'np'); ?>
        <?php /* line 39 */ echo $form->error($model,'np'); ?>
    </div>

    <div class="row">
        <?php /* line 43 */ echo $form->labelEx($model,'gpus'); ?>
        <?php /* line 44 */ echo $form->textField($model,'gpus'); ?>
        <?php /* line 45 */ echo $form->error($model,'gpus'); ?>
    </div>

    <div class="row">
        <?php /* line 49 */ echo $form->labelEx($model,'mics'); ?>
        <?php /* line 50 */ echo $form->textField($model,'mics'); ?>
        <?php /* line 51 */ echo $form->error($model,'mics'); ?>
    </div>

    <div class="row buttons">
        <?php /* line 55 */ echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->