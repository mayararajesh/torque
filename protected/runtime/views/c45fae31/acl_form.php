<?php /* source file: /var/www/html/torque/protected/views/queue/acl_form.php */ ?>
<?php
/* @var $this AclUserController */
/* @var $model AclUser */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'acl-user-acl_form-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // See class documentation of CActiveForm for details on this,
        // you need to use the performAjaxValidation()-method described there.
        'enableAjaxValidation' => false,
    ));
 ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>
    <?php
    foreach (Yii::app()->user->getFlashes() as $key => $message) {
        #if ($message === NULL) {
        echo '<div class="flash-' . $key . '">' . $message . "</div>";
        #}
    }
    ?>    
    <div class="row">
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name'); ?>
        <?php echo $form->error($model, 'name'); ?>
    </div>


    <div class="row buttons">
        <?php echo CHtml::submitButton(!isset($action) ? 'Add' : 'Save'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
