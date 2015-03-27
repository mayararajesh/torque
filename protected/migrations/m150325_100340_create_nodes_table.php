<?php
/**
 * Node Table structure
 * 
 * @author Mayara Rajesh<rajesh.mayara@locuz.com>
 * @version 2.0
 * @since 2.0
 */
class m150325_100340_create_nodes_table extends CDbMigration {
    /*
      public function up() {

      }

      public function down() {
      echo "m150325_100340_create_nodes_table does not support migration down.\n";
      return false;
      }
     */

    // Use safeUp/safeDown to do migration with transaction
    public function safeUp() {
        $this->createTable('nodes',array(
            'id' => 'pk',
            'name' => 'varchar(255) NOT NULL',
            'np' => 'int(10) NULL',
            'gpus' => 'int(10) NULL',
            'mics' => 'int(10) NULL',
        ));
    }

    public function safeDown() {
        $this->dropTable('nodes');
    }

}
