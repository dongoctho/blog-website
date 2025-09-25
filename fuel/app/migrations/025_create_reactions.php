<?php

namespace Fuel\Migrations;

class Create_reactions
{
	public function up()
	{
		\DBUtil::create_table('reactions', array(
			'id' => array('type' => 'int', 'unsigned' => true, 'null' => false, 'auto_increment' => true),
			'user_id' => array('null' => false, 'type' => 'int','unsigned' => true),
			'post_id' => array('null' => false, 'type' => 'int','unsigned' => true),
			'reaction_type' => array('constraint' => 20, 'type' => 'varchar'),
			'created_at' => array('null' => false, 'type' => 'datetime'),
			'updated_at' => array('null' => false, 'type' => 'datetime'),
		), array('id'));

		\DB::query('ALTER TABLE reactions ADD CONSTRAINT fk_user_reactions FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE')->execute();
		\DB::query('ALTER TABLE reactions ADD CONSTRAINT fk_post_reactions FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE')->execute();
	}

	public function down()
	{
		\DB::query('ALTER TABLE reactions DROP FOREIGN KEY fk_user_reactions')->execute();
		\DB::query('ALTER TABLE reactions DROP FOREIGN KEY fk_post_reactions')->execute();
		\DBUtil::drop_table('reactions');
	}
}
