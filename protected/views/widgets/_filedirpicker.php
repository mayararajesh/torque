<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'filedirdialog',
    'options' => array(
        'title' => $title,
        'autoOpen' => false,
        'modal' => true,
        'minWidth' => 400,
        #'width'=>250,
        'minHeight' => 300,
        #'height'=>250,
        'buttons' => array(
            'Select' => array(
                'text' => 'Select',
                'id' => 'btnselect',
                'style' => 'display:none;',
                'click' => 'js:function(){$("#' . $returnid . '").val($("#filedirpath").val());$(this).dialog("close");}',
            ),
            'Cancel' => array(
                'text' => 'Cancel',
                'id' => 'btncancel',
                'click' => 'js:function(){$(this).dialog("close");}',
            )),
        # Workaround for adding icon in button(s) of this jui dialog
        'create' => 'js:function(){$("#btnselect").button({icons:{primary:"ui-icon-circle-check"}});
								$("#btncancel").button({icons:{primary:"ui-icon-circle-close"}});}',
    ),
    'themeUrl' => Yii::app()->baseUrl . '/css/jq',
        #'theme'=>'custom-theme',
));
?>
<div id="headfixed" class="row" style="position:absolute;top:0;height:37px;left:0; right:0;overflow:hidden;padding-right:8px">
    <div class="ui-dialog-content">
        <input id="filedirpath" style="width:100%;" type="<?php echo $inputtype; ?>" <?php echo $readonly ? 'readonly' : ''; ?> value="<?php echo $path; ?>">
    </div>
</div>
<div style="position: absolute;bottom:0;left:0;right:0;top:37px;overflow:auto;">
    <div  class="ui-dialog-content">
        <?php
        $this->widget('application.extensions.cfilebrowser.CFileBrowserWidget', array(
            'script' => array(Yii::app()->controller->createUrl('browser/getlist', array(
                    'dironly' => $dironly, 'remote' => $remote,)),),
            'root' => $path,
            'folderEvent' => 'click',
            'expandSpeed' => 200,
            'collapseSpeed' => 200,
            'multiFolder' => false,
            'filecallbackFunction' => 'function(f){$("#filedirpath").val(f);$("#btnselect").show();}',
            'dircallbackFunction' => 'function(d){$("#filedirpath").val(d);$("#btnselect").hide();}',
        ));
        ?>
    </div>
</div>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>

