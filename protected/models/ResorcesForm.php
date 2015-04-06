<?php

/**
 * ResourceForm Model Calss is using to validate the forms of queue resource_[available|default|max|min]
 *
 * @author Rajesh Mayara<rajesh.mayara@locuz.com>
 * @version     2.0
 * @since       2.0
 */
class ResorcesForm extends CFormModel {

    //put your code here
    public $arch;
    public $ncpus;
    public $nodect;
    public $nodes;
    public $procct;
    public $walltime_hh;
    public $walltime_mm;
    public $walltime_ss;
    public $memNumber;
    public $vmemNumber;
    public $pvmemNumber;
    public $mem_multiplier;
    public $vmem_multiplier;
    public $pvmem_multiplier;

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            array('arch', 'length', 'allowEmpty' => TRUE, 'max' => 255),
            array('nodes,procct,nodect,ncpus,memNumber, pvmemNumber, vmemNumber,walltime_mm,walltime_hh,walltime_ss', 'match', 'allowEmpty' => TRUE, 'pattern' => '/^(\s*|\d+)$/'),
            array('arch, nodes,procct,nodect,ncpus,memNumber, pvmemNumber, vmemNumber', 'default', 'setOnEmpty' => TRUE, 'value' => NULL),
            array('walltime_mm,walltime_hh,walltime_ss', 'default', 'setOnEmpty' => TRUE, 'value' => NULL),
            array('mem_multiplier,vmem_multiplier,pvmem_multiplier','default','setOnEmpty' => TRUE, 'value' => 'mb'),
            array('mem_multiplier,vmem_multiplier,pvmem_multiplier','in','range'=>array('mb','gb','tb'),'allowEmpty'=>FALSE),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'queue_id' => 'Queue',
            'arch' => 'Architecture',
            'memNumber' => 'Memory',
            'ncpus' => 'Ncpus',
            'nodect' => 'Nodect',
            'nodes' => 'Nodes',
            'pvmemNumber' => 'Pvmem',
            'vmemNumber' => 'Virtual memory',
            'walltime_mm' => 'Wall Time(minutes)',
            'walltime_hh' => 'Wall Time(hours)',
            'walltime_ss' => 'Wall Time(seconds)',
            'procct' => 'Procct',
            'mem_multiplier' => 'Memory Size Type',
            'vmem_multiplier' => 'VMemory Size Type',
            'pvmem_multiplier' => 'PVMemory Size Type',
        );
    }

}

#End of the ResorcesForm Class
#End of the ResorcesForm.php file