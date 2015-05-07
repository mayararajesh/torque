<?php

$sort = new CSort();
$sort->attributes = array(
    'id', 'Job_Name', 'application', 'Job_Owner', 'queue', 'job_state', 'etime'
);
$dataProvider = new CArrayDataProvider($model, array(
    'id' => 'id',
    'pagination' => array(
        'pageSize' => 10
    ),
    'sort' => $sort
        ));
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'task-list',
    'afterAjaxUpdate' => 'function(id,data){initilizeGridView();}',
    'dataProvider' => $dataProvider,
    #'filter' => $dataProvider,
    'columns' => array(
        array(
            'name' => 'id',
            'type' => 'raw',
            'header' => 'Job Id',
        ),
        array(
            'name' => 'Job_Name',
            'header' => 'Job Name'
        ),
        array(
            'name' => 'application',
            'header' => 'Application'
        ),
        array(
            'name' => 'Job_Owner',
            'header' => 'Submitted By'
        ),
        array(
            'name' => 'queue',
            'header' => 'Queue'
        ),
        array(
            'name' => 'job_state',
            'header' => 'Job State'
        ),
        array(
            'name' => 'etime',
            'header' => 'Elapsed Time',
            'value' => function($data) {
                return isset($data['etime']) ? date('Y-m-d H:i:s', $data['etime']) : "";
            }
        ),
        array(
            'class' => 'CButtonColumn',
            'template' => '{View}  {Status}  {Delete}',
            'buttons' => array(
                'View' => array(
                    'class' => 'delete-job',
                    'label' => '<i class="font-icon fa fa-search"></i>',
                    'imageUrl' => false,
                    'url' => function($data) {
                        return Yii::app()->createUrl('task/details', array('id' => (int) $data['id']));
                    },
                            'options' => array(
                                'title' => 'Show Job Details'
                            ),
                        ),
                        'Status' => array(
                            'label' => '<i class="font-icon font-icon-status fa fa-pause"></i>',
                            'imageUrl' => false,
                            'url' => function($data) {
                                if (trim($data['job_state']) === "Q") {
                                    return Yii::app()->createUrl('task/hold/', array('id' => (int) $data['id']));
                                } else {
                                    return Yii::app()->createUrl('task/release/', array('id' => (int) $data['id']));
                                }
                            },
                                    'options' => array(
                                        'title' => 'Hold/Release Job'
                                    ),
                                ),
                                'Delete' => array(
                                    'label' => '<i class="delete-job font-icon fa fa-trash-o"></i>',
                                    'imageUrl' => false,
                                    'url' => function($d) {
                                        return 'javascript:void(0)';
                                    },
                                    'options' => array(
                                        'title' => 'Delete Job'
                                    ),
                                ),
                            ),
                        ),
                    ),
                ));
                #sleep(4);