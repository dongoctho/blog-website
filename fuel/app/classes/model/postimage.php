<?php

use Orm\Model;

class Model_PostImage extends Model
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
		"image_id" => array(
			"label" => "Image id",
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

	protected static $_table_name = 'post_images';

	protected static $_primary_key = array('id');

	protected static $_belongs_to = array(
		'post' => array(
			'key_from' => 'post_id',
			'model_to' => 'Model_Post',
			'key_to' => 'id',
		),
		'image' => array(
			'key_from' => 'image_id',
			'model_to' => 'Model_Image',
			'key_to' => 'id',
		),
	);
}
