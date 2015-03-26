<?php
/**
 * Migration to create/drop the tables for config and tbl_users
 * 
 * @access public
 * @since 2.0.0
 * @author Mayara Rajesh <mayara.rajesh@locuz.com>
 */
class m150324_063710_create_tbl_users_table extends CDbMigration {
    //--------------------------------------------------------------------------
    /**
     * Creates the tables for config and tbl_users
     * 
     */
    public function safeUp() {
        $this->createTable('config', array(
            'id' => 'pk',
            'setting' => 'varchar(255)',
            'value' => 'text'
        ));
        $this->createTable('tbl_users', array(
            'id' => 'pk',
            'username' => 'varchar(127) NOT NULL',
            'email' => 'varchar(255) NULL',
            'created_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'timestamp NULL',
            'deleted_at' => 'timestamp NULL',
        ));
        $this->insert('tbl_users', array(
            'username' => 'root',
            'email' => 'root@locuz.com',
        ));
    }
    //--------------------------------------------------------------------------
    /**
     * Drops the tables which is created by safeUp function
     */
    public function safeDown() {
        $this->dropTable('config');
        $this->dropTable('tbl_users');
    }
}
# End of the m150324_063710_create_tbl_users_table class
# End of the m150324_063710_create_tbl_users_table.php