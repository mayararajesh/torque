<?php

/**
 * This is the model class for table "queues".
 *
 * The followings are the available columns in table 'queues':
 * @property integer $id
 * @property string $name
 * @property string $disallowed_types
 * @property boolean $enabled
 * @property string $features_required
 * @property integer $keep_completed
 * @property integer $kill_delay
 * @property integer $max_queuable
 * @property integer $max_running
 * @property integer $max_user_queuable
 * @property integer $max_user_run
 * @property integer $priority
 * @property string $queue_type
 * @property string $required_login_property
 * @property boolean $started
 *
 * The followings are the available model relations:
 * @property ResourcesAvailable[] $resourcesAvailables
 * @property ResourcesDefault[] $resourcesDefaults
 * @property ResourcesMax[] $resourcesMaxes
 * @property ResourcesMin[] $resourcesMins
 */
class Queue extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'queues';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('keep_completed, kill_delay, max_queuable, max_running, max_user_queuable, max_user_run, priority', 'numerical', 'integerOnly' => true),
            array('name, required_login_property', 'length', 'max' => 128),
            array('features_required', 'length', 'max' => 30),
            array('disallowed_types, enabled, queue_type, started', 'safe'),
            array('disallowed_types,required_login_property', 'default', 'setOnEmpty' => TRUE, 'value' => NULL),
            array('enabled,started,acl_group_enable,acl_group_sloppy,acl_logic_or,acl_user_enable', 'default', 'setOnEmpty' => TRUE, 'value' => FALSE),
            array('acl_host_enable', 'default', 'setOnEmpty' => TRUE, 'value' => TRUE),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, disallowed_types, enabled, features_required, keep_completed, kill_delay, max_queuable, max_running, max_user_queuable, max_user_run, priority, queue_type, required_login_property, started', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'resourcesAvailables' => array(self::HAS_MANY, 'ResourcesAvailable', 'queue_id'),
            'resourcesDefaults' => array(self::HAS_MANY, 'ResourcesDefault', 'queue_id'),
            'resourcesMaxes' => array(self::HAS_MANY, 'ResourcesMax', 'queue_id'),
            'resourcesMins' => array(self::HAS_MANY, 'ResourcesMin', 'queue_id'),
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

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('disallowed_types', $this->disallowed_types, true);
        $criteria->compare('enabled', $this->enabled);
        $criteria->compare('features_required', $this->features_required, true);
        $criteria->compare('keep_completed', $this->keep_completed);
        $criteria->compare('kill_delay', $this->kill_delay);
        $criteria->compare('max_queuable', $this->max_queuable);
        $criteria->compare('max_running', $this->max_running);
        $criteria->compare('max_user_queuable', $this->max_user_queuable);
        $criteria->compare('max_user_run', $this->max_user_run);
        $criteria->compare('priority', $this->priority);
        $criteria->compare('queue_type', $this->queue_type, true);
        $criteria->compare('required_login_property', $this->required_login_property, true);
        $criteria->compare('started', $this->started);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Queue the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
