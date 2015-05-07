<?php
/**
 * m150330_051452_create_queues_table class is using to specify the structure of
 * the queue related tables and to maintain source code version of the database.
 * 
 * @author Rajesh Mayara<rajesh.mayara@locuz.com>
 * @version     2.0
 * @since       2.0
 */
class m150330_051452_create_queues_table extends CDbMigration {

    // Use safeUp/safeDown to do migration with transaction
    public function safeUp() {
        $this->execute('CREATE TYPE qtype AS ENUM(\'execution\',\'route\')');
        /* $this->execute('CREATE TYPE disallowtypes AS ENUM('
          . '\'interactive\','
          . '\'batch\','
          . '\'rerunable\','
          . '\'nonrerunable\','
          . '\'fault_tolerant\','
          . '\'fault_intolerant\','
          . '\'job_array\')'); */
        $this->createTable('queues', array(
            'id' => 'SERIAL PRIMARY KEY',
            'name' => 'VARCHAR(128) NOT NULL UNIQUE',
            'disallowed_types' => 'VARCHAR(255)',
            'enabled' => 'BOOLEAN DEFAULT FALSE',
            'features_required' => 'VARCHAR(30)',
            'keep_completed' => 'INTEGER DEFAULT 0',
            'kill_delay' => 'INTEGER DEFAULT 2',
            'max_queuable' => 'INTEGER',
            'max_running' => 'INTEGER',
            'max_user_queuable' => 'INTEGER',
            'max_user_run' => 'INTEGER',
            'priority' => 'INTEGER NOT NULL DEFAULT 0',
            'queue_type' => 'qtype',
            'required_login_property' => 'VARCHAR(128)',
            'started' => 'BOOLEAN DEFAULT FALSE',
            'acl_group_enable' => 'BOOLEAN DEFAULT FALSE',
            'acl_group_sloppy' => 'BOOLEAN DEFAULT FALSE',
            'acl_host_enable' => 'BOOLEAN DEFAULT FALSE',
            'acl_logic_or' => 'BOOLEAN DEFAULT FALSE',
            'acl_user_enable' => 'BOOLEAN DEFAULT FALSE',
            'status' => 'TEXT',
        ));
        $this->createTable('resources_available', array(
            'id' => 'SERIAL PRIMARY KEY',
            'queue_id' => 'INTEGER UNIQUE',
            'arch' => 'VARCHAR(128)',
            'mem' => 'VARCHAR(128)',
            'ncpus' => 'INTEGER',
            'nodect' => 'INTEGER',
            'procct' => 'INTEGER',
            'nodes' => 'INTEGER',
            'pvmem' => 'VARCHAR(128)',
            'vmem' => 'VARCHAR(128)',
            'walltime' => 'VARCHAR(128)',
        ));
        $this->createTable('resources_default', array(
            'id' => 'SERIAL PRIMARY KEY',
            'queue_id' => 'INTEGER UNIQUE',
            'arch' => 'VARCHAR(128)',
            'mem' => 'VARCHAR(128)',
            'ncpus' => 'INTEGER',
            'nodect' => 'INTEGER',
            'nodes' => 'INTEGER',
            'procct' => 'INTEGER',
            'pvmem' => 'VARCHAR(128)',
            'vmem' => 'VARCHAR(128)',
            'walltime' => 'VARCHAR(128)',
        ));
        $this->createTable('resources_max', array(
            'id' => 'SERIAL PRIMARY KEY',
            'queue_id' => 'INTEGER UNIQUE',
            'arch' => 'VARCHAR(128)',
            'mem' => 'VARCHAR(128)',
            'ncpus' => 'INTEGER',
            'nodect' => 'INTEGER',
            'nodes' => 'INTEGER',
            'procct' => 'INTEGER',
            'pvmem' => 'VARCHAR(128)',
            'vmem' => 'VARCHAR(128)',
            'walltime' => 'VARCHAR(128)',
        ));
        $this->createTable('resources_min', array(
            'id' => 'SERIAL PRIMARY KEY',
            'queue_id' => 'INTEGER UNIQUE',
            'arch' => 'VARCHAR(128)',
            'mem' => 'VARCHAR(128)',
            'ncpus' => 'INTEGER',
            'nodect' => 'INTEGER',
            'nodes' => 'INTEGER',
            'procct' => 'INTEGER',
            'pvmem' => 'VARCHAR(128)',
            'vmem' => 'VARCHAR(128)',
            'walltime' => 'VARCHAR(128)',
        ));
        $this->createTable('acl_groups', array(
            'id' => 'SERIAL PRIMARY KEY',
            'queue_id' => 'INTEGER',
            'name' => 'VARCHAR(255)'
        ));
        $this->createTable('acl_hosts', array(
            'id' => 'SERIAL PRIMARY KEY',
            'queue_id' => 'INTEGER',
            'name' => 'VARCHAR(255)'
        ));
        $this->createTable('acl_users', array(
            'id' => 'SERIAL PRIMARY KEY',
            'queue_id' => 'INTEGER',
            'name' => 'VARCHAR(255)'
        ));
        $this->addForeignKey('fk_resources_availble', 'resources_available', 'queue_id', 'queues', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_resources_default', 'resources_default', 'queue_id', 'queues', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_resources_max', 'resources_max', 'queue_id', 'queues', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_resources_min', 'resources_min', 'queue_id', 'queues', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_acl_groups', 'acl_groups', 'queue_id', 'queues', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_acl_hosts', 'acl_hosts', 'queue_id', 'queues', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_acl_users', 'acl_users', 'queue_id', 'queues', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown() {
        $this->dropTable('acl_users');
        $this->dropTable('acl_hosts');
        $this->dropTable('acl_groups');
        $this->dropTable('resources_min');
        $this->dropTable('resources_max');
        $this->dropTable('resources_default');
        $this->dropTable('resources_available');
        $this->dropTable('queues');
        $this->execute('DROP TYPE qtype');
    }

}
#End of the m150330_051452_create_queues_table Class
#End of the m150330_051452_create_queues_table.php file