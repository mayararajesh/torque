<?php

/**
 * QueuesForm Class is using to validates the Queue form data
 *
 * @author Rajesh Mayara<rajesh.mayara@locuz.com>
 * @version     2.0
 * @since       2.0
 */
class QueuesForm extends CFormModel {

    public $name;
    public $disallowed_types;
    public $enabled;
    public $features_required;
    public $keep_completed;
    public $kill_delay;
    public $max_queuable;
    public $max_running;
    public $max_user_queuable;
    public $max_user_run;
    public $priority;
    public $queue_type;
    public $required_login_property;
    public $started;
    public $acl_group_enable;
    public $acl_group_sloppy;
    public $acl_host_enable;
    public $acl_logic_or;
    public $acl_user_enable;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name,priority,enabled,started,queue_type', 'required'),
            #array('name','contraints','readOnly'=>true, 'on'=>'update'),
            array('acl_host_enable,acl_group_enable,acl_group_sloppy,acl_logic_or,acl_user_enable', 'default','setOnEmpty'=>TRUE,'value'=>'0'),
            array('keep_completed, kill_delay, max_queuable, max_running, max_user_queuable, max_user_run, priority', 'numerical', 'integerOnly' => true),
            array('name, required_login_property', 'length', 'max' => 128),
            array('features_required', 'length', 'max' => 30),
            array('required_login_property,disallowed_types,features_required,keep_completed, kill_delay,max_queuable, max_running, max_user_queuable, max_user_run', 'default', 'setOnEmpty' => true, 'value' => NULL),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'disallowed_types' => 'Disallowed Types',
            'enabled' => 'Enabled',
            'features_required' => 'Features Required',
            'keep_completed' => 'Keep Completed',
            'kill_delay' => 'Kill Delay',
            'max_queuable' => 'Max Queuable',
            'max_running' => 'Max Running',
            'max_user_queuable' => 'Max User Queuable',
            'max_user_run' => 'Max User Run',
            'priority' => 'Priority',
            'queue_type' => 'Queue Type',
            'required_login_property' => 'Required Login Property',
            'started' => 'Started',
            'acl_group_enable' => 'ACL Group Enable',
            'acl_group_sloppy' => 'ACL Group Sloppy',
            'acl_logic_or' => 'ACL Logic OR',
            'acl_user_enable' => 'ACL User Enable',
            'acl_host_enable' => 'ACL Host Enable',
        );
    }

}

#End of the QueuesForm Class
#End of the QueuesForm.php file