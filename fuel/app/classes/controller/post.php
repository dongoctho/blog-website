<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\Input;
use Fuel\Core\View;
use Fuel\Core\HttpNotFoundException;
use Fuel\Core\Uri;
use Fuel\Core\Pagination;
use Fuel\Core\DB;

class Controller_Post extends Controller_Template
{
	/**
	 * Display all posts with pagination
	 */
	public function action_index($page = 1)
	{
		$data["subnav"] = array('index'=> 'active');
		$per_page = 8;
		
		// Xử lý tìm kiếm
		$search = Input::get('search', '');
		$search = trim($search);

		// Lọc theo danh mục (category_id)
		$categoryId = (int) Input::get('category', 0);
		
		// Xử lý page number: nếu có search/category thì dùng GET, nếu không dùng URI
		if (!empty($search) || $categoryId > 0) {
			$page = (int) Input::get('page', 1);
		} else {
			$page = (int) $page;
		}
		
		$offset = ($page - 1) * $per_page;

		// Xây dựng query với điều kiện tìm kiếm / danh mục
		$query = Model_Post::query()->where('is_published', 1)->where('type', 1);
		$posts_query = Model_Post::query()
			->related('user')
			->related('post_categories')
			->related('images')
			->where('type', 1)
			->where('is_published', 1);

		// Thêm điều kiện tìm kiếm nếu có
		if (!empty($search)) {
			$query->where_open()
				->where('title', 'LIKE', "%{$search}%")
				->or_where('content', 'LIKE', "%{$search}%")
				->where_close();
				
			$posts_query->where_open()
				->where('title', 'LIKE', "%{$search}%")
				->or_where('content', 'LIKE', "%{$search}%")
				->where_close();
		}

		// Lọc theo danh mục nếu có
		if ($categoryId > 0) {
			// Sử dụng subquery để lấy post IDs có category này
			$post_ids = DB::select('post_id')
				->from('post_categories')
				->where('category_id', $categoryId)
				->execute()
				->as_array();
			
			$ids = array_column($post_ids, 'post_id');
			if (!empty($ids)) {
				$query->where('id', 'IN', $ids);
				$posts_query->where('id', 'IN', $ids);
			} else {
				// Không có post nào thuộc category này
				$query->where('id', 'IN', array(0));
				$posts_query->where('id', 'IN', array(0));
			}
		}
		$total_posts = $query->count();
		
		// Cấu hình pagination, giữ nguyên các tham số filter hiện tại
		if (!empty($search) || $categoryId > 0) {
			$queryParams = array();
			if (!empty($search)) { $queryParams['search'] = $search; }
			if ($categoryId > 0) { $queryParams['category'] = $categoryId; }
			$queryParams['page'] = '';
			$baseUrl = Uri::create('post');
			$paginationUrl = $baseUrl . '?' . http_build_query($queryParams);
			$config = array(
				'pagination_url' => $paginationUrl,
				'total_items'    => $total_posts,
				'per_page'       => $per_page,
				'uri_segment'    => 'page',
				'current_page'   => $page,
				'show_first'     => true,
				'show_last'      => true,
			);
		} else {
			// Pagination bình thường khi không có filter
			$config = array(
				'pagination_url' => Uri::create('post/index'),
				'total_items'    => $total_posts,
				'per_page'       => $per_page,
				'uri_segment'    => 3,
				'current_page'   => $page,
			);
		}
		
		// Chỉ tạo pagination khi cần thiết (nhiều hơn 1 trang)
		$pagination = null;
		if ($total_posts > $per_page) {
			$pagination = Pagination::forge('posts', $config);
		}
		
		$posts = $posts_query
			->order_by('created_at', 'desc')
			->limit($per_page)
			->offset($offset)
			->get();

		// Lấy bài viết nổi bật (4 bài có views cao nhất)
		$featured_posts = Model_Post::query()
			->related('user')
			->related('post_categories')
			->related('images')
			->where('type', 1)
			->where('is_published', 1)
			->order_by('views', 'desc')
			->limit(4)
			->get();

		$data['posts'] = $posts;
		$data['featured_posts'] = $featured_posts;
		$data['pagination'] = $pagination;
		$data['total_posts'] = $total_posts;
		$data['current_page'] = $page;
		$data['per_page'] = $per_page;
		$data['search'] = $search;
		$data['category_id'] = $categoryId;
		$data['categories'] = Model_Category::query()->order_by('name', 'asc')->get();
		
		$this->template->title = 'Blog CMS';
		$this->template->content = View::forge('post/index', $data);
	}

	/**
	 * View a single post (by ID or slug)
	 */
	public function action_view($identifier = null)
	{
		$data["subnav"] = array('view'=> 'active');
		
		if (!$identifier) {
			throw new HttpNotFoundException();
		}
		
		// Kiểm tra xem identifier là số (ID) hay chuỗi (slug)
		if (is_numeric($identifier)) {
			// Tìm theo ID
			$post = Model_Post::query()
				->related('user')
				->related('post_categories')
				->related('images')
				->where('id', $identifier)
				->where('type', 1)
				->where('is_published', 1)
				->get_one();
		} else {
			// Tìm theo slug
			$post = Model_Post::query()
				->related('user')
				->related('post_categories')
				->related('images')
				->where('slug', $identifier)
				->where('type', 1)
				->where('is_published', 1)
				->get_one();
		}
		
		if (!$post) {
			throw new HttpNotFoundException();
		}

		// Tăng số lượt xem
		$post->views = ($post->views ?? 0) + 1;
		$post->save();

		// Load comments cho bài viết này và tạo tree structure
		$all_comments = Model_Comment::query()
			->related('user')
			->where('post_id', $post->id)
			->order_by('created_at', 'asc')
			->get();

		// Tạo comment tree structure
		$comments = array();
		$comment_map = array();
		
		// Tạo map của tất cả comments
		foreach ($all_comments as $comment) {
			$comment_map[$comment->id] = array(
				'comment' => $comment,
				'children' => array()
			);
		}
		
		// Xây dựng tree
		foreach ($all_comments as $comment) {
			if ($comment->parent_id && isset($comment_map[$comment->parent_id])) {
				$comment_map[$comment->parent_id]['children'][] = &$comment_map[$comment->id];
			} else {
				$comments[] = &$comment_map[$comment->id];
			}
		}

		$data['post'] = $post;
		$data['comments'] = $comments;
		$this->template->title = $post->title;
		$this->template->content = View::forge('post/view', $data);
	}
}
