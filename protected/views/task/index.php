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