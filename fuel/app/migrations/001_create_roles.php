<?php

namespace Fuel\Migrations;

class Create_roles
{
	public function up()
	{
		\DBUtil::create_table('roles', array(
			'id' => array('type' => 'int', 'unsigned' => true, 'null' => false, 'auto_increment' => true),
			'name' => array('constraint' => 50, 'null' => false, 'type' => 'varchar'),
			'created_at' => array('null' => false, 'type' => 'datetime'),
			'updated_at' => array('null' => false, 'type' => 'datetime'),
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('roles');
	}
}