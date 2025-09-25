<?php

namespace Fuel\Migrations;

class Create_users
{
	public function up()
	{
		\DBUtil::create_table('users', array(
			'id' => array('type' => 'int', 'unsigned' => true, 'null' => false, 'auto_increment' => true),
			'username' => array('constraint' => 50, 'null' => false, 'type' => 'varchar'),
			'email' => array('constraint' => 100, 'null' => false, 'type' => 'varchar'),
			'password' => array('constraint' => 255, 'null' => false, 'type' => 'varchar'),
			'role_id' => array('null' => false, 'type' => 'int','unsigned' => true),
			'remember_me' => array('null' => false, 'type' => 'boolean','default' => false),
			'last_login' => array('null' => true, 'type' => 'int'),
			'login_hash' => array('type' => 'varchar', 'constraint' => 255, 'null' => true),
			'created_at' => array('null' => false, 'type' => 'datetime'),
			'updated_at' => array('null' => true, 'type' => 'datetime'),
		), array('id'));

		\DB::query('ALTER TABLE users ADD CONSTRAINT fk_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE')->execute();
	}

	public function down()
	{
		\DBUtil::drop_table('users');
	}
}