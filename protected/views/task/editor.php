<style>
    .editor-area{
        margin-left: 10px !important;
        margin-right: 10px !important;
    }
</style>
<!--<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl . '/js/codemirror/lib/codemirror.css'; ?>">-->
<!--<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl . '/js/codemirror/theme/rubyblue.css'; ?>">-->
<!--<script src="<?php echo Yii::app()->request->baseUrl . '/js/codemirror/lib/codemirror.js'; ?>"></script>-->
<div class="editor-area">
    <div class="row">
        <textarea id="codemirror-shell-editor"><?php echo isset($content) ? $content : ''; ?></textarea>
    </div>
    <div class="row">
        <div class="form"> 
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'task-submit-form',
                'enableAjaxValidation' => false,
                'action' => Yii::app()->createUrl('task/submit'),#'/torque/index.php/task/submit'
            ));
            ?>

            <div id="codemirror-text">
                <?php echo $submitButton = CHtml::submitButton('Submit Task', array('id' => 'submitTask', 'class' => 'btn btn-success')); ?>
            </div>
            <?php $this->endWidget(); ?>
        </div><!-- form -->
    </div>
</div>
<!--<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/tasks/editor.js"></script>-->
<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseUrl . '/js/codemirror/lib/codemirror.css');
$cs->registerCssFile($baseUrl . '/js/codemirror/theme/rubyblue.css');
$cs->registerScriptFile($baseUrl . '/js/codemirror/lib/codemirror.js');
$cs->registerScriptFile($baseUrl . '/js/tasks/editor.js');
?>