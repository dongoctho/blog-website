<?php

namespace Fuel\Migrations;

class Create_posts
{
	public function up()
	{
		\DBUtil::create_table('posts', array(
			'id' => array('type' => 'int', 'unsigned' => true, 'null' => false, 'auto_increment' => true),
			'title' => array('constraint' => 255, 'null' => false, 'type' => 'varchar'),
			'slug' => array('constraint' => 255, 'null' => false, 'type' => 'varchar'),
			'content' => array('null' => false, 'type' => 'text'),
			'user_id' => array('null' => false, 'type' => 'int','unsigned' => true),
			'category_id' => array('null' => true, 'type' => 'int','unsigned' => true),
			'is_published' => array('null' => false, 'type' => 'boolean'),
			'created_at' => array('null' => false, 'type' => 'datetime'),
			'updated_at' => array('null' => false, 'type' => 'datetime'),
		), array('id'));

		\DB::query('ALTER TABLE posts ADD CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE')->execute();
    	\DB::query('ALTER TABLE posts ADD CONSTRAINT fk_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL')->execute();
	}

	public function down()
	{
		\DBUtil::drop_table('posts');
	}
}