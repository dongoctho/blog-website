<?php

namespace Fuel\Migrations;

class Delete_category_id_in_posts
{
	public function up()
	{
		\DB::query('ALTER TABLE posts DROP FOREIGN KEY fk_category')->execute();
		\DBUtil::drop_fields('posts', array(
			'category_id',
		));
	}

	public function down()
	{
		\DB::query('ALTER TABLE posts ADD CONSTRAINT fk_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL')->execute();
		\DBUtil::add_fields('posts', array(
			'category_id',
		));
	}
}