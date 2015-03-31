<?php /* source file: /var/www/html/torque/protected/views/node/update.php */ ?>
<?php
/* @var $this NodeController */
/* @var $model Node */

$this->breadcrumbs=array(
	'Nodes'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Node', 'url'=>array('index')),
	array('label'=>'Create Node', 'url'=>array('create')),
	array('label'=>'View Node', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Node', 'url'=>array('admin')),
);
?>

<h1>Node :: <?php /* line 19 */ echo $model->name; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>