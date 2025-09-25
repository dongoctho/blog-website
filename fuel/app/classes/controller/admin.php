<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\Debug;
use Fuel\Core\View;
use Fuel\Core\Response;
use Fuel\Core\Session;
use Fuel\Core\Validation;
use Fuel\Core\Input;
use Fuel\Core\Log;
use Fuel\Core\Pagination;
use Fuel\Core\Upload;
use Fuel\Core\Uri;

class Controller_Admin extends Controller_Template
{
	/**
	 * Admin template
	 */
	public $template = 'admin/template';

	public function before()
	{
		parent::before();
		if (!Auth::check()) {
		    Response::redirect('login');
		}
	}

	/**
	 * Admin Dashboard
	 */
	public function action_index()
	{
		// Thống kê tổng quan
		$data = array();
		
		$data['total_posts'] = Model_Post::query()->where('user_id', Auth::get('id'))->count();
		$data['published_posts'] = Model_Post::query()->where('user_id', Auth::get('id'))->where('is_published', 1)->count();
		$data['draft_posts'] = Model_Post::query()->where('user_id', Auth::get('id'))->where('is_published', 0)->count();
		
		if (Auth::get('role_id') == 1 || Auth::get('role_id') == 2) {
			$data['total_categories'] = Model_Category::query()->count();
			$data['total_users'] = Model_User::query()->count();
		}

		// Bài viết gần đây
		$data['recent_posts'] = Model_Post::query()
			->related('user')
			->related('post_categories')
			->order_by('created_at', 'desc')
			->where('user_id', Auth::get('id'))
			->limit(5)
			->get();

		$this->template->title = 'Admin Dashboard';
		$this->template->content = View::forge('admin/dashboard', $data);
	}

	/**
	 * Quản lý bài viết
	 */
	public function action_posts($page = 1)
	{
		$data["subnav"] = array('index'=> 'active');
		$per_page = 6;
		$page = (int) $page; // Ensure it's an integer
		$offset = ($page - 1) * $per_page;

		$posts = Model_Post::query()
			->related('user')
			->related('post_categories')
			->order_by('created_at', 'desc')
			->where('user_id', Auth::get('id'))
			->where('type', 1)
			->limit($per_page)
			->offset($offset)
			->get();

		$total_posts = Model_Post::query()->where('user_id', Auth::get('id'))->where('type', 1)->count();
		
		$config = array(
			'pagination_url' => Uri::base() . 'admin/posts/',
			'total_items'    => $total_posts,
			'per_page'       => $per_page,
			'uri_segment'    => 3,
			'current_page'   => $page,
		);

		$pagination = Pagination::forge('posts', $config);

		$data = array(
			'posts' => $posts,
			'pagination' => $pagination,
			'total_posts' => $total_posts,
		);

		$this->template->title = 'Quản lý bài viết';
		$this->template->content = View::forge('admin/posts/index', $data);
	}

	/**
	 * Tạo bài viết mới (Admin)
	 */
	public function action_posts_create()
	{
		$data = array();

		if (Input::method() == 'POST' && (Input::post('title') || Input::post('content'))) {
			$val = Validation::forge('post');
			
			$val->add_field('title', 'Tiêu đề', 'required|min_length[5]|max_length[255]');
			$val->add_field('content', 'Nội dung', 'required|min_length[10]');
			$val->add_field('category_ids', 'Danh mục', 'required');
			$val->add_field('publish_start_date', 'Ngày bắt đầu xuất bản', 'valid_string[datetime]');
			$val->add_field('publish_end_date', 'Ngày kết thúc xuất bản', 'valid_string[datetime]');
			$val->add_field('is_published_date', 'Ngày xuất bản', 'valid_string[numeric]');
			$val->add_field('is_published', 'Xuất bản', 'valid_string[numeric]');

			if ($val->run()) {
				try {
					$post = Model_Post::forge();
					$post->title = Input::post('title');
					$post->slug = Model_Post::create_slug(Input::post('title'));
					$post->content = Input::post('content');
					$post->user_id = Auth::get('id');
					$now = date('Y-m-d H:i:s');
					$is_published = Input::post('is_published', 0);

					// Lưu khung thời gian xuất bản nếu người dùng chọn
					if (Input::post('is_published_date')) {
						$start = Input::post('publish_start_date');
						$end = Input::post('publish_end_date');
						$post->publish_start_date = $start ? date('Y-m-d H:i:s', strtotime($start)) : null;
						$post->publish_end_date = $end ? date('Y-m-d H:i:s', strtotime($end)) : null;
						if ($post->publish_start_date <= $now) {
							$is_published = 1;
						}
					} else {
						$post->publish_start_date = null;
						$post->publish_end_date = null;
					}
					$post->is_published = $is_published;
					$post->type = 1;
					$post->views = 0; // Khởi tạo số lượt xem = 0 cho bài viết mới
					$post->created_at = date('Y-m-d H:i:s');
					$post->updated_at = date('Y-m-d H:i:s');
					
					if ($post->save()) {
						// Thêm categories cho post
						$category_ids = Input::post('category_ids', array());
						if (!empty($category_ids)) {
							$post->update_categories($category_ids);
						}
						
						// Process image uploads
						$this->process_images($post);
						
						Session::set_flash('success', 'Bài viết đã được tạo thành công!');
						Response::redirect('admin/posts');
					}
				} catch (Exception $e) {
					Session::set_flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
				}
			} else {
				$errors = array();
				foreach ($val->error() as $error) {
					$errors[] = $error->get_message();
				}
				Session::set_flash('error', $errors);
			}
		}

		// Lấy danh sách categories cho multiple selection
		$categories_list = Model_Category::find('all');
		$categories = array();
		foreach ($categories_list as $cat) {
			$categories[$cat->id] = $cat->name;
		}
		$data['categories'] = $categories;

		$this->template->title = 'Tạo bài viết mới';
		$this->template->content = View::forge('admin/posts/create', $data);
	}

	/**
	 * Process deletion of selected images
	 */
	private function process_delete_images()
	{
		$delete_images = Input::post('delete_images', array());
		
		if (!empty($delete_images)) {
			foreach ($delete_images as $image_id) {
				try {
					$image = Model_Image::find($image_id);
					if ($image) {
						// Delete physical file
						$file_path = DOCROOT . 'uploads/' . $image->file_path;
						if (file_exists($file_path)) {
							unlink($file_path);
						}

						// Delete link in post_images table
						$post_images = Model_PostImage::find('all', array(
							'where' => array('image_id' => $image_id)
						));
						foreach ($post_images as $post_image) {
							$post_image->delete();
						}

						// Delete record in images table
						$image->delete();
						
						Log::info("Successfully deleted image {$image_id}");
					}
				} catch (Exception $e) {
					Log::error("Error deleting image {$image_id}: " . $e->getMessage());
				}
			}
		}
	}

	/**
	 * Process image uploads (same as Post controller)
	 */
	private function process_images($post)
	{
        // Chỉ xử lý nếu có trường images được upload (tránh lỗi khi form không có file)
        $has_files = false;
        if (isset($_FILES['images'])) {
            $names = $_FILES['images']['name'];
            if (is_array($names)) {
                $has_files = count(array_filter($names)) > 0;
            } else {
                $has_files = !empty($names);
            }
        }

        if (!$has_files) {
            Log::debug('No images uploaded in request');
            return;
        }

        if (!is_dir(DOCROOT . 'uploads')) {
            mkdir(DOCROOT . 'uploads', 0755, true);
        }

        Upload::process(array(
            'path' => DOCROOT . 'uploads',
            'max_size' => 5242880,
            'ext_whitelist' => array('jpg', 'jpeg', 'png', 'gif'),
            'normalize' => true,
            'auto_rename' => true,
        ));

        if (!Upload::is_valid()) {
            $errors = Upload::get_errors();
            foreach ($errors as $error) {
                Log::error("Upload error: " . json_encode($error));
            }
            return;
        }

        Upload::save();
        $files = Upload::get_files();
        $post_id = $post->id;

        foreach ($files as $file) {
            if (!$file['error']) {
                try {
                    $image = Model_Image::forge();
                    $image->file_path = $file['saved_as'];
                    $image->uploaded_by = Auth::get('id');
                    $image->uploaded_at = date('Y-m-d H:i:s');
                    $image->created_at = date('Y-m-d H:i:s');
                    $image->updated_at = date('Y-m-d H:i:s');
                    $image->save();

                    $post_image = Model_PostImage::forge();
                    $post_image->post_id = $post_id;
                    $post_image->image_id = $image->id;
                    $post_image->created_at = date('Y-m-d H:i:s');
                    $post_image->updated_at = date('Y-m-d H:i:s');
                    $post_image->save();

                    Log::info("Successfully linked image {$image->id} ('{$file['saved_as']}') with post {$post_id}");
                } catch (Exception $e) {
                    Log::error("Error processing image: " . $e->getMessage());
                    if (isset($image) && $image->id) {
                        $image->delete();
                    }
                }
            }
        }
	}

	/**
	 * Xem chi tiết bài viết (Admin)
	 */
	public function action_posts_view($id = null)
	{
		is_null($id) and Response::redirect('admin/posts');

		try {
			$post = Model_Post::query()
				->related('user')
				->related('post_categories')
				->related('images')
				->where('id', $id)
				->get_one();

			if (!$post) {
				Session::set_flash('error', 'Không tìm thấy bài viết.');
				Response::redirect('admin/posts');
			}

			$data = array(
				'post' => $post,
			);

			$this->template->title = 'Xem bài viết: ' . $post->title;
			$this->template->content = View::forge('admin/posts/view', $data);

		} catch (Exception $e) {
			Log::error('Error viewing post: ' . $e->getMessage());
			Session::set_flash('error', 'Có lỗi xảy ra khi xem bài viết.');
			Response::redirect('admin/posts');
		}
	}

	/**
	 * Chỉnh sửa bài viết (Admin)
	 */
	public function action_posts_edit($id = null)
	{
		is_null($id) and Response::redirect('admin/posts');

		try {
			$post = Model_Post::query()
				->related('user')
				->related('post_categories')
				->related('images')
				->where('id', $id)
				->get_one();

			if (!$post) {
				Session::set_flash('error', 'Không tìm thấy bài viết.');
				Response::redirect('admin/posts');
			}

			if (Input::method() == 'POST' && (Input::post('title') || Input::post('content'))) {
				$val = Validation::forge('post_edit');
				
				$val->add_field('title', 'Tiêu đề', 'required|min_length[5]|max_length[255]');
				$val->add_field('content', 'Nội dung', 'required|min_length[10]');
				$val->add_field('category_ids', 'Danh mục', 'required');
				$val->add_field('views', 'Số lượt xem', 'valid_string[numeric]');
				$val->add_field('publish_start_date', 'Ngày bắt đầu xuất bản', 'valid_string[datetime]');
				$val->add_field('publish_end_date', 'Ngày kết thúc xuất bản', 'valid_string[datetime]');
				$val->add_field('is_published_date', 'Ngày xuất bản', 'valid_string[numeric]');
				$val->add_field('is_published', 'Xuất bản', 'valid_string[numeric]');

				if ($val->run()) {
					try {
						$post->title = Input::post('title');
						$post->slug = Model_Post::create_slug(Input::post('title'));
						$post->content = Input::post('content');
						// Cập nhật categories cho post
					$category_ids = Input::post('category_ids', array());
					if (!empty($category_ids)) {
						$post->update_categories($category_ids);
					}
						
						// Xử lý trường views - chỉ cập nhật nếu có giá trị
						$views_input = Input::post('views');
						if ($views_input !== null && $views_input !== '') {
							$post->views = (int)$views_input;
						}
						
						$now = date('Y-m-d H:i:s');
						$is_published = Input::post('is_published', 0);

						if (Input::post('is_published_date')) {
							$start = Input::post('publish_start_date');
							$end = Input::post('publish_end_date');
							$post->publish_start_date = $start ? date('Y-m-d H:i:s', strtotime($start)) : null;
							$post->publish_end_date = $end ? date('Y-m-d H:i:s', strtotime($end)) : null;
							if ($post->publish_start_date <= $now) {
								$is_published = 1;
							}
						} else {
							$post->publish_start_date = null;
							$post->publish_end_date = null;
						}
						$post->is_published = $is_published;
						$post->updated_at = date('Y-m-d H:i:s');
						
						if ($post->save()) {
							// Process deletion of selected images
							$this->process_delete_images();
							
							// Process new image uploads if any
							$this->process_images($post);
							
							Session::set_flash('success', 'Bài viết đã được cập nhật thành công!');
							Response::redirect('admin/posts');
						}
					} catch (Exception $e) {
						Session::set_flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
					}
				} else {
					$errors = array();
					foreach ($val->error() as $field => $error) {
						$errors[] = $error->get_message();
					}
					Session::set_flash('error', $errors);
				}
			}

			// Lấy danh sách categories cho multiple selection
			$categories_list = Model_Category::find('all');
			$categories = array();
			foreach ($categories_list as $cat) {
				$categories[$cat->id] = $cat->name;
			}

			$data = array(
				'post' => $post,
				'categories' => $categories,
			);

			$this->template->title = 'Chỉnh sửa: ' . $post->title;
			$this->template->content = View::forge('admin/posts/edit', $data);

		} catch (Exception $e) {
			Log::error('Error editing post: ' . $e->getMessage());
			Session::set_flash('error', 'Có lỗi xảy ra khi chỉnh sửa bài viết.');
			Response::redirect('admin/posts');
		}
	}

	/**
	 * Xóa bài viết (Admin)
	 */
	public function action_posts_delete($id = null)
	{
		is_null($id) and Response::redirect('admin/posts');

		try {
			$post = Model_Post::find($id);

			if (!$post) {
				Session::set_flash('error', 'Không tìm thấy bài viết.');
				Response::redirect('admin/posts');
			}

			$post_images = Model_PostImage::find('all', array(
				'where' => array('post_id' => $id)
			));
			
			foreach ($post_images as $post_image) {
				$post_image->delete();
			}

			// Xóa bài viết
			if ($post->delete()) {
				Session::set_flash('success', 'Bài viết đã được xóa thành công!');
			} else {
				Session::set_flash('error', 'Không thể xóa bài viết.');
			}

		} catch (Exception $e) {
			Log::error('Error deleting post: ' . $e->getMessage());
			Session::set_flash('error', 'Có lỗi xảy ra khi xóa bài viết.');
		}

		Response::redirect('admin/posts');
	}

	/**
	 * Quản lý thảo luận (type = 2)
	 */
	public function action_discussion($page = 1)
	{
		$data["subnav"] = array('index'=> 'active');
		$per_page = 6;
		$page = (int) $page;
		$offset = ($page - 1) * $per_page;

		$posts = Model_Post::query()
			->related('user')
			->related('post_categories')
			->order_by('created_at', 'desc')
			->where('user_id', Auth::get('id'))
			->where('type', 2)
			->limit($per_page)
			->offset($offset)
			->get();

		$total_posts = Model_Post::query()->where('user_id', Auth::get('id'))->where('type', 2)->count();

		$config = array(
			'pagination_url' => Uri::base() . 'admin/discussion/',
			'total_items'    => $total_posts,
			'per_page'       => $per_page,
			'uri_segment'    => 3,
			'current_page'   => $page,
		);

		$pagination = Pagination::forge('discussion', $config);

		$data = array(
			'posts' => $posts,
			'pagination' => $pagination,
			'total_posts' => $total_posts,
		);

		$this->template->title = 'Quản lý thảo luận';
		$this->template->content = View::forge('admin/discussion/index', $data);
	}

	/**
	 * Tạo thảo luận mới: luôn publish và type = 2
	 */
	public function action_discussion_create()
	{
		$data = array();

		if (Input::method() == 'POST' && (Input::post('title') || Input::post('content'))) {
			$val = Validation::forge('discussion_create');
			$val->add_field('title', 'Tiêu đề', 'required|min_length[5]|max_length[255]');
			$val->add_field('content', 'Nội dung', 'required|min_length[10]');
			$val->add_field('category_ids', 'Danh mục', 'required');

			if ($val->run()) {
				try {
					$post = Model_Post::forge();
					$post->title = Input::post('title');
					$post->slug = Model_Post::create_slug(Input::post('title'));
					$post->content = Input::post('content');
					$post->user_id = Auth::get('id');
					$post->type = 2; // Discussion
					$post->is_published = 1; // Luôn xuất bản
					$post->publish_start_date = null; // Không dùng lịch xuất bản
					$post->publish_end_date = null;
					$post->views = 0;
					$post->created_at = date('Y-m-d H:i:s');
					$post->updated_at = date('Y-m-d H:i:s');

					if ($post->save()) {
						// Sau khi có post_id mới gán categories để tránh NULL
						$category_ids = Input::post('category_ids', array());
						if (!empty($category_ids)) {
							$post->update_categories($category_ids);
						}
						$this->process_images($post);
						Session::set_flash('success', 'Thảo luận đã được tạo và xuất bản.');
						Response::redirect('admin/discussion');
					}
				} catch (Exception $e) {
					Session::set_flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
				}
			} else {
				$errors = array();
				foreach ($val->error() as $error) { $errors[] = $error->get_message(); }
				Session::set_flash('error', $errors);
			}
		}

		$categories_list = Model_Category::find('all');
		$categories = array();
		foreach ($categories_list as $cat) {
			$categories[$cat->id] = $cat->name;
		}
		$data['categories'] = $categories;

		$this->template->title = 'Tạo thảo luận mới';
		$this->template->content = View::forge('admin/discussion/create', $data);
	}

	/**
	 * Xem chi tiết thảo luận
	 */
	public function action_discussion_view($id = null)
	{
		is_null($id) and Response::redirect('admin/discussion');
		try {
			$post = Model_Post::query()
				->related('user')
				->related('post_categories')
				->related('images')
				->where('id', $id)
				->where('type', 2)
				->get_one();

			if (!$post) {
				Session::set_flash('error', 'Không tìm thấy thảo luận.');
				Response::redirect('admin/discussion');
			}

			$this->template->title = 'Xem thảo luận: ' . $post->title;
			$this->template->content = View::forge('admin/discussion/view', array('post' => $post));
		} catch (Exception $e) {
			Log::error('Error viewing discussion: ' . $e->getMessage());
			Session::set_flash('error', 'Có lỗi xảy ra khi xem thảo luận.');
			Response::redirect('admin/discussion');
		}
	}

	/**
	 * Chỉnh sửa thảo luận: luôn giữ publish = 1, bỏ lịch xuất bản
	 */
	public function action_discussion_edit($id = null)
	{
		is_null($id) and Response::redirect('admin/discussion');
		try {
			$post = Model_Post::query()
				->related('user')
				->related('post_categories')
				->related('images')
				->where('id', $id)
				->where('type', 2)
				->get_one();

			if (!$post) {
				Session::set_flash('error', 'Không tìm thấy thảo luận.');
				Response::redirect('admin/discussion');
			}

			if (Input::method() == 'POST' && (Input::post('title') || Input::post('content'))) {
				$val = Validation::forge('discussion_edit');
				$val->add_field('title', 'Tiêu đề', 'required|min_length[5]|max_length[255]');
				$val->add_field('content', 'Nội dung', 'required|min_length[10]');
				$val->add_field('category_ids', 'Danh mục', 'required');
				$val->add_field('views', 'Số lượt xem', 'valid_string[numeric]');

				if ($val->run()) {
					try {
						$post->title = Input::post('title');
						$post->slug = Model_Post::create_slug(Input::post('title'));
						$post->content = Input::post('content');
						// Cập nhật categories cho post
					$category_ids = Input::post('category_ids', array());
					if (!empty($category_ids)) {
						$post->update_categories($category_ids);
					}
						$views_input = Input::post('views');
						if ($views_input !== null && $views_input !== '') {
							$post->views = (int)$views_input;
						}
						$post->type = 2; // Ensure type remains discussion
						$post->is_published = 1; // Always published
						$post->publish_start_date = null;
						$post->publish_end_date = null;
						$post->updated_at = date('Y-m-d H:i:s');

						if ($post->save()) {
							$this->process_delete_images();
							$this->process_images($post);
							Session::set_flash('success', 'Đã cập nhật thảo luận.');
							Response::redirect('admin/discussion');
						}
					} catch (Exception $e) {
						Session::set_flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
					}
				} else {
					$errors = array();
					foreach ($val->error() as $field => $error) { $errors[] = $error->get_message(); }
					Session::set_flash('error', $errors);
				}
			}

			$categories_list = Model_Category::find('all');
			$categories = array();
			foreach ($categories_list as $cat) { $categories[$cat->id] = $cat->name; }

			$this->template->title = 'Chỉnh sửa: ' . $post->title;
			$this->template->content = View::forge('admin/discussion/edit', array(
				'post' => $post,
				'categories' => $categories,
			));
		} catch (Exception $e) {
			Log::error('Error editing discussion: ' . $e->getMessage());
			Session::set_flash('error', 'Có lỗi xảy ra khi chỉnh sửa thảo luận.');
			Response::redirect('admin/discussion');
		}
	}

	/**
	 * Xóa thảo luận
	 */
	public function action_discussion_delete($id = null)
	{
		is_null($id) and Response::redirect('admin/discussion');
		try {
			$post = Model_Post::query()->where('id', $id)->where('type', 2)->get_one();
			if (!$post) {
				Session::set_flash('error', 'Không tìm thấy thảo luận.');
				Response::redirect('admin/discussion');
			}

			$post_images = Model_PostImage::find('all', array('where' => array('post_id' => $id)));
			foreach ($post_images as $post_image) { $post_image->delete(); }

			if ($post->delete()) {
				Session::set_flash('success', 'Đã xóa thảo luận.');
			} else {
				Session::set_flash('error', 'Không thể xóa thảo luận.');
			}
		} catch (Exception $e) {
			Log::error('Error deleting discussion: ' . $e->getMessage());
			Session::set_flash('error', 'Có lỗi xảy ra khi xóa thảo luận.');
		}

		Response::redirect('admin/discussion');
	}

	/**
	 * Quản lý danh mục
	 */
	public function action_categories($page = 1)
	{
		if (Auth::get('role_id') != 1 && Auth::get('role_id') != 2) {
			Response::redirect('admin/dashboard');
		}

		$per_page = 10;
		$page = (int) $page;
		$offset = ($page - 1) * $per_page;

		$total_categories = Model_Category::query()->count();
		$categories = Model_Category::query()
			->order_by('created_at', 'desc')
			->limit($per_page)
			->offset($offset)
			->get();

		$config = array(
			'pagination_url' => Uri::base() . 'admin/categories/',
			'total_items'    => $total_categories,
			'per_page'       => $per_page,
			'uri_segment'    => 3,
			'current_page'   => $page,
		);

		$pagination = Pagination::forge('admin_categories', $config);

		$data = array(
			'categories' => $categories,
			'pagination' => $pagination,
			'total_categories' => $total_categories,
		);

		$this->template->title = 'Quản lý danh mục';
		$this->template->content = View::forge('admin/categories/index', $data);
	}

	/**
	 * Tạo danh mục (Admin)
	 */
	public function action_categories_create()
	{
		if (Auth::get('role_id') != 1 && Auth::get('role_id') != 2) {
			Response::redirect('admin/dashboard');
		}

		if (Input::method() == 'POST') {
			$val = Validation::forge('category_create');
			$val->add_field('name', 'Tên danh mục', 'required|min_length[2]|max_length[100]');
			$val->add_field('parent_id', 'Danh mục cha', 'valid_string[numeric]');

			if ($val->run()) {
				$category = Model_Category::forge();
				$category->name = trim(Input::post('name'));
				$parentId = (int) Input::post('parent_id', 0);
				$category->parent_id = $parentId > 0 ? $parentId : null;
				$category->created_at = date('Y-m-d H:i:s');
				$category->updated_at = date('Y-m-d H:i:s');

				if ($category->save()) {
					Session::set_flash('success', 'Tạo danh mục thành công.');
					return Response::redirect('admin/categories');
				}

				Session::set_flash('error', 'Không thể lưu danh mục.');
			} else {
				$errors = array();
				foreach ($val->error() as $error) { $errors[] = $error->get_message(); }
				Session::set_flash('error', implode(', ', $errors));
			}
		}

		$parents = Model_Category::query()->order_by('name', 'asc')->get();

		$this->template->title = 'Thêm danh mục';
		$this->template->content = View::forge('admin/categories/create', array(
			'parents' => $parents,
		));
	}

	/**
	 * Chỉnh sửa danh mục (Admin)
	 */
	public function action_categories_edit($id = null)
	{
		if (Auth::get('role_id') != 1 && Auth::get('role_id') != 2) {
			Response::redirect('admin/dashboard');
		}

		$category = $id ? Model_Category::find($id) : null;
		if (!$category) {
			Session::set_flash('error', 'Danh mục không tồn tại.');
			return Response::redirect('admin/categories');
		}

		if (Input::method() === 'POST') {
			$val = Validation::forge('category_edit');
			$val->add_field('name', 'Tên danh mục', 'required|min_length[2]|max_length[100]');
			$val->add_field('parent_id', 'Danh mục cha', 'valid_string[numeric]');

			if ($val->run()) {
				$category->name = trim(Input::post('name'));
				$parentId = (int) Input::post('parent_id', 0);
				$category->parent_id = $parentId > 0 ? $parentId : null;
				$category->updated_at = date('Y-m-d H:i:s');

				if ($category->save()) {
					Session::set_flash('success', 'Cập nhật danh mục thành công.');
					return Response::redirect('admin/categories');
				}

				Session::set_flash('error', 'Không thể cập nhật danh mục.');
			} else {
				$errors = array();
				foreach ($val->error() as $error) { $errors[] = $error->get_message(); }
				Session::set_flash('error', implode(', ', $errors));
			}
		}

		$parents = Model_Category::query()->where('id', '!=', $category->id)->order_by('name', 'asc')->get();

		$this->template->title = 'Chỉnh sửa danh mục';
		$this->template->content = View::forge('admin/categories/edit', array(
			'category' => $category,
			'parents' => $parents,
		));
	}

	/**
	 * Xóa danh mục (Admin)
	 */
	public function action_categories_delete($id = null)
	{
		if (Auth::get('role_id') != 1 && Auth::get('role_id') != 2) {
			Response::redirect('admin/dashboard');
		}

		$category = $id ? Model_Category::find($id) : null;
		if (!$category) {
			Session::set_flash('error', 'Danh mục không tồn tại.');
			return Response::redirect('admin/categories');
		}

		// Đếm số bài viết thuộc danh mục qua bảng post_categories
		$postsCount = \DB::select(\DB::expr('COUNT(DISTINCT post_id) as cnt'))
			->from('post_categories')
			->where('category_id', $category->id)
			->execute()
			->get('cnt', 0);
		if ($postsCount > 0) {
			Session::set_flash('error', "Không thể xóa. Danh mục đang có {$postsCount} bài viết.");
			return Response::redirect('admin/categories');
		}

		$children = Model_Category::query()->where('parent_id', $category->id)->get();
		foreach ($children as $child) {
			$child->parent_id = null;
			$child->save();
		}

		$category->delete();
		Session::set_flash('success', 'Đã xóa danh mục.');
		return Response::redirect('admin/categories');
	}

	/**
	 * Danh sách người dùng (Admin)
	 */
	public function action_users()
	{
		if (Auth::get('role_id') != 1) {
			Response::redirect('admin/dashboard');
		}

		$per_page = 10;
		$page = Input::get('page', 1);
		$offset = ($page - 1) * $per_page;

		// Lấy tổng số users
		$total_users = Model_User::count();
		
		// Lấy danh sách users với phân trang
		$users = Model_User::query()
			->order_by('created_at', 'desc')
			->limit($per_page)
			->offset($offset)
			->get();

		// Tạo pagination nếu cần
		$pagination = null;
		if ($total_users > $per_page) {
			$pagination = Pagination::forge('users_pagination', array(
				'total_items' => $total_users,
				'per_page' => $per_page,
				'uri_segment' => 3,
				'pagination_url' => Uri::create('admin/users'),
			));
		}

		$this->template->title = 'Quản lý người dùng';
		$this->template->content = View::forge('admin/users/index', array(
			'users' => $users,
			'pagination' => $pagination,
		));
	}

	/**
	 * Tạo người dùng mới (Admin)
	 */
	public function action_users_create()
	{
		if (Auth::get('role_id') != 1) {
			Response::redirect('admin/dashboard');
		}

		if (Input::method() == 'POST') {
			$val = Validation::forge('user');
			$val->add_field('username', 'Họ và tên', 'required|max_length[50]|min_length[3]');
			$val->add_field('email', 'Email', 'required|valid_email|max_length[100]');
			$val->add_field('password', 'Mật khẩu', 'required|min_length[6]');
			$val->add_field('password_confirm', 'Xác nhận mật khẩu', 'required|match_field[password]');
			$val->add_field('role_id', 'Vai trò', 'required|valid_string[numeric]');

			if ($val->run()) {
				// Kiểm tra email đã tồn tại
				$existing_email = Model_User::query()
					->where('email', Input::post('email'))
					->get_one();
				if ($existing_email) {
					Session::set_flash('error', 'Email đã tồn tại.');
					return Response::redirect('admin/users/create');
				}

				// Tạo user mới
				$user = Model_User::forge();
				$user->username = Input::post('username');
				$user->email = Input::post('email');
				$user->password = Auth::hash_password(Input::post('password'));
				$user->role_id = Input::post('role_id', 3);
				$user->remember_me = 0;
				$user->is_google_account = 0;
				$user->created_at = date('Y-m-d H:i:s');
				$user->updated_at = date('Y-m-d H:i:s');

				if ($user->save()) {
					Session::set_flash('success', 'Đã tạo người dùng mới.');
					return Response::redirect('admin/users');
				} else {
					Session::set_flash('error', 'Có lỗi xảy ra khi tạo người dùng.');
				}
			} else {
				Session::set_flash('error', 'Dữ liệu không hợp lệ.');
			}
		}

		$this->template->title = 'Thêm người dùng';
		$this->template->content = View::forge('admin/users/create');
	}

	/**
	 * Chỉnh sửa người dùng (Admin)
	 */
	public function action_users_edit($id = null)
	{
		if (Auth::get('role_id') != 1) {
			Response::redirect('admin/dashboard');
		}

		$user = $id ? Model_User::find($id) : null;
		if (!$user) {
			Session::set_flash('error', 'Người dùng không tồn tại.');
			return Response::redirect('admin/users');
		}

		if (Input::method() == 'POST') {
			$val = Validation::forge('user');
			$val->add_field('username', 'Họ và tên', 'required|max_length[50]|min_length[3]');
			$val->add_field('email', 'Email', 'required|valid_email|max_length[100]');
			$val->add_field('password', 'Mật khẩu', 'min_length[6]');
			$val->add_field('password_confirm', 'Xác nhận mật khẩu', 'match_field[password]');
			$val->add_field('role_id', 'Vai trò', 'required|valid_string[numeric]');

			if ($val->run()) {
				// Kiểm tra email đã tồn tại (trừ user hiện tại)
				$existing_email = Model_User::query()
					->where('email', Input::post('email'))
					->where('id', '!=', $user->id)
					->get_one();
				if ($existing_email) {
					Session::set_flash('error', 'Email đã tồn tại.');
					return Response::redirect('admin/users/edit/' . $user->id);
				}

				// Cập nhật thông tin user
				$user->username = Input::post('username');
				$user->email = Input::post('email');
				$user->role_id = Input::post('role_id');
				$user->updated_at = date('Y-m-d H:i:s');

				// Cập nhật mật khẩu nếu có
				$password = Input::post('password');
				if (!empty($password)) {
					$user->password = Auth::hash_password($password);
				}

				if ($user->save()) {
					Session::set_flash('success', 'Đã cập nhật thông tin người dùng.');
					return Response::redirect('admin/users');
				} else {
					Session::set_flash('error', 'Có lỗi xảy ra khi cập nhật.');
				}
			} else {
				Session::set_flash('error', 'Dữ liệu không hợp lệ.');
			}
		}

		$this->template->title = 'Chỉnh sửa người dùng';
		$this->template->content = View::forge('admin/users/edit', array(
			'user' => $user,
		));
	}

	/**
	 * Xóa người dùng (Admin)
	 */
	public function action_users_delete($id = null)
	{
		if (Auth::get('role_id') != 1) {
			Response::redirect('admin/dashboard');
		}

		$user = $id ? Model_User::find($id) : null;
		if (!$user) {
			Session::set_flash('error', 'Người dùng không tồn tại.');
			return Response::redirect('admin/users');
		}

		// Không cho xóa admin đầu tiên
		if ($user->id == 1) {
			Session::set_flash('error', 'Không thể xóa tài khoản quản trị viên chính.');
			return Response::redirect('admin/users');
		}

		// Không cho xóa chính mình
		if ($user->id == Auth::get('id')) {
			Session::set_flash('error', 'Không thể xóa tài khoản của chính mình.');
			return Response::redirect('admin/users');
		}

		// Kiểm tra có bài viết không
		$postsCount = Model_Post::count(array('where' => array(array('user_id', $user->id))));
		if ($postsCount > 0) {
			Session::set_flash('error', "Không thể xóa. Người dùng đang có {$postsCount} bài viết.");
			return Response::redirect('admin/users');
		}

		// Xóa comments của user
		$comments = Model_Comment::query()->where('user_id', $user->id)->get();
		foreach ($comments as $comment) {
			$comment->delete();
		}

		$user->delete();
		Session::set_flash('success', 'Đã xóa người dùng.');
		return Response::redirect('admin/users');
	}
}
