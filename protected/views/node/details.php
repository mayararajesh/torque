<?php

$this->breadcrumbs = array(
    'Nodes' => array('index'),
    $model->name
);
foreach (Yii::app()->user->getFlashes() as $key => $message) {
    #if (Yii::app()->user->hasFlash($key)) {
    echo '<div class="flash-' . $key . '">' . $message . "</div>";
    #}
}
$this->menu = array(
    array('label' => 'List Node', 'url' => array('index')),
    array('label' => 'Create Node', 'url' => array('create')),
    array('label' => 'Update Node', 'url' => array('update', 'id' => $model->id)),
    array('label' => 'Delete Node', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
    array('label' => 'Manage Node', 'url' => array('admin')),
);
$response = "<h4> No Details Found.</h4>";
if (isset($model->status)) {
    $nodeStatusDetails = json_decode($model->status, TRUE);
    if (isset($nodeStatusDetails['gpus'])) {
        REQUIRED::replaceKey(&$nodeStatusDetails, 'gpus', '#_GPUs');
    }
    if (isset($nodeStatusDetails['ntype'])) {
        REQUIRED::replaceKey(&$nodeStatusDetails, 'ntype', 'node_type');
    }
    if (isset($nodeStatusDetails['np'])) {
        REQUIRED::replaceKey(&$nodeStatusDetails, 'np', '#_processors');
    }
    if (isset($nodeStatusDetails['status'])) {
        if (isset($nodeStatusDetails['status']['rectime'])) {
            $nodeStatusDetails['status']['rectime'] = date('Y-m-d H:i:s', (int) $nodeStatusDetails['status']['rectime']);
            REQUIRED::replaceKey(&$nodeStatusDetails['status'], 'rectime', 'last_recorded_time');
        }
        if (isset($nodeStatusDetails['status']['varattr'])) {
            REQUIRED::replaceKey(&$nodeStatusDetails['status'], 'varattr', 'variable_attribute');
        }
        if (isset($nodeStatusDetails['status']['idletime'])) {
            $minutes = round($nodeStatusDetails['status']['idletime'] / 60);
//            $hours = floor($nodeStatusDetails['status']['idletime'] / (60 * 60));
//            $minutes = floor(($nodeStatusDetails['status']['idletime'] - ($hours * 60 * 60) ) / 60);
//            $seconds = ($nodeStatusDetails['status']['idletime'] - ($hours * 60 * 60) - ($minute.s * 60) );
//            $hours = strlen($hours) == 1 ? "0" . $hours : $hours;
//            $minutes = strlen($minutes) == 1 ? "0" . $minutes : $minutes;
//            $seconds = strlen($seconds) == 1 ? "0" . $seconds : $seconds;
            $nodeStatusDetails['status']['idletime'] = $minutes . " mins";
            REQUIRED::replaceKey(&$nodeStatusDetails['status'], 'idletime', 'idle_time');
        }
        if (isset($nodeStatusDetails['status']['ncpus'])) {
            REQUIRED::replaceKey(&$nodeStatusDetails['status'], 'ncpus', '#_CPUs');
        }
        if (isset($nodeStatusDetails['status']['physmem'])) {
            REQUIRED::replaceKey(&$nodeStatusDetails['status'], 'physmem', 'physical_memory');
        }
        if (isset($nodeStatusDetails['status']['availmem'])) {
            REQUIRED::replaceKey(&$nodeStatusDetails['status'], 'availmem', 'available_memory');
        }
        if (isset($nodeStatusDetails['status']['totmem'])) {
            REQUIRED::replaceKey(&$nodeStatusDetails['status'], 'totmem', 'total_memory');
        }
        if (isset($nodeStatusDetails['status']['uname'])) {
            REQUIRED::replaceKey(&$nodeStatusDetails['status'], 'uname', 'node_details');
        }
        if (isset($nodeStatusDetails['status']['opsys'])) {
            REQUIRED::replaceKey(&$nodeStatusDetails['status'], 'opsys', 'operating_system');
        }
        if (isset($nodeStatusDetails['status']['netload'])) {
            REQUIRED::replaceKey(&$nodeStatusDetails['status'], 'netload', 'network_load');
        }
        if (isset($nodeStatusDetails['status']['loadave'])) {
            REQUIRED::replaceKey(&$nodeStatusDetails['status'], 'loadave', 'load_average');
        }
        if (isset($nodeStatusDetails['status']['nusers'])) {
            REQUIRED::replaceKey(&$nodeStatusDetails['status'], 'nusers', '#_users');
        }
        if (isset($nodeStatusDetails['status']['nsessions'])) {
            REQUIRED::replaceKey(&$nodeStatusDetails['status'], 'nsessions', '#_sessions');
        }
        $temp = $nodeStatusDetails['status'];
        unset($nodeStatusDetails['status']);
        $nodeStatusDetails['status'] = $temp;
    }
    unset($nodeStatusDetails['mom_service_port']);
    unset($nodeStatusDetails['mom_manager_port']);
    $response = REQUIRED::createXDetailView($nodeStatusDetails);
}
echo $response;
