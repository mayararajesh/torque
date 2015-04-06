<?php /* source file: /var/www/html/torque/protected/views/queue/update.php */ ?>
<?php
/* @var $this QueueController */
/* @var $model Queue */

$this->breadcrumbs = array(
    'Queues' => array('index'),
    $modelTemp->name => array('view', 'id' => $modelTemp->id),
    'Update',
);

$this->menu = array(
    array('label' => 'List Queue', 'url' => array('index')),
    array('label' => 'Create Queue', 'url' => array('create')),
    array('label' => 'View Queue', 'url' => array('view', 'id' => $modelTemp->id)),
    array('label' => 'Manage Queue', 'url' => array('admin')),
);
?>

<h1>Update :: <?php echo $modelTemp->name; ?> </h1>
<?php $this->renderPartial('_form', array('model' => $model, 'modelTemp' => $modelTemp)); ?>