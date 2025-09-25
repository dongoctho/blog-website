<?php

namespace Fuel\Migrations;

class Create_qa_logs
{
	public function up()
	{
		\DBUtil::create_table('qa_logs', array(
			'id' => array('type' => 'int', 'unsigned' => true, 'null' => false, 'auto_increment' => true),
			'user_id' => array('null' => true, 'type' => 'int','unsigned' => true),
			'question' => array('null' => false, 'type' => 'text'),
			'answer' => array('null' => false, 'type' => 'text'),
			'created_at' => array('null' => false, 'type' => 'datetime'),
			'updated_at' => array('null' => false, 'type' => 'datetime'),
		), array('id'));

		\DB::query('ALTER TABLE qa_logs ADD CONSTRAINT fk_user_qa_logs FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE')->execute();
	}

	public function down()
	{
		\DBUtil::drop_table('qa_logs');
	}
}
