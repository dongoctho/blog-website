<?php

use Orm\Model;

class Model_PostCategory extends Model
{
	protected static $_properties = array(
		"id" => array(
			"label" => "Id",
			"data_type" => "int",
		),
		"post_id" => array(
			"label" => "Post id",
			"data_type" => "int",
		),
		"category_id" => array(
			"label" => "Category id",
			"data_type" => "int",
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

	protected static $_table_name = 'post_categories';

	protected static $_primary_key = array('id');

	protected static $_belongs_to = array(
		'post' => array(
			'key_from' => 'post_id',
			'model_to' => 'Model_Post',
			'key_to' => 'id',
		),
		'category' => array(
			'key_from' => 'category_id',
			'model_to' => 'Model_Category',
			'key_to' => 'id',
		),
	);
}

?>