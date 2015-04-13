<?php /* source file: /var/www/html/torque/protected/views/task/index.php */ ?>
<?php

/* @var $this TaskController */

$this->breadcrumbs = array(
    'task',
);
?>
<?php

if ($show == "editor") {
    $this->renderPartial('editor', array('content' => $content));
} else {
    $params['model'] = $model;
    if(isset($queues)){
        $params['queues'] = $queues;
    }
    $this->renderPartial('_form', $params);
}?>