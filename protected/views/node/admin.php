<?php
/* @var $this NodeController */
/* @var $model Node */

$this->breadcrumbs = array(
    'Nodes' => array('index'),
    'Manage',
);

$this->menu = array(
    array('label' => 'List Node', 'url' => array('index')),
    array('label' => 'Create Node', 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#node-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Nodes</h1>

<p>
    You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
    or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search', '#', array('class' => 'search-button')); ?>
<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('_search', array(
        'model' => $model,
    ));
    ?>
</div><!-- search-form -->

<?php
$host = Yii::app()->params->hostDetails['host'];
$port = Yii::app()->params->hostDetails['port'];
$sshHost = new SSH($host, $port, 'root');
$nodes = $model->findAll();
if ($sshHost->isConnected() && $sshHost->authenticate_pass('root123')) {
    foreach($nodes as $node){
        echo $sshHost->cmd('pbsnodes -a -x '.$node->name);
    }
    $sshHost->disconnect();
    #exit;
}
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'node-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'name',
        'np',
        'gpus',
        'mics',
        array(
            'class' => 'CButtonColumn',
            'template' => '{Status} {Update} {Delete}',
            'buttons' => array(
                'Update' => array(
                    'label' => '<i class="font-icon fa fa-pencil-square"></i>',
                    'imageUrl' => false,
                    'url' => '$this->grid->controller->createUrl("/node/update", array("id"=>$data->primaryKey,"asDialog"=>1,"gridId"=>$this->grid->id))',
                    'options' => array(
                        'title' => 'Edit Node'
                    ),
                ),
                'Status' => array(
                    'label' => '<i class="font-icon font-icon-status fa fa-square-o"></i>',
                    'imageUrl' => false,
                    'url' => '$this->grid->controller->createUrl("/node/online", array("id"=>$data->primaryKey,"asDialog"=>1,"gridId"=>$this->grid->id))',
                    'options' => array(
                        'title' => 'Make Offline'
                    ),
                ),
                'Delete' => array(
                    'label' => '<i class="font-icon font-icon-status fa fa-times-circle"></i>',
                    'imageUrl' => false,
                    'url' => '$this->grid->controller->createUrl("/node/delete", array("id"=>$data->primaryKey,"asDialog"=>1,"gridId"=>$this->grid->id))',
                    'options' => array(
                        'title' => 'Delete Node'
                    ),
                ),
            ),
        ),
    ),
));
?>
