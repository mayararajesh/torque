<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl . '/assets/codemirror/lib/codemirror.css'; ?>">
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl . '/assets/codemirror/theme/rubyblue.css'; ?>">
<script src="<?php echo Yii::app()->request->baseUrl . '/assets/codemirror/lib/codemirror.js'; ?>"></script>
<div class="row">
    <textarea id="codemirror-shell-editor"><?php echo isset($content) ? $content : ''; ?></textarea>
</div>
<div class="form"> 
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'task-submit-form',
        'enableAjaxValidation' => false,
        'action' => '/torque/index.php/task/submit'
    ));
    ?>
    <div class="row" id="codemirror-text">
        
        <?php echo $submitButton = CHtml::submitButton('Submit Task', array('id' => 'submitTask')); ?>
    </div>
    <script>
        $(document).ready(function () {
            var codemirror = CodeMirror.fromTextArea(document.getElementById('codemirror-shell-editor'), {
                mode: 'shell',
                lineNumbers: true,
                matchBrackets: true,
                theme: 'rubyblue'
            });
            $('#task-submit-form').on('submit', function () {
                var lineCount = codemirror.lineCount();
                var text = "";
                var strLen = 0;
                $('#codemirror-text').find(':hidden').remove();
                for (var i = 0; i < lineCount; i++) {
                    var content = codemirror.getLine(i);
                    strLen += content.length;
                    if(i == 1){
                        text = codemirror.getLine(i).split(' ');
                        text = text[text.length-1];
                    }
                }
                if(strLen > 0) {
                    $('#codemirror-text').append('<input type="hidden" name="script-name" value="'+text+'"/>');
                    text = codemirror.getValue();
                    text = text.replace("\r","");
                    $('#codemirror-text').append('<input type="hidden" name="codemirror-text" value="'+codemirror.getValue()+'"/>');
                    return true;
                }
                alert("Please write the script in editor.");
                return false;
            });
        });
    </script>
    <?php $this->endWidget(); ?>
</div><!-- form -->
