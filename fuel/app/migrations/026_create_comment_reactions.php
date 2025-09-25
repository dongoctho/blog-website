<?php

namespace Fuel\Migrations;

class Create_comment_reactions
{
	public function up()
	{
		\DBUtil::create_table('comment_reactions', array(
			'id' => array('type' => 'int', 'unsigned' => true, 'null' => false, 'auto_increment' => true),
			'user_id' => array('null' => false, 'type' => 'int','unsigned' => true),
			'comment_id' => array('null' => false, 'type' => 'int','unsigned' => true),
			'reaction_type' => array('constraint' => 20, 'type' => 'varchar'),
			'created_at' => array('null' => false, 'type' => 'datetime'),
			'updated_at' => array('null' => false, 'type' => 'datetime'),
		), array('id'));

		\DB::query('ALTER TABLE comment_reactions ADD CONSTRAINT fk_user_comment_reactions FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE')->execute();
		\DB::query('ALTER TABLE comment_reactions ADD CONSTRAINT fk_comment_comment_reactions FOREIGN KEY (comment_id) REFERENCES comments(id) ON DELETE CASCADE')->execute();
	}

	public function down()
	{
		\DB::query('ALTER TABLE comment_reactions DROP FOREIGN KEY fk_user_comment_reactions')->execute();
		\DB::query('ALTER TABLE comment_reactions DROP FOREIGN KEY fk_comment_comment_reactions')->execute();
		\DBUtil::drop_table('comment_reactions');
	}
}