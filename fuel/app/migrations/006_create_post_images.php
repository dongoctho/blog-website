<?php

namespace Fuel\Migrations;

class Create_post_images
{
	public function up()
	{
		\DBUtil::create_table('post_images', array(
			'id' => array('type' => 'int', 'unsigned' => true, 'null' => false, 'auto_increment' => true),
			'post_id' => array('null' => false, 'type' => 'int','unsigned' => true),
			'image_id' => array('null' => false, 'type' => 'int','unsigned' => true),
			'created_at' => array('null' => true, 'type' => 'datetime'),
			'updated_at' => array('null' => true, 'type' => 'datetime'),
		), array('id'));

		\DB::query('ALTER TABLE post_images ADD CONSTRAINT fk_post FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE')->execute();
		\DB::query('ALTER TABLE post_images ADD CONSTRAINT fk_image FOREIGN KEY (image_id) REFERENCES images(id) ON DELETE CASCADE')->execute();
	}

	public function down()
	{
		\DBUtil::drop_table('post_images');
	}
}