<?php

namespace Fuel\Migrations;

class Create_categories
{
	public function up()
	{
		\DBUtil::create_table('categories', array(
			'id' => array('type' => 'int', 'unsigned' => true, 'null' => false, 'auto_increment' => true),
			'name' => array('constraint' => 100, 'null' => false, 'type' => 'varchar'),
			'parent_id' => array('null' => true, 'type' => 'int'),
			'created_at' => array('null' => true, 'type' => 'datetime'),
			'updated_at' => array('null' => true, 'type' => 'datetime'),
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('categories');
	}
}