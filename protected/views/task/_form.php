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
        <?php echo $form->labelEx($model, 'inputFile'); ?>
        <table class="table table-bordered table-condensed" style="width: 20%;">
            <tbody>
                <tr>
                    <td>
                        <input id="TaskForm_inputFile" type="text" size="40" maxlength="256" name="TaskForm[inputFile][]" readonly="raedonly"></td>
                    <td>
                        <button type="button" name="browse5" class="btn btn-info browse" role="button" title="Open directory browser"><span class="font-icon icon-fa-folder-open"></span></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'inputFile'); ?>
        <table style="width: 20%;" class="table table-bordered table-condensed">
            <thead>
                <tr>
                    <td colspan="3" style="text-align: right">
                        <button title="Add" class="btn btn-success add-file"><i class="font-icon icon-fa-plus-sign"></i></button>

                    </td>
                </tr>
            </thead>
            <tbody id="file-item-container">
<!--                <tr>
                    <td>
                        <input id="TaskForm_inputFile0" type="text" size="40" maxlength="256" name="TaskForm[inputFile][]" readonly="raedonly">
                    </td>
                    <td>
                        <button type="button" name="browse" id="browse0" class="btn btn-info browse" role="button" title="Open directory browser"><span class="font-icon icon-fa-folder-open"></span></button>
                    </td>
                    <td>
                        <button class="btn btn-danger remove-file" title="Remove"><i class="font-icon icon-fa-minus-sign"></i></button>
                    </td>
                </tr>-->
            </tbody>
        </table>

        <?php #echo $form->textField($model, 'inputFile', array('size' => 40, 'maxlength' => 256)); ?>

        <?php
        #$returnid = 'TaskForm_inputFile0';
        #$this->renderPartial('/widgets/_filedirpicker', array('title' => 'Slect File', 'path' => '/',
        #    'inputtype' => 'text', 'readonly' => true, 'dironly' => FALSE, 'remote' => true, 'returnid' => $returnid));
        ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton('Generate Script'); ?>
    </div>
    <?php $this->endWidget(); ?>
</div><!-- form -->
<div id="filedirdialog">
    <div id="headfixed" class="row" style="height:37px;top:0;left:0;overflow:hidden;margin-left:-20px;margin-right:2px">
        <div class="ui-dialog-content">
            <input id="filedirpath" style="width:98%;" type="text" readonly="readonly" value="/">
        </div>
    </div>
    <div style="position: absolute;bottom:0;left:0;right:0;top:37px;overflow:auto;">
        <div  class="ui-dialog-content">
            <div id="filebrowser" title=""></div>
        </div>
    </div>
</div>
<!--
<link href="http://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet" />
<link href="http://labs.abeautifulsite.net/archived/jquery-fileTree/demo/jqueryFileTree.css" rel="stylesheet" />
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script src="http://labs.abeautifulsite.net/archived/jquery-fileTree/demo/jqueryFileTree.js"></script>
<script type="text/javascript" src="<?php #echo Yii::app()->request->baseUrl; ?>/js/tasks/taskManagerHome.js"></script>
-->

<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseUrl . '/js/jquery-ui/jquery-ui.min.css');
$cs->registerCssFile($baseUrl . '/js/jquery-ui/jquery-ui.structure.min.css');
$cs->registerCssFile($baseUrl . '/js/jquery-ui/jquery-ui.theme.min.css');
$cs->registerCssFile($baseUrl . '/js/jqueryFileTree/jqueryFileTree.css');
$cs->registerScriptFile($baseUrl . '/js/jquery-ui/jquery-ui.min.js');
$cs->registerScriptFile($baseUrl . '/js/jqueryFileTree/jqueryFileTree.js');
$cs->registerScriptFile($baseUrl . '/js/tasks/taskManagerHome.js');
?>
