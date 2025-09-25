<?php

use Fuel\Core\Str;

class Model_Post extends \Orm\Model
{
	protected static $_properties = array(
		"id" => array(
			"label" => "Id",
			"data_type" => "int",
		),
		"title" => array(
			"label" => "Title",
			"data_type" => "varchar",
		),
		"slug" => array(
			"label" => "Slug",
			"data_type" => "varchar",
		),
		"content" => array(
			"label" => "Content",
			"data_type" => "text",
		),
		"user_id" => array(
			"label" => "User id",
			"data_type" => "int",
		),
		"is_published" => array(
			"label" => "Is published",
			"data_type" => "boolean",
		),
		"publish_start_date" => array(
			"label" => "Publish start date",
			"data_type" => "datetime",
		),
		"publish_end_date" => array(
			"label" => "Publish end date",
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
		"views" => array(
			"label" => "Views",
			"data_type" => "bigint",
		),
		"type" => array(
			"label" => "Type",
			"data_type" => "int",
		),
	);

	protected static $_table_name = 'posts';

	protected static $_primary_key = array('id');

	protected static $_has_many = array(
		'post_categories' => array(
			'key_from' => 'id',
			'model_to' => 'Model_PostCategory',
			'key_to' => 'post_id',
		),
	);

	protected static $_many_many = array(
		'images' => array(
			'key_from' => 'id',
			'key_through_from' => 'post_id',
			'table_through' => 'post_images',
			'key_through_to' => 'image_id',
			'model_to' => 'Model_Image',
			'key_to' => 'id',
		),
	);

	protected static $_has_one = array(
	);

	protected static $_belongs_to = array(
		'user' => array(
			'key_from' => 'user_id',
			'model_to' => 'Model_User',
			'key_to' => 'id',
		),
	);

	/**
	 * Đặt giá trị mặc định trước khi lưu để tránh lỗi NOT NULL
	 */
	public function before_save()
	{
		// Nếu type chưa được set, mặc định là bài viết thường (1)
		if ($this->type === null || $this->type === '') {
			$this->type = 1;
		}
		// Views mặc định = 0 nếu null
		if ($this->views === null || $this->views === '') {
			$this->views = 0;
		}
	}

	/**
	 * Tạo slug từ title - tự động tạo URL-friendly slug
	 */
	public static function create_slug($title)
	{
		// Chuyển đổi tiếng Việt thành không dấu
		$slug = Str::lower($title);
		$slug = preg_replace('/[àáạảãâầấậẩẫăằắặẳẵ]/u', 'a', $slug);
		$slug = preg_replace('/[èéẹẻẽêềếệểễ]/u', 'e', $slug);
		$slug = preg_replace('/[ìíịỉĩ]/u', 'i', $slug);
		$slug = preg_replace('/[òóọỏõôồốộổỗơờớợởỡ]/u', 'o', $slug);
		$slug = preg_replace('/[ùúụủũưừứựửữ]/u', 'u', $slug);
		$slug = preg_replace('/[ỳýỵỷỹ]/u', 'y', $slug);
		$slug = preg_replace('/đ/u', 'd', $slug);
		$slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
		$slug = preg_replace('/[\s-]+/', '-', $slug);
		$slug = trim($slug, '-');
		
		return $slug;
	}

	/**
	 * Lấy excerpt từ content - trích xuất đoạn mô tả ngắn
	 */
	public function get_excerpt($length = 150)
	{
		$content = strip_tags($this->content);
		if (strlen($content) <= $length) {
			return $content;
		}
		return substr($content, 0, $length) . '...';
	}

	/**
	 * Lấy danh sách categories của post này
	 */
	public function get_categories()
	{
		$post_categories = $this->post_categories;
		$categories = array();
		foreach ($post_categories as $post_category) {
			$categories[] = $post_category->category;
		}
		return $categories;
	}

	/**
	 * Lấy category đầu tiên của post (để tương thích với code cũ)
	 */
	public function get_primary_category()
	{
		$categories = $this->get_categories();
		return !empty($categories) ? $categories[0] : null;
	}

	/**
	 * Thêm category cho post
	 */
	public function add_category($category_id)
	{
		$post_category = Model_PostCategory::forge();
		$post_category->post_id = $this->id;
		$post_category->category_id = $category_id;
		$post_category->created_at = date('Y-m-d H:i:s');
		$post_category->updated_at = date('Y-m-d H:i:s');
		return $post_category->save();
	}

	/**
	 * Xóa tất cả categories của post
	 */
	public function clear_categories()
	{
		foreach ($this->post_categories as $post_category) {
			$post_category->delete();
		}
	}

	/**
	 * Cập nhật categories cho post
	 */
	public function update_categories($category_ids)
	{
		// Xóa tất cả categories cũ
		$this->clear_categories();
		
		// Thêm categories mới
		if (!empty($category_ids)) {
			foreach ($category_ids as $category_id) {
				$this->add_category($category_id);
			}
		}
	}

}
