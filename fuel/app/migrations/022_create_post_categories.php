<?php

namespace Fuel\Migrations;

class Create_post_categories
{
	public function up()
	{
		\DBUtil::create_table('post_categories', array(
			'id' => array('type' => 'int', 'unsigned' => true, 'null' => false, 'auto_increment' => true),
			'post_id' => array('null' => false, 'type' => 'int','unsigned' => true),
			'category_id' => array('null' => false, 'type' => 'int','unsigned' => true),
			'created_at' => array('null' => true, 'type' => 'datetime'),
			'updated_at' => array('null' => true, 'type' => 'datetime'),
		), array('id'));

		\DB::query('ALTER TABLE post_categories ADD CONSTRAINT fk_post_categories_post FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE')->execute();
		\DB::query('ALTER TABLE post_categories ADD CONSTRAINT fk_post_categories_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE')->execute();
	}

	public function down()
	{
		\DB::query('ALTER TABLE post_categories DROP FOREIGN KEY fk_post_categories_post')->execute();
		\DB::query('ALTER TABLE post_categories DROP FOREIGN KEY fk_post_categories_category')->execute();
		\DBUtil::drop_table('post_categories');
	}
}