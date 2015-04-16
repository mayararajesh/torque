<?php /* source file: /var/www/html/torque/protected/views/task/editor.php */ ?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl . '/assets/codemirror/lib/codemirror.css'; ?>">
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl . '/assets/codemirror/theme/rubyblue.css'; ?>">
<script src="<?php echo Yii::app()->request->baseUrl . '/assets/codemirror/lib/codemirror.js'; ?>"></script>
<div class="row">
    <textarea id="codemirror-shell-editor"><?php echo isset($content) ? $content : ''; ?></textarea>
</div>
<div class="row">
    <div class="form"> 
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'task-submit-form',
            'enableAjaxValidation' => false,
            'action' => '/torque/index.php/task/submit'
        ));
        ?>
        <div id="codemirror-text">
            <?php echo $submitButton = CHtml::submitButton('Submit Task', array('id' => 'submitTask')); ?>
        </div>
        <script>
            
        </script>
        <?php $this->endWidget(); ?>
    </div><!-- form -->
</div>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/tasks/editor.js"></script>
