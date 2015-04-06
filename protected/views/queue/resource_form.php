<!--

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
-->
<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>

<h2> Queue :: <?php echo $queue->name; ?> :: Resource <?php echo $type; ?></h2>
<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'queue-resource-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    ));
    ?>
    <?php
    foreach (Yii::app()->user->getFlashes() as $key => $message) {
        echo '<div class="flash-' . $key . '">' . $message . "</div>";
    }
    ?>
    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'arch'); ?>
        <?php echo $form->textField($model, 'arch'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'mem'); ?>
        <?php echo $form->textField($model, 'memNumber'); ?>
        <?php echo $form->dropDownList($model, 'mem_multiplier', array('mb' => 'Megabytes', 'gb' => 'Gigabytes', 'tb' => 'Terabytes')); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'ncpus'); ?>
        <?php echo $form->textField($model, 'ncpus'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'nodect'); ?>
        <?php echo $form->textField($model, 'nodect'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'nodes'); ?>
        <?php echo $form->textField($model, 'nodes'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'procct'); ?>
        <?php echo $form->textField($model, 'procct'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'pvmemNumber'); ?>
        <?php echo $form->textField($model, 'pvmemNumber'); ?>
        <?php echo $form->dropDownList($model, 'pvmem_multiplier', array('mb' => 'Megabytes', 'gb' => 'Gigabytes', 'tb' => 'Terabytes')); ?>

    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'vmemNumber'); ?>
        <?php echo $form->textField($model, 'vmemNumber'); ?>
        <?php echo $form->dropDownList($model, 'vmem_multiplier', array('mb' => 'Megabytes', 'gb' => 'Gigabytes', 'tb' => 'Terabytes')); ?>

    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'walltime'); ?>
        HH<?php echo $form->textField($model, 'walltime_hh', array('size' => 5)); ?>:MM<?php echo $form->textField($model, 'walltime_mm', array('size' => 5)); ?>:SS<?php echo $form->textField($model, 'walltime_ss', array('size' => 5)); ?>

    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton('Save'); ?>
    </div>
    <?php $this->endWidget(); ?>
</div>
