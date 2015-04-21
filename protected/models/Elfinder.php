<?php

/**
 * Manages the PBS Node(cretes,deletes,updtaes and other)
 * 
 * @author Rajesh Mayara<rajesh.mayara@locuz.com>
 * @version     2.0
 * @since       2.0
 */
class Elfinder extends CFormModel {

    public $serverFile;
    

    public function rules() {
        return array(
            
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'serverFile' => 'serverFile',
            
        );
    }

}
