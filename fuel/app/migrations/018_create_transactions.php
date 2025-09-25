<?php

namespace Fuel\Migrations;

class Create_transactions
{
	public function up()
	{
		\DBUtil::create_table('transactions', array(
			'id' => array('type' => 'int', 'unsigned' => true, 'null' => false, 'auto_increment' => true, 'constraint' => 11),
			'wallet_id' => array('constraint' => 11, 'null' => false, 'type' => 'int'),
			'type' => array('constraint' => 11, 'null' => false, 'type' => 'int'),
			'amount' => array('constraint' => '10,2', 'null' => false, 'type' => 'decimal'),
			'description' => array('null' => false, 'type' => 'text'),
			'created_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
			'updated_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('transactions');
	}
}