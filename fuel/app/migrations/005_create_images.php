<?php

namespace Fuel\Migrations;

class Create_images
{
	public function up()
	{
		\DBUtil::create_table('images', array(
			'id' => array('type' => 'int', 'unsigned' => true, 'null' => false, 'auto_increment' => true),
			'file_path' => array('constraint' => 255, 'null' => false, 'type' => 'varchar'),
			'uploaded_by' => array('null' => false, 'type' => 'int'),
			'uploaded_at' => array('null' => false, 'type' => 'datetime'),
			'created_at' => array('null' => true, 'type' => 'datetime'),
			'updated_at' => array('null' => true, 'type' => 'datetime'),
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('images');
	}
}