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
    Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#queue-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
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
        <?php echo CHtml::submitButton('Add'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
<?php
if (isset($data)) {
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'acl-queue-grid',
        'dataProvider' => $data->search(),
        'columns' => array(
            'name',
            array(
                'class' => 'CButtonColumn',
                'template' => '{Update} {Delete}',
                'buttons' => array(
                    'Update' => array(
                        'label' => '<i class="font-icon fa fa-pencil-square"></i>',
                        'imageUrl' => false,
                        'url' => '"javascript:void(0);"', #'$this->grid->controller->createUrl("/queue/acl", array("id"=>$data->primaryKey,"asDialog"=>1,"gridId"=>$this->grid->id))',
                        'options' => array(
                            'title' => 'Edit Node',
                            'class' => 'editAcl'
                        ),
                    ),
                    'Delete' => array(
                        'label' => '<i class="font-icon font-icon-status fa fa-times-circle"></i>',
                        'imageUrl' => false,
                        'url' => '"javascript:void(0);"', #'url' => '$this->grid->controller->createUrl("/node/delete", array("id"=>$data->primaryKey,"asDialog"=>1,"gridId"=>$this->grid->id))',
                        'options' => array(
                            'title' => 'Delete Node',
                            'class' => 'deleteAcl'
                        ),
                    ),
                ),
            ),
        ),
    ));
    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#acl-queue-grid").on('click', '.editAcl', function () {
                var html = '<form action="<?php echo $this->createUrl('/queue/acl/' . $queue->id . '?type=' . $type . '&action=edit'); ?>" method="post">';
                html += '<input name="acl_name" value="' + $(this).closest('tr').find('td:eq(0)').html() + '">&nbsp;';
                html += '<input type="submit" value="Edit"/>';
                html += '</form>';
                $(this).closest('tr').find('td:eq(0)').html(html);
            });
        });
    </script>
    <?php
} else {
    echo "<h4>Not yet added</h4>";
}
?>
