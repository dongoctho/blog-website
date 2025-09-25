<?php

class Model_User extends \Orm\Model
{
	protected static $_properties = array(
		"id" => array(
			"label" => "Id",
			"data_type" => "int",
		),
		"username" => array(
			"label" => "Username",
			"data_type" => "varchar",
		),
		"email" => array(
			"label" => "Email",
			"data_type" => "varchar",
		),
		"password" => array(
			"label" => "Password",
			"data_type" => "varchar",
		),
		"remember_me" => array(
			"label" => "Remember me",
			"data_type" => "boolean",
		),
		"last_login" => array(
			"label" => "Last login",
			"data_type" => "int",
		),
		"login_hash" => array(
			"label" => "Login hash",
			"data_type" => "varchar",
		),
		"created_at" => array(
			"label" => "Created at",
			"data_type" => "datetime",
		),
		"role_id" => array(
			"label" => "Role id",
			"data_type" => "int",
		),
		"updated_at" => array(
			"label" => "Updated at",
			"data_type" => "datetime",
		),
		"google_id" => array(
			"label" => "Google id",
			"data_type" => "varchar",
		),
		"google_avatar" => array(
			"label" => "Google avatar",
			"data_type" => "varchar",
		),
		"is_google_account" => array(
			"label" => "Is google account",
			"data_type" => "boolean",
		),
		"google_access_token" => array(
			"label" => "Google access token",
			"data_type" => "varchar",
		),
		"google_refresh_token" => array(
			"label" => "Google refresh token",
			"data_type" => "varchar",
		),
	);

	protected static $_table_name = 'users';

	protected static $_primary_key = array('id');

	protected static $_has_many = array(
		'posts' => array(
			'key_from' => 'id',
			'model_to' => 'Model_Post',
			'key_to' => 'user_id',
		),
		'images' => array(
			'key_from' => 'id',
			'model_to' => 'Model_Image',
			'key_to' => 'uploaded_by',
		),
		'role' => array(
			'key_from' => 'role_id',
			'model_to' => 'Model_Role',
			'key_to' => 'id',
		),
	);

	protected static $_many_many = array(
	);

	protected static $_has_one = array(
	);

	protected static $_belongs_to = array(
		'role' => array(
			'key_from' => 'role_id',
			'model_to' => 'Model_Role',
			'key_to' => 'id',
		),
	);

}
