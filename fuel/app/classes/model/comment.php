<?php

class Model_Comment extends \Orm\Model
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
		"parent_id" => array(
			"label" => "Parent id",
			"data_type" => "int",
		),
		"user_id" => array(
			"label" => "User id",
			"data_type" => "int",
		),
		"content" => array(
			"label" => "Content",
			"data_type" => "text",
		),
		"created_at" => array(
			"label" => "Created at",
			"data_type" => "timestamp",
		),
		"updated_at" => array(
			"label" => "Updated at",
			"data_type" => "timestamp",
		),
	);

	protected static $_table_name = 'comments';

	protected static $_primary_key = array('id');

	protected static $_has_many = array(
		'replies' => [
            'model_to' => 'Model_Comment',
            'key_from' => 'id',
            'key_to' => 'parent_id',
        ],
        'reactions' => [
            'model_to' => 'Model_CommentReaction',
            'key_from' => 'id',
            'key_to' => 'comment_id',
        ],
	);

	protected static $_many_many = array(
	);

	protected static $_has_one = array(
	);

	protected static $_belongs_to = array(
		'post' => [
            'model_to' => 'Model_Post',
            'key_from' => 'post_id',
            'key_to' => 'id',
        ],
        'user' => [
            'model_to' => 'Model_User',
            'key_from' => 'user_id',
            'key_to' => 'id',
        ],
        'parent' => [
            'model_to' => 'Model_Comment',
            'key_from' => 'parent_id',
            'key_to' => 'id',
        ],

	);

}
