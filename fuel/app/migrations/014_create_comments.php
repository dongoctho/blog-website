<?php

namespace Fuel\Migrations;

class Create_comments
{
	public function up()
	{
		\DBUtil::create_table('comments', array(
			'id' => array('type' => 'int', 'unsigned' => true, 'null' => false, 'auto_increment' => true, 'constraint' => '11'),
			'post_id' => array('constraint' => '11', 'null' => false, 'type' => 'int','unsigned' => true),
			'parent_id' => array('constraint' => '11', 'null' => false, 'type' => 'int','unsigned' => true),
			'user_id' => array('constraint' => '11', 'null' => false, 'type' => 'int','unsigned' => true),
			'content' => array('null' => false, 'type' => 'text'),
			'created_at' => array('null' => false, 'type' => 'timestamp'),
			'updated_at' => array('null' => false, 'type' => 'timestamp'),
		), array('id'));

		\DB::query('ALTER TABLE comments ADD CONSTRAINT fk_comments_post FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE')->execute();
		\DB::query('ALTER TABLE comments ADD CONSTRAINT fk_comments_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE')->execute();
	}

	public function down()
	{
		\DBUtil::drop_table('comments');
	}
}