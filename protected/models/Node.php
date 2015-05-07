<?php
/**
 * This is the model class for table "nodes".
 *
 * @author Rajesh Mayara<rajesh.mayara@locuz.com>
 * @version     2.0
 * @since       2.0
 * 
 * The followings are the available columns in table 'nodes':
 * @property integer $id
 * @property string $name
 * @property integer $np
 * @property integer $gpus
 * @property integer $phi
 */
class Node extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nodes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
            array('name', 'length', 'max'=>255),
            array('np', 'default','setOnEmpty' => TRUE, 'value' => 1),
            #array('gpus', 'default','setOnEmpty' => TRUE, 'value' => 0),
			array('np, gpus, mics', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('name, np, gpus, mics,status', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'np' => '#Processors',
			'gpus' => '#GPU',
			'mics' => '#Phi Cards',
            'status' => 'Status'
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('np',$this->np);
		$criteria->compare('gpus',$this->gpus);
		$criteria->compare('mics',$this->mics);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Node the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
