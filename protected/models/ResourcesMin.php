<?php

/**
 * This is the model class for table "resources_min".
 *
 * The followings are the available columns in table 'resources_min':
 * @property integer $id
 * @property integer $queue_id
 * @property string $arch
 * @property integer $mem
 * @property integer $ncpus
 * @property integer $nodect
 * @property integer $nodes
 * @property integer $pvmem
 * @property integer $vmem
 * @property string $walltime
 * @property integer $procct
 *
 * The followings are the available model relations:
 * @property Queues $queue
 */
class ResourcesMin extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'resources_min';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('queue_id,  ncpus, nodect, nodes, pvmem, vmem, procct, walltime', 'numerical', 'integerOnly'=>true),
			array('arch', 'length', 'max'=>128),
			array('mem, pvmem, vmem', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, queue_id, arch, mem, ncpus, nodect, nodes, pvmem, vmem, walltime, procct', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'queue' => array(self::BELONGS_TO, 'Queues', 'queue_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'queue_id' => 'Queue',
			'arch' => 'Arch',
			'mem' => 'Mem',
			'ncpus' => 'Ncpus',
			'nodect' => 'Nodect',
			'nodes' => 'Nodes',
			'pvmem' => 'Pvmem',
			'vmem' => 'Vmem',
			'walltime' => 'Walltime',
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('queue_id',$this->queue_id);
		$criteria->compare('arch',$this->arch,true);
		$criteria->compare('mem',$this->mem);
		$criteria->compare('ncpus',$this->ncpus);
		$criteria->compare('nodect',$this->nodect);
		$criteria->compare('nodes',$this->nodes);
		$criteria->compare('pvmem',$this->pvmem);
		$criteria->compare('vmem',$this->vmem);
		$criteria->compare('walltime',$this->walltime,true);
		$criteria->compare('procct',$this->procct);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ResourcesMin the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
