<?php

$this->breadcrumbs = array(
    'Nodes' => array('index'),
    $model->name => array('node/' . $model->id),
    'details'
);
foreach (Yii::app()->user->getFlashes() as $key => $message) {
    #if (Yii::app()->user->hasFlash($key)) {
        echo '<div class="flash-' . $key . '">' . $message . "</div>";
    #}
}
if (isset($model->status)) {
    $nodeStatusDetails = json_decode($model->status, TRUE);
    if (isset($nodeStatusDetails['gpus'])) {
        $nodeStatusDetails['#_GPUs'] = $nodeStatusDetails['gpus'];
        unset($nodeStatusDetails['gpus']);
    }
    if (isset($nodeStatusDetails['ntype'])) {
        $nodeStatusDetails['node_type'] = $nodeStatusDetails['ntype'];
        unset($nodeStatusDetails['ntype']);
    }
    if (isset($nodeStatusDetails['np'])) {
        $nodeStatusDetails['#_processors'] = $nodeStatusDetails['np'];
        unset($nodeStatusDetails['np']);
    }
    if (isset($nodeStatusDetails['status'])) {
        if (isset($nodeStatusDetails['status']['rectime'])) {
            $nodeStatusDetails['status']['rectime'] = date('Y-m-d H:i:s', (int) $nodeStatusDetails['status']['rectime']);
        }
        if (isset($nodeStatusDetails['status']['varattr'])) {
            $nodeStatusDetails['status']['variable_attribute'] = $nodeStatusDetails['status']['varattr'];
            unset($nodeStatusDetails['status']['varattr']);
        }
        if (isset($nodeStatusDetails['status']['idletime'])) {
            $minutes = round($nodeStatusDetails['status']['idletime'] / 60);
//            $hours = floor($nodeStatusDetails['status']['idletime'] / (60 * 60));
//            $minutes = floor(($nodeStatusDetails['status']['idletime'] - ($hours * 60 * 60) ) / 60);
//            $seconds = ($nodeStatusDetails['status']['idletime'] - ($hours * 60 * 60) - ($minute.s * 60) );
//            $hours = strlen($hours) == 1 ? "0" . $hours : $hours;
//            $minutes = strlen($minutes) == 1 ? "0" . $minutes : $minutes;
//            $seconds = strlen($seconds) == 1 ? "0" . $seconds : $seconds;
            $nodeStatusDetails['status']['idle_time'] = $minutes . " mins";
            unset($nodeStatusDetails['status']['idletime']);
        }
        if (isset($nodeStatusDetails['status']['ncpus'])) {
            $nodeStatusDetails['status']['#_CPUs'] = $nodeStatusDetails['status']['ncpus'];
            unset($nodeStatusDetails['status']['ncpus']);
        }
        if (isset($nodeStatusDetails['status']['physmem'])) {
            $nodeStatusDetails['status']['physical_memory'] = $nodeStatusDetails['status']['physmem'];
            unset($nodeStatusDetails['status']['physmem']);
        }
        if (isset($nodeStatusDetails['status']['availmem'])) {
            $nodeStatusDetails['status']['available_memory'] = $nodeStatusDetails['status']['availmem'];
            unset($nodeStatusDetails['status']['availmem']);
        }
        if (isset($nodeStatusDetails['status']['totmem'])) {
            $nodeStatusDetails['status']['total_memory'] = $nodeStatusDetails['status']['totmem'];
            unset($nodeStatusDetails['status']['totmem']);
        }
        if (isset($nodeStatusDetails['status']['uname'])) {
            $nodeStatusDetails['status']['node_details'] = $nodeStatusDetails['status']['uname'];
            unset($nodeStatusDetails['status']['uname']);
        }
        if (isset($nodeStatusDetails['status']['opsys'])) {
            $nodeStatusDetails['status']['operating_system'] = $nodeStatusDetails['status']['opsys'];
            unset($nodeStatusDetails['status']['opsys']);
        }
        if (isset($nodeStatusDetails['status']['netload'])) {
            $nodeStatusDetails['status']['network_load'] = $nodeStatusDetails['status']['netload'];
            unset($nodeStatusDetails['status']['netload']);
        }
        if (isset($nodeStatusDetails['status']['loadave'])) {
            $nodeStatusDetails['status']['load_average'] = $nodeStatusDetails['status']['loadave'];
            unset($nodeStatusDetails['status']['loadave']);
        }
        if (isset($nodeStatusDetails['status']['nusers'])) {
            $nodeStatusDetails['status']['#_users'] = $nodeStatusDetails['status']['nusers'];
            unset($nodeStatusDetails['status']['nusers']);
        }
        if (isset($nodeStatusDetails['status']['nsessions'])) {
            $nodeStatusDetails['status']['#_sessions'] = $nodeStatusDetails['status']['nsessions'];
            unset($nodeStatusDetails['status']['nsessions']);
        }
        $temp = $nodeStatusDetails['status'];
        unset($nodeStatusDetails['status']);
        $nodeStatusDetails['status'] = $temp;
    }
    unset($nodeStatusDetails['mom_service_port']);
    unset($nodeStatusDetails['mom_manager_port']);
    echo REQUIRED::createXDetailView($nodeStatusDetails);
} else {
    echo '<h4> No Details Found.</h4>';
}