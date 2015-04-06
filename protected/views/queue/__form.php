<?php
/* @var $this QueuesFormController */
/* @var $model QueuesForm */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'queues-form-__form-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // See class documentation of CActiveForm for details on this,
        // you need to use the performAjaxValidation()-method described there.
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
        <?php echo $form->labelEx($model, 'priority'); ?>
<?php echo $form->textField($model, 'priority'); ?>
<?php echo $form->error($model, 'priority'); ?>
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
        <?php echo $form->labelEx($model, 'required_login_property'); ?>
<?php echo $form->textField($model, 'required_login_property'); ?>
<?php echo $form->error($model, 'required_login_property'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'features_required'); ?>
<?php echo $form->textField($model, 'features_required'); ?>
<?php echo $form->error($model, 'features_required'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'disallowed_types'); ?>
<?php echo $form->textField($model, 'disallowed_types'); ?>
<?php echo $form->error($model, 'disallowed_types'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'enabled'); ?>
<?php echo $form->textField($model, 'enabled'); ?>
<?php echo $form->error($model, 'enabled'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'started'); ?>
<?php echo $form->textField($model, 'started'); ?>
<?php echo $form->error($model, 'started'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'acl_group_enable'); ?>
<?php echo $form->textField($model, 'acl_group_enable'); ?>
<?php echo $form->error($model, 'acl_group_enable'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'acl_group_sloppy'); ?>
<?php echo $form->textField($model, 'acl_group_sloppy'); ?>
<?php echo $form->error($model, 'acl_group_sloppy'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'acl_logic_or'); ?>
<?php echo $form->textField($model, 'acl_logic_or'); ?>
<?php echo $form->error($model, 'acl_logic_or'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'acl_user_enable'); ?>
<?php echo $form->textField($model, 'acl_user_enable'); ?>
<?php echo $form->error($model, 'acl_user_enable'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'acl_host_enable'); ?>
<?php echo $form->textField($model, 'acl_host_enable'); ?>
<?php echo $form->error($model, 'acl_host_enable'); ?>
    </div>


    <div class="row buttons">
    <?php echo CHtml::submitButton('Submit'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->