<?php
/* @var $this QueueController */
/* @var $model Queue */

$this->breadcrumbs = array(
    'Queues' => array('index'),
    $model->name,
);
foreach (Yii::app()->user->getFlashes() as $key => $message) {
    if (Yii::app()->user->hasFlash($key)) {
        echo '<div class="flash-' . $key . '">' . $message . "</div>";
    }
}
$this->menu = array(
    array('label' => 'List Queue', 'url' => array('index')),
    array('label' => 'Add Queue', 'url' => array('create')),
    array('label' => 'Edit Queue', 'url' => array('update', 'id' => $model->id)),
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
        array(
            'name' => 'enabled',
            'label' => 'Enabled',
            'value' => (($model->enabled === FALSE) ? "No" : "Yes"),
        ),
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
        array(
            'name' => 'started',
            'label' => 'Started',
            'value' => (($model->started === FALSE) ? "No" : "Yes"),
        ),
        array(
            'name' => 'acl_group_enable',
            'label' => 'ACL Group Enable',
            'value' => (($model->acl_group_enable === FALSE) ? "FALSE" : "TRUE"),
        ),
        array(
            'name' => 'acl_group_sloppy',
            'label' => 'ACL Group Sloppy',
            'value' => (($model->acl_group_sloppy === FALSE) ? "FALSE" : "TRUE"),
        ),
        array(
            'name' => 'acl_logic_or',
            'label' => 'ACL Logic OR',
            'value' => (($model->acl_logic_or === FALSE) ? "FALSE" : "TRUE"),
        ),
        array(
            'name' => 'acl_user_enable',
            'label' => 'ACL User Enable',
            'value' => (($model->acl_user_enable === FALSE) ? "FALSE" : "TRUE"),
        ),
        array(
            'name' => 'acl_host_enable',
            'label' => 'ACL Host Enable',
            'value' => (($model->acl_host_enable === FALSE) ? "FALSE" : "TRUE"),
        ),
    ),
));
#$url = new CUrlManager();
?>

<h1> <?php echo $model->name; ?> :: <a alt="Resource Available" titile="Edit" href="<?php echo $this->createUrl('queue/resource', array('type' => 'available', 'id' => $model->id)); ?>">Resource Available</a> </h1>
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
} else {
    echo "<h4>Not yet added</h4>";
}
?>
<h1> <?php echo $model->name; ?> :: <a alt="Resource Default" titile="Edit" href="<?php echo $this->createUrl('queue/resource', array('type' => 'default', 'id' => $model->id)); ?>">Resource Defualt</a> </h1>
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
} else {
    echo "<h4>Not yet added</h4>";
}
?>
<h1> <?php echo $model->name; ?> :: <a alt="Resource Maximum" titile="Edit" href="<?php echo $this->createUrl('queue/resource', array('type' => 'max', 'id' => $model->id)); ?>">Resource Max</a> </h1>
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
} else {
    echo "<h4>Not yet added</h4>";
}
?>
<h1> <?php echo $model->name; ?> :: <a alt="Resource Minimum" titile="Edit" href="<?php echo $this->createUrl('queue/resource', array('type' => 'min', 'id' => $model->id)); ?>">Resource Min</a> </h1>
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
} else {
    echo "<h4>Not yet added</h4>";
}
?>
    <?php if ($model->acl_group_enable) { ?>
    <h1> <?php echo $model->name; ?> :: <a alt="ACL users" titile="Edit" href="<?php echo $this->createUrl('queue/acl', array('type' => 'groups', 'id' => $model->id)); ?>">ACL Groups</a> </h1>
    <?php
    if (isset($groups)) {
        $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'acl-hosts-queue-grid',
            'dataProvider' => $groups->search(),
            'columns' => array(
                'id',
                'name',
                array(
                    'class' => 'CButtonColumn',
                    'template' => '{Update} {Delete}',
                    'buttons' => array(
                        'Update' => array(
                            'label' => '<i class="font-icon fa fa-pencil-square"></i>',
                            'imageUrl' => false,
                            'url' => '$this->grid->controller->createUrl("/queue/acl/".$data->queue_id."?type=groups&action=edit&aclId=$data->primaryKey")',
                            'options' => array(
                                'title' => 'Edit Group',
                                'class' => 'editAcl'
                            ),
                        ),
                        'Delete' => array(
                            'label' => '<i class="font-icon font-icon-status fa fa-times-circle"></i>',
                            'imageUrl' => false,
                            'linkOptions' => array('submit' => array('acl?type=hosts&action=delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?'),
                            'url' => '$this->grid->controller->createUrl("/queue/acl/".$data->queue_id."?type=groups&action=delete&aclId=$data->primaryKey")',
                            'options' => array(
                                'title' => 'Delete Group',
                                'class' => 'deleteAcl'
                            ),
                        ),
                    ),
                ),
            ),
        ));
        ?>
        <script type="text/javascript">
            jQuery('#body').on('click', '.deleteAcl', function () {
                if (confirm('Are you sure you want to delete this host?')) {
                    jQuery.yii.submitForm(this, $(this).href, {
                    });
                    return false;
                } else
                    return false;
            });
        </script>
        <?php
    } else {
        echo '<h4>Not yet added.';
    }
}
?>
<?php if ($model->acl_user_enable) { ?>
    <h1> <?php echo $model->name; ?> :: <a alt="ACL users" titile="Edit" href="<?php echo $this->createUrl('queue/acl', array('type' => 'users', 'id' => $model->id)); ?>">ACL Users</a> </h1>
    <?php
    if (isset($users)) {
        $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'acl-hosts-queue-grid',
            'dataProvider' => $users->search(),
            'columns' => array(
                'id',
                'name',
                array(
                    'class' => 'CButtonColumn',
                    'template' => '{Update} {Delete}',
                    'buttons' => array(
                        'Update' => array(
                            'label' => '<i class="font-icon fa fa-pencil-square"></i>',
                            'imageUrl' => false,
                            'url' => '$this->grid->controller->createUrl("/queue/acl/".$data->queue_id."?type=users&action=edit&aclId=$data->primaryKey")',
                            'options' => array(
                                'title' => 'Edit User',
                                'class' => 'editAcl'
                            ),
                        ),
                        'Delete' => array(
                            'label' => '<i class="font-icon font-icon-status fa fa-user-times"></i>',
                            'imageUrl' => false,
                            'linkOptions' => array('submit' => array('acl?type=hosts&action=delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?'),
                            'url' => '$this->grid->controller->createUrl("/queue/acl/".$data->queue_id."?type=users&action=delete&aclId=$data->primaryKey")',
                            'options' => array(
                                'title' => 'Delete User',
                                'class' => 'deleteAcl'
                            ),
                        ),
                    ),
                ),
            ),
        ));
        ?>
        <script type="text/javascript">
            jQuery('#body').on('click', '.deleteAcl', function () {
                if (confirm('Are you sure you want to delete this host?')) {
                    jQuery.yii.submitForm(this, $(this).href, {
                    });
                    return false;
                } else
                    return false;
            });
        </script>
        <?php
    } else {
        echo '<h4>Not yet added.';
    }
}
?>

<?php if ($model->acl_host_enable) { ?>
    <h1> <?php echo $model->name; ?> :: <a alt="ACL hosts" titile="Edit" href="<?php echo $this->createUrl('queue/acl', array('type' => 'hosts', 'id' => $model->id)); ?>">ACL Hosts</a> </h1>
    <?php
    if (isset($hosts)) {
        $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'acl-hosts-queue-grid',
            'dataProvider' => $hosts->search(),
            'columns' => array(
                'id',
                'name',
                array(
                    'class' => 'CButtonColumn',
                    'template' => '{Update} {Delete}',
                    'buttons' => array(
                        'Update' => array(
                            'label' => '<i class="font-icon fa fa-pencil-square"></i>',
                            'imageUrl' => false,
                            'url' => '$this->grid->controller->createUrl("/queue/acl/".$data->queue_id."?type=hosts&action=edit&aclId=$data->primaryKey")',
                            'options' => array(
                                'title' => 'Edit Host',
                                'class' => 'editAcl'
                            ),
                        ),
                        'Delete' => array(
                            'label' => '<i class="font-icon font-icon-status fa fa-times-circle"></i>',
                            'imageUrl' => false,
                            'linkOptions' => array('submit' => array('acl?type=hosts&action=delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?'),
                            'url' => '$this->grid->controller->createUrl("/queue/acl/".$data->queue_id."?type=hosts&action=delete&aclId=$data->primaryKey")',
                            'options' => array(
                                'title' => 'Delete Host',
                                'class' => 'deleteAcl'
                            ),
                        ),
                    ),
                ),
            ),
        ));
        ?>
        <script type="text/javascript">
            jQuery('#body').on('click', '.deleteAcl', function () {
                if (confirm('Are you sure you want to delete this host?')) {
                    jQuery.yii.submitForm(this, $(this).href, {
                    });
                    return false;
                } else
                    return false;
            });
        </script>
        <?php
    } else {
        echo '<h4>Not yet added.';
    }
}
?>
