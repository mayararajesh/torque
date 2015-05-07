<?php

class m150428_060231_job_details extends CDbMigration
{
	public function safeUp() 
	{
        $this->createTable('jobs', array(
            'id' => 'SERIAL PRIMARY KEY',
            'job_id' => 'INTEGER UNIQUE',
            'status' => 'TEXT',
            'submitted_by' => 'VARCHAR(255)',
            'application' => 'VARCHAR(255)',
            'is_deleted' => 'BOOLEAN DEFAULT FALSE',
        ));
	}

	public function safeDown()
	{
		$this->dropTable('jobs');
	}

}