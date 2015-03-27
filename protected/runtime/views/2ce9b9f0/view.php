<?php /* source file: /var/www/html/torque/protected/views/node/view.php */ ?>
<?php
/* @var $this NodeController */
/* @var $model Node */

$this->breadcrumbs=array(
	'Nodes'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Node', 'url'=>array('index')),
	array('label'=>'Create Node', 'url'=>array('create')),
	array('label'=>'Update Node', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Node', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Node', 'url'=>array('admin')),
);
?>

<h1>Node :: <?php /* line 19 */ echo $model->name ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'name',
		'np',
		'gpus',
		'mics',
	),
)); ?>
