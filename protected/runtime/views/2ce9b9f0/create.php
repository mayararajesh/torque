<?php /* source file: /var/www/html/torque/protected/views/node/create.php */ ?>
<?php
/* @var $this NodeController */
/* @var $model Node */

$this->breadcrumbs=array(
	'Nodes'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Node', 'url'=>array('index')),
	array('label'=>'Manage Node', 'url'=>array('admin')),
);
?>

<h1>Create Node</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>