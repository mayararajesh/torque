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
        <?php echo $form->textField($model, 'name', array('autocomplete' => "off")); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'queue'); ?>
        <?php echo CHtml::activeDropDownList($model, 'queue', array("" => '-- Select --'), array('autocomplete' => "off")); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'nodes'); ?>
        <?php echo $form->textField($model, 'nodes', array('autocomplete' => "off")); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'ppn'); ?>
        <?php echo $form->textField($model, 'ppn', array('autocomplete' => "off")); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model,'share'); ?>
		<?php echo $form->textField($model,'share',array('size'=>40,'maxlength'=>256)); ?>
        <?php
        $this->widget('zii.widgets.jui.CJuiButton', array(
            'buttonType' => 'button',
            'name' => 'browse',
            'caption' => 'Open directory browser',
            'options' => array('text' => false, 'icons' => 'js:{primary:"ui-icon-folder-open"}'),
            //'themeUrl' => Yii::app()->baseUrl . '/css/jq',
            //'theme'=>'custom-theme',
            'onclick' => 'js:function(){jQuery("#filedirdialog").dialog("open");}',
        ));
        ?>
        <?php
        $returnid = CHtml::getIdByName(get_class($model) . '[share]');
        $this->renderPartial('/widgets/_filedirpicker', array('title' => 'Select directory', 'path' => '/',
            'inputtype' => 'text', 'readonly' => true, 'dironly' => true, 'remote' => true, 'returnid' => $returnid));
        ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton('Generate Script'); ?>
    </div>
    <?php $this->endWidget(); ?>
</div><!-- form -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/tasks/taskManagerHome.js"></script>