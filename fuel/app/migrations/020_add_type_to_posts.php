<?php

namespace Fuel\Migrations;

class Add_type_to_posts
{
	public function up()
	{
		\DBUtil::add_fields('posts', array(
			'type' => array('null' => false, 'type' => 'int', 'default' => 1),
		));
	}

	public function down()
	{
		\DBUtil::drop_fields('posts', array(
			'type',
		));
	}
}