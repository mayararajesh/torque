<?php /* source file: /var/www/html/torque/protected/views/queue/create.php */ ?>
<?php
/* @var $this QueueController */
/* @var $model Queue */

$this->breadcrumbs=array(
	'Queues'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Queue', 'url'=>array('index')),
	array('label'=>'Manage Queue', 'url'=>array('admin')),
);
?>

<h1>Create Queue</h1>
<?php $this->renderPartial('_form', array('model'=>$model,'modelTemp'=>$modelTemp)); ?>