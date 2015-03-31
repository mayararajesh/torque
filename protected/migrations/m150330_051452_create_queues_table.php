<?php

class m150330_051452_create_queues_table extends CDbMigration {

    // Use safeUp/safeDown to do migration with transaction
    public function safeUp() {
        $this->execute('CREATE TYPE qtype AS ENUM(\'execution\',\'route\')');
        /*$this->execute('CREATE TYPE disallowtypes AS ENUM('
                . '\'interactive\','
                . '\'batch\','
                . '\'rerunable\','
                . '\'nonrerunable\','
                . '\'fault_tolerant\','
                . '\'fault_intolerant\','
                . '\'job_array\')');*/
        $this->createTable('queues', array(
            'id' => 'SERIAL PRIMARY KEY',
            'name' => 'VARCHAR(128) NOT NULL UNIQUE',
            'disallowed_types' => 'disallowtypes',
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
        ));
        $this->createTable('resources_available', array(
            'id' => 'SERIAL PRIMARY KEY',
            'queue_id' => 'INTEGER UNIQUE',
            'arch' => 'VARCHAR(128)',
            'mem' => 'VARCHAR(255)',
            'ncpus' => 'INTEGER',
            'nodect' => 'INTEGER',
            'procct' => 'INTEGER',
            'nodes' => 'INTEGER',
            'pvmem' => 'VARCHAR(255)',
            'vmem' => 'VARCHAR(255)',
            'walltime' => 'INTEGER',
        ));
        $this->createTable('resources_default', array(
            'id' => 'SERIAL PRIMARY KEY',
            'queue_id' => 'INTEGER UNIQUE',
            'arch' => 'VARCHAR(128)',
            'mem' => 'VARCHAR(255)',
            'ncpus' => 'INTEGER',
            'nodect' => 'INTEGER',
            'nodes' => 'INTEGER',
            'procct' => 'INTEGER',
            'pvmem' => 'VARCHAR(255)',
            'vmem' => 'VARCHAR(255)',
            'walltime' => 'INTEGER',
        ));
        $this->createTable('resources_max', array(
            'id' => 'SERIAL PRIMARY KEY',
            'queue_id' => 'INTEGER UNIQUE',
            'arch' => 'VARCHAR(128)',
            'mem' => 'VARCHAR(255)',
            'ncpus' => 'INTEGER',
            'nodect' => 'INTEGER',
            'nodes' => 'INTEGER',
            'procct' => 'INTEGER',
            'pvmem' => 'VARCHAR(255)',
            'vmem' => 'VARCHAR(255)',
            'walltime' => 'INTEGER',
        ));
        $this->createTable('resources_min', array(
            'id' => 'SERIAL PRIMARY KEY',
            'queue_id' => 'INTEGER UNIQUE',
            'arch' => 'VARCHAR(128)',
            'mem' => 'VARCHAR(255)',
            'ncpus' => 'INTEGER',
            'nodect' => 'INTEGER',
            'nodes' => 'INTEGER',
            'procct' => 'INTEGER',
            'pvmem' => 'VARCHAR(255)',
            'vmem' => 'VARCHAR(255)',
            'walltime' => 'INTEGER',
        ));
        $this->addForeignKey('fk_resources_availble', 'resources_available', 'queue_id', 'queues', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_resources_default', 'resources_default', 'queue_id', 'queues', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_resources_max', 'resources_max', 'queue_id', 'queues', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_resources_min', 'resources_min', 'queue_id', 'queues', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown() {
        $this->dropTable('resources_min');
        $this->dropTable('resources_max');
        $this->dropTable('resources_default');
        $this->dropTable('resources_available');
        $this->dropTable('queues');
        #$this->execute('DROP TYPE disallowtypes');
        $this->execute('DROP TYPE qtype');
    }

}
