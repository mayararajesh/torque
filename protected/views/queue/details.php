<?php

$this->breadcrumbs = array(
    'Queues' => array('index'),
    $model->name => array('queue/'.$model->id),
    'details'
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
if (isset($model->status)) {
    $queueStatusDetails = json_decode($model->status, TRUE);
    if(isset($queueStatusDetails['mtime'])){
        $queueStatusDetails['mtime'] = date('Y-m-d H:i:s',(int)$queueStatusDetails['mtime']);
    }
    #print_r($queueStatusDetails);
    echo REQUIRED::createXDetailView($queueStatusDetails);
} else {
    echo '<h4> No Details Found.</h4>';
}