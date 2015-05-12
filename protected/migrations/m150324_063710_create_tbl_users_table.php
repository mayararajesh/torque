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
        $this->createTable('users', array(
            'id' => 'pk',
            'username' => 'VARCHAR(127) NOT NULL',
            'email' => 'VARCHAR(255) NULL',
            'pub_key_path' => 'VARCHAR(255) NULL',
            'created_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'timestamp NULL',
            'deleted_at' => 'timestamp NULL',
        ));
    }
    //--------------------------------------------------------------------------
    /**
     * Drops the tables which is created by safeUp function
     */
    public function safeDown() {
        #$this->dropTable('config');
        $this->dropTable('users');
    }
}
# End of the m150324_063710_create_tbl_users_table class
# End of the m150324_063710_create_tbl_users_table.php