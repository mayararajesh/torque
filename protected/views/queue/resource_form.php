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
    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'arch'); ?>
        <?php echo $form->textField($model, 'arch'); ?>

        <?php echo $form->error($model, 'arch'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'mem'); ?>
        <?php echo $form->textField($model, 'mem[number]'); ?>
        <?php echo $form->dropDownList($model, 'mem[multiplier]', array('mb' => 'Megabytes', 'gb' => 'Gigabytes', 'tb' => 'Terabytes')); ?>
        <?php echo $form->error($model, 'mem'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'ncpus'); ?>
        <?php echo $form->textField($model, 'ncpus'); ?>

        <?php echo $form->error($model, 'ncpus'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'nodect'); ?>
        <?php echo $form->textField($model, 'nodect'); ?>

        <?php echo $form->error($model, 'nodect'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'nodes'); ?>
        <?php echo $form->textField($model, 'nodes'); ?>

        <?php echo $form->error($model, 'nodes'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'procct'); ?>
        <?php echo $form->textField($model, 'procct'); ?>

        <?php echo $form->error($model, 'procct'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'pvmem'); ?>
        <?php echo $form->textField($model, 'pvmem[number]'); ?>
        <?php echo $form->dropDownList($model, 'pvmem[multiplier]', array('mb' => 'Megabytes', 'gb' => 'Gigabytes', 'tb' => 'Terabytes')); ?>
        <?php echo $form->error($model, 'pvmem'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'vmem'); ?>
        <?php echo $form->textField($model, 'vmem[number]'); ?>
        <?php echo $form->dropDownList($model, 'vmem[multiplier]',array('mb' => 'Megabytes', 'gb' => 'Gigabytes', 'tb' => 'Terabytes')); ?>
        <?php echo $form->error($model, 'vmem'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'walltime'); ?>
        HH<?php echo $form->textField($model, 'walltime[hh]',array('size'=>5)); ?>:MM<?php echo $form->textField($model, 'walltime[mm]',array('size'=>5)); ?>:SS<?php echo $form->textField($model, 'walltime[ss]',array('size'=>5)); ?>
        <?php echo $form->error($model, 'walltime'); ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton(!$mtemp ? 'Create' : 'Save'); ?>
    </div>
    <?php $this->endWidget(); ?>
</div>
