<?php

class Model_Category extends \Orm\Model
{
	protected static $_properties = array(
		"id" => array(
			"label" => "Id",
			"data_type" => "int",
		),
		"name" => array(
			"label" => "Name",
			"data_type" => "varchar",
		),
		"parent_id" => array(
			"label" => "Parent id",
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

	protected static $_table_name = 'categories';

	protected static $_primary_key = array('id');

	protected static $_has_many = array(
		'post_categories' => array(
			'key_from' => 'id',
			'model_to' => 'Model_PostCategory',
			'key_to' => 'category_id',
		),
		'children' => array(
			'key_from' => 'id',
			'model_to' => 'Model_Category',
			'key_to' => 'parent_id',
		),
	);

	protected static $_many_many = array(
	);

	protected static $_has_one = array(
	);

	protected static $_belongs_to = array(
		'parent' => array(
			'key_from' => 'parent_id',
			'model_to' => 'Model_Category',
			'key_to' => 'id',
		),
	);

	/**
	 * Lấy danh sách posts thuộc category này
	 */
	public function get_posts()
	{
		$post_categories = $this->post_categories;
		$posts = array();
		foreach ($post_categories as $post_category) {
			$posts[] = $post_category->post;
		}
		return $posts;
	}
}
