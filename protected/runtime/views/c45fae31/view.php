<?php /* source file: /var/www/html/torque/protected/views/queue/view.php */ ?>
<?php
/* @var $this QueueController */
/* @var $model Queue */

$this->breadcrumbs = array(
    'Queues' => array('index'),
    $model->name,
);

$this->menu = array(
    array('label' => 'List Queue', 'url' => array('index')),
    array('label' => 'Create Queue', 'url' => array('create')),
    array('label' => 'Update Queue', 'url' => array('update', 'id' => $model->id)),
    array('label' => 'Delete Queue', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
    array('label' => 'Manage Queue', 'url' => array('admin')),
);
?>

<h1> Queue :: <?php echo $model->name; ?></h1>

<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'name',
        'disallowed_types',
        'enabled',
        'features_required',
        'keep_completed',
        'kill_delay',
        'max_queuable',
        'max_running',
        'max_user_queuable',
        'max_user_run',
        'priority',
        'queue_type',
        'required_login_property',
        'started',
    ),
));
#$url = new CUrlManager();
?>

<h1> <?php echo $model->name;?> :: Resources Available </h1>
<?php
if ($available) {
    $this->widget('zii.widgets.CDetailView', array(
        'data' => $available,
        'attributes' => array(
            'arch',
            'mem',
            'ncpus',
            'nodect',
            'nodes',
            'procct',
            'pvmem',
            'vmem',
            'walltime'
        ),
    ));
}else{
    echo "<h4>Not yet added</h4>";
}
?>
<h1> <?php echo $model->name;?> :: Resources Default </h1>
<?php
if ($default) {
    $this->widget('zii.widgets.CDetailView', array(
        'data' => $default,
        'attributes' => array(
            'arch',
            'mem',
            'ncpus',
            'nodect',
            'nodes',
            'procct',
            'pvmem',
            'vmem',
            'walltime'
        ),
    ));
}else{
    echo "<h4>Not yet added</h4>";
}
?>
<h1> <?php echo $model->name;?> :: Resources Max </h1>
<?php
if ($max) {
    $this->widget('zii.widgets.CDetailView', array(
        'data' => $max,
        'attributes' => array(
            'arch',
            'mem',
            'ncpus',
            'nodect',
            'nodes',
            'procct',
            'pvmem',
            'vmem',
            'walltime'
        ),
    ));
}else{
    echo "<h4>Not yet added</h4>";
}
?>
<h1> <?php echo $model->name;?> :: Resources Min </h1>
<?php
if ($min) {
    $this->widget('zii.widgets.CDetailView', array(
        'data' => $min,
        'attributes' => array(
            'arch',
            'mem',
            'ncpus',
            'nodect',
            'nodes',
            'procct',
            'pvmem',
            'vmem',
            'walltime'
        ),
    ));
}else{
    echo "<h4>Not yet added</h4>";
}
?>
<div class="row">
    <a href="<?php echo $this->createUrl('queue/resource', array('type' => 'available', 'id' => $model->id)); ?>">Resource Available</a>
    <a href="<?php echo $this->createUrl('queue/resource', array('type' => 'default', 'id' => $model->id)); ?>">Resource Defualt</a>
    <a href="<?php echo $this->createUrl('queue/resource', array('type' => 'max', 'id' => $model->id)); ?>">Resource Max</a>
    <a href="<?php echo $this->createUrl('queue/resource', array('type' => 'min', 'id' => $model->id)); ?>">Resource Min</a>
</div>
