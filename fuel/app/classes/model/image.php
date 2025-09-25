<?php

use Fuel\Core\Uri;

class Model_Image extends \Orm\Model
{
	protected static $_properties = array(
		"id" => array(
			"label" => "Id",
			"data_type" => "int",
		),
		"file_path" => array(
			"label" => "File path",
			"data_type" => "varchar",
		),
		"uploaded_by" => array(
			"label" => "Uploaded by",
			"data_type" => "int",
		),
		"uploaded_at" => array(
			"label" => "Uploaded at",
			"data_type" => "datetime",
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

	protected static $_table_name = 'images';

	protected static $_primary_key = array('id');

	protected static $_has_many = array(
	);

	protected static $_many_many = array(
		'posts' => array(
			'key_from' => 'id',
			'key_through_from' => 'image_id',
			'table_through' => 'post_images',
			'key_through_to' => 'post_id',
			'model_to' => 'Model_Post',
			'key_to' => 'id',
		),
	);

	protected static $_has_one = array(
	);

	protected static $_belongs_to = array(
		'user' => array(
			'key_from' => 'uploaded_by',
			'model_to' => 'Model_User',
			'key_to' => 'id',
		),
	);

	/**
	 * Get full URL of image
	 */
	public function get_url()
	{
		return Uri::base() . 'uploads/' . $this->file_path;
	}

	/**
	 * Check if file exists
	 */
	public function file_exists()
	{
		return file_exists(DOCROOT . 'uploads/' . $this->file_path);
	}

}
