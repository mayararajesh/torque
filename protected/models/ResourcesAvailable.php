<?php

/**
 * This is the model class for table "resources_available".
 *
 * The followings are the available columns in table 'resources_available':
 * @property integer $id
 * @property integer $queue_id
 * @property string $arch
 * @property integer $mem
 * @property integer $ncpus
 * @property integer $nodect
 * @property integer $nodes
 * @property integer $pvmem
 * @property integer $vmem
 * @property integer $walltime
 * @property integer $procct
 *
 * The followings are the available model relations:
 * @property Queues $queue
 */
class ResourcesAvailable extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'resources_available';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            #array('mem', 'check'),
           array('queue_id,  ncpus, nodect, nodes,  procct, walltime,arch,mem, pvmem, vmem', 'safe'), 
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, queue_id, arch, mem, ncpus, nodect, nodes, pvmem, vmem, walltime, procct', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'queue' => array(self::BELONGS_TO, 'Queues', 'queue_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'queue_id' => 'Queue',
            'arch' => 'Architecture',
            'mem' => 'Memory',
            'ncpus' => 'Ncpus',
            'nodect' => 'Nodect',
            'nodes' => 'Nodes',
            'pvmem' => 'Pvmem',
            'vmem' => 'Virtual memory',
            'walltime' => 'Wall Ttime',
            'procct' => 'Procct',
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
        $criteria->compare('queue_id', $this->queue_id);
        $criteria->compare('arch', $this->arch, true);
        $criteria->compare('mem', $this->mem);
        $criteria->compare('ncpus', $this->ncpus);
        $criteria->compare('nodect', $this->nodect);
        $criteria->compare('nodes', $this->nodes);
        $criteria->compare('pvmem', $this->pvmem);
        $criteria->compare('vmem', $this->vmem);
        $criteria->compare('walltime', $this->walltime, true);
        $criteria->compare('procct', $this->procct);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ResourcesAvailable the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function check($attribute) {
        print_r($attribute);
        exit;
        if (preg_match('/^[0-9]*$/', $this->$attribute['number']))
            return true;
        else
            $this->addError($attribute, 'Memory should be in integer');
    }

}
