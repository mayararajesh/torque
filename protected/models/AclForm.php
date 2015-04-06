<?php
/**
 * AclForm is using to validate the ACL features of Queue
 *
 * @author Rajesh Mayara<rajesh.mayara@locuz.com>
 * @version     2.0
 * @since       2.0
 */
class AclForm extends CFormModel {

    public $queue_id;
    public $name;
    //--------------------------------------------------------------------------
    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            array('queue_id, name', 'required'),
            array('queue_id', 'numerical', 'integerOnly' => TRUE),
        );
    }
    //--------------------------------------------------------------------------
    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'id'=>'ID',
            'queue_id'=>'Queue',
            'name' => 'Name',
        );
    }

}
#End of the AclForm Class
#End of the AclForm.php file