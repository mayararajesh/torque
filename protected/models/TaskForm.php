<?php

/**
 * Manages the PBS Node(cretes,deletes,updtaes and other)
 * 
 * @author Rajesh Mayara<rajesh.mayara@locuz.com>
 * @version     2.0
 * @since       2.0
 */
class TaskForm extends CFormModel {

    public $name;
    public $queue;
    public $nodes;
    public $ppn;

    public function rules() {
        return array(
            array('name,queue,nodes,ppn', 'required'),
            array('name,queue', 'length','max'=>127),
            array('nodes,ppn', 'numerical', 'integerOnly' => TRUE),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'name' => 'Name',
            'queue' => 'Queue',
            'nodes' => 'Nodes',
            'ppn' => 'Processors',
        );
    }

}
