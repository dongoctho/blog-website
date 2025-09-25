<?php

class Model_QaLogs extends \Orm\Model
{
	protected static $_properties = array(
		"id" => array(
			"label" => "Id",
			"data_type" => "int",
		),
		"user_id" => array(
			"label" => "User id",
			"data_type" => "int",
		),
		"question" => array(
			"label" => "Question",
			"data_type" => "text",
		),
		"answer" => array(
			"label" => "Answer",
			"data_type" => "text",
		),
		"created_at" => array(
			"label" => "Created at",
			"data_type" => "datetime",
		),
		"updated_at" => array(
			"label" => "Updated at",
			"data_type" => "datetime",
		),
	);

	protected static $_table_name = 'qa_logs';

	protected static $_primary_key = array('id');

	protected static $_has_many = array(
	);

	protected static $_belongs_to = array(
		'user' => array(
			'key_from' => 'user_id',
			'model_to' => 'Model_User',
			'key_to' => 'id',
		),
	);
}
?>