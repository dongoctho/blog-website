<?php

namespace Fuel\Migrations;

class Create_wallets
{
	public function up()
	{
		\DBUtil::create_table('wallets', array(
			'id' => array('type' => 'int', 'unsigned' => true, 'null' => false, 'auto_increment' => true, 'constraint' => 11),
			'user_id' => array('constraint' => 11, 'null' => false, 'type' => 'int'),
			'balance' => array('constraint' => '10,2', 'null' => false, 'type' => 'decimal'),
			'currency' => array('constraint' => 255, 'null' => false, 'type' => 'varchar'),
			'created_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
			'updated_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('wallets');
	}
}